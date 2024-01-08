-- TABLES
ALTER TABLE `bx_ads_entries` MODIFY `status_admin` enum('active','hidden','pending','unpaid') NOT NULL DEFAULT 'active';

CREATE TABLE IF NOT EXISTS `bx_ads_sources` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `caption` varchar(128) NOT NULL default '',
  `description` varchar(128) NOT NULL default '',
  `option_prefix` varchar(32) NOT NULL default '',
  `active` tinyint(4) NOT NULL default '0',
  `order` tinyint(4) NOT NULL default '0',
  `class_name` varchar(128) NOT NULL default '',
  `class_file` varchar(255) NOT NULL  default '',
  PRIMARY KEY(`id`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_sources_options` (
  `id` int(11) NOT NULL auto_increment,
  `source_id` varchar(64) NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  `type` varchar(64) NOT NULL default 'text',
  `caption` varchar(255) NOT NULL default '',
  `description` text NOT NULL default '',
  `extra` varchar(255) NOT NULL default '',
  `check_type` varchar(64) NOT NULL default '',
  `check_params` varchar(128) NOT NULL default '',
  `check_error` varchar(128) NOT NULL default '',
  `order` tinyint(4) NOT NULL default '0',
  PRIMARY KEY(`id`),
  UNIQUE KEY `name`(`name`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_sources_options_values` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL default '0',
  `option_id` int(11) NOT NULL default '0',  
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY(`id`),
  UNIQUE KEY `value`(`profile_id`, `option_id`)
);

SET @iSourceId = (SELECT `id` FROM `bx_ads_sources` WHERE `name`='shopify_admin' LIMIT 1);
DELETE FROM `bx_ads_sources` WHERE `id`=@iSourceId;
DELETE FROM `bx_ads_sources_options` WHERE `source_id`=@iSourceId;

INSERT INTO `bx_ads_sources`(`name`, `caption`, `description`, `option_prefix`, `active`, `order`, `class_name`) VALUES
('shopify_admin', '_bx_ads_src_cpt_shopify_admin', '_bx_ads_src_dsc_shopify_admin', 'shf_adm_', 1, 1, 'BxAdsSourceShopifyAdmin');
SET @iSourceId = LAST_INSERT_ID();

INSERT INTO `bx_ads_sources_options`(`source_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iSourceId, 'shf_adm_active', 'checkbox', '_bx_ads_src_opt_cpt_active', '_bx_ads_src_opt_dsc_active', '', '', '', '', 1),
(@iSourceId, 'shf_adm_shop_domain', 'text', '_bx_ads_src_opt_cpt_shop_domain', '_bx_ads_src_opt_dsc_shop_domain', '', '', '', '', 2),
(@iSourceId, 'shf_adm_access_token', 'text', '_bx_ads_src_opt_cpt_access_token', '_bx_ads_src_opt_dsc_access_token', '', '', '', '', 3);

CREATE TABLE IF NOT EXISTS `bx_ads_commodities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL default '0',
  `type` varchar(16) NOT NULL DEFAULT '',
  `amount` float NOT NULL,
  `added` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_promo_licenses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL default '0',
  `commodity_id` int(11) unsigned NOT NULL default '0',
  `entry_id` int(11) unsigned NOT NULL default '0',
  `amount` float NOT NULL default '0',
  `order` varchar(32) NOT NULL default '',
  `license` varchar(32) NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `license` (`license`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_promo_licenses_deleted` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL default '0',
  `commodity_id` int(11) unsigned NOT NULL default '0',
  `entry_id` int(11) unsigned NOT NULL default '0',
  `amount` float NOT NULL default '0',
  `order` varchar(32) NOT NULL default '',
  `license` varchar(32) NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  `reason` varchar(16) NOT NULL default '',
  `deleted` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `license` (`license`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_promo_tracker` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) unsigned NOT NULL default '0',
  `date` int(11) unsigned NOT NULL default '0',
  `impressions` int(11) unsigned NOT NULL default '0',
  `clicks` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `track` (`entry_id`, `date`)
);



-- FORMS
DELETE FROM `sys_objects_form` WHERE `object`='bx_ads_form_sources_details';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_ads_form_sources_details', 'bx_ads', '_bx_ads_form_sources_details', '', '', 'submit', '', 'id', '', '', 'a:1:{s:14:"checker_helper";s:36:"BxAdsSourcesDetailsFormCheckerHelper";}', 0, 1, 'BxAdsFormSourcesDetails', 'modules/boonex/ads/classes/BxAdsFormSourcesDetails.php');

DELETE FROM `sys_form_displays` WHERE `object`='bx_ads_form_sources_details';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_ads_form_sources_details_edit', 'bx_ads', 'bx_ads_form_sources_details', '_bx_ads_form_sources_details_display_edit', 0);


DELETE FROM `sys_form_displays` WHERE `object`='bx_ads' AND `display_name`='bx_ads_entry_edit_budget';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_ads', 'bx_ads_entry_edit_budget', 'bx_ads', 0, '_bx_ads_form_entry_display_edit_budget');


DELETE FROM `sys_form_inputs` WHERE `object`='bx_ads' AND `name` IN ('source_type', 'source', 'url', 'budget_total', 'budget_daily', 'seg_header_beg', 'seg_header_end', 'seg', 'seg_gender', 'seg_age', 'seg_country');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_ads', 'bx_ads', 'source_type', '', '', 0, 'hidden', '_bx_ads_form_entry_input_sys_source_type', '_bx_ads_form_entry_input_source_type', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_ads', 'bx_ads', 'source', '', '', 0, 'custom', '_bx_ads_form_entry_input_sys_source', '_bx_ads_form_entry_input_source', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_ads', 'bx_ads', 'url', '', '', 0, 'hidden', '_bx_ads_form_entry_input_sys_url', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_ads', 'bx_ads', 'budget_total', '', '', 0, 'text', '_bx_ads_form_entry_input_sys_budget_total', '_bx_ads_form_entry_input_budget_total', '', 0, 0, 0, '', '', '', '', '', '', 'Float', '', 1, 0),
('bx_ads', 'bx_ads', 'budget_daily', '', '', 0, 'text', '_bx_ads_form_entry_input_sys_budget_daily', '_bx_ads_form_entry_input_budget_daily', '', 0, 0, 0, '', '', '', '', '', '', 'Float', '', 1, 0),
('bx_ads', 'bx_ads', 'seg_header_beg', '', '', 0, 'block_header', '_bx_ads_form_entry_input_sys_header_beg_seg', '_bx_ads_form_entry_input_header_beg_seg', '', 0, 1, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_ads', 'bx_ads', 'seg_header_end', '', '', 0, 'block_end', '_bx_ads_form_entry_input_sys_header_end_seg', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_ads', 'bx_ads', 'seg', 1, '', 0, 'switcher', '_bx_ads_form_entry_input_sys_seg', '_bx_ads_form_entry_input_seg', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_ads', 'bx_ads', 'seg_gender', '', '#!Sex', 0, 'checkbox_set', '_bx_ads_form_entry_input_sys_seg_gender', '_bx_ads_form_entry_input_seg_gender', '', 0, 0, 0, '', '', '', '', '', '', 'Set', '', 1, 0),
('bx_ads', 'bx_ads', 'seg_age', '', '', 0, 'doublerange', '_bx_ads_form_entry_input_sys_seg_age', '_bx_ads_form_entry_input_seg_age', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_ads', 'bx_ads', 'seg_country', '', '#!Country', 0, 'select', '_bx_ads_form_entry_input_sys_seg_country', '_bx_ads_form_entry_input_seg_country', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0);


DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_ads_entry_edit_budget';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_ads_entry_edit_budget', 'budget_total', 2147483647, 1, 1),
('bx_ads_entry_edit_budget', 'budget_daily', 2147483647, 1, 2),
('bx_ads_entry_edit_budget', 'do_submit', 2147483647, 1, 3);


DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_ads_entry_price_add' AND `input_name` IN ('url', 'source_type', 'source', 'budget_total', 'budget_daily', 'seg_header_beg', 'seg', 'seg_sex', 'seg_age', 'seg_country', 'seg_header_end');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_ads_entry_price_add', 'url', 2147483647, 1, 0),
('bx_ads_entry_price_add', 'source_type', 2147483647, 1, 1),
('bx_ads_entry_price_add', 'source', 2147483647, 1, 2),
('bx_ads_entry_price_add', 'budget_total', 2147483647, 1, 17),
('bx_ads_entry_price_add', 'budget_daily', 2147483647, 1, 18),
('bx_ads_entry_price_add', 'seg_header_beg', 2147483647, 1, 23),
('bx_ads_entry_price_add', 'seg', 2147483647, 1, 24),
('bx_ads_entry_price_add', 'seg_sex', 2147483647, 1, 25),
('bx_ads_entry_price_add', 'seg_age', 2147483647, 1, 26),
('bx_ads_entry_price_add', 'seg_country', 2147483647, 1, 27),
('bx_ads_entry_price_add', 'seg_header_end', 2147483647, 1, 28);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_ads_entry_price_edit' AND `input_name` IN ('source_type', 'source', 'seg_header_beg', 'seg', 'seg_sex', 'seg_age', 'seg_country', 'seg_header_end');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_ads_entry_price_edit', 'source_type', 2147483647, 1, 1),
('bx_ads_entry_price_edit', 'source', 2147483647, 1, 2),
('bx_ads_entry_price_edit', 'seg_header_beg', 2147483647, 1, 20),
('bx_ads_entry_price_edit', 'seg', 2147483647, 1, 21),
('bx_ads_entry_price_edit', 'seg_sex', 2147483647, 1, 22),
('bx_ads_entry_price_edit', 'seg_age', 2147483647, 1, 23),
('bx_ads_entry_price_edit', 'seg_country', 2147483647, 1, 24),
('bx_ads_entry_price_edit', 'seg_header_end', 2147483647, 1, 25);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_ads_entry_price_year_add' AND `input_name` IN ('url', 'source_type', 'source', 'budget_total', 'budget_daily', 'seg_header_beg', 'seg', 'seg_sex', 'seg_age', 'seg_country', 'seg_header_end');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_ads_entry_price_year_add', 'url', 2147483647, 1, 0),
('bx_ads_entry_price_year_add', 'source_type', 2147483647, 1, 1),
('bx_ads_entry_price_year_add', 'source', 2147483647, 1, 2),
('bx_ads_entry_price_year_add', 'budget_total', 2147483647, 1, 18),
('bx_ads_entry_price_year_add', 'budget_daily', 2147483647, 1, 19),
('bx_ads_entry_price_year_add', 'seg_header_beg', 2147483647, 1, 24),
('bx_ads_entry_price_year_add', 'seg', 2147483647, 1, 25),
('bx_ads_entry_price_year_add', 'seg_sex', 2147483647, 1, 26),
('bx_ads_entry_price_year_add', 'seg_age', 2147483647, 1, 27),
('bx_ads_entry_price_year_add', 'seg_country', 2147483647, 1, 28),
('bx_ads_entry_price_year_add', 'seg_header_end', 2147483647, 1, 29);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_ads_entry_price_year_edit' AND `input_name` IN ('source_type', 'source', 'seg_header_beg', 'seg', 'seg_sex', 'seg_age', 'seg_country', 'seg_header_end');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_ads_entry_price_year_edit', 'source_type', 2147483647, 1, 1),
('bx_ads_entry_price_year_edit', 'source', 2147483647, 1, 2),
('bx_ads_entry_price_year_edit', 'seg_header_beg', 2147483647, 1, 21),
('bx_ads_entry_price_year_edit', 'seg', 2147483647, 1, 22),
('bx_ads_entry_price_year_edit', 'seg_sex', 2147483647, 1, 23),
('bx_ads_entry_price_year_edit', 'seg_age', 2147483647, 1, 24),
('bx_ads_entry_price_year_edit', 'seg_country', 2147483647, 1, 25),
('bx_ads_entry_price_year_edit', 'seg_header_end', 2147483647, 1, 26);
