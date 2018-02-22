
-- Settings

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_froala', '_bx_froala_adm_stg_cpt_type', 'bx_froala@modules/boonex/froala/|std-icon.svg', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_froala_general', '_bx_froala_adm_stg_cpt_category_general', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_froala_option_plugins', 'emoticons,embedly,draggable,fullscreen,image,link', @iCategId, '_bx_froala_option_plugins', 'digit', '', '', '', 10),
('bx_froala_option_toolbar_mini', 'bold,italic,underline,quote,|,emoticons,embedly,insertLink,insertImage,|,fullscreen', @iCategId, '_bx_froala_option_toolbar_mini', 'digit', '', '', '', 20),
('bx_froala_option_toolbar_standard', 'bold,italic,underline,quote,|,undo,redo,|,align,formatOL,formatUL,outdent,indent,|,emoticons,embedly,insertLink,insertImage,|,fullscreen', @iCategId, '_bx_froala_option_toolbar_standard', 'digit', '', '', '', 22),
('bx_froala_option_toolbar_full', 'bold,italic,underline,quote,strikeThrough,clearFormatting,|,undo,redo,|,paragraphFormat,align,formatOL,formatUL,outdent,indent,|,emoticons,embedly,insertLink,insertImage,insertHR,insertTable,|,print,fullscreen', @iCategId, '_bx_froala_option_toolbar_full', 'digit', '', '', '', 24);

-- Editor

INSERT INTO `sys_objects_editor` (`object`, `title`, `skin`, `override_class_name`, `override_class_file`) VALUES
('bx_froala', 'Froala', 'gray', 'BxFroalaEditor', 'modules/boonex/froala/classes/BxFroalaEditor.php');

UPDATE `sys_options` SET `value` = 'bx_froala' WHERE `name` = 'sys_editor_default';


-- Alerts

INSERT INTO `sys_alerts_handlers` SET `name` = 'bx_froala', `class` = 'BxFroalaAlerts', `file` = 'modules/boonex/froala/classes/BxFroalaAlerts.php';

SET @iHandlerId := (SELECT `id` FROM `sys_alerts_handlers`  WHERE `name` = 'bx_froala');

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandlerId);

