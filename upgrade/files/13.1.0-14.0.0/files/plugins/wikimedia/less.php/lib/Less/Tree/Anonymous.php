<?php
/**
 * @private
 * @see less-2.5.3.js#Anonymous.prototype
 */
class Less_Tree_Anonymous extends Less_Tree implements Less_Tree_HasValueProperty {
	public $value;
	public $quote;
	public $index;
	public $mapLines;
	public $currentFileInfo;
	/** @var bool */
	public $rulesetLike;
	public $isReferenced;

	/**
	 * @param string $value
	 * @param int|null $index
	 * @param array|null $currentFileInfo
	 * @param bool|null $mapLines
	 * @param bool $rulesetLike
	 * @param bool $referenced
	 */
	public function __construct( $value, $index = null, $currentFileInfo = null, $mapLines = null, $rulesetLike = false, $referenced = false ) {
		$this->value = $value;
		$this->index = $index;
		$this->mapLines = $mapLines;
		$this->currentFileInfo = $currentFileInfo;
		$this->rulesetLike = $rulesetLike;
		// TODO: remove isReferenced and implement $visibilityInfo
		// https://github.com/less/less.js/commit/ead3e29f7b79390ad3ac798bf42195b24919107d
		$this->isReferenced = $referenced;
	}

	public function compile( $env ) {
		return new self( $this->value, $this->index, $this->currentFileInfo, $this->mapLines, $this->rulesetLike, $this->isReferenced );
	}

	/**
	 * @param Less_Tree|mixed $x
	 * @return int|null
	 * @see less-3.13.1.js#Anonymous.prototype.compare
	 */
	public function compare( $x ) {
		return ( $x instanceof Less_Tree && $this->toCSS() === $x->toCSS() ) ? 0 : null;
	}

	public function isRulesetLike() {
		return $this->rulesetLike;
	}

	/**
	 * @see less-3.13.1.js#Anonymous.prototype.genCSS
	 */
	public function genCSS( $output ) {
		// TODO: When we implement $visibilityInfo, store this result in-class
		$nodeVisible = $this->value !== "" && $this->value !== 0;
		if ( $nodeVisible ) {
			$output->add( $this->value, $this->currentFileInfo, $this->index, $this->mapLines );
		}
	}

	public function markReferenced() {
		$this->isReferenced = true;
	}

	public function getIsReferenced() {
		return !isset( $this->currentFileInfo['reference'] ) || !$this->currentFileInfo['reference'] || $this->isReferenced;
	}
}
