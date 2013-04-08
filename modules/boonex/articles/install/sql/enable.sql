SET @sName = 'bx_articles';

SET @iTMOrder = (SELECT MAX(`Order`) FROM `sys_menu_top` WHERE `Parent`='0');
INSERT INTO `sys_menu_top` (`Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`, `BQuickLink`, `Statistics`) VALUES
(0, 'Articles', '_articles_top_menu_item', 'modules/?r=articles/index/|modules/?r=articles/', @iTMOrder+1, 'non,memb', '', '', '', 1, 1, 1, 'top', 'bx_articles@modules/boonex/articles/|top_menu_icon.png', 0, '');

SET @iTMParentId = LAST_INSERT_ID( );
INSERT INTO `sys_menu_top` (`Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`, `BQuickLink`, `Statistics`) VALUES
(@iTMParentId, 'ArticlesHome', '_articles_home_top_menu_sitem', 'modules/?r=articles/index/', 0, 'non,memb', '', '', '', 1, 1, 1, 'custom', '', 0, ''),
(@iTMParentId, 'ArticlesArchive', '_articles_archive_top_menu_sitem', 'modules/?r=articles/archive/', 1, 'non,memb', '', '', '', 1, 1, 1, 'custom', '', 0, ''),
(@iTMParentId, 'ArticlesTop', '_articles_top_top_menu_sitem', 'modules/?r=articles/top/', 2, 'non,memb', '', '', '', 1, 1, 1, 'custom', '', 0, ''),
(@iTMParentId, 'ArticlesPopular', '_articles_popular_top_menu_sitem', 'modules/?r=articles/popular/', 3, 'non,memb', '', '', '', 1, 1, 1, 'custom', '', 0, ''),
(@iTMParentId, 'ArticlesFeatured', '_articles_featured_top_menu_sitem', 'modules/?r=articles/featured/', 4, 'non,memb', '', '', '', 1, 1, 1, 'custom', '', 0, ''),
(@iTMParentId, 'ArticlesTags', '_articles_tags_top_menu_sitem', 'modules/?r=articles/tags/', 5, 'non,memb', '', '', '', 1, 1, 1, 'custom', '', 0, ''),
(@iTMParentId, 'ArticlesCategories', '_articles_categories_top_menu_sitem', 'modules/?r=articles/categories/', 6, 'non,memb', '', '', '', 1, 1, 1, 'custom', '', 0, ''),
(@iTMParentId, 'ArticlesCalendar', '_articles_calendar_top_menu_sitem', 'modules/?r=articles/calendar/', 7, 'non,memb', '', '', '', 1, 1, 1, 'custom', '', 0, ''),
(@iTMParentId, 'ArticlesSearch', '_articles_search_top_menu_sitem', CONCAT('searchKeyword.php?type=', @sName), 8, 'non,memb', '', '', '', 1, 1, 1, 'custom', '', 0, ''),
(0, '[db_prefix]view', '_articles_view_top_menu_sitem', 'modules/?r=articles/view/', 0, 'non,memb', '', '', '', 1, 1, 1, 'system', 'modules/boonex/articles/|top_menu_icon.png', 0, '');

INSERT INTO `sys_menu_member`(`Caption`, `Name`, `Icon`, `Link`, `Script`, `Eval`, `Order`, `Active`, `Editable`, `Deletable`, `Target`, `Position`, `Type`) VALUES
('_articles_ext_menu_item', 'Articles', '', 'modules/?r=articles/', '', '', 6, '1', 0, 0, '', 'bottom', 'link');


SET @iPCPOrder = (SELECT MAX(`Order`) FROM `sys_page_compose_pages`);
INSERT INTO `sys_page_compose_pages`(`Name`, `Title`, `Order`) VALUES ('articles_single', 'Single Article', @iPCPOrder+1);

SET @iPCPOrder = (SELECT MAX(`Order`) FROM `sys_page_compose_pages`);
INSERT INTO `sys_page_compose_pages`(`Name`, `Title`, `Order`) VALUES ('articles_home', 'Articles Home', @iPCPOrder+1);

SET @iPCOrder = (SELECT MAX(`Order`) FROM `sys_page_compose` WHERE `Page`='index' AND `Column`='1');
INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES
('index', '998px', 'Show list of featured articles', '_articles_bcaption_featured', 1, @iPCOrder+1, 'PHP', 'return BxDolService::call(\'articles\', \'featured_block_index\', array(0, 0, false));', 1, 66, 'non,memb', 0),
('index', '998px', 'Show list of latest articles', '_articles_bcaption_latest', 1, @iPCOrder+2, 'PHP', 'return BxDolService::call(\'articles\', \'archive_block_index\', array(0, 0, false));', 1, 66, 'non,memb', 0),
('member', '998px', 'Show list of featured articles', '_articles_bcaption_featured', 2, 3, 'PHP', 'return BxDolService::call(\'articles\', \'featured_block_member\', array(0, 0, false));', 1, 66, 'memb', 0),
('member', '998px', 'Show list of latest articles', '_articles_bcaption_latest', 2, 4, 'PHP', 'return BxDolService::call(\'articles\', \'archive_block_member\', array(0, 0, false));', 1, 66, 'memb', 0),
('articles_single', '998px', 'Articles main content', '_articles_bcaption_view_main', 1, 0, 'Content', '', 1, 66, 'non,memb', 0),
('articles_single', '998px', 'Articles comments', '_articles_bcaption_view_comment', 1, 1, 'Comment', '', 1, 66, 'non,memb', 0),
('articles_single', '998px', 'Articles actions', '_articles_bcaption_view_action', 2, 0, 'Action', '', 1, 34, 'non,memb', 0),
('articles_single', '998px', 'Articles rating', '_articles_bcaption_view_vote', 2, 1, 'Vote', '', 1, 34, 'non,memb', 0),
('articles_home', '998px', 'Articles featured', '_articles_bcaption_featured', 1, 0, 'Featured', '', 1, 34, 'non,memb', 0),
('articles_home', '998px', 'Articles Latest', '_articles_bcaption_latest', 2, 0, 'Latest', '', 1, 66, 'non,memb', 0);


SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group`='modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', @sName, '_articles_adm_stg_cpt_type', 'bx_articles@modules/boonex/articles/|std-mi.png', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, CONCAT(@sName, '_general'), '_articles_adm_stg_cpt_category_general', 1);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'articles_autoapprove', 'Publish articles automatically', 'on', 'checkbox', '', '', '', 1),
(@iCategoryId, 'articles_snippet_length', 'The length of article snippet for home and account pages', '200', 'digit', '', '', '', 2);

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` ) 
VALUES (@iTypeId, 'bx_articles_privacy', '_articles_adm_stg_cpt_category_privacy', 2);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'articles_comments', 'Allow comments for articles', 'on', 'checkbox', '', '', '', 1),
(@iCategoryId, 'articles_votes', 'Allow votes for articles', 'on', 'checkbox', '', '', '', 2);

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` ) 
VALUES (@iTypeId, 'bx_articles_listings', '_articles_adm_stg_cpt_category_listings', 3);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'articles_per_page', 'The number of items shown on the page', '10', 'digit', '', '', '', 1),
(@iCategoryId, 'articles_index_number', 'The number of articles on home page', '10', 'digit', '', '', '', 2),
(@iCategoryId, 'articles_member_number', 'The number of articles on account page', '10', 'digit', '', '', '', 3),
(@iCategoryId, 'articles_rss_length', 'The number of items shown in the RSS feed', '10', 'digit', '', '', '', 4);

INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(0, 'category_auto_app_bx_articles', 'Autoapprove for categories', 'on', 'checkbox', '', '', '', 0);



INSERT INTO `sys_objects_cmts` (`ObjectName`, `TableCmts`, `TableTrack`, `AllowTags`, `Nl2br`, `SecToEdit`, `PerView`, `IsRatable`, `ViewingThreshold`, `AnimationEffect`, `AnimationSpeed`, `IsOn`, `IsMood`, `RootStylePrefix`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
(@sName, 'bx_arl_comments', 'bx_arl_comments_track', 0, 1, 90, 10, 1, -3, 'slide', 2000, 1, 0, 'cmt', 'bx_arl_entries', 'id', 'cmts_count', 'BxArlCmts', 'modules/boonex/articles/classes/BxArlCmts.php');

INSERT INTO `sys_objects_vote` (`ObjectName`, `TableRating`, `TableTrack`, `RowPrefix`, `MaxVotes`, `PostName`, `IsDuplicate`, `IsOn`, `className`, `classFile`, `TriggerTable`, `TriggerFieldRate`, `TriggerFieldRateCount`, `TriggerFieldId`, `OverrideClassName`, `OverrideClassFile`) VALUES
(@sName, 'bx_arl_voting', 'bx_arl_voting_track', 'arl_', 5, 'vote_send_result', 'BX_PERIOD_PER_VOTE', 1, '', '', 'bx_arl_entries', 'rate', 'rate_count', 'id', 'BxArlVoting', 'modules/boonex/articles/classes/BxArlVoting.php');

INSERT INTO `sys_objects_tag` (`ObjectName`, `Query`, `PermalinkParam`, `EnabledPermalink`, `DisabledPermalink`, `LangKey`) VALUES
(@sName, 'SELECT `tags` FROM `bx_arl_entries` WHERE `id`={iID} AND `status`=0', 'permalinks_modules', 'm/articles/tag/{tag}', 'modules/?r=articles/tag/{tag}', '_articles_lcaption_tags');

INSERT INTO `sys_objects_categories` (`ObjectName`, `Query`, `PermalinkParam`, `EnabledPermalink`, `DisabledPermalink`, `LangKey`) VALUES 
(@sName, 'SELECT `categories` FROM `bx_arl_entries` WHERE `id`=''{iID}'' AND `status`=''0''', 'permalinks_modules', 'm/articles/category/{tag}', 'modules/?r=articles/category/{tag}', '_articles_lcaption_categories');

INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `ClassName`, `ClassPath`) VALUES
(@sName, '_articles_lcaption_search_object', 'BxArlSearchResult', 'modules/boonex/articles/classes/BxArlSearchResult.php');

INSERT INTO `sys_objects_views`(`name`, `table_track`, `period`, `trigger_table`, `trigger_field_id`, `trigger_field_views`, `is_on`) VALUES
(@sName, 'bx_arl_views_track', 86400, 'bx_arl_entries', 'id', 'view_count', 1);

INSERT INTO `sys_objects_actions`(`Caption`, `Icon`, `Url`, `Script`, `Eval`, `Order`, `Type`, `bDisplayInSubMenuHeader`) VALUES
('{sbs_articles_title}', 'action_subscribe.png', '', '{sbs_articles_script}', '', 1, @sName, 0),
('{del_articles_title}', 'action_block.png', '', '{del_articles_script}', '', 2, @sName, 0);


--INSERT INTO `sys_email_templates`(`Name`, `Subject`, `Body`, `Desc`, `LangID`) VALUES
--('t_sbsArticlesComments', 'New article comments', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p><b>Dear <RealName></b>,</p><br /><p>The article you subscribed to has new comments!</p><br /> <p>Click <a href="<ViewLink>">here</a> to view them.</p><br /> <p><b>Thank you for using our services!</b></p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'New article comments subscription.', '0'),
--('t_sbsArticlesComments', 'New article comments', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p><b>Dear <RealName></b>,</p><br /><p>The article you subscribed to has new comments!</p><br /> <p>Click <a href="<ViewLink>">here</a> to view them.</p><br /> <p><b>Thank you for using our services!</b></p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'New article comments subscription.', '1'),
--('t_sbsArticlesRates', 'Article was rated', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p><b>Dear <RealName></b>,</p><br /><p>The article you subscribed to was rated!</p><br /> <p>Click <a href="<ViewLink>">here</a> to view it.</p><br /> <p><b>Thank you for using our services!</b></p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'New article rates subscription.', '0'),
--('t_sbsArticlesRates', 'Article was rated', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p><b>Dear <RealName></b>,</p><br /><p>The article you subscribed to was rated!</p><br /> <p>Click <a href="<ViewLink>">here</a> to view it.</p><br /> <p><b>Thank you for using our services!</b></p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'New article rates subscription.', '1');


INSERT INTO `sys_acl_actions`(`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES 
(@sName, 'Articles Add', '', '_articles_acl_action_add', '', '1', '1'),
(@sName, 'Articles Approve', '', '_articles_acl_action_approve', '', '1', '1'),
(@sName, 'Articles Delete', '', '_articles_acl_action_delete', '', '1', '1');


INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `eval`) VALUES
(@sName, '*/5 * * * *', 'BxArlCron', 'modules/boonex/articles/classes/BxArlCron.php', '');
