<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Forum Forum
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Browse entries pages.
 */
class BxForumPageBrowse extends BxBaseModTextPageBrowse
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_forum';
        parent::__construct($aObject, $oTemplate);

        $this->_oModule->_oTemplate->addJs(array('main.js'));

        $iCategory = bx_process_input(bx_get('category'), BX_DATA_INT);
        if(!empty($iCategory))
	        $this->addMarkers(array(
	        	'category_id' => $iCategory,
	        	'category_name' => BxDolCategory::getObjectInstance($this->_oModule->_oConfig->CNF['OBJECT_CATEGORY'])->getCategoryTitle($iCategory),
	        ));

		$sKeyword = bx_process_input(bx_get('keyword'));
        if(!empty($sKeyword))
	        $this->addMarkers(array(
	        	'keyword_name' => '#' . $sKeyword
	        ));
    }

	public function getCode()
    {
    	return $this->_oModule->_oTemplate->getJsCode('main') . parent::getCode();
    }
}

/** @} */
