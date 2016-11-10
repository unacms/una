-- TABLES
CREATE TABLE IF NOT EXISTS `bx_market_favorites_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`author_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- FORMS
UPDATE `sys_form_inputs` SET `editable`='0' WHERE `object`='bx_market' AND `name` IN ('header_end_single', 'header_end_recurring', 'header_end_privacy');


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name`='bx_market';
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_market', 'bx_market_favorites_track', '1', '1', '1', 'page.php?i=view-product&id={object_id}', 'bx_market_products', 'id', 'author', 'favorites', '', '');


-- STUDIO PAGE & WIDGET
UPDATE `sys_std_pages` SET `icon`='bx_market@modules/boonex/market/|std-icon.svg' WHERE `name`='bx_market';
UPDATE `sys_std_widgets` SET `icon`='bx_market@modules/boonex/market/|std-icon.svg' WHERE `module`='bx_market' AND `caption`='_bx_market';