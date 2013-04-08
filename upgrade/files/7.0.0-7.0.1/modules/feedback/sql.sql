
DELETE FROM `sys_menu_top` WHERE `Parent` = 0 AND `Name` = '[db_prefix]_view' AND `Caption` = '_feedback_top_menu_sitem' AND `Link` = 'modules/?r=feedback/view/';
INSERT INTO `sys_menu_top` (`Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`, `BQuickLink`, `Statistics`) VALUES
(0, '[db_prefix]_view', '_feedback_top_menu_sitem', 'modules/?r=feedback/view/', 0, 'non,memb', '', '', '', 1, 1, 1, 'system', 'modules/boonex/feedback/|top_menu_icon.png', 0, '');

UPDATE `sys_menu_top` SET `Link` = 'modules/?r=feedback/index/|modules/?r=feedback/' WHERE `Parent` = 120 AND `Name` = 'Feedback' AND `Link` = 'modules/?r=feedback/';

UPDATE `sys_modules` SET `version` = '1.0.1' WHERE `uri` = 'feedback' AND `version` = '1.0.0';

