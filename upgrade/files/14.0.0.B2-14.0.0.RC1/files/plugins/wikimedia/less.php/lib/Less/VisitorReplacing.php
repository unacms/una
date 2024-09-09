<?php
/**
 * @private
 */
class Less_VisitorReplacing extends Less_Visitor {

	public function visitArray( &$nodes ) {
		$newNodes = [];
		foreach ( $nodes as $node ) {
			$evald = $this->visitObj( $node );
			if ( $evald ) {
				if ( is_array( $evald ) ) {
					self::flatten( $evald, $newNodes );
				} else {
					$newNodes[] = $evald;
				}
			}
		}
		return $newNodes;
	}

	public function flatten( $arr, &$out ) {
		foreach ( $arr as $item ) {
			if ( !is_array( $item ) ) {
				$out[] = $item;
				continue;
			}

			foreach ( $item as $nestedItem ) {
				if ( is_array( $nestedItem ) ) {
					self::flatten( $nestedItem, $out );
				} else {
					$out[] = $nestedItem;
				}
			}
		}

		return $out;
	}

}
