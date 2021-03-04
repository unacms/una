SET @sName = 'bx_timeline';


-- TABLES
DELETE FROM `bx_timeline_handlers` WHERE `alert_unit`='comment' AND `alert_action` IN ('added', 'edited', 'deleted');
INSERT INTO `bx_timeline_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES
('comment', 'insert', 'comment', 'added', 'a:5:{s:11:"module_name";s:6:"system";s:13:"module_method";s:17:"get_timeline_post";s:12:"module_class";s:17:"TemplCmtsServices";s:9:"groupable";i:0;s:8:"group_by";s:0:"";}'),
('comment', 'update', 'comment', 'edited', ''),
('comment', 'delete', 'comment', 'deleted', '');
