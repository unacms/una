<?php
/**
 * CSS `@import` node
 *
 * The general strategy here is that we don't want to wait
 * for the parsing to be completed, before we start importing
 * the file. That's because in the context of a browser,
 * most of the time will be spent waiting for the server to respond.
 *
 * On creation, we push the import path to our import queue, though
 * `import,push`, we also pass it a callback, which it'll call once
 * the file has been fetched, and parsed.
 *
 * @private
 * @see less-2.5.3.js#Import.prototype
 */
class Less_Tree_Import extends Less_Tree {

	public $options;
	public $index;
	public $path;
	public $features;
	public $currentFileInfo;
	public $css;
	/** @var bool|null This is populated by Less_ImportVisitor */
	public $doSkip = false;
	/** @var string|null This is populated by Less_ImportVisitor */
	public $importedFilename;
	/**
	 * This is populated by Less_ImportVisitor.
	 *
	 * For imports that use "inline", this holds a raw string.
	 *
	 * @var string|Less_Tree_Ruleset|null
	 */
	public $root;

	public function __construct( $path, $features, array $options, $index, $currentFileInfo = null ) {
		$this->options = $options + [ 'inline' => false, 'optional' => false, 'multiple' => false ];
		$this->index = $index;
		$this->path = $path;
		$this->features = $features;
		$this->currentFileInfo = $currentFileInfo;

		if ( isset( $this->options['less'] ) || $this->options['inline'] ) {
			$this->css = !isset( $this->options['less'] ) || !$this->options['less'] || $this->options['inline'];
		} else {
			$pathValue = $this->getPath();
			// Leave any ".css" file imports as literals for the browser.
			// Also leave any remote HTTP resources as literals regardless of whether
			// they contain ".css" in their filename.
			if ( $pathValue && (
				preg_match( '/[#\.\&\?\/]css([\?;].*)?$/', $pathValue )
				|| preg_match( '/^(https?:)?\/\//i', $pathValue )
			) ) {
				$this->css = true;
			}
		}
	}

//
// The actual import node doesn't return anything, when converted to CSS.
// The reason is that it's used at the evaluation stage, so that the rules
// it imports can be treated like any other rules.
//
// In `eval`, we make sure all Import nodes get evaluated, recursively, so
// we end up with a flat structure, which can easily be imported in the parent
// ruleset.
//

	public function accept( $visitor ) {
		if ( $this->features ) {
			$this->features = $visitor->visitObj( $this->features );
		}
		$this->path = $visitor->visitObj( $this->path );

		if ( !$this->options['inline'] && $this->root ) {
			$this->root = $visitor->visit( $this->root );
		}
	}

	public function genCSS( $output ) {
		if ( $this->css && !isset( $this->path->currentFileInfo["reference"] ) ) {
			$output->add( '@import ', $this->currentFileInfo, $this->index );
			$this->path->genCSS( $output );
			if ( $this->features ) {
				$output->add( ' ' );
				$this->features->genCSS( $output );
			}
			$output->add( ';' );
		}
	}

	/**
	 * @return string|null
	 */
	public function getPath() {
		// During the first pass, Less_Tree_Url may contain a Less_Tree_Variable (not yet expanded),
		// and thus has no value property defined yet. Return null until we reach the next phase.
		// https://github.com/wikimedia/less.php/issues/29
		// TODO: Upstream doesn't need a check against Less_Tree_Variable. Why do we?
		$path = ( $this->path instanceof Less_Tree_Url && !( $this->path->value instanceof Less_Tree_Variable ) )
			? $this->path->value->value
			// e.g. Less_Tree_Quoted
			: $this->path->value;

		if ( is_string( $path ) ) {
			// remove query string and fragment
			return preg_replace( '/[\?#][^\?]*$/', '', $path );
		}
	}

	public function isVariableImport() {
		$path = $this->path;
		if ( $path instanceof Less_Tree_Url ) {
			$path = $path->value;
		}
		if ( $path instanceof Less_Tree_Quoted ) {
			return $path->containsVariables();
		}
		return true;
	}

	public function compileForImport( $env ) {
		$path = $this->path;
		if ( $path instanceof Less_Tree_Url ) {
			 $path = $path->value;
		}
		return new self( $path->compile( $env ), $this->features, $this->options, $this->index, $this->currentFileInfo );
	}

	public function compilePath( $env ) {
		$path = $this->path->compile( $env );
		$rootpath = $this->currentFileInfo['rootpath'] ?? null;

		if ( !( $path instanceof Less_Tree_Url ) ) {
			if ( $rootpath ) {
				$pathValue = $path->value;
				// Add the base path if the import is relative
				if ( $pathValue && Less_Environment::isPathRelative( $pathValue ) ) {
					$path->value = $this->currentFileInfo['uri_root'] . $pathValue;
				}
			}
			$path->value = Less_Environment::normalizePath( $path->value );
		}

		return $path;
	}

	/**
	 * @param Less_Environment $env
	 * @see less-2.5.3.js#Import.prototype.eval
	 */
	public function compile( $env ) {
		$features = ( $this->features ? $this->features->compile( $env ) : null );

		// import once
		if ( $this->skip( $env ) ) {
			return [];
		}

		if ( $this->options['inline'] ) {
			$contents = new Less_Tree_Anonymous( $this->root, 0,
				[
					'filename' => $this->importedFilename,
					'reference' => $this->currentFileInfo['reference'] ?? null,
				],
				true,
				true,
				false
			);
			return $this->features
				? new Less_Tree_Media( [ $contents ], $this->features->value )
				: [ $contents ];
		} elseif ( $this->css ) {
			$newImport = new self( $this->compilePath( $env ), $features, $this->options, $this->index );
			// TODO: We might need upstream's `if (!newImport.css && this.error) { throw this.error;`
			return $newImport;
		} else {
			$ruleset = new Less_Tree_Ruleset( null, $this->root->rules );

			$ruleset->evalImports( $env );

			return $this->features
				? new Less_Tree_Media( $ruleset->rules, $this->features->value )
				: $ruleset->rules;

		}
	}

	/**
	 * Should the import be skipped?
	 *
	 * @param Less_Environment $env
	 * @return bool|null
	 */
	public function skip( $env ) {
		$path = $this->getPath();
		// TODO: Since our Import->getPath() varies from upstream Less.js (ours can return null).
		// we therefore need an empty string fallback here. Remove this fallback once getPath()
		// is in sync with upstream.
		$fullPath = Less_FileManager::getFilePath( $path, $this->currentFileInfo )[0] ?? $path ?? '';

		if ( $this->doSkip !== null ) {
			return $this->doSkip;
		}

		// @see less-2.5.3.js#ImportVisitor.prototype.onImported
		if ( isset( $env->importVisitorOnceMap[$fullPath] ) ) {
			return true;
		}

		$env->importVisitorOnceMap[$fullPath] = true;
		return false;
	}
}
