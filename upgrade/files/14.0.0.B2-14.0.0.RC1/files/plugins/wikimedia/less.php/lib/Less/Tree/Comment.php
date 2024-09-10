<?php
/**
 * @private
 * @see less-2.5.3.js#Comment.prototype
 */
class Less_Tree_Comment extends Less_Tree implements Less_Tree_HasValueProperty {
	public $value;
	public $isLineComment;
	public $isReferenced;
	public $currentFileInfo;

	public function __construct( $value, $isLineComment, $index = null, $currentFileInfo = null ) {
		$this->value = $value;
		$this->isLineComment = (bool)$isLineComment;
		$this->currentFileInfo = $currentFileInfo;
	}

	public function genCSS( $output ) {
		// NOTE: Skip debugInfo handling (not implemented)

		$output->add( $this->value );
	}

	public function isSilent() {
		$isReference = ( $this->currentFileInfo && isset( $this->currentFileInfo['reference'] ) && ( !isset( $this->isReferenced ) || !$this->isReferenced ) );
		$isCompressed = Less_Parser::$options['compress'] && ( $this->value[2] ?? '' ) !== "!";
		return $this->isLineComment || $isReference || $isCompressed;
	}

	public function markReferenced() {
		$this->isReferenced = true;
	}

}
