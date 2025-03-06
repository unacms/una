SET @sName = 'bx_organizations';


-- LIVE UPDATES
DELETE FROM `sys_objects_live_updates` WHERE `name`='bx_organizations_friend_requests_new';
INSERT INTO `sys_objects_live_updates`(`name`, `frequency`, `service_call`, `active`) VALUES
('bx_organizations_friend_requests_new', 1, 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:44:"get_live_updates_unconfirmed_connections_new";s:6:"params";a:5:{i:0;s:16:"bx_organizations";i:1;s:20:"sys_profiles_friends";i:2;a:0:{}i:3;a:0:{}i:4;s:7:"{count}";}s:5:"class";s:23:"TemplServiceConnections";}', 1);
