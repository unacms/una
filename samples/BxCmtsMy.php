<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Samples
 * @{
 */

/** 
 * @page samples
 * @section comments Comments
 */

/**
 * DB Queries for Comments Sample.

INSERT INTO `sys_objects_cmts` (`ID`, `Name`, `TableCmts`, `TableTrack`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Nl2br`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
(1, 'sample', 'sample_cmts', 'sample_cmts_track', 1, 5000, 1000, 1, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'samples/comments.php', 'sample_cmts_trigger', 'id', 'title', 'comments', 'BxCmtsMy', 'samples/BxCmtsMy.php');

CREATE TABLE IF NOT EXISTS `sample_cmts` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sample_cmts_track` (
  `cmt_system_id` int(11) NOT NULL DEFAULT '0',
  `cmt_id` int(11) NOT NULL DEFAULT '0',
  `cmt_rate` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate_author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cmt_rate_author_nip` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_rate_ts` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cmt_system_id`,`cmt_id`,`cmt_rate_author_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sample_cmts_trigger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `sample_cmts_trigger` (`id`, `title`, `comments`) VALUES
(1, 'Sample Comments', 0);

 */


/**
 * DB Queries for Privacy Sample.
 * 

ALTER TABLE `sample_cmts` ADD `allow_view_to` INT( 11 ) NOT NULL DEFAULT '3';

INSERT INTO `sys_objects_privacy`(`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('comments_view', 'comments', 'view', '', '3', 'sample_cmts', 'cmt_id', 'cmt_author_id', '', '');

 */

require_once(BX_DOL_DIR_STUDIO_INC . "utils.inc.php");

bx_import('BxTemplCmtsView');

class BxCmtsMy extends BxTemplCmtsView
{
	function BxCmtsMy( $sSystem, $iId, $iInit = 1 )
	{
        BxTemplCmtsView::BxTemplCmtsView( $sSystem, $iId, $iInit );
    }

    function getComment($mixedCmt, $aBp = array(), $aDp = array())
    {
    	$iCmtId = is_array($mixedCmt) ? (int)$mixedCmt['cmt_id'] : (int)$mixedCmt;

    	bx_import('BxDolPrivacy');
    	$oPrivacy = BxDolPrivacy::getObjectInstance('comments_view');
		if(!$oPrivacy->check($iCmtId))
			return '';

    	return parent::getComment($mixedCmt, $aBp, $aDp);
    }

    protected function _getFormObject($sAction, $iId)
    {
    	$oForm = parent::_getFormObject($sAction, $iId);

    	$sModule = 'comments';
    	$sAction = 'view';

    	bx_import('BxDolPrivacy');
    	$sFieldName = BxDolPrivacy::getFieldName($sAction);
    	$aFieldDescriptor = BxDolPrivacy::getGroupChooser($sModule, $sAction);
		$aFieldDescriptor['caption'] = '';
	
    	$oForm->aInputs = bx_array_insert_after(array($sFieldName => $aFieldDescriptor), $oForm->aInputs, 'cmt_text');
    	return $oForm;
    }
}

/** @} */
