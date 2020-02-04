
-- Wiki home page

INSERT INTO `sys_objects_page` (`object`, `uri`, `title_system`, `title`, `module`, `cover`, `cover_image`, `type_id`, `layout_id`, `submenu`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('bx_wiki_home', 'wiki-home', '', '_bx_wiki_page_home', 'bx_wiki', 0, 0, 1, 1, '', 2147483647, 1, 'r.php?_q=wiki/wiki-home', '', '', '', 0, 1, 0, 'BxTemplPageWiki', '');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `hidden_on`, `type`, `content`, `text`, `text_updated`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_wiki_home', 1, 'bx_wiki', '', '_bx_wiki_block_contents', 0, 2147483647, '0', 'wiki', '', '# Contents', UNIX_TIMESTAMP(), 0, 1, 1, 1);
SET @iBlockId = LAST_INSERT_ID();
INSERT INTO `sys_pages_wiki_blocks` (`block_id`, `revision`, `language`, `main_lang`, `profile_id`, `content`, `notes`, `added`) VALUES
(@iBlockId, 1, 'en', 1, 0, '# Contents\r\n\r\n{{~bx_wiki:contents["wiki-missing-translations,wiki-outdated-translations"]~}}', 'Initial version', UNIX_TIMESTAMP());

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `hidden_on`, `type`, `content`, `text`, `text_updated`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_wiki_home', 1, 'bx_wiki', '', '_bx_wiki_block_administration', 0, 192, '0', 'wiki', '', '# Administration', UNIX_TIMESTAMP(), 0, 1, 1, 2);
SET @iBlockId = LAST_INSERT_ID();
INSERT INTO `sys_pages_wiki_blocks` (`block_id`, `revision`, `language`, `main_lang`, `profile_id`, `content`, `notes`, `added`) VALUES
(@iBlockId, 1, 'en', 1, 0, '# Administration\r\n\r\n{{~bx_wiki:contents["","wiki-missing-translations,wiki-outdated-translations"]~}}', 'Initial version', UNIX_TIMESTAMP());

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `hidden_on`, `type`, `content`, `text`, `text_updated`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_wiki_home', 2, 'bx_wiki', '', '_bx_wiki_block_home', 0, 2147483647, '0', 'wiki', '', '# Wiki Home\r\n\r\nInsert **your content** here', UNIX_TIMESTAMP(), 0, 1, 1, 1);
SET @iBlockId = LAST_INSERT_ID();
INSERT INTO `sys_pages_wiki_blocks` (`block_id`, `revision`, `language`, `main_lang`, `profile_id`, `content`, `notes`, `added`) VALUES
(@iBlockId, 1, 'en', 1, 0, '# Wiki Home\r\n\r\nInsert **your content** here', 'Initial version', UNIX_TIMESTAMP());

-- Wiki missing translations page

INSERT INTO `sys_objects_page` (`object`, `uri`, `title_system`, `title`, `module`, `cover`, `cover_image`, `type_id`, `layout_id`, `submenu`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('bx_wiki_missing_translations', 'wiki-missing-translations', '', '_bx_wiki_page_missing_translations', 'bx_wiki', 0, 0, 1, 5, '', 192, 1, 'r.php?_q=wiki/wiki-missing-translations', '', '', '', 0, 1, 0, 'BxTemplPageWiki', '');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `hidden_on`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_wiki_missing_translations', 1, 'bx_wiki', '', '_bx_wiki_block_missing_translations', 0, 192, '0', 'wiki', '', 0, 1, 1, 1);
SET @iBlockId = LAST_INSERT_ID();
INSERT INTO `sys_pages_wiki_blocks` (`block_id`, `revision`, `language`, `main_lang`, `profile_id`, `content`, `notes`, `added`) VALUES
(@iBlockId, 1, 'en', 1, 0, '### Missing translations for English:\r\n\r\n{{~bx_wiki:missing_translations["en"]~}}', 'Initial version', UNIX_TIMESTAMP());

-- Wiki outdated translations page

INSERT INTO `sys_objects_page` (`object`, `uri`, `title_system`, `title`, `module`, `cover`, `cover_image`, `type_id`, `layout_id`, `submenu`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('bx_wiki_outdated_translations', 'wiki-outdated-translations', '', '_bx_wiki_page_outdated_translations', 'bx_wiki', 0, 0, 1, 5, '', 192, 1, 'r.php?_q=wiki/wiki-outdated-translations', '', '', '', 0, 1, 0, 'BxTemplPageWiki', '');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `hidden_on`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_wiki_outdated_translations', 1, 'bx_wiki', '', '_bx_wiki_block_outdated_translations', 0, 192, '0', 'wiki', '', 0, 1, 1, 1);
SET @iBlockId = LAST_INSERT_ID();
INSERT INTO `sys_pages_wiki_blocks` (`block_id`, `revision`, `language`, `main_lang`, `profile_id`, `content`, `notes`, `added`) VALUES
(@iBlockId, 1, 'en', 1, 0, '### Outdated translations for English:\r\n\r\n{{~bx_wiki:outdated_translations["en"]~}}', 'Initial version', UNIX_TIMESTAMP());

-- Studio page and widget

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_wiki', '_bx_wiki', '_bx_wiki', 'bx_wiki@modules/boonex/wiki/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_wiki', '{url_studio}module.php?name=bx_wiki', '', 'bx_wiki@modules/boonex/wiki/|std-icon.svg', '_bx_wiki', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

