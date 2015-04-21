<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     TridentModules
 *
 * @{
 */

class BxBaseModGeneralCmtsSearchResult extends BxBaseModGeneralSearchResult
{
	protected $sModule;
	protected $sModuleObjectComments;

    function __construct($sMode = '', $aParams = array())
    {
        parent::__construct($sMode, $aParams);

        $this->oModule = $this->getMain();
        $this->sModuleObjectComments = $this->oModule->_oConfig->CNF['OBJECT_COMMENTS'];

        $this->aCurrent = array(
        	'name' => $this->oModule->_oConfig->getName() . '_cmts',
        	'module_name' => $this->oModule->_oConfig->getName(),
        	'title' => '',
            'table' => $this->oModule->_oConfig->getDbPrefix() . 'cmts',
            'ownFields' => array('cmt_id', 'cmt_object_id', 'cmt_author_id', 'cmt_text', 'cmt_time'),
            'searchFields' => array('cmt_text'),
            'restriction' => array(
                'author' => array('value' => '', 'field' => 'cmt_author_id', 'operator' => '='),
            ),
            'paginate' => array('start' => 0, 'perPage' => 3),
            'sorting' => 'last',
        );

        $this->sBrowseUrl = BX_DOL_SEARCH_KEYWORD_PAGE;
    }

    function getMain()
    {
        return BxDolModule::getInstance($this->sModule);
    }

    function displaySearchUnit($aData)
    {
		$oCmts = BxDolCmts::getObjectInstance($this->sModuleObjectComments, $aData['object_id']);
		$oCmts->addCssJs();

		if($this->_bLiveSearch) {
			$aCnf = &$this->oModule->_oConfig->CNF;

			return $oCmts->getCommentLiveSearch($aData['id'], array(
				'txt_sample_single' => isset($aCnf['T']['txt_sample_comment_single']) ? $aCnf['T']['txt_sample_comment_single'] : ''
			));
		}
		else
			return $oCmts->getComment($aData['id'], array(), array('type' => BX_CMT_DISPLAY_FLAT, 'view_only' => true));
    }

    function displayResultBlock ()
    {
        $s = parent::displayResultBlock ();
        $s = '<ul class="cmts">' . $s . '</ul>';
        return $s;
    }

    function getAlterOrder()
    {
        if($this->aCurrent['sorting'] == 'last')
            return array(
            	'order' => " ORDER BY `" . $this->aCurrent['table'] . "`.`cmt_time` DESC"
            );

        return array();
    }

    function _getPseud ()
    {
        return array(
            'id' => 'cmt_id',
        	'object_id' => 'cmt_object_id',
            'author' => 'cmt_author_id',
            'text' => 'cmt_text',
            'added' => 'cmt_time'
        );
    }
}

/** @} */
