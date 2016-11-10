SET @sName = 'bx_developer';


-- STUDIO PAGE & WIDGET
UPDATE `sys_std_pages` SET `icon`='bx_developer@modules/boonex/developer/|std-icon.svg' WHERE `name`=@sName;
UPDATE `sys_std_widgets` SET `icon`='bx_developer@modules/boonex/developer/|std-icon.svg' WHERE `module`=@sName AND `caption`='_bx_dev_adm_wgt_cpt';