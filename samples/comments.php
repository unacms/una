<?php
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
 * DB Queries.

INSERT INTO `sys_objects_cmts` (`ID`, `ObjectName`, `TableCmts`, `TableTrack`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Nl2br`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
(1, 'profile', 'sys_cmts_sample', 'sys_cmts_sample_track', 3, 2048, 100, 1, 5, 3, 'tail', 1, 'both', 1, 1, 1, -3, 1, 'cmt', '', '', '', '', '');

CREATE TABLE IF NOT EXISTS `sys_cmts_sample` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_cmts_sample_track` (
  `cmt_system_id` int(11) NOT NULL DEFAULT '0',
  `cmt_id` int(11) NOT NULL DEFAULT '0',
  `cmt_rate` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate_author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cmt_rate_author_nip` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_rate_ts` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cmt_system_id`,`cmt_id`,`cmt_rate_author_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "params.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxDolAcl');
bx_import('BxDolLanguages');
bx_import('BxTemplFunctions');

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Comments");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();


/**
 * page code function
 */
function PageCompMainCode() {
	$iAccountId = getLoggedId();

	bx_import('BxTemplCmtsView');
	$oCmts = new BxTemplCmtsView('profile', (int)$iAccountId);
	if(!$oCmts->isEnabled())
		return '';

    return DesignBoxContent("Comments",  $oCmts->getCommentsBlock(), BX_DB_PADDING_DEF);
}



/** @} */
