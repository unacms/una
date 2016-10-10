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
 * Entry create/edit pages
 */
class BxForumPageEntry extends BxBaseModTextPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_forum';
        parent::__construct($aObject, $oTemplate);

        $this->_oModule->_oTemplate->addJs(array('main.js', 'entry.js'));
        $this->_oModule->_oTemplate->addCss(array('main-media-phone.css', 'main-media-tablet.css', 'main-media-desktop.css'));
    }

    public function getCode()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$aCategory = $this->_oModule->_oDb->getCategories(array('type' => 'by_category', 'category' => $this->_aContentInfo[$CNF['FIELD_CATEGORY']]));
    	if(!empty($aCategory['visible_for_levels']) && !BxDolAcl::getInstance()->isMemberLevelInSet($aCategory['visible_for_levels']))
    		return $this->_oTemplate->displayAccessDenied();

    	return $this->_oModule->_oTemplate->getJsCode('entry') . parent::getCode();
    }
}

/** @} */
