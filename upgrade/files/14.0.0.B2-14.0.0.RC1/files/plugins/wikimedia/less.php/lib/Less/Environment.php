<?php
/**
 * @private
 */
class Less_Environment {

	/**
	 * Information about the current file - for error reporting and importing and making urls relative etc.
	 *
	 * - rootpath: rootpath to append to URLs
	 *
	 * @var array|null
	 */
	public $currentFileInfo;

	/** @var bool Whether we are currently importing multiple copies */
	public $importMultiple = false;

	/**
	 * @var array
	 */
	public $frames = [];
	/** @var array */
	public $importantScope = [];
	public $inCalc = false;
	public $mathOn = true;

	private $calcStack = [];

	/** @var Less_Tree_Media[] */
	public $mediaBlocks = [];
	/** @var Less_Tree_Media[] */
	public $mediaPath = [];

	/** @var string[] */
	public $imports = [];

	/**
	 * This is the equivalent of `importVisitor.onceFileDetectionMap`
	 * as used by the dynamic `importNode.skip` function.
	 *
	 * @see less-2.5.3.js#ImportVisitor.prototype.onImported
	 * @var array<string,true>
	 */
	public $importVisitorOnceMap = [];

	public static $tabLevel = 0;

	public static $lastRule = false;

	public static $_noSpaceCombinators;

	public static $mixin_stack = 0;

	public $math = self::MATH_PARENS_DIVISION;

	public $importCallback = null;

	public $parensStack = [];

	public const MATH_ALWAYS = 0;
	public const MATH_PARENS_DIVISION = 1;
	public const MATH_PARENS = 2;

	/**
	 * @var array
	 */
	public $functions = [];

	public function Init() {
		self::$tabLevel = 0;
		self::$lastRule = false;
		self::$mixin_stack = 0;

		self::$_noSpaceCombinators = [
			'' => true,
			' ' => true,
			'|' => true
		];
	}

	/**
	 * @param string $file
	 * @return void
	 */
	public function addParsedFile( $file ) {
		$this->imports[] = $file;
	}

	public function clone() {
		$new_env = clone $this;
		// NOTE: Match JavaScript by-ref behaviour for arrays
		$new_env->imports =& $this->imports;
		$new_env->importVisitorOnceMap =& $this->importVisitorOnceMap;
		return $new_env;
	}

	/**
	 * @param string $file
	 * @return bool
	 */
	public function isFileParsed( $file ) {
		return in_array( $file, $this->imports );
	}

	public function copyEvalEnv( $frames = [] ) {
		$new_env = new self();
		$new_env->frames = $frames;
		$new_env->importantScope = $this->importantScope;
		$new_env->math = $this->math;
		return $new_env;
	}

	/**
	 * @return bool
	 * @see less-3.13.1.js#Eval.prototype.isMathOn
	 */
	public function isMathOn( $op = "" ) {
		if ( !$this->mathOn ) {
			return false;
		}
		if ( $op === '/' && $this->math !== $this::MATH_ALWAYS && !$this->parensStack ) {
			return false;
		}

		if ( $this->math > $this::MATH_PARENS_DIVISION ) {
			return (bool)$this->parensStack;
		}
		return true;
	}

	/**
	 * @see less-3.13.1.js#Eval.prototype.inParenthesis
	 */
	public function inParenthesis() {
		// Optimization: We don't need undefined/null, always have an array
		$this->parensStack[] = true;
	}

	/**
	 * @see less-3.13.1.js#Eval.prototype.inParenthesis
	 */
	public function outOfParenthesis() {
		array_pop( $this->parensStack );
	}

	/**
	 * @param string $path
	 * @return bool
	 * @see less-2.5.3.js#Eval.isPathRelative
	 */
	public static function isPathRelative( $path ) {
		return !preg_match( '/^(?:[a-z-]+:|\/|#)/', $path );
	}

	public function enterCalc() {
		$this->calcStack[] = true;
		$this->inCalc = true;
	}

	public function exitCalc() {
		array_pop( $this->calcStack );
		if ( !$this->calcStack ) {
			$this->inCalc = false;
		}
	}

	/**
	 * Canonicalize a path by resolving references to '/./', '/../'
	 * Does not remove leading "../"
	 * @param string $path or url
	 * @return string Canonicalized path
	 */
	public static function normalizePath( $path ) {
		$segments = explode( '/', $path );
		$segments = array_reverse( $segments );

		$path = [];
		$path_len = 0;

		while ( $segments ) {
			$segment = array_pop( $segments );
			switch ( $segment ) {

				case '.':
					break;

				case '..':
					// @phan-suppress-next-line PhanTypeInvalidDimOffset False positive
					if ( !$path_len || ( $path[$path_len - 1] === '..' ) ) {
						$path[] = $segment;
						$path_len++;
					} else {
						array_pop( $path );
						$path_len--;
					}
					break;

				default:
					$path[] = $segment;
					$path_len++;
					break;
			}
		}

		return implode( '/', $path );
	}

	public function unshiftFrame( $frame ) {
		array_unshift( $this->frames, $frame );
	}

	public function shiftFrame() {
		return array_shift( $this->frames );
	}

}
