-- TABLES
ALTER TABLE `bx_ads_offers` MODIFY `status` enum('accepted','awaiting','declined','canceled','paid') NOT NULL DEFAULT 'awaiting';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_ads_offer' AND `name`='total';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_ads_offer', 'bx_ads', 'total', '', '', 0, 'text', '_bx_ads_form_offer_input_sys_total', '_bx_ads_form_offer_input_total', '', 0, 0, 0, 'a:1:{s:8:"readonly";s:8:"readonly";}', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_ads_offer_add', 'bx_ads_offer_view') AND `input_name`='total';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_ads_offer_add', 'total', 2147483647, 1, 2),
('bx_ads_offer_view', 'total', 2147483647, 1, 2);
