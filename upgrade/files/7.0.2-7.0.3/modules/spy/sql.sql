
UPDATE `sys_cron_jobs` SET `time` = '1 */12 * * *' WHERE `name` = 'bx_spy' AND `time` = '0 0 * * *';

UPDATE `sys_options` SET `VALUE` = '10000' WHERE `Name` = 'bx_spy_update_time' AND `VALUE` = '5000';

UPDATE `sys_options` SET `VALUE` = '' WHERE `Name` = 'bx_spy_guest_allow' AND `VALUE` = 'on';

UPDATE `sys_page_compose` SET `Content`   = 'return BxDolService::call(''spy'', ''get_spy_block'', array(''member.php'', $this->iMember));', `DesignBox` = 1 WHERE `Page` = 'member' AND `Content`   = 'BxDolService::call(''spy'', ''get_member_spy_block'');';

INSERT INTO 
	`sys_page_compose`
(`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`)
	VALUES
('index', '998px', 'Spy data here', '_bx_spy', 2, 0, 'PHP', 'return BxDolService::call(''spy'', ''get_spy_block'');', 1, 34, 'non,memb', 0);

UPDATE `sys_menu_member` SET `Description` = '_bx_spy_notifications' WHERE `Name` = 'Spy' AND `Link` = 'member.php#spy_block' AND `PopupMenu` = 'BxDolService::call(''spy'', ''get_member_menu_spy_data''); ';

DELETE FROM `sys_injections` WHERE `name` = 'spy_css_styles';


UPDATE `sys_modules` SET `version` = '1.0.3' WHERE `uri` = 'spy' AND `version` = '1.0.2';

