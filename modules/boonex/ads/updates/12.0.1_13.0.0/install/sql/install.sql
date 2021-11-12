-- TABLES
ALTER TABLE `bx_ads_entries` MODIFY `status` enum('active','awaiting','offer','sold','hidden') NOT NULL DEFAULT 'active';
ALTER TABLE `bx_ads_entries` MODIFY `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active';

CREATE TABLE IF NOT EXISTS `bx_ads_licenses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL default '0',
  `entry_id` int(11) unsigned NOT NULL default '0',
  `count` int(11) unsigned NOT NULL default '0',
  `order` varchar(32) NOT NULL default '',
  `license` varchar(32) NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  `new` tinyint(1) NOT NULL default '1',
  PRIMARY KEY (`id`),
  KEY `product_id` (`entry_id`, `profile_id`),
  KEY `license` (`license`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_licenses_deleted` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL default '0',
  `entry_id` int(11) unsigned NOT NULL default '0',
  `count` int(11) unsigned NOT NULL default '0',
  `order` varchar(32) NOT NULL default '',
  `license` varchar(32) NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  `new` tinyint(1) NOT NULL default '1',
  `reason` varchar(16) NOT NULL default '',
  `deleted` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `product_id` (`entry_id`,`profile_id`),
  KEY `license` (`license`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_cmts_notes` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(11) NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  `cmt_pinned` int(11) NOT NULL default '0',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`),
  FULLTEXT KEY `search_fields` (`cmt_text`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `added` int(11) NOT NULL default '0',
  `changed` int(11) NOT NULL default '0',
  `amount` float NOT NULL default '0',
  `quantity` int(11) NOT NULL default '0',
  `message` text NOT NULL,
  `status` enum('accepted','awaiting','declined','canceled') NOT NULL DEFAULT 'awaiting',
  PRIMARY KEY (`id`)
);


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_ads_view_photos';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_ads_view_photos', 'bx_ads_photos_resized', 'Storage', 'a:1:{s:6:"object";s:13:"bx_ads_photos";}', 'no', '1', '2592000', '0', '', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_ads_view_photos';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_ads_view_photos', 'Resize',  'a:2:{s:1:"w";s:4:"2000";s:1:"h";s:4:"2000";}', '0');


-- FORMS:
UPDATE `sys_form_inputs` SET `attrs`='a:1:{s:8:"onchange";s:34:"oBxAdsForm.onChangeCategory(this);";}' WHERE `object`='bx_ads' AND `name`='category_select';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_ads' AND `name` IN ('name', 'auction', 'quantity', 'notes_purchased');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `help`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_ads', 'bx_ads', 'name', '', '', 0, 'text', '_bx_ads_form_entry_input_sys_name', '_bx_ads_form_entry_input_name', '', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ads_form_entry_input_name_err', 'Xss', '', 1, 0),
('bx_ads', 'bx_ads', 'auction', 1, '', 0, 'checkbox', '_bx_ads_form_entry_input_sys_auction', '_bx_ads_form_entry_input_auction', '_bx_ads_form_entry_input_auction_info', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_ads', 'bx_ads', 'quantity', '', '', 0, 'text', '_bx_ads_form_entry_input_sys_quantity', '_bx_ads_form_entry_input_quantity', '', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ads_form_entry_input_quantity_err', 'Xss', '', 1, 0),
('bx_ads', 'bx_ads', 'notes_purchased', '', '', 0, 'textarea', '_bx_ads_form_entry_input_sys_notes_purchased', '_bx_ads_form_entry_input_notes_purchased', '_bx_ads_form_entry_input_notes_purchased_inf', '', 0, 0, 3, '', '', '', '', '', '', 'XssHtml', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_ads_entry_price_add', 'bx_ads_entry_price_edit') AND `input_name` IN ('name', 'auction', 'quantity', 'notes_purchased');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_ads_entry_price_add', 'name', 2147483647, 1, 4),
('bx_ads_entry_price_add', 'auction', 2147483647, 1, 6),
('bx_ads_entry_price_add', 'quantity', 2147483647, 1, 7),
('bx_ads_entry_price_add', 'notes_purchased', 2147483647, 1, 16),

('bx_ads_entry_price_edit', 'name', 2147483647, 1, 3),
('bx_ads_entry_price_edit', 'auction', 2147483647, 1, 5),
('bx_ads_entry_price_edit', 'quantity', 2147483647, 1, 6),
('bx_ads_entry_price_edit', 'notes_purchased', 2147483647, 1, 15);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_ads_entry_price_year_add', 'bx_ads_entry_price_year_edit') AND `input_name` IN ('name', 'auction', 'quantity', 'notes_purchased');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_ads_entry_price_year_add', 'name', 2147483647, 1, 4),
('bx_ads_entry_price_year_add', 'auction', 2147483647, 1, 6),
('bx_ads_entry_price_year_add', 'quantity', 2147483647, 1, 7),
('bx_ads_entry_price_year_add', 'notes_purchased', 2147483647, 1, 17),

('bx_ads_entry_price_year_edit', 'name', 2147483647, 1, 3),
('bx_ads_entry_price_year_edit', 'auction', 2147483647, 1, 5),
('bx_ads_entry_price_year_edit', 'quantity', 2147483647, 1, 6),
('bx_ads_entry_price_year_edit', 'notes_purchased', 2147483647, 1, 16);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_ads_entry_price_year_view' AND `input_name`='quantity';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_ads_entry_price_year_view', 'quantity', 2147483647, 1, 3);


DELETE FROM `sys_objects_form` WHERE `object`='bx_ads_offer';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_ads_offer', 'bx_ads', '_bx_ads_form_offer', '', '', 'do_submit', 'bx_ads_offers', 'id', '', '', '', 0, 1, 'BxAdsFormOffer', 'modules/boonex/ads/classes/BxAdsFormOffer.php');

DELETE FROM `sys_form_displays` WHERE `object`='bx_ads_offer';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_ads_offer_add', 'bx_ads', 'bx_ads_offer', '_bx_ads_form_offer_display_add', 0),
('bx_ads_offer_view', 'bx_ads', 'bx_ads_offer', '_bx_ads_form_offer_display_view', 1);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_ads_offer';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `help`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_ads_offer', 'bx_ads', 'amount', '', '', 0, 'text', '_bx_ads_form_offer_input_sys_amount', '_bx_ads_form_offer_input_amount', '', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ads_form_offer_input_amount_err', 'Xss', '', 1, 0),
('bx_ads_offer', 'bx_ads', 'quantity', '1', '', 0, 'text', '_bx_ads_form_offer_input_sys_quantity', '_bx_ads_form_offer_input_quantity', '', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ads_form_offer_input_quantity_err', 'Xss', '', 1, 0),
('bx_ads_offer', 'bx_ads', 'message', '', '', 0, 'textarea', '_bx_ads_form_offer_input_sys_message', '_bx_ads_form_offer_input_message', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_ads_offer', 'bx_ads', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_ads_offer', 'bx_ads', 'do_submit', '_bx_ads_form_offer_input_do_submit', '', 0, 'submit', '_bx_ads_form_offer_input_sys_do_submit', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_ads_offer', 'bx_ads', 'do_cancel', '_bx_ads_form_offer_input_do_cancel', '', 0, 'button', '_bx_ads_form_offer_input_do_cancel', '', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_ads_offer_add', 'bx_ads_offer_view');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_ads_offer_add', 'amount', 2147483647, 1, 1),
('bx_ads_offer_add', 'quantity', 2147483647, 1, 2),
('bx_ads_offer_add', 'message', 2147483647, 1, 3),
('bx_ads_offer_add', 'controls', 2147483647, 1, 4),
('bx_ads_offer_add', 'do_submit', 2147483647, 1, 5),
('bx_ads_offer_add', 'do_cancel', 2147483647, 1, 6),

('bx_ads_offer_view', 'amount', 2147483647, 1, 1),
('bx_ads_offer_view', 'quantity', 2147483647, 1, 2),
('bx_ads_offer_view', 'message', 2147483647, 1, 3);

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_ads_notes';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_ads_notes', 'bx_ads', 'bx_ads_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-ad&id={object_id}', '', 'bx_ads_entries', 'id', 'author', 'title', '', 'BxTemplCmtsNotes', '');