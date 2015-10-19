<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Samples
 * @{
 */

/**
 * @page samples
 * @section comments Comments
 */

/**
 * DB Queries for Vote Sample.

INSERT INTO `sys_objects_vote`(`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES
('sample_cmts_vote', 'sample_cmts_votes', 'sample_cmts_votes_track', '604800', '1', '1', '1', '1', 'sample_cmts', 'cmt_id', 'cmt_rate', 'cmt_votes', '', '');

CREATE TABLE IF NOT EXISTS `sample_cmts_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sample_cmts_votes_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `vote` (`object_id`, `author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

 */

/**
 * DB Queries for Comments Sample.

INSERT INTO `sys_objects_cmts` (`ID`, `Name`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Nl2br`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
(1, 'sample', 'sample_cmts', 1, 5000, 1000, 1, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'samples/comments.php', 'sample_cmts_vote', 'sample_cmts_trigger', 'id', 'title', 'comments', 'BxCmtsMy', 'samples/BxCmtsMy.php');

CREATE TABLE IF NOT EXISTS `sample_cmts` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_votes` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

Note: 'sample_cmts_trigger' table is needed just to check comments counter. In real integration it should be your main content table.
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

class BxCmtsMy extends BxTemplCmts
{
    function BxCmtsMy( $sSystem, $iId, $iInit = 1 )
    {
        parent::BxTemplCmts( $sSystem, $iId, $iInit );
    }

    function getComment($mixedCmt, $aBp = array(), $aDp = array())
    {
        $iCmtId = is_array($mixedCmt) ? (int)$mixedCmt['cmt_id'] : (int)$mixedCmt;

        $oPrivacy = BxDolPrivacy::getObjectInstance('comments_view');
        if(!$oPrivacy->check($iCmtId))
            return '';

        return parent::getComment($mixedCmt, $aBp, $aDp);
    }

    protected function _getFormObject($sAction, $iId)
    {
        $oForm = parent::_getFormObject($sAction, $iId);

        $sPrivacyObject = 'comments_view';
        $sFieldName = BxDolPrivacy::getFieldName($sPrivacyObject);
        $aFieldDescriptor = BxDolPrivacy::getGroupChooser($sPrivacyObject);
        $aFieldDescriptor['caption'] = '';

        $oForm->aInputs = bx_array_insert_after(array($sFieldName => $aFieldDescriptor), $oForm->aInputs, 'cmt_text');
        return $oForm;
    }
}

/** @} */
