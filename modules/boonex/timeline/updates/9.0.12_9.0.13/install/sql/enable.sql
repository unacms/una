-- PRELOADER
DELETE FROM `sys_preloader` WHERE `module`='bx_timeline';
SET @iOrderPreloader = (SELECT IFNULL(MAX(`order`), 9999) FROM `sys_preloader` WHERE `type`='js_system' AND `order` > 9999 LIMIT 1);
INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('bx_timeline', 'js_system', 'modernizr.min.js', 1, @iOrderPreloader + 1);
