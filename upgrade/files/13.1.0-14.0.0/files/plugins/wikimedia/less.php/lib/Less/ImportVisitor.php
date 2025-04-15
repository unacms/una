<?php
/**
 * @private
 */
class Less_ImportVisitor extends Less_Visitor {

	public $env;
	public $variableImports = [];
	public $recursionDetector = [];

	public $_currentDepth = 0;
	public $importItem;

	public function __construct( $env ) {
		parent::__construct();
		// NOTE: Upstream creates a new environment/context here. We re-use the main one instead.
		// This makes Less_Environment->addParsedFile() easier to support (which is custom to Less.php)
		$this->env = $env;
		// NOTE: Upstream `importCount` is not here, appears unused.
		// NOTE: Upstream `isFinished` is not here, we simply call tryRun() once at the end.
		// NOTE: Upstream `onceFileDetectionMap` is instead Less_Environment->isFileParsed.
		// NOTE: Upstream `ImportSequencer` logic is directly inside ImportVisitor for simplicity.
	}

	public function run( $root ) {
		$this->visitObj( $root );
		$this->tryRun();
	}

	public function visitImport( $importNode, &$visitDeeper ) {
		$inlineCSS = $importNode->options['inline'];

		if ( !$importNode->css || $inlineCSS ) {

			$env = $this->env->clone();
			$importParent = $env->frames[0];
			if ( $importNode->isVariableImport() ) {
				$this->addVariableImport( [
					'function' => 'processImportNode',
					'args' => [ $importNode, $env, $importParent ]
				] );
			} else {
				$this->processImportNode( $importNode, $env, $importParent );
			}
		}
		$visitDeeper = false;
	}

	public function processImportNode( $importNode, $env, &$importParent ) {
		$evaldImportNode = $inlineCSS = $importNode->options['inline'];

		try {
			$evaldImportNode = $importNode->compileForImport( $env );
		} catch ( Exception $e ) {
			$importNode->css = true;
		}

		if ( $evaldImportNode && ( !$evaldImportNode->css || $inlineCSS ) ) {

			if ( $importNode->options['multiple'] ) {
				$env->importMultiple = true;
			}

			$tryAppendLessExtension = $evaldImportNode->css === null;

			for ( $i = 0; $i < count( $importParent->rules ); $i++ ) {
				if ( $importParent->rules[$i] === $importNode ) {
					$importParent->rules[$i] = $evaldImportNode;
					break;
				}
			}

			// Rename $evaldImportNode to $importNode here so that we avoid avoid mistaken use
			// of not-yet-compiled $importNode after this point, which upstream's code doesn't
			// have access to after this point, either.
			$importNode = $evaldImportNode;
			unset( $evaldImportNode );

			// NOTE: Upstream Less.js's ImportVisitor puts the rest of the processImportNode logic
			// into a separate ImportVisitor.prototype.onImported function, because file loading
			// is async there. They implement and call:
			//
			// - ImportSequencer.prototype.addImport:
			//   remembers what processImportNode() was doing, and will call onImported
			//   once the async file load is finished.
			// - ImportManager.prototype.push:
			//   resolves the import path to full path and uri,
			//   then parses the file content into a root Ruleset for that file.
			// - ImportVisitor.prototype.onImported:
			//   marks the file as parsed (for skipping duplicates, to avoid recursion),
			//   and calls tryRun() if this is the last remaining import.
			//
			// In PHP we load files synchronously, so we can put a simpler version of this
			// logic directly here.

			// @see less-2.5.3.js#ImportManager.prototype.push

			// NOTE: This is the equivalent to upstream `newFileInfo` and `fileManager.getPath()`

			$path = $importNode->getPath();

			if ( $tryAppendLessExtension ) {
					$path = preg_match( '/(\.[a-z]*$)|([\?;].*)$/', $path ) ? $path : $path . '.less';
			}

			[ $fullPath, $uri ] =
				Less_FileManager::getFilePath( $path, $importNode->currentFileInfo ) ?? [ $path, $path ];

			// @see less-2.5.3.js#ImportManager.prototype.push/loadFileCallback

			// NOTE: Upstream creates the next `currentFileInfo` here as `newFileInfo`
			// We instead let Less_Parser::SetFileInfo() do that later via Less_Parser::parseFile().
			// This means that instead of setting `newFileInfo.reference` we modify the $env,
			// and Less_Parser::SetFileInfo will inherit that.
			if ( $importNode->options['reference'] ?? false ) {
				$env->currentFileInfo['reference'] = true;
			}

			$e = null;
			try {
				if ( $importNode->options['inline'] ) {
					if ( !file_exists( $fullPath ) ) {
						throw new Less_Exception_Parser(
							sprintf( 'File `%s` not found.', $fullPath ),
							null,
							$importNode->index,
							$importNode->currentFileInfo
						);
					}
					$root = file_get_contents( $fullPath );
				} else {
					$parser = new Less_Parser( $env );
					// NOTE: Upstream sets `env->processImports = false` here to avoid
					// running ImportVisitor again (infinite loop). We instead separate
					// Less_Parser->parseFile() from Less_Parser->getCss(),
					// and only getCss() runs ImportVisitor.
					$root = $parser->parseFile( $fullPath, $uri, true );
				}
			} catch ( Less_Exception_Parser $err ) {
				$e = $err;
			}

			// @see less-2.5.3.js#ImportManager.prototype.push/fileParsedFunc

			if ( $importNode->options['optional'] && $e ) {
				$e = null;
				$root = new Less_Tree_Ruleset( null, [] );
				$fullPath = null;
			}

			// @see less-2.5.3.js#ImportVisitor.prototype.onImported

			if ( $e instanceof Less_Exception_Parser ) {
				if ( !is_numeric( $e->index ) ) {
					$e->index = $importNode->index;
					$e->currentFile = $importNode->currentFileInfo;
					$e->genMessage();
				}
				throw $e;
			}

			$duplicateImport = isset( $this->recursionDetector[$fullPath] );

			if ( !$env->importMultiple ) {
				if ( $duplicateImport ) {
					$importNode->doSkip = true;
				} else {
					// NOTE: Upstream implements skip() as dynamic function.
					// We instead have a regular Less_Tree_Import::skip() method,
					// and in cases where skip() would be re-defined here we set doSkip=null.
					$importNode->doSkip = null;
				}
			}

			if ( !$fullPath && $importNode->options['optional'] ) {
				$importNode->doSkip = true;
			}

			if ( $root ) {
				$importNode->root = $root;
				$importNode->importedFilename = $fullPath;

				if ( !$inlineCSS && ( $env->importMultiple || !$duplicateImport ) && $fullPath ) {
					$this->recursionDetector[$fullPath] = true;
					$oldContext = $this->env;
					$this->env = $env;
					$this->visitObj( $root );
					$this->env = $oldContext;
				}
			}
		} else {
			$this->tryRun();
		}
	}

	public function addVariableImport( $callback ) {
		$this->variableImports[] = $callback;
	}

	public function tryRun() {
		while ( true ) {
			// NOTE: Upstream keeps a `this.imports` queue here that resumes
			// processImportNode() logic by calling onImported() after a file
			// is finished loading. We don't need that since we load and parse
			// synchronously within processImportNode() instead.

			if ( count( $this->variableImports ) === 0 ) {
				break;
			}
			$variableImport = $this->variableImports[0];

			$this->variableImports = array_slice( $this->variableImports, 1 );
			$function = $variableImport['function'];

			$this->$function( ...$variableImport["args"] );
		}
	}

	public function visitDeclaration( $declNode, $visitDeeper ) {
		// TODO: We might need upstream's `if (… DetachedRuleset) { this.context.frames.unshift(ruleNode); }`
		$visitDeeper = false;
	}

	// TODO: Implement less-3.13.1.js#ImportVisitor.prototype.visitDeclarationOut
	// if (… DetachedRuleset) { this.context.frames.shift(); }

	public function visitAtRule( $atRuleNode, $visitArgs ) {
		array_unshift( $this->env->frames, $atRuleNode );
	}

	public function visitAtRuleOut( $atRuleNode ) {
		array_shift( $this->env->frames );
	}

	public function visitMixinDefinition( $mixinDefinitionNode, $visitArgs ) {
		array_unshift( $this->env->frames, $mixinDefinitionNode );
	}

	public function visitMixinDefinitionOut( $mixinDefinitionNode ) {
		array_shift( $this->env->frames );
	}

	public function visitRuleset( $rulesetNode, $visitArgs ) {
		array_unshift( $this->env->frames, $rulesetNode );
	}

	public function visitRulesetOut( $rulesetNode ) {
		array_shift( $this->env->frames );
	}

	public function visitMedia( $mediaNode, $visitArgs ) {
		// TODO: Upsteam does not modify $mediaNode here. Why do we?
		$mediaNode->allExtends = [];
		array_unshift( $this->env->frames, $mediaNode->allExtends );
	}

	public function visitMediaOut( $mediaNode ) {
		array_shift( $this->env->frames );
	}

}
