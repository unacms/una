SET @sName = 'bx_artificer';


-- PRELOADER
DELETE FROM `sys_preloader` WHERE `module`='bx_artificer';
INSERT INTO `sys_preloader` (`module`, `type`, `content`, `active`) VALUES
('bx_artificer', 'css_system', 'modules/boonex/artificer/template/css/|main.css', 1),
('bx_artificer', 'js_system', 'modules/boonex/artificer/js/|utils.js', 1),
('bx_artificer', 'js_system', 'modules/boonex/artificer/js/|sidebar.js', 1);
