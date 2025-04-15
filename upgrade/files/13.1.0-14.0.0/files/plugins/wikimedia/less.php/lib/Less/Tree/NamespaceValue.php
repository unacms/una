<?php
/**
 * @private
 * @see less-3.13.1.js#NamespaceValue.prototype
 */
class Less_Tree_NamespaceValue extends Less_Tree {

	/** @var Less_Tree_Mixin_Call|Less_Tree_VariableCall */
	public $value;
	public $index;
	public $lookups;
	public $currentFileInfo;

	public function __construct( $ruleCall, $lookups, $index = null, $currentFileInfo = null ) {
		$this->value = $ruleCall;
		$this->lookups = $lookups;
		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
	}

	public function compile( $env ) {
		/** @var Less_Tree_Ruleset $rules */
		$rules = $this->value->compile( $env );

		foreach ( $this->lookups as $name ) {
			/**
			 * Eval'd DRs return rulesets.
			 * Eval'd mixins return rules, so let's make a ruleset if we need it.
			 * We need to do this because of late parsing of values
			 */
			if ( is_array( $rules ) ) {
				$rules = new Less_Tree_Ruleset( [ new Less_Tree_Selector( [] ) ], $rules );
			}
			if ( $name === '' ) {
				$rules = $rules->lastDeclaration();
			} elseif ( $name[0] === '@' ) {
				if ( ( $name[1] ?? '' ) === '@' ) {
					$variable = ( new Less_Tree_Variable( substr( $name, 1 ) ) )->compile( $env );
					$name = "@" . $variable->value;
				}
				if ( $rules instanceof Less_Tree_Ruleset ) {
					$rules = $rules->variable( $name );
				}

				if ( !$rules ) {
					throw new Less_Exception_Compiler(
						"Variable $name not found",
						null,
						$this->index,
						$this->currentFileInfo
					);
				}
			} else {
				if ( strncmp( $name, '$@', 2 ) === 0 ) {
					$variable = ( new Less_Tree_Variable( substr( $name, 1 ) ) )->compile( $env );
					$name = "$" . $variable->value;
				} else {
					$name = $name[0] === '$' ? $name : ( '$' . $name );
				}

				if ( $rules instanceof Less_Tree_Ruleset ) {
					$rules = $rules->property( $name );
				}

				if ( !$rules ) {
					throw new Less_Exception_Compiler(
						"Property $name not found",
						null,
						$this->index,
						$this->currentFileInfo
					);
				}
				// Properties are an array of values, since a ruleset can have multiple props.
				// We pick the last one (the "cascaded" value)
				if ( is_array( $rules ) ) { // to satisfy phan checks
					$rules = $rules[ count( $rules ) - 1 ];
				}
			}

			if ( $rules->value ) {
				$rules = $rules->compile( $env )->value;
			}
			if ( $rules instanceof Less_Tree_DetachedRuleset && $rules->ruleset ) {
				// @todo - looks like this is never evaluated, investigate later
				// @see https://github.com/less/less.js/commit/29468bffcd8a9f2f
				$rules = $rules->ruleset->compile( $env );
			}
		}

		return $rules;
	}

}
