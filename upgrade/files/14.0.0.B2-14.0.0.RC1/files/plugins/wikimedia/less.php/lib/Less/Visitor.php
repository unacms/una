<?php
/**
 * @private
 */
class Less_Visitor {

	protected $methods = [];
	protected $_visitFnCache = [];

	public function __construct() {
		$this->_visitFnCache = get_class_methods( get_class( $this ) );
		$this->_visitFnCache = array_flip( $this->_visitFnCache );
	}

	public function visitObj( $node ) {
		if ( !$node || !is_object( $node ) ) {
			return $node;
		}
		$funcName = 'visit' . str_replace( [ 'Less_Tree_', '_' ], '', get_class( $node ) );
		if ( isset( $this->_visitFnCache[$funcName] ) ) {
			$visitDeeper = true;
			$newNode = $this->$funcName( $node, $visitDeeper );
			if ( $this instanceof Less_VisitorReplacing ) {
				$node = $newNode;
			}

			if ( $visitDeeper && is_object( $node ) ) {
				$node->accept( $this );
			}

			$funcName .= 'Out';
			if ( isset( $this->_visitFnCache[$funcName] ) ) {
				$this->$funcName( $node );
			}

		} else {
			$node->accept( $this );
		}

		return $node;
	}

	public function visitArray( &$nodes ) {
		// NOTE: The use of by-ref in a normal (non-replacing) Visitor may be surprising,
		// but upstream relies on this for Less_ImportVisitor, which modifies values of
		// `$importParent->rules` yet is not a replacing visitor.
		foreach ( $nodes as &$node ) {
			$this->visitObj( $node );
		}
		return $nodes;
	}
}
