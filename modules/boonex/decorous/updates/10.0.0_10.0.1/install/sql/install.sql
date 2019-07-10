SET @sName = 'bx_decorous';


-- SETTINGS
UPDATE `sys_options` SET `value`='rgba(245, 250, 255, 0.9)' WHERE `name`=CONCAT(@sName, '_cover_bg_color');
UPDATE `sys_options` SET `value`='0px 1px 2px 0px rgba(0, 0, 0, 0.05)' WHERE `name`=CONCAT(@sName, '_cover_shadow');

UPDATE `sys_options` SET `value`='rgba(245, 250, 255, 0.9)' WHERE `name`=CONCAT(@sName, '_menu_page_bg_color');
UPDATE `sys_options` SET `value`='0px' WHERE `name`=CONCAT(@sName, '_menu_page_content_padding');
UPDATE `sys_options` SET `value`='0px 1px 2px 0px rgba(0, 0, 0, 0.05)' WHERE `name`=CONCAT(@sName, '_menu_page_shadow');

UPDATE `sys_options` SET `value`='100%' WHERE `name`=CONCAT(@sName, '_vpt_font_size_scale');

UPDATE `sys_options` SET `value`='87.5%' WHERE `name`=CONCAT(@sName, '_vpm_font_size_scale');
