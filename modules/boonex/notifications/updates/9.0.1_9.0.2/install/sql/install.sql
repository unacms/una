SET @sName = 'bx_notifications';


-- STUDIO PAGE & WIDGET
UPDATE `sys_std_pages` SET `icon`='bx_notifications@modules/boonex/notifications/|std-icon.svg' WHERE `name`=@sName;
UPDATE `sys_std_widgets` SET `icon`='bx_notifications@modules/boonex/notifications/|std-icon.svg' WHERE `module`=@sName AND `caption`='_bx_ntfs';