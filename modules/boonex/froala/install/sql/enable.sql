
-- Settings

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_froala', '_bx_froala_adm_stg_cpt_type', 'bx_froala@modules/boonex/froala/|std-icon.svg', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_froala_general', '_bx_froala_adm_stg_cpt_category_general', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_froala_icons_template', 'svg', @iCategId, '_bx_froala_option_icons_template', 'select', 'svg,font_awesome_5,text', '', '', 5),
('bx_froala_option_plugins', 'emoticons,embedly,draggable,fullscreen,image,link', @iCategId, '_bx_froala_option_plugins', 'digit', '', '', '', 10),

('bx_froala_option_toolbar_mini', "{'moreText': {'buttons': ['bold', 'italic', 'underline','quote'],'buttonsVisible': 2},'moreRich': {'buttons': ['insertLink', 'insertImage','emoticons', 'embedly'],'buttonsVisible': 2},'moreMisc': {'buttons': ['undo', 'redo', 'fullscreen'],'align': 'right','buttonsVisible': 0}}", @iCategId, '_bx_froala_option_toolbar_mini', 'digit', '', '', '', 20),
('bx_froala_option_toolbar_mini_mobile', "{'moreText': {'buttons': ['bold', 'italic', 'underline','quote'],'buttonsVisible': 0},'moreRich': {'buttons': ['insertLink', 'insertImage','emoticons', 'embedly'],'buttonsVisible': 0},'moreMisc': {'buttons': ['undo', 'redo', 'fullscreen'],'align': 'right','buttonsVisible': 0}}", @iCategId, '_bx_froala_option_toolbar_mini_mobile', 'digit', '', '', '', 21),

('bx_froala_option_toolbar_standard', "{'moreText': {'buttons': ['bold', 'italic', 'underline','alignLeft', 'alignCenter', 'alignRight', 'formatOL', 'formatUL','insertLink', 'insertImage'],'buttonsVisible': 40},'moreMisc': {'buttons': [ 'fullscreen', 'html'],'align': 'right','buttonsVisible': 2}}", @iCategId, '_bx_froala_option_toolbar_standard', 'digit', '', '', '', 22),
('bx_froala_option_toolbar_standard_mobile', "{'moreText': {'buttons': ['bold', 'italic', 'underline', 'clearFormatting'],'buttonsVisible': 0},'moreParagraph': {'buttons': ['alignLeft', 'alignCenter', 'alignRight', 'formatOL', 'formatUL', 'paragraphFormat', 'outdent', 'indent', 'quote'],'buttonsVisible': 0},'moreRich': {'buttons': ['insertLink', 'insertImage','emoticons', 'embedly'],'buttonsVisible': 0},'moreMisc': {'buttons': ['undo', 'redo', 'fullscreen', 'html'],'align': 'right','buttonsVisible': 2}}", @iCategId, '_bx_froala_option_toolbar_standard_mobile', 'digit', '', '', '', 23),

('bx_froala_option_toolbar_full', "{'moreText': {'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'clearFormatting'], 'buttonsVisible': 5}, 'moreParagraph': {'buttons': ['alignLeft', 'alignCenter', 'alignRight', 'formatOL', 'formatUL', 'paragraphFormat', 'paragraphStyle', 'outdent', 'indent', 'quote']}, 'moreRich': {'buttons': ['insertLink', 'insertImage','emoticons', 'embedly', 'insertHR'], 'buttonsVisible': 5}, 'moreMisc': {'buttons': ['undo', 'redo', 'fullscreen', 'html'], 'align': 'right', 'buttonsVisible': 2}}", @iCategId, '_bx_froala_option_toolbar_full', 'digit', '', '', '', 24),
('bx_froala_option_toolbar_full_mobile', "{'moreText': {'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'clearFormatting'], 'buttonsVisible': 0}, 'moreParagraph': {'buttons': ['alignLeft', 'alignCenter', 'alignRight', 'formatOL', 'formatUL', 'paragraphFormat', 'paragraphStyle', 'outdent', 'indent', 'quote'], 'buttonsVisible': 0}, 'moreRich': {'buttons': ['insertLink', 'insertImage','emoticons', 'embedly', 'insertHR'], 'buttonsVisible': 0}, 'moreMisc': {'buttons': ['undo', 'redo', 'fullscreen', 'html'], 'align': 'right', 'buttonsVisible': 2}}", @iCategId, '_bx_froala_option_toolbar_full_mobile', 'digit', '', '', '', 25);

-- Editor

INSERT INTO `sys_objects_editor` (`object`, `title`, `skin`, `override_class_name`, `override_class_file`) VALUES
('bx_froala', 'Froala', 'gray', 'BxFroalaEditor', 'modules/boonex/froala/classes/BxFroalaEditor.php');

UPDATE `sys_options` SET `value` = 'bx_froala' WHERE `name` = 'sys_editor_default';

-- Injections

INSERT INTO `sys_injections` (`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
('bx_froala', 0, 'injection_footer', 'service', 'a:2:{s:6:"module";s:9:"bx_froala";s:6:"method";s:9:"injection";}', 0, 1);

-- Preloader

SET @iMaxOrder = (SELECT `order` FROM `sys_preloader` WHERE `type` = 'css_system' ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('bx_froala', 'css_system', 'modules/boonex/froala/plugins/froala/css/|froala_style.min.css',  '1',  @iMaxOrder + 1);
