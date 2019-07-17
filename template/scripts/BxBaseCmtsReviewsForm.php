<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

class BxBaseCmtsReviewsForm extends BxTemplCmtsForm
{
    protected $_sSystem; ///< current review system name
    protected $_iId; ///< obect id to be reviewed

    public function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);

        $this->_sSystem = '';
        $this->_iId = 0;
    }

    protected function genCustomInputCmtMood ($aInput)
    {
        $oSystem = $this->_getSystemObject();

        $sStylePrefix = $oSystem->getStylePrefix();
        $sJsObject = $oSystem->getJsObjectName();

        $iMoodMinValue = $oSystem->getMoodMinValue();
        $iMoodMaxValue = $oSystem->getMoodMaxValue();

        $aTmplVarsStars = $aTmplVarsSlider = $aTmplVarsButtons = array();
        for($i = $iMoodMinValue; $i <= $iMoodMaxValue; $i++) {
            $aTmplVarsStars[] = array(
                'style_prefix' => $sStylePrefix,
                'value' => $i
            );

            $aTmplVarsSlider[] = array(
                'style_prefix' => $sStylePrefix
            );

            $aTmplVarsButtons[] = array(
                'style_prefix' => $sStylePrefix,
                'js_object' => $sJsObject,
                'value' => $i
            );
        }

        return $this->oTemplate->parseHtmlByName('review_mood_form_field.html', array(
            'style_prefix' => $sStylePrefix,
            'name' => $aInput['name'],
            'value' => !empty($aInput['value']) ? $aInput['value'] : 0,
            'bx_repeat:stars' => $aTmplVarsStars,
            'bx_repeat:slider' => $aTmplVarsSlider,
            'bx_repeat:buttons' => $aTmplVarsButtons,
            'bx_if:show_init' => array(
                'condition' => $this->_bDynamicMode,
                'content' => array(
                    'style_prefix' => $sStylePrefix,
                    'js_object' => $sJsObject,
                    'form_id' => $this->getId(),
                )
            )
        ));
    }

    protected function _getSystemObject()
    {
        if(empty($this->_sSystem))
            $this->_sSystem = $this->aInputs['sys']['value'];

        if(empty($this->_iId))
            $this->_iId = (int)$this->aInputs['id']['value'];

        return BxDolCmts::getObjectInstance($this->_sSystem, $this->_iId);
    }
}

/** @} */
