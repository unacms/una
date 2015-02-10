<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Developer Developer
 * @ingroup     TridentModules
 *
 * @{
 */

require_once('BxDevFunctions.php');

class BxDevFormsField extends BxTemplStudioFormsField
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);
    }
}

class BxDevFormsFieldBlockHeader extends BxTemplStudioFormsFieldBlockHeader
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldValue extends BxTemplStudioFormsFieldValue
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldText extends BxTemplStudioFormsFieldText
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldPassword extends BxTemplStudioFormsFieldPassword
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldTextarea extends BxTemplStudioFormsFieldTextarea
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldNumber extends BxTemplStudioFormsFieldNumber
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldDatepicker extends BxTemplStudioFormsFieldDatepicker
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldDatetime extends BxTemplStudioFormsFieldDatetime
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldCheckbox extends BxTemplStudioFormsFieldCheckbox
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);

        $this->aForm['inputs']['value']['type'] = 'text';
    }
}

class BxDevFormsFieldSwitcher extends BxTemplStudioFormsFieldSwitcher
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);

        $this->aForm['inputs']['value']['type'] = 'text';
    }
}

class BxDevFormsFieldFile extends BxTemplStudioFormsFieldFile
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldSlider extends BxTemplStudioFormsFieldSlider
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldDoublerange extends BxTemplStudioFormsFieldDoublerange
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldHidden extends BxTemplStudioFormsFieldHidden
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldButton extends BxTemplStudioFormsFieldButton
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldReset extends BxTemplStudioFormsFieldReset
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldSubmit extends BxTemplStudioFormsFieldSubmit
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldImage extends BxTemplStudioFormsFieldImage
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldSelect extends BxTemplStudioFormsFieldSelect
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldSelectMultiple extends BxTemplStudioFormsFieldSelectMultiple
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldCheckboxSet extends BxTemplStudioFormsFieldCheckboxSet
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldRadioSet extends BxTemplStudioFormsFieldRadioSet
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldCustom extends BxTemplStudioFormsFieldCustom
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldInputSet extends BxTemplStudioFormsFieldInputSet
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}

class BxDevFormsFieldCaptcha extends BxTemplStudioFormsFieldCaptcha
{
    function __construct($aParams = array(), $aField = array())
    {
        parent::__construct($aParams, $aField);

        BxDevFunctions::changeFormField($this->aParams, $this->aForm['inputs'], $this->oDb);
    }
}
/** @} */
