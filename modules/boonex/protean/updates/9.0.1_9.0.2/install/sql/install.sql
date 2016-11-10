SET @sName = 'bx_protean';


-- SETTINGS
UPDATE `sys_options_types` SET `icon`='bx_protean@modules/boonex/protean/|std-icon.svg' WHERE `name`=@sName;

UPDATE `sys_options` SET `value`='1024' WHERE `name`=CONCAT(@sName, '_page_width');

SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Neat-Mix' LIMIT 1);
UPDATE sys_options_mixes2options SET `value`='1024' WHERE `option`='bx_protean_page_width' AND `mix_id`=@iMixId;


-- STUDIO PAGE & WIDGET
UPDATE `sys_std_pages` SET `icon`='bx_protean@modules/boonex/protean/|std-icon.svg' WHERE `name`=@sName;
UPDATE `sys_std_widgets` SET `icon`='bx_protean@modules/boonex/protean/|std-icon.svg' WHERE `module`=@sName AND `caption`='_bx_protean_wgt_cpt';