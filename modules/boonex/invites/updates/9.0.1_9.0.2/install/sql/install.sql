SET @sName = 'bx_invites';


-- STUDIO PAGE & WIDGET
UPDATE `sys_std_pages` SET `icon`='bx_invites@modules/boonex/invites/|std-icon.svg' WHERE `name`=@sName;
UPDATE `sys_std_widgets` SET `icon`='bx_invites@modules/boonex/invites/|std-icon.svg' WHERE `module`=@sName AND `caption`='_bx_invites';