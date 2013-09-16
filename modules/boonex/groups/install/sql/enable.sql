INSERT INTO `sys_objects_page` (`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('bx_groups_view', 'groups-view', '_bx_groups_page_title_sys_view', '', 'bx_groups', 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxGroupsPageView', 'modules/boonex/groups/classes/BxGroupsPageView.php'),
('bx_groups_home', 'groups-home', '_bx_groups_page_title_sys_home', '_bx_groups_page_title_home', 'bx_groups', 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxGroupsPageMain', 'modules/boonex/groups/classes/BxGroupsPageMain.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_groups_view', 1, 'bx_groups', '_bx_groups_page_block_title_info', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:12:"content_info";s:6:"params";a:1:{i:0;s:5:"{uri}";}}', 0, 1, 1),
('bx_groups_home', 1, 'bx_groups', '_bx_groups_page_block_title_main', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:14:"homepage_block";s:6:"params";a:0:{}}', 0, 1, 1);

-- page compose pages
--SET @iMaxOrder = (SELECT `Order` FROM `sys_page_compose_pages` ORDER BY `Order` DESC LIMIT 1);
--INSERT INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`) VALUES ('bx_groups_view', 'Group View', @iMaxOrder+1);
--INSERT INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`) VALUES ('bx_groups_celendar', 'Groups Calendar', @iMaxOrder+2);
--INSERT INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`) VALUES ('bx_groups_main', 'Groups Home', @iMaxOrder+3);
--INSERT INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`) VALUES ('bx_groups_my', 'Groups My', @iMaxOrder+4);

-- page compose blocks
--INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES 
--    ('bx_groups_view', '998px', 'Group''s actions block', '_bx_groups_block_actions', '1', '0', 'Actions', '', '11', '34', 'non,memb', '0'),    
--    ('bx_groups_view', '998px', 'Group''s rate block', '_bx_groups_block_rate', '1', '1', 'Rate', '', '11', '34', 'non,memb', '0'),    
--    ('bx_groups_view', '998px', 'Group''s info block', '_bx_groups_block_info', '1', '2', 'Info', '', '11', '34', 'non,memb', '0'),
--    ('bx_groups_view', '998px', 'Group''s fans block', '_bx_groups_block_fans', '1', '3', 'Fans', '', '11', '34', 'non,memb', '0'),    
--    ('bx_groups_view', '998px', 'Group''s unconfirmed fans block', '_bx_groups_block_fans_unconfirmed', '11', '5', 'FansUnconfirmed', '', '1', '34', 'memb', '0'),
--    ('bx_groups_view', '998px', 'Group''s description block', '_bx_groups_block_desc', '2', '0', 'Desc', '', '11', '66', 'non,memb', '0'),
--    ('bx_groups_view', '998px', 'Group''s photo block', '_bx_groups_block_photo', '2', '1', 'Photo', '', '11', '66', 'non,memb', '0'),
--    ('bx_groups_view', '998px', 'Group''s comments block', '_bx_groups_block_comments', '2', '5', 'Comments', '', '11', '66', 'non,memb', '0'),
--    ('bx_groups_main', '998px', 'Latest Featured Group', '_bx_groups_block_latest_featured_group', '2', '0', 'LatestFeaturedGroup', '', '11', '66', 'non,memb', '0'),
--    ('bx_groups_main', '998px', 'Recent Groups', '_bx_groups_block_recent', '1', '0', 'Recent', '', '11', '34', 'non,memb', '0'),
--    ('bx_groups_my', '998px', 'Administration Owner', '_bx_groups_block_administration_owner', '1', '0', 'Owner', '', '11', '100', 'non,memb', '0'),
--    ('bx_groups_my', '998px', 'User''s groups', '_bx_groups_block_users_groups', '1', '1', 'Browse', '', '0', '100', 'non,memb', '0'),
--    ('index', '998px', 'Groups', '_bx_groups_block_homepage', 0, 0, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''groups'', ''homepage_block'');', 11, 66, 'non,memb', 0);

-- settings

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group`='modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_groups', '_bx_groups', 'bx_groups@modules/boonex/groups/|std-mi.png', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_groups', '_bx_groups', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `order`, `extra`) VALUES
('bx_groups_autoapproval', 'on', @iCategId, 'Activate all groups after creation automatically', 'checkbox', '', '', '0', ''),
('bx_groups_author_comments_admin', 'on', @iCategId, 'Allow group admin to edit and delete any comment', 'checkbox', '', '', '0', ''),
('bx_groups_max_email_invitations', '10', @iCategId, 'Max number of email invitation to send per one invite', 'digit', '', '', '0', ''),
('category_auto_app_bx_groups', 'on', @iCategId, 'Activate all categories after creation automatically', 'checkbox', '', '', '0', ''),
('bx_groups_perpage_view_fans', '6', @iCategId, 'Number of fans to show on group view page', 'digit', '', '', '0', ''),
('bx_groups_perpage_browse_fans', '30', @iCategId, 'Number of fans to show on browse fans page', 'digit', '', '', '0', ''),
('bx_groups_perpage_main_recent', '10', @iCategId, 'Number of recently added GROUPS to show on groups home', 'digit', '', '', '0', ''),
('bx_groups_perpage_browse', '14', @iCategId, 'Number of groups to show on browse pages', 'digit', '', '', '0', ''),
('bx_groups_perpage_profile', '4', @iCategId, 'Number of groups to show on profile page', 'digit', '', '', '0', ''),
('bx_groups_perpage_homepage', '5', @iCategId, 'Number of groups to show on homepage', 'digit', '', '', '0', ''),
('bx_groups_homepage_default_tab', 'featured', @iCategId, 'Default groups block tab on homepage', 'select', '', '', '0', 'featured,recent,top,popular'),
('bx_groups_max_rss_num', '10', @iCategId, 'Max number of rss items to provide', 'digit', '', '', '0', '');

-- search objects
INSERT INTO `sys_objects_search` VALUES(NULL, 'bx_groups', '_bx_groups', 'BxGroupsSearchResult', 'modules/boonex/groups/classes/BxGroupsSearchResult.php');

-- vote objects
INSERT INTO `sys_objects_vote` VALUES (NULL, 'bx_groups', 'bx_groups_rating', 'bx_groups_rating_track', 'gal_', '5', 'vote_send_result', 'BX_PERIOD_PER_VOTE', '1', '', '', 'bx_groups_main', 'rate', 'rate_count', 'id', 'BxGroupsVoting', 'modules/boonex/groups/classes/BxGroupsVoting.php');

-- comments objects
INSERT INTO `sys_objects_cmts` VALUES (NULL, 'bx_groups', 'bx_groups_cmts', 'bx_groups_cmts_track', '0', '1', '90', '5', '1', '-3', 'slide', '2000', '1', '1', 'cmt', 'bx_groups_main', 'id', 'comments_count', 'BxGroupsCmts', 'modules/boonex/groups/classes/BxGroupsCmts.php');

-- views objects
INSERT INTO `sys_objects_views` VALUES(NULL, 'bx_groups', 'bx_groups_views_track', 86400, 'bx_groups_main', 'id', 'views', 1);

-- users actions
--INSERT INTO `sys_objects_actions` (`Caption`, `Icon`, `Url`, `Script`, `Eval`, `Order`, `Type`) VALUES 
--    ('{TitleEdit}', 'modules/boonex/groups/|edit.png', '{evalResult}', '', '$oConfig = $GLOBALS[''oBxGroupsModule'']->_oConfig; return  BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''edit/{ID}'';', '0', 'bx_groups'),
--    ('{TitleDelete}', 'modules/boonex/groups/|action_block.png', '', 'getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''{evalResult}'', false, ''post'');return false;', '$oConfig = $GLOBALS[''oBxGroupsModule'']->_oConfig; return  BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''delete/{ID}'';', '1', 'bx_groups'),
--    ('{TitleShare}', 'modules/boonex/groups/|action_share.png', '', 'showPopupAnyHtml (''{BaseUri}share_popup/{ID}'');', '', '2', 'bx_groups'),
--    ('{TitleBroadcast}', 'modules/boonex/groups/|action_broadcast.png', '{BaseUri}broadcast/{ID}', '', '', '3', 'bx_groups'),
--    ('{TitleJoin}', 'modules/boonex/groups/|user_add.png', '', 'getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''{evalResult}'', false, ''post'');return false;', '$oConfig = $GLOBALS[''oBxGroupsModule'']->_oConfig; return BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''join/{ID}/{iViewer}'';', '4', 'bx_groups'),
--    ('{TitleInvite}', 'modules/boonex/groups/|group_add.png', '{evalResult}', '', '$oConfig = $GLOBALS[''oBxGroupsModule'']->_oConfig; return BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''invite/{ID}'';', '5', 'bx_groups'),
--    ('{AddToFeatured}', 'modules/boonex/groups/|star__plus.png', '', 'getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''{evalResult}'', false, ''post'');return false;', '$oConfig = $GLOBALS[''oBxGroupsModule'']->_oConfig; return BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''mark_featured/{ID}'';', '6', 'bx_groups'),
--    ('{TitleManageFans}', 'modules/boonex/groups/|action_manage_fans.png', '', 'showPopupAnyHtml (''{BaseUri}manage_fans_popup/{ID}'');', '', '8', 'bx_groups'),
--    ('{TitleUploadPhotos}', 'modules/boonex/groups/|action_upload_photos.png', '{BaseUri}upload_photos/{URI}', '', '', '9', 'bx_groups'),
--    ('{TitleSubscribe}', 'action_subscribe.png', '', '{ScriptSubscribe}', '', '13', 'bx_groups'),
--    ('{evalResult}', 'modules/boonex/groups/|group_create.png', '{BaseUri}browse/my&bx_groups_filter=add_group', '', 'return $GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin''] ? _t(''_bx_groups_action_add_group'') : '''';', '1', 'bx_groups_title'),
--    ('{evalResult}', 'modules/boonex/groups/|groups.png', '{BaseUri}browse/my', '', 'return $GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin''] ? _t(''_bx_groups_action_my_groups'') : '''';', '2', 'bx_groups_title'),
--    ('{evalResult}', 'modules/boonex/groups/|groups.png', '{BaseUri}', '', 'return $GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin''] ? _t(''_bx_groups_action_groups_home'') : '''';', '2', 'bx_groups_title');
    

-- site menu 

SET @iMenuSiteMaxOrder := (SELECT MAX(`order`) FROM `sys_menu_items` WHERE `set_name` = 'sys_site');
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title`, `link`, `onclick`, `target`, `icon`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_site', 'bx_groups', 'groups', '_bx_groups_menu_root', 'modules/?r=groups/home/', '', '', '', 2147483647, 1, 1, @iMenuSiteMaxOrder + 1);

-- groups menu 

INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_groups', '_bx_groups_menu_title_main', 'bx_groups', 'bx_groups', 1, 0, 1, '', '');

INSERT INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('bx_groups', 'bx_groups', '_bx_groups_menu_set_title_main', 0);

INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title`, `link`, `onclick`, `target`, `icon`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('bx_groups', 'bx_groups', 'main_page', '_bx_groups_menu_main', 'modules/?r=groups/home/', '', '', '', 2147483647, 1, 1, 1),
('bx_groups', 'bx_groups', 'recent', '_bx_groups_menu_recent', 'modules/?r=groups/browse/recent', '', '', '', 2147483647, 1, 1, 2),
('bx_groups', 'bx_groups', 'top_rated', '_bx_groups_menu_top_rated', 'modules/?r=groups/browse/top', '', '', '', 2147483647, 1, 1, 3),
('bx_groups', 'bx_groups', 'popular', '_bx_groups_menu_popular', 'modules/?r=groups/browse/popular', '', '', '', 2147483647, 1, 1, 4),
('bx_groups', 'bx_groups', 'featured', '_bx_groups_menu_featured', 'modules/?r=groups/browse/featured', '', '', '', 2147483647, 1, 1, 5),
('bx_groups', 'bx_groups', 'tags', '_bx_groups_menu_tags', 'modules/?r=groups/tags', '', '', '', 2147483647, 1, 1, 6),
('bx_groups', 'bx_groups', 'categories', '_bx_groups_menu_categories', 'modules/?r=groups/categories', '', '', '', 2147483647, 1, 1, 7),
('bx_groups', 'bx_groups', 'calendar', '_bx_groups_menu_calendar', 'modules/?r=groups/calendar', '', '', '', 2147483647, 1, 1, 8),
('bx_groups', 'bx_groups', 'search', '_bx_groups_menu_search', 'modules/?r=groups/search', '', '', '', 2147483647, 1, 1, 9);

-- group view menu 

INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_groups_view', '_bx_groups_menu_title_group_view', 'bx_groups_view', 'bx_groups', 1, 0, 1, '', '');

INSERT INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('bx_groups_view', 'bx_groups', '_bx_groups_menu_set_title_group_view', 0);

INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title`, `link`, `onclick`, `target`, `icon`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('bx_groups_view', 'bx_groups', 'view', '_bx_groups_menu_view_group', 'modules/?r=groups/view/{bx_groups_view_uri}', '', '', '', 2147483647, 1, 1, 1),
('bx_groups_view', 'bx_groups', 'view_forum', '_bx_groups_menu_view_forum', 'forum/groups/forum/{bx_groups_view_uri}-0.htm', '', '', '', 2147483647, 1, 1, 2),
('bx_groups_view', 'bx_groups', 'view_fans', '_bx_groups_menu_view_fans', 'modules/?r=groups/browse_fans/{bx_groups_view_uri}', '', '', '', 2147483647, 1, 1, 3),
('bx_groups_view', 'bx_groups', 'view_comments', '_bx_groups_menu_view_comments', 'modules/?r=groups/comments/{bx_groups_view_uri}', '', '', '', 2147483647, 1, 1, 4);


-- site stats

INSERT INTO `sys_stat_site` VALUES(NULL, 'bx_groups', 'bx_groups', 'modules/?r=groups/', 'SELECT COUNT(`id`) FROM `bx_groups_main` WHERE `status` = ''approved''', '../modules/?r=groups/administration', 'SELECT COUNT(`id`) FROM `bx_groups_main` WHERE `status` != ''approved''', 'modules/boonex/groups/|groups.png', 0);

-- email templates
-- INSERT INTO `sys_email_templates` (`Name`, `Subject`, `Body`, `Desc`, `LangID`) VALUES 
-- ('bx_groups_broadcast', '<BroadcastTitle>', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p><a href="<EntryUrl>"><EntryTitle></a> group admin has sent the following broadcast message:</p> <pre><BroadcastMessage></pre> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'Groups broadcast message', '0'),
-- ('bx_groups_join_request', 'New join request to your group', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p>New join request in your group <a href="<EntryUrl>"><EntryTitle></a>. Please review this request and reject or confirm it.</p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'New join request to a group notification message', '0'),
-- ('bx_groups_join_reject', 'Your join request to a group was rejected', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p>Sorry, but your request to join <a href="<EntryUrl>"><EntryTitle></a> group was rejected by group admin(s).</p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'Join request to a group was rejected notification message', '0'),
-- ('bx_groups_join_confirm', 'Your join request to a group was confirmed', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p>Congratulations! Your request to join <a href="<EntryUrl>"><EntryTitle></a> group was confirmed by group admin(s).</p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'Join request to a group was confirmed notification message', '0'),
-- ('bx_groups_fan_remove', 'You was removed from fans of a group', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p>You was removed from fans of <a href="<EntryUrl>"><EntryTitle></a> group by group admin(s).</p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'User was removed from fans of group notification message', '0'),
-- ('bx_groups_fan_become_admin', 'You become admin of a group', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p>Congratulations! You become admin of <a href="<EntryUrl>"><EntryTitle></a> group.</p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'User become admin of a group notification message', '0'),
-- ('bx_groups_admin_become_fan', 'You group admin status was removed', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p>Your admin status was removed from <a href="<EntryUrl>"><EntryTitle></a> group by group admin(s).</p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'User group admin status was removed notification message', '0'),
-- ('bx_groups_invitation', 'Invitation to group: <GroupName>', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p><a href="<InviterUrl>"><InviterNickName></a> has invited you to this group:</p> <pre><InvitationText></pre> <p> <b>Group Information:</b><br /> Name: <GroupName><br /> Location: <GroupLocation><br /> <a href="<GroupUrl>">More details</a> </p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'Group invitation template', '0'),
-- ('bx_groups_sbs', 'Group was changed', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p><a href="<ViewLink>"><EntryTitle></a> group was changed: <br /> <ActionName> </p> <p>You may cancel the subscription by clicking the following link: <a href="<UnsubscribeLink>"><UnsubscribeLink></a></p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'Group subscription template', '0');

-- membership actions
SET @iLevelNonMember := 1;
SET @iLevelStandard := 2;
SET @iLevelModerator := 6;
SET @iLevelAdmin := 7;
SET @iLevelPremium := 8;

INSERT INTO `sys_acl_actions` VALUES (NULL, 'bx_groups', 'groups view group', NULL, '_bx_groups_action_view', '', 1, 0);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelNonMember, @iAction), (@iLevelAuthenticated, @iAction),(@iLevelStandard, @iAction), (@iLevelPremium, @iAction), (@iLevelModerator, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'bx_groups', 'groups browse', NULL, '_bx_groups_action_browse', '', 0, 0);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelNonMember, @iAction), (@iLevelAuthenticated, @iAction), (@iLevelStandard, @iAction), (@iLevelPremium, @iAction), (@iLevelModerator, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'bx_groups', 'groups search', NULL, '_bx_groups_action_search', '', 0, 0);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelNonMember, @iAction), (@iLevelAuthenticated, @iAction), (@iLevelStandard, @iAction), (@iLevelPremium, @iAction), (@iLevelModerator, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'bx_groups', 'groups add group', NULL, '_bx_groups_action_add', '', 1, 1);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelStandard, @iAction), (@iLevelPremium, @iAction), (@iLevelModerator, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'bx_groups', 'groups comments delete and edit', NULL, '_bx_groups_action_comments_delete_edit', '', 0, 1);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelModerator, @iAction), (@iLevelAdmin, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'bx_groups', 'groups edit any group', NULL, '_bx_groups_action_edit_any_group', '', 0, 1);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelAdmin, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'bx_groups', 'groups delete any group', NULL, '_bx_groups_action_delete_any_group', '', 0, 1);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelAdmin, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'bx_groups', 'groups mark as featured', NULL, '_bx_groups_action_mark_as_featured', '', 0, 1);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelModerator, @iAction), (@iLevelAdmin, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'bx_groups', 'groups approve groups', NULL, '_bx_groups_action_approve', '', 0, 1);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelModerator, @iAction), (@iLevelAdmin, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'bx_groups', 'groups broadcast message', NULL, '_bx_groups_action_broadcat_message', '', 0, 1);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelModerator, @iAction), (@iLevelAdmin, @iAction);

-- alert handlers
INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'bx_groups_profile_delete', '', '', 'BxDolService::call(''groups'', ''response_profile_delete'', array($this));');
SET @iHandler := LAST_INSERT_ID();
INSERT INTO `sys_alerts` VALUES (NULL , 'profile', 'delete', @iHandler);

INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'bx_groups_account_delete', '', '', 'BxDolService::call(''groups'', ''response_account_delete'', array($this));');
SET @iHandler := LAST_INSERT_ID();
INSERT INTO `sys_alerts` VALUES (NULL , 'account', 'delete', @iHandler);


-- privacy
INSERT INTO `sys_privacy_actions` (`module_uri`, `name`, `title`, `default_group`) VALUES
('groups', 'view_group', '_bx_groups_privacy_view_group', '3'),
('groups', 'view_fans', '_bx_groups_privacy_view_fans', '3'),
('groups', 'comment', '_bx_groups_privacy_comment', 'f'),
('groups', 'rate', '_bx_groups_privacy_rate', 'f'),
('groups', 'join', '_bx_groups_privacy_join', '3'),
('groups', 'upload_photos', '_bx_groups_privacy_upload_photos', 'a');


