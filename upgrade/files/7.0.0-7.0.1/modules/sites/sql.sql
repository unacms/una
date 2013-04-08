
DELETE FROM `sys_objects_actions` WHERE `Type` = 'bx_sites_title' AND `Url` = '{BaseUri}browse/my/add';
DELETE FROM `sys_objects_actions` WHERE `Type` = 'bx_sites_title' AND `Url` = '{BaseUri}browse/my';
DELETE FROM `sys_objects_actions` WHERE `Type` = 'bx_sites_title' AND `Url` = '{BaseUri}home/';

INSERT INTO `sys_objects_actions` (`Caption`, `Icon`, `Url`, `Script`, `Eval`, `Order`, `Type`) VALUES 
    ('{evalResult}', 'modules/boonex/sites/|site_add.png', '{BaseUri}browse/my/add', '', 'if (($GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin'']) && {isAllowedAdd} == 1) return _t(''_bx_sites_action_add_site''); return;', 1, 'bx_sites_title'),
    ('{evalResult}', 'modules/boonex/sites/|sites.png', '{BaseUri}browse/my', '', 'if ($GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin'']) return _t(''_bx_sites_action_my_sites''); return;', 2, 'bx_sites_title'),
    ('{evalResult}', 'modules/boonex/sites/|sites.png', '{BaseUri}home/', '', 'if ($GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin'']) return _t(''_bx_sites_action_home_sites''); return;', 3, 'bx_sites_title');

UPDATE `sys_modules` SET `version` = '1.0.1' WHERE `uri` = 'sites' AND `version` = '1.0.0';

