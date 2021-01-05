<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

class BxBaseLabelForm extends BxTemplFormView
{
    protected $_oObject;

    public function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);

        $this->_oObject = BxDolLabel::getInstance();

        switch($this->aParams['display']) {
            case 'sys_labels_select':
                $this->aInputs['action']['value'] = 'select_labels';
                break;
        }
    }

    public function initChecker($aValues = array(), $aSpecificValues = array())
    {
        return parent::initChecker($aValues, $aSpecificValues);
    }

    protected function genCustomInputSearch($aInput)
    {
        $aInput = array_merge($aInput, array(
            'custom' => array(
                'b_img' => false
            ),
            'ajax_get_suggestions' => BX_DOL_URL_ROOT . bx_append_url_params('label.php', array(
              'action' => 'labels_list',
            ))
        ));

        return $this->genCustomInputUsernamesSuggestions($aInput);
    }
    
    protected function genCustomInputList($aInput)
    {
        return $this->_oObject->getLabelsList($aInput, $this);
    }
}

/** @} */
