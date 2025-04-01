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
		static $funcNames = [];

		if ( !$node || !is_object( $node ) ) {
			return $node;
		}

		// Map a class name like "Less_Tree_Foo_Bar" to method like "visitFooBar".
		//
		// We do this by taking the last part of the class name (instead of doing
		// a find-replace from "Less_Tree" to "visit"), so that we support codemod
		// tools (such as Strauss and Mozart), which may modify our code in-place
		// to add a namespace or class prefix.
		// "MyVendor\Something_Less_Tree_Foo_Bar" should also map to "FooBar".
		//
		// https://packagist.org/packages/brianhenryie/strauss
		// https://packagist.org/packages/coenjacobs/mozart
		$class = get_class( $node );
		$funcName = $funcNames[$class] ??= 'visit' . str_replace( [ '_', '\\' ], '',
			substr( $class, strpos( $class, 'Less_Tree_' ) + 10 )
		);

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
