SET @sName = 'bx_contact';

-- STUDIO PAGE & WIDGET
UPDATE `sys_std_pages` SET `icon`='bx_contact@modules/boonex/contact/|std-icon.svg' WHERE `name`=@sName;
UPDATE `sys_std_widgets` SET `icon`='bx_contact@modules/boonex/contact/|std-icon.svg' WHERE `module`=@sName AND `caption`='_bx_contact';