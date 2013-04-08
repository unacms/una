

INSERT IGNORE INTO `sys_countries` VALUES('AX', 'ALA', 248, 'Aland Islands', 'Europe', 'Euro', 'EUR');
INSERT IGNORE INTO `sys_countries` VALUES('BL', 'BLM', 652, 'Saint Barthelemy', 'Central America and the Caribbean', 'Euro', 'EUR');
INSERT IGNORE INTO `sys_countries` VALUES('GG', 'GGY', 831, 'Guernsey', 'Europe', 'Pound sterling', 'GBP');
INSERT IGNORE INTO `sys_countries` VALUES('IM', 'IMN', 833, 'Isle of Man', 'Europe', 'Pound sterling', 'GBP');
INSERT IGNORE INTO `sys_countries` VALUES('JE', 'JEY', 832, 'Jersey', 'Europe', 'Pound sterling', 'GBP');
INSERT IGNORE INTO `sys_countries` VALUES('ME', 'MNE', 499, 'Montenegro', 'Europe', 'Euro', 'EUR');
INSERT IGNORE INTO `sys_countries` VALUES('MF', 'MAF', 663, 'Saint Martin (French part)', 'Central America and the Caribbean', 'Euro', 'EUR');
INSERT IGNORE INTO `sys_countries` VALUES('RS', 'SRB', 688, 'Serbia', 'Europe', 'Serbian Dinar', 'RSD');
DELETE FROM `sys_countries` WHERE `ISO2` = 'YU';


INSERT IGNORE INTO `sys_email_templates` VALUES(19, 't_UserJoined', 'New user joined', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p>New user <RealName> with email <Email> has joined, his/her ID is <recipientID></p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'Profile activation message template.', 0);
INSERT IGNORE INTO `sys_email_templates` VALUES(21, 't_UserConfirmed', 'New user confirmed', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p>New user <RealName> with email <Email> has been confirmed, his/her ID is <recipientID></p>\r\n\r\n<p>--</p>\r\n<p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!!\r\n<br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'Profile activation message template.', 0);
INSERT IGNORE INTO `sys_email_templates` VALUES(55, 't_FriendRequestAccepted', 'Friend request was accepted', '<html>\r\n<body style="font: 12px Verdana; color:#000000">\r\n    <p><b>Dear <Recipient></b>,</p>\r\n    <br />\r\n    <p><a href="<SenderLink>"><Sender></a> accepted your friend request.</p>\r\n    <br /> \r\n    <p><b>Thank you for using our services!</b></p> \r\n    <p>--</p>\r\n    <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! \r\n    <br />Auto-generated e-mail, please, do not reply!!!</p>\r\n</html>', 'Friend accepted request message', 0);


DELETE FROM `sys_options` WHERE `Name` = 'enable_inbox_notify';
UPDATE `sys_options` SET `desc` = 'Insert Meta description on site homepage' WHERE `Name` = 'MetaDescription';
UPDATE `sys_options` SET `desc` = 'Insert Meta keywords on site homepage (comma-separated list)' WHERE `Name` = 'MetaKeyWords';
UPDATE `sys_options` SET `kateg` = 14, `order_in_kateg` = 1 WHERE `Name` = 'enable_member_store_ip';
UPDATE `sys_options` SET `kateg` = 14, `order_in_kateg` = 2 WHERE `Name` = 'ipBlacklistMode';
UPDATE `sys_options` SET `kateg` = 14, `order_in_kateg` = 3 WHERE `Name` = 'ipListGlobalType';
UPDATE `sys_options` SET `desc` = 'The number of tabs shown in the navigation menu before the More link for logged in members.' WHERE `Name` = 'nav_menu_elements_on_line_usr';
UPDATE `sys_options` SET `desc` = 'The number of tabs shown in the navigation menu before the More link for visitors.' WHERE `Name` = 'nav_menu_elements_on_line_gst';
UPDATE `sys_options` SET `desc` = 'Enable cache for images (do not work for IE7)' WHERE `Name` = 'sys_template_cache_image_enable';
UPDATE `sys_options` SET `VALUE` = '-1', `kateg` = 14, `order_in_kateg` = 4 WHERE `Name` = 'sys_security_impact_threshold_log' AND `VALUE` = 9;
UPDATE `sys_options` SET `VALUE` = '-1', `kateg` = 14, `order_in_kateg` = 5 WHERE `Name` = 'sys_security_impact_threshold_block' AND `VALUE` = 27;
INSERT IGNORE INTO `sys_options` VALUES('enable_member_store_ip', 'on', 14, 'When a member login into dolphin that the IP address from that person will be saved into the database', 'checkbox', '', '', 1, '');
INSERT IGNORE INTO `sys_options` VALUES('sys_template_page_width_min', '774', 13, 'Min page width(in pixels)', 'digit', '', '', 7, '');
INSERT IGNORE INTO `sys_options` VALUES('sys_template_page_width_max', '1600', 13, 'Max page width(in pixels)', 'digit', '', '', 8, '');
INSERT IGNORE INTO `sys_options` VALUES('sys_security_form_token_enable', 'on', 14, 'Enable CSRF tocken in forms', 'checkbox', '', '', 6, '');
INSERT IGNORE INTO `sys_options` VALUES('sys_security_form_token_lifetime', '86400', 14, 'CSRF tocken lifetime in seconds(0 - do not track time)', 'digit', '', '', 7, '');


UPDATE `sys_options_cats` SET `menu_order` = 15 WHERE `ID` = 15;
UPDATE `sys_options_cats` SET `menu_order` = 16 WHERE `ID` = 16;
UPDATE `sys_options_cats` SET `menu_order` = 19 WHERE `ID` = 19;
UPDATE `sys_options_cats` SET `menu_order` = 23 WHERE `ID` = 23;
INSERT IGNORE INTO `sys_options_cats` VALUES (14, 'Security', 14);


SET @iMax := (SELECT MAX(`ID`)+1 FROM `sys_page_compose`);
UPDATE `sys_page_compose` SET `ID` = @iMax WHERE `ID` = 176;
INSERT INTO `sys_page_compose` VALUES(176, 'profile', '998px', 'Profile Fields Block', '_FieldCaption_Misc_View', 0, 0, 'PFBlock', '20', 1, 66, 'non,memb', 0);


CREATE TABLE IF NOT EXISTS `sys_sessions` (
  `id` varchar(32) NOT NULL default '',
  `user_id` int(10) unsigned NOT NULL default '0',
  `data` text collate utf8_unicode_ci,
  `date` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;


DELETE FROM `sys_pre_values` WHERE `Key` = 'Country';
INSERT INTO `sys_pre_values` VALUES 
('Country', 'AF', 1, '__Afghanistan', '', '', '', '', ''),
('Country', 'AX', 2, '__Aland_Islands', '', '', '', '', ''),
('Country', 'AL', 3, '__Albania', '', '', '', '', ''),
('Country', 'DZ', 4, '__Algeria', '', '', '', '', ''),
('Country', 'AS', 5, '__American Samoa', '', '', '', '', ''),
('Country', 'AD', 6, '__Andorra', '', '', '', '', ''),
('Country', 'AO', 7, '__Angola', '', '', '', '', ''),
('Country', 'AI', 8, '__Anguilla', '', '', '', '', ''),
('Country', 'AQ', 9, '__Antarctica', '', '', '', '', ''),
('Country', 'AG', 10, '__Antigua and Barbuda', '', '', '', '', ''),
('Country', 'AR', 11, '__Argentina', '', '', '', '', ''),
('Country', 'AM', 12, '__Armenia', '', '', '', '', ''),
('Country', 'AW', 13, '__Aruba', '', '', '', '', ''),
('Country', 'AU', 14, '__Australia', '', '', '', '', ''),
('Country', 'AT', 15, '__Austria', '', '', '', '', ''),
('Country', 'AZ', 16, '__Azerbaijan', '', '', '', '', ''),
('Country', 'BH', 17, '__Bahrain', '', '', '', '', ''),
('Country', 'BD', 18, '__Bangladesh', '', '', '', '', ''),
('Country', 'BB', 19, '__Barbados', '', '', '', '', ''),
('Country', 'BY', 20, '__Belarus', '', '', '', '', ''),
('Country', 'BE', 21, '__Belgium', '', '', '', '', ''),
('Country', 'BZ', 22, '__Belize', '', '', '', '', ''),
('Country', 'BJ', 23, '__Benin', '', '', '', '', ''),
('Country', 'BM', 24, '__Bermuda', '', '', '', '', ''),
('Country', 'BT', 25, '__Bhutan', '', '', '', '', ''),
('Country', 'BO', 26, '__Bolivia', '', '', '', '', ''),
('Country', 'BA', 27, '__Bosnia and Herzegovina', '', '', '', '', ''),
('Country', 'BW', 28, '__Botswana', '', '', '', '', ''),
('Country', 'BV', 29, '__Bouvet Island', '', '', '', '', ''),
('Country', 'BR', 30, '__Brazil', '', '', '', '', ''),
('Country', 'IO', 31, '__British Indian Ocean Territory', '', '', '', '', ''),
('Country', 'VG', 32, '__British Virgin Islands', '', '', '', '', ''),
('Country', 'BN', 33, '__Brunei Darussalam', '', '', '', '', ''),
('Country', 'BG', 34, '__Bulgaria', '', '', '', '', ''),
('Country', 'BF', 35, '__Burkina Faso', '', '', '', '', ''),
('Country', 'MM', 36, '__Burma', '', '', '', '', ''),
('Country', 'BI', 37, '__Burundi', '', '', '', '', ''),
('Country', 'KH', 38, '__Cambodia', '', '', '', '', ''),
('Country', 'CM', 39, '__Cameroon', '', '', '', '', ''),
('Country', 'CA', 40, '__Canada', '', '', '', '', ''),
('Country', 'CV', 41, '__Cape Verde', '', '', '', '', ''),
('Country', 'KY', 42, '__Cayman Islands', '', '', '', '', ''),
('Country', 'CF', 43, '__Central African Republic', '', '', '', '', ''),
('Country', 'TD', 44, '__Chad', '', '', '', '', ''),
('Country', 'CL', 45, '__Chile', '', '', '', '', ''),
('Country', 'CN', 46, '__China', '', '', '', '', ''),
('Country', 'CX', 47, '__Christmas Island', '', '', '', '', ''),
('Country', 'CC', 48, '__Cocos (Keeling) Islands', '', '', '', '', ''),
('Country', 'CO', 49, '__Colombia', '', '', '', '', ''),
('Country', 'KM', 50, '__Comoros', '', '', '', '', ''),
('Country', 'CD', 51, '__Congo, Democratic Republic of the', '', '', '', '', ''),
('Country', 'CG', 52, '__Congo, Republic of the', '', '', '', '', ''),
('Country', 'CK', 53, '__Cook Islands', '', '', '', '', ''),
('Country', 'CR', 54, '__Costa Rica', '', '', '', '', ''),
('Country', 'CI', 55, '__Cote d''Ivoire', '', '', '', '', ''),
('Country', 'HR', 56, '__Croatia', '', '', '', '', ''),
('Country', 'CU', 57, '__Cuba', '', '', '', '', ''),
('Country', 'CY', 58, '__Cyprus', '', '', '', '', ''),
('Country', 'CZ', 59, '__Czech Republic', '', '', '', '', ''),
('Country', 'DK', 60, '__Denmark', '', '', '', '', ''),
('Country', 'DJ', 61, '__Djibouti', '', '', '', '', ''),
('Country', 'DM', 62, '__Dominica', '', '', '', '', ''),
('Country', 'DO', 63, '__Dominican Republic', '', '', '', '', ''),
('Country', 'TL', 64, '__East Timor', '', '', '', '', ''),
('Country', 'EC', 65, '__Ecuador', '', '', '', '', ''),
('Country', 'EG', 66, '__Egypt', '', '', '', '', ''),
('Country', 'SV', 67, '__El Salvador', '', '', '', '', ''),
('Country', 'GQ', 68, '__Equatorial Guinea', '', '', '', '', ''),
('Country', 'ER', 69, '__Eritrea', '', '', '', '', ''),
('Country', 'EE', 70, '__Estonia', '', '', '', '', ''),
('Country', 'ET', 71, '__Ethiopia', '', '', '', '', ''),
('Country', 'FK', 72, '__Falkland Islands (Islas Malvinas)', '', '', '', '', ''),
('Country', 'FO', 73, '__Faroe Islands', '', '', '', '', ''),
('Country', 'FJ', 74, '__Fiji', '', '', '', '', ''),
('Country', 'FI', 75, '__Finland', '', '', '', '', ''),
('Country', 'FR', 76, '__France', '', '', '', '', ''),
('Country', 'GF', 77, '__French Guiana', '', '', '', '', ''),
('Country', 'PF', 78, '__French Polynesia', '', '', '', '', ''),
('Country', 'TF', 79, '__French Southern and Antarctic Lands', '', '', '', '', ''),
('Country', 'GA', 80, '__Gabon', '', '', '', '', ''),
('Country', 'GE', 81, '__Georgia', '', '', '', '', ''),
('Country', 'DE', 82, '__Germany', '', '', '', '', ''),
('Country', 'GH', 83, '__Ghana', '', '', '', '', ''),
('Country', 'GI', 84, '__Gibraltar', '', '', '', '', ''),
('Country', 'GR', 85, '__Greece', '', '', '', '', ''),
('Country', 'GL', 86, '__Greenland', '', '', '', '', ''),
('Country', 'GD', 87, '__Grenada', '', '', '', '', ''),
('Country', 'GP', 88, '__Guadeloupe', '', '', '', '', ''),
('Country', 'GU', 89, '__Guam', '', '', '', '', ''),
('Country', 'GT', 90, '__Guatemala', '', '', '', '', ''),
('Country', 'GG', 91, '__Guernsey', '', '', '', '', ''),
('Country', 'GN', 92, '__Guinea', '', '', '', '', ''),
('Country', 'GW', 93, '__Guinea-Bissau', '', '', '', '', ''),
('Country', 'GY', 94, '__Guyana', '', '', '', '', ''),
('Country', 'HT', 95, '__Haiti', '', '', '', '', ''),
('Country', 'HM', 96, '__Heard Island and McDonald Islands', '', '', '', '', ''),
('Country', 'VA', 97, '__Holy See (Vatican City)', '', '', '', '', ''),
('Country', 'HN', 98, '__Honduras', '', '', '', '', ''),
('Country', 'HK', 99, '__Hong Kong (SAR)', '', '', '', '', ''),
('Country', 'HU', 100, '__Hungary', '', '', '', '', ''),
('Country', 'IS', 101, '__Iceland', '', '', '', '', ''),
('Country', 'IN', 102, '__India', '', '', '', '', ''),
('Country', 'ID', 103, '__Indonesia', '', '', '', '', ''),
('Country', 'IR', 104, '__Iran', '', '', '', '', ''),
('Country', 'IQ', 105, '__Iraq', '', '', '', '', ''),
('Country', 'IE', 106, '__Ireland', '', '', '', '', ''),
('Country', 'IM', 107, '__Isle_of_Man', '', '', '', '', ''),
('Country', 'IL', 108, '__Israel', '', '', '', '', ''),
('Country', 'IT', 109, '__Italy', '', '', '', '', ''),
('Country', 'JM', 110, '__Jamaica', '', '', '', '', ''),
('Country', 'JP', 111, '__Japan', '', '', '', '', ''),
('Country', 'JP', 112, '__Jersey', '', '', '', '', ''),
('Country', 'JE', 112, '__Jersey', '', '', '', '', ''),
('Country', 'JO', 113, '__Jordan', '', '', '', '', ''),
('Country', 'KZ', 114, '__Kazakhstan', '', '', '', '', ''),
('Country', 'KE', 115, '__Kenya', '', '', '', '', ''),
('Country', 'KI', 116, '__Kiribati', '', '', '', '', ''),
('Country', 'KP', 117, '__Korea, North', '', '', '', '', ''),
('Country', 'KR', 118, '__Korea, South', '', '', '', '', ''),
('Country', 'KW', 119, '__Kuwait', '', '', '', '', ''),
('Country', 'KG', 120, '__Kyrgyzstan', '', '', '', '', ''),
('Country', 'LA', 121, '__Laos', '', '', '', '', ''),
('Country', 'LV', 122, '__Latvia', '', '', '', '', ''),
('Country', 'LB', 123, '__Lebanon', '', '', '', '', ''),
('Country', 'LS', 124, '__Lesotho', '', '', '', '', ''),
('Country', 'LR', 125, '__Liberia', '', '', '', '', ''),
('Country', 'LY', 126, '__Libya', '', '', '', '', ''),
('Country', 'LI', 127, '__Liechtenstein', '', '', '', '', ''),
('Country', 'LT', 128, '__Lithuania', '', '', '', '', ''),
('Country', 'LU', 129, '__Luxembourg', '', '', '', '', ''),
('Country', 'MO', 130, '__Macao', '', '', '', '', ''),
('Country', 'MK', 131, '__Macedonia, The Former Yugoslav Republic of', '', '', '', '', ''),
('Country', 'MG', 132, '__Madagascar', '', '', '', '', ''),
('Country', 'MW', 133, '__Malawi', '', '', '', '', ''),
('Country', 'MY', 134, '__Malaysia', '', '', '', '', ''),
('Country', 'MV', 135, '__Maldives', '', '', '', '', ''),
('Country', 'ML', 136, '__Mali', '', '', '', '', ''),
('Country', 'MT', 137, '__Malta', '', '', '', '', ''),
('Country', 'MH', 138, '__Marshall Islands', '', '', '', '', ''),
('Country', 'MQ', 139, '__Martinique', '', '', '', '', ''),
('Country', 'MR', 140, '__Mauritania', '', '', '', '', ''),
('Country', 'MU', 141, '__Mauritius', '', '', '', '', ''),
('Country', 'YT', 142, '__Mayotte', '', '', '', '', ''),
('Country', 'MX', 143, '__Mexico', '', '', '', '', ''),
('Country', 'FM', 144, '__Micronesia, Federated States of', '', '', '', '', ''),
('Country', 'MD', 145, '__Moldova', '', '', '', '', ''),
('Country', 'MC', 146, '__Monaco', '', '', '', '', ''),
('Country', 'MN', 147, '__Mongolia', '', '', '', '', ''),
('Country', 'ME', 148, '__Montenegro', '', '', '', '', ''),
('Country', 'MS', 149, '__Montserrat', '', '', '', '', ''),
('Country', 'MA', 150, '__Morocco', '', '', '', '', ''),
('Country', 'MZ', 151, '__Mozambique', '', '', '', '', ''),
('Country', 'NA', 152, '__Namibia', '', '', '', '', ''),
('Country', 'NR', 153, '__Nauru', '', '', '', '', ''),
('Country', 'NP', 154, '__Nepal', '', '', '', '', ''),
('Country', 'NL', 155, '__Netherlands', '', '', '', '', ''),
('Country', 'AN', 156, '__Netherlands Antilles', '', '', '', '', ''),
('Country', 'NC', 157, '__New Caledonia', '', '', '', '', ''),
('Country', 'NZ', 158, '__New Zealand', '', '', '', '', ''),
('Country', 'NI', 159, '__Nicaragua', '', '', '', '', ''),
('Country', 'NE', 160, '__Niger', '', '', '', '', ''),
('Country', 'NG', 161, '__Nigeria', '', '', '', '', ''),
('Country', 'NU', 162, '__Niue', '', '', '', '', ''),
('Country', 'NF', 163, '__Norfolk Island', '', '', '', '', ''),
('Country', 'MP', 164, '__Northern Mariana Islands', '', '', '', '', ''),
('Country', 'NO', 165, '__Norway', '', '', '', '', ''),
('Country', 'OM', 166, '__Oman', '', '', '', '', ''),
('Country', 'PK', 167, '__Pakistan', '', '', '', '', ''),
('Country', 'PW', 168, '__Palau', '', '', '', '', ''),
('Country', 'PS', 169, '__Palestinian Territory, Occupied', '', '', '', '', ''),
('Country', 'PA', 170, '__Panama', '', '', '', '', ''),
('Country', 'PG', 171, '__Papua New Guinea', '', '', '', '', ''),
('Country', 'PY', 172, '__Paraguay', '', '', '', '', ''),
('Country', 'PE', 173, '__Peru', '', '', '', '', ''),
('Country', 'PH', 174, '__Philippines', '', '', '', '', ''),
('Country', 'PN', 175, '__Pitcairn Islands', '', '', '', '', ''),
('Country', 'PL', 176, '__Poland', '', '', '', '', ''),
('Country', 'PT', 177, '__Portugal', '', '', '', '', ''),
('Country', 'PR', 178, '__Puerto Rico', '', '', '', '', ''),
('Country', 'QA', 179, '__Qatar', '', '', '', '', ''),
('Country', 'RE', 180, '__Reunion', '', '', '', '', ''),
('Country', 'RO', 181, '__Romania', '', '', '', '', ''),
('Country', 'RU', 182, '__Russia', '', '', '', '', ''),
('Country', 'RW', 183, '__Rwanda', '', '', '', '', ''),
('Country', 'SH', 184, '__Saint Helena', '', '', '', '', ''),
('Country', 'KN', 185, '__Saint Kitts and Nevis', '', '', '', '', ''),
('Country', 'LC', 186, '__Saint Lucia', '', '', '', '', ''),
('Country', 'PM', 187, '__Saint Pierre and Miquelon', '', '', '', '', ''),
('Country', 'VC', 188, '__Saint Vincent and the Grenadines', '', '', '', '', ''),
('Country', 'BL', 189, '__Saint_Barthelemy', '', '', '', '', ''),
('Country', 'MF', 190, '__Saint_Martin_French_part', '', '', '', '', ''),
('Country', 'WS', 191, '__Samoa', '', '', '', '', ''),
('Country', 'SM', 192, '__San Marino', '', '', '', '', ''),
('Country', 'ST', 193, '__Sao Tome and Principe', '', '', '', '', ''),
('Country', 'SA', 194, '__Saudi Arabia', '', '', '', '', ''),
('Country', 'SN', 195, '__Senegal', '', '', '', '', ''),
('Country', 'RS', 196, '__Serbia', '', '', '', '', ''),
('Country', 'SC', 197, '__Seychelles', '', '', '', '', ''),
('Country', 'SL', 198, '__Sierra Leone', '', '', '', '', ''),
('Country', 'SG', 199, '__Singapore', '', '', '', '', ''),
('Country', 'SK', 200, '__Slovakia', '', '', '', '', ''),
('Country', 'SI', 201, '__Slovenia', '', '', '', '', ''),
('Country', 'SB', 202, '__Solomon Islands', '', '', '', '', ''),
('Country', 'SO', 203, '__Somalia', '', '', '', '', ''),
('Country', 'ZA', 204, '__South Africa', '', '', '', '', ''),
('Country', 'GS', 205, '__South Georgia and the South Sandwich Islands', '', '', '', '', ''),
('Country', 'ES', 206, '__Spain', '', '', '', '', ''),
('Country', 'LK', 207, '__Sri Lanka', '', '', '', '', ''),
('Country', 'SD', 208, '__Sudan', '', '', '', '', ''),
('Country', 'SR', 209, '__Suriname', '', '', '', '', ''),
('Country', 'SJ', 210, '__Svalbard', '', '', '', '', ''),
('Country', 'SZ', 211, '__Swaziland', '', '', '', '', ''),
('Country', 'SE', 212, '__Sweden', '', '', '', '', ''),
('Country', 'CH', 213, '__Switzerland', '', '', '', '', ''),
('Country', 'SY', 214, '__Syria', '', '', '', '', ''),
('Country', 'TW', 215, '__Taiwan', '', '', '', '', ''),
('Country', 'TJ', 216, '__Tajikistan', '', '', '', '', ''),
('Country', 'TZ', 217, '__Tanzania', '', '', '', '', ''),
('Country', 'TH', 218, '__Thailand', '', '', '', '', ''),
('Country', 'BS', 219, '__The Bahamas', '', '', '', '', ''),
('Country', 'GM', 220, '__The Gambia', '', '', '', '', ''),
('Country', 'TG', 221, '__Togo', '', '', '', '', ''),
('Country', 'TK', 222, '__Tokelau', '', '', '', '', ''),
('Country', 'TO', 223, '__Tonga', '', '', '', '', ''),
('Country', 'TT', 224, '__Trinidad and Tobago', '', '', '', '', ''),
('Country', 'TN', 225, '__Tunisia', '', '', '', '', ''),
('Country', 'TR', 226, '__Turkey', '', '', '', '', ''),
('Country', 'TM', 227, '__Turkmenistan', '', '', '', '', ''),
('Country', 'TC', 228, '__Turks and Caicos Islands', '', '', '', '', ''),
('Country', 'TV', 229, '__Tuvalu', '', '', '', '', ''),
('Country', 'UG', 230, '__Uganda', '', '', '', '', ''),
('Country', 'UA', 231, '__Ukraine', '', '', '', '', ''),
('Country', 'AE', 232, '__United Arab Emirates', '', '', '', '', ''),
('Country', 'GB', 233, '__United Kingdom', '', '', '', '', ''),
('Country', 'US', 234, '__United States', '', '', '', '', ''),
('Country', 'UM', 235, '__United States Minor Outlying Islands', '', '', '', '', ''),
('Country', 'UY', 236, '__Uruguay', '', '', '', '', ''),
('Country', 'UZ', 237, '__Uzbekistan', '', '', '', '', ''),
('Country', 'VU', 238, '__Vanuatu', '', '', '', '', ''),
('Country', 'VE', 239, '__Venezuela', '', '', '', '', ''),
('Country', 'VN', 240, '__Vietnam', '', '', '', '', ''),
('Country', 'VI', 241, '__Virgin Islands', '', '', '', '', ''),
('Country', 'WF', 242, '__Wallis and Futuna', '', '', '', '', ''),
('Country', 'EH', 243, '__Western Sahara', '', '', '', '', ''),
('Country', 'YE', 244, '__Yemen', '', '', '', '', ''),
('Country', 'ZM', 245, '__Zambia', '', '', '', '', ''),
('Country', 'ZW', 246, '__Zimbabwe', '', '', '', '', '');


DELETE FROM `sys_shared_sites` WHERE `Name` = 'furl';
DELETE FROM `sys_shared_sites` WHERE `Name` = 'netscape';
DELETE FROM `sys_shared_sites` WHERE `Name` = 'shadows';
DELETE FROM `sys_shared_sites` WHERE `Name` = 'sphere';


DELETE FROM `sys_page_compose_pages` WHERE `Name` = 'all_members';
DELETE FROM `sys_page_compose` WHERE `Page` = 'all_members';
UPDATE `sys_page_compose_pages` SET `Title` = 'All Members' WHERE `Name` = 'browse_page';


-- last step is to update current version

INSERT INTO `sys_options` VALUES ('sys_tmp_version', '7.0.1', 0, 'Temporary Dolphin version ', 'digit', '', '', 0, '') ON DUPLICATE KEY UPDATE `VALUE` = '7.0.1';

