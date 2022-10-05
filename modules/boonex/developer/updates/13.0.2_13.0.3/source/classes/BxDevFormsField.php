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

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldBlockEnd extends BxTemplStudioFormsFieldBlockEnd
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldValue extends BxTemplStudioFormsFieldValue
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldText extends BxTemplStudioFormsFieldText
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldPassword extends BxTemplStudioFormsFieldPassword
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
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

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldNumber extends BxTemplStudioFormsFieldNumber
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldDatepicker extends BxTemplStudioFormsFieldDatepicker
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldDateselect extends BxTemplStudioFormsFieldDateselect
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldDatetime extends BxTemplStudioFormsFieldDatetime
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
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

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
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

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldFile extends BxTemplStudioFormsFieldFile
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldFiles extends BxTemplStudioFormsFieldFiles
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
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

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
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

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldHidden extends BxTemplStudioFormsFieldHidden
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldButton extends BxTemplStudioFormsFieldButton
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldReset extends BxTemplStudioFormsFieldReset
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldSubmit extends BxTemplStudioFormsFieldSubmit
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldImage extends BxTemplStudioFormsFieldImage
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
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

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
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

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
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

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
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

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldCustom extends BxTemplStudioFormsFieldCustom
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldInputSet extends BxTemplStudioFormsFieldInputSet
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldCaptcha extends BxTemplStudioFormsFieldCaptcha
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}

class BxDevFormsFieldLocation extends BxTemplStudioFormsFieldLocation
{
    public function init()
    {
        parent::init();

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }

    protected function getFormAdd($sAction, $sObject)
    {
        $aForm = parent::getFormAdd($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }

    protected function getFormEdit($sAction, $sObject)
    {
        $aForm = parent::getFormEdit($sAction, $sObject);
        BxDevFunctions::changeForm($sAction, $aForm, $this);
        return $aForm;
    }
}
/** @} */
