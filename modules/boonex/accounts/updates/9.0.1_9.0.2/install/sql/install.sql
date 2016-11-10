SET @sName = 'bx_accounts';


-- STUDIO PAGE & WIDGET
UPDATE `sys_std_pages` SET `icon`='bx_accounts@modules/boonex/accounts/|std-icon.svg' WHERE `name`=@sName;
UPDATE `sys_std_widgets` SET `icon`='bx_accounts@modules/boonex/accounts/|std-icon.svg' WHERE `module`=@sName AND `caption`='_bx_accnt';