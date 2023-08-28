-- Settings
UPDATE `sys_options` SET `value` = 'modules/boonex/fontawesome/template/css/|icons.css' WHERE `name` = 'sys_css_icons_default';


-- CSS Loader
UPDATE `sys_preloader` SET `active`='1' WHERE `module`='system' AND `type`='css_system' AND `content`='icons.css';
UPDATE `sys_preloader` SET `active`='1' WHERE `module`='system' AND `type`='css_system' AND `content`='a:4:{s:6:"module";s:6:"system";s:6:"method";s:21:"get_preloader_content";s:6:"params";a:1:{i:0;s:5:"icons";}s:5:"class";s:12:"BaseServices";}';
UPDATE `sys_preloader` SET `content`='a:4:{s:6:"module";s:6:"system";s:6:"method";s:21:"get_preloader_content";s:6:"params";a:1:{i:0;s:5:"icons";}s:5:"class";s:12:"BaseServices";}' WHERE `module`='system' AND `type`='css_system' AND `content`='icons.css';

DELETE FROM `sys_preloader` WHERE `module`='bx_fontawesome' AND `type`='css_system' AND `content`='modules/boonex/fontawesome/template/css/|icons.css';
