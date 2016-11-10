SET @sName = 'bx_ru';


-- STUDIO PAGE & WIDGET
UPDATE `sys_std_pages` SET `icon`='bx_ru@modules/boonex/russian/|std-icon.svg' WHERE `name`=@sName;
UPDATE `sys_std_widgets` SET `icon`='bx_ru@modules/boonex/russian/|std-icon.svg' WHERE `module`=@sName AND `caption`='_bx_eng_wgt_cpt';


-- SETTINGS
UPDATE `sys_options_types` SET `icon`='bx_ru@modules/boonex/russian/|std-icon.svg' WHERE `name`=@sName;