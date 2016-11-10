SET @sName = 'bx_acl';


-- STUDIO PAGE & WIDGET
UPDATE `sys_std_pages` SET `icon`='bx_acl@modules/boonex/acl/|std-icon.svg' WHERE `name`=@sName;
UPDATE `sys_std_widgets` SET `icon`='bx_acl@modules/boonex/acl/|std-icon.svg' WHERE `module`=@sName AND `caption`='_bx_acl';