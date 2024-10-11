<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Courses Courses
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxCoursesFormContentNode extends BxTemplFormView
{
    protected $_sModule;
    protected $_oModule;

    protected $_iParentId;
    protected $_iLevel;
    protected $_iLevelMax;
    protected $_aLevelMaxInputs;

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_courses';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aInfo, $oTemplate);

        $this->_iParentId = 0;
        $this->_iLevel = 0;
        $this->_iLevelMax = $this->_oModule->_oConfig->getContentLevelMax();
        $this->_aLevelMaxInputs = ['text', 'passing'];
        
        if(isset($this->aInputs['passing']))
            $this->aInputs['passing']['values'] = [
                ['key' => 0, 'value' => _t('_bx_courses_form_content_node_input_passing_all')],
                ['key' => 1, 'value' => _t('_bx_courses_form_content_node_input_passing_alternate')]
            ];
    }

    public function setData($iParentId, $iLevel = 0)
    {
        $this->_iParentId = $iParentId;

        if(!$iLevel) {
            $aParent = $this->_oModule->_oDb->getContentStructure([
                'sample' => 'node_id', 
                'node_id' => $this->_iParentId
            ]);

            $iLevel = $this->_oModule->getNodeLevelByParent($aParent);
        }

        $this->_iLevel = $iLevel;
    }

    public function initChecker($aValues = [], $aSpecificValues = [])
    {
        if($this->_iLevel != $this->_iLevelMax)
            $this->aInputs = array_diff_key($this->aInputs, array_flip($this->_aLevelMaxInputs));

        parent::initChecker ($aValues, $aSpecificValues);
    }
}

/** @} */
