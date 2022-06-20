-- TABLE: types of categories
CREATE TABLE IF NOT EXISTS `bx_editor_toolbar_buttons` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint(4) NOT NULL default '0',
  `order` tinyint(4) NOT NULL default '0',
  `name` varchar(64) NOT NULL DEFAULT '',
  `mode` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
);

INSERT INTO `bx_editor_toolbar_buttons`(`active`, `order`, `name`, `mode`) VALUES
(1, 0, 'bold', 'full'),
(1, 1, 'italic', 'full'),
(1, 2, 'underline', 'full'),
(1, 3, 'strike', 'full'),
(1, 4, 'subscript', 'full'),
(1, 5, 'superscript', 'full'),
(1, 6, 'code', 'full'),
(1, 7, 'highlight', 'full'),
(1, 8, 'separator', 'full'),
(1, 9, 'indent', 'full'),
(1, 10, 'outdent', 'full'),
(1, 11, 'bulletList', 'full'),
(1, 12, 'codeBlock', 'full'),
(1, 13, 'orderedList', 'full'),
(1, 14, 'separator', 'full'),
(1, 15, 'alignLeft', 'full'),
(1, 16, 'alignCenter', 'full'),
(1, 17, 'alignRight', 'full'),
(1, 18, 'alignJustify', 'full'),
(1, 19, 'separator', 'full'),
(1, 20, 'h1', 'full'),
(1, 21, 'h2', 'full'),
(1, 22, 'h3', 'full'),
(1, 23, 'h4', 'full'),
(1, 24, 'separator', 'full'),
(1, 25, 'link', 'full'),
(1, 26, 'image', 'full'),
(1, 27, 'embed', 'full'),

(1, 0, 'bold', 'standard'),
(1, 1, 'italic', 'standard'),
(1, 2, 'underline', 'standard'),
(1, 3, 'strike', 'standard'),
(0, 4, 'subscript', 'standard'),
(0, 5, 'superscript', 'standard'),
(0, 6, 'code', 'standard'),
(0, 7, 'highlight', 'standard'),
(1, 8, 'separator', 'standard'),
(0, 9, 'indent', 'standard'),
(0, 10, 'outdent', 'standard'),
(1, 11, 'bulletList', 'standard'),
(0, 12, 'codeBlock', 'standard'),
(1, 13, 'orderedList', 'standard'),
(1, 14, 'separator', 'standard'),
(1, 15, 'alignLeft', 'standard'),
(1, 16, 'alignCenter', 'standard'),
(1, 17, 'alignRight', 'standard'),
(1, 18, 'alignJustify', 'standard'),
(1, 19, 'separator', 'standard'),
(1, 20, 'h1', 'standard'),
(1, 21, 'h2', 'standard'),
(1, 22, 'h3', 'standard'),
(1, 23, 'h4', 'standard'),
(1, 24, 'separator', 'standard'),
(1, 25, 'link', 'standard'),
(1, 26, 'image', 'standard'),
(1, 27, 'embed', 'standard'),

(1, 0, 'bold', 'mini'),
(1, 1, 'italic', 'mini'),
(1, 2, 'underline', 'mini'),
(0, 3, 'strike', 'mini'),
(0, 4, 'subscript', 'mini'),
(0, 5, 'superscript', 'mini'),
(0, 6, 'code', 'mini'),
(0, 7, 'highlight', 'mini'),
(0, 8, 'separator', 'mini'),
(0, 9, 'indent', 'mini'),
(0, 10, 'outdent', 'mini'),
(0, 11, 'bulletList', 'mini'),
(0, 12, 'codeBlock', 'mini'),
(0, 13, 'orderedList', 'mini'),
(1, 14, 'separator', 'mini'),
(1, 15, 'alignLeft', 'mini'),
(1, 16, 'alignCenter', 'mini'),
(1, 17, 'alignRight', 'mini'),
(1, 18, 'alignJustify', 'mini'),
(0, 19, 'separator', 'mini'),
(0, 20, 'h1', 'mini'),
(0, 21, 'h2', 'mini'),
(0, 22, 'h3', 'mini'),
(0, 23, 'h4', 'mini'),
(1, 24, 'separator', 'mini'),
(1, 25, 'link', 'mini'),
(1, 26, 'image', 'mini'),
(1, 27, 'embed', 'mini');

-- Studio page and widget

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_editor', '_bx_editor', '_bx_editor', 'bx_editor@modules/boonex/editor/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_editor', 'integrations', '{url_studio}module.php?name=bx_editor', '', 'bx_editor@modules/boonex/editor/|std-icon.svg', '_bx_editor', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

