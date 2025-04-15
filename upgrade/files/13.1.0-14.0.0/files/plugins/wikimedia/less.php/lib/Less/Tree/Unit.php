<?php
/**
 * @private
 * @see less-2.5.3.js#Unit.prototype
 */
class Less_Tree_Unit extends Less_Tree {

	public $numerator = [];
	public $denominator = [];
	public $backupUnit;

	public function __construct( $numerator = [], $denominator = [], $backupUnit = null ) {
		$this->numerator = $numerator;
		$this->denominator = $denominator;
		sort( $this->numerator );
		sort( $this->denominator );
		$this->backupUnit = $backupUnit ?? $numerator[0] ?? null;
	}

	public function clone() {
		// we are recreating a new object to trigger logic from constructor
		return new Less_Tree_Unit( $this->numerator, $this->denominator, $this->backupUnit );
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ) {
		$strictUnits = Less_Parser::$options['strictUnits'];

		if ( count( $this->numerator ) === 1 ) {
			$output->add( $this->numerator[0] ); // the ideal situation
		} elseif ( !$strictUnits && $this->backupUnit ) {
			$output->add( $this->backupUnit );
		} elseif ( !$strictUnits && $this->denominator ) {
			$output->add( $this->denominator[0] );
		}
	}

	public function toString() {
		$returnStr = implode( '*', $this->numerator );
		foreach ( $this->denominator as $d ) {
			$returnStr .= '/' . $d;
		}
		return $returnStr;
	}

	public function __toString() {
		return $this->toString();
	}

	/**
	 * @param self $other
	 */
	public function compare( $other ) {
		return $this->is( $other->toString() ) ? 0 : -1;
	}

	public function is( $unitString ) {
		return strtoupper( $this->toString() ) === strtoupper( $unitString );
	}

	public function isLength() {
		$css = $this->toCSS();
		return (bool)preg_match( '/px|em|%|in|cm|mm|pc|pt|ex/', $css );
	}

	// TODO: Remove unused method
	public function isAngle() {
		return isset( Less_Tree_UnitConversions::$angle[$this->toCSS()] );
	}

	public function isEmpty() {
		return !$this->numerator && !$this->denominator;
	}

	public function isSingular() {
		return count( $this->numerator ) <= 1 && !$this->denominator;
	}

	public function usedUnits() {
		$result = [];

		foreach ( Less_Tree_UnitConversions::$groups as $groupName ) {
			$group = Less_Tree_UnitConversions::${$groupName};

			foreach ( $this->numerator as $atomicUnit ) {
				if ( isset( $group[$atomicUnit] ) && !isset( $result[$groupName] ) ) {
					$result[$groupName] = $atomicUnit;
				}
			}

			foreach ( $this->denominator as $atomicUnit ) {
				if ( isset( $group[$atomicUnit] ) && !isset( $result[$groupName] ) ) {
					$result[$groupName] = $atomicUnit;
				}
			}
		}

		return $result;
	}

	/**
	 * @see less-2.5.3.js#Unit.prototype.cancel
	 */
	public function cancel() {
		$counter = [];

		foreach ( $this->numerator as $atomicUnit ) {
			$counter[$atomicUnit] = ( $counter[$atomicUnit] ?? 0 ) + 1;
		}

		foreach ( $this->denominator as $atomicUnit ) {
			$counter[$atomicUnit] = ( $counter[$atomicUnit] ?? 0 ) - 1;
		}

		$this->numerator = [];
		$this->denominator = [];

		foreach ( $counter as $atomicUnit => $count ) {
			if ( $count > 0 ) {
				for ( $i = 0; $i < $count; $i++ ) {
					$this->numerator[] = $atomicUnit;
				}
			} elseif ( $count < 0 ) {
				for ( $i = 0; $i < -$count; $i++ ) {
					$this->denominator[] = $atomicUnit;
				}
			}
		}

		sort( $this->numerator );
		sort( $this->denominator );
	}

}
