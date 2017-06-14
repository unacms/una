<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Developer Developer
 * @ingroup     UnaModules
 *
 * @{
 */

require_once('BxDevFunctions.php');

class BxDevFormsField extends BxTemplStudioFormsField
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        $this->aTypes = array_merge($this->aTypes, array(
        	'captcha' => array('add' => 1),
        	'location' => array('add' => 1), 
        	'custom' => array('add' => 1)
        ));
    }
}

class BxDevFormsFieldBlockHeader extends BxTemplStudioFormsFieldBlockHeader
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldValue extends BxTemplStudioFormsFieldValue
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldText extends BxTemplStudioFormsFieldText
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldPassword extends BxTemplStudioFormsFieldPassword
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldTextarea extends BxTemplStudioFormsFieldTextarea
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'required', array(
            'unique' => $this->aFieldUnique
        ));
    }
}

class BxDevFormsFieldNumber extends BxTemplStudioFormsFieldNumber
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldDatepicker extends BxTemplStudioFormsFieldDatepicker
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldDatetime extends BxTemplStudioFormsFieldDatetime
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldCheckbox extends BxTemplStudioFormsFieldCheckbox
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);

        $this->aForm['inputs']['value']['type'] = 'text';
    }
}

class BxDevFormsFieldSwitcher extends BxTemplStudioFormsFieldSwitcher
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);

        $this->aForm['inputs']['value']['type'] = 'text';
    }
}

class BxDevFormsFieldFile extends BxTemplStudioFormsFieldFile
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldFiles extends BxTemplStudioFormsFieldFiles
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldSlider extends BxTemplStudioFormsFieldSlider
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'required', array(
            'unique' => $this->aFieldUnique
        ));
    }
}

class BxDevFormsFieldDoublerange extends BxTemplStudioFormsFieldDoublerange
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'required', array(
            'unique' => $this->aFieldUnique
        ));
    }
}

class BxDevFormsFieldHidden extends BxTemplStudioFormsFieldHidden
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldButton extends BxTemplStudioFormsFieldButton
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldReset extends BxTemplStudioFormsFieldReset
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldSubmit extends BxTemplStudioFormsFieldSubmit
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldImage extends BxTemplStudioFormsFieldImage
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldSelect extends BxTemplStudioFormsFieldSelect
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'required', array(
            'unique' => $this->aFieldUnique
        ));
    }
}

class BxDevFormsFieldSelectMultiple extends BxTemplStudioFormsFieldSelectMultiple
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'required', array(
            'unique' => $this->aFieldUnique
        ));
    }
}

class BxDevFormsFieldCheckboxSet extends BxTemplStudioFormsFieldCheckboxSet
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'required', array(
            'unique' => $this->aFieldUnique
        ));
    }
}

class BxDevFormsFieldRadioSet extends BxTemplStudioFormsFieldRadioSet
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'required', array(
            'unique' => $this->aFieldUnique
        ));
    }
}

class BxDevFormsFieldCustom extends BxTemplStudioFormsFieldCustom
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldInputSet extends BxTemplStudioFormsFieldInputSet
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldCaptcha extends BxTemplStudioFormsFieldCaptcha
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldLocation extends BxTemplStudioFormsFieldLocation
{
    public function init()
	{
		parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}
/** @} */
