
ALTER TABLE `sys_objects_search` ADD `GlobalSearch` tinyint(4) NOT NULL DEFAULT '1' AFTER `Order`;


CREATE TABLE `sys_objects_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `search_object` varchar(64) NOT NULL,
  `form_object` varchar(64) NOT NULL,
  `list_name` varchar(255) NOT NULL,
  `table` varchar(255) NOT NULL,
  `field` varchar(255) NOT NULL,
  `join` varchar(255) NOT NULL,
  `where` varchar(255) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`),
  UNIQUE KEY `form_object` (`form_object`,`list_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- can be safely applied multiple times


INSERT IGNORE INTO `sys_menu_templates` (`id`, `template`, `title`) VALUES
(16, 'menu_buttons_ver.html', '_sys_menu_template_title_buttons_ver');



INSERT IGNORE INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_homepage', '_sys_menu_title_homepage', 'sys_homepage', 'system', 14, 0, 1, 'BxTemplMenuHomepage', '');

DELETE FROM `sys_objects_menu` WHERE `object` = 'sys_site_submenu_main';



INSERT IGNORE INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('sys_homepage', 'system', '_sys_menu_set_title_homepage', 0);



DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND  `module` = 'system' AND `title` = '_sys_page_block_title_login';

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('sys_home', 1, 'system', '_sys_page_block_title_homepage_menu', 3, 2147483647, 'menu', 'sys_homepage', 0, 1, 0);



UPDATE `sys_options` SET `value` = 'sys_recaptcha_new' WHERE `name` = 'sys_captcha_default';
UPDATE `sys_options` SET `value` = '10000' WHERE `name` = 'sys_live_updates_interval';
UPDATE `sys_options` SET `value` = 'on' WHERE `name` = 'sys_db_cache_enable';
UPDATE `sys_options` SET `value` = 'on' WHERE `name` = 'sys_page_cache_enable';
UPDATE `sys_options` SET `value` = 'on' WHERE `name` = 'sys_pb_cache_enable';
UPDATE `sys_options` SET `value` = 'on' WHERE `name` = 'sys_template_cache_enable';
UPDATE `sys_options` SET `value` = 'on' WHERE `name` = 'sys_template_cache_css_enable';
UPDATE `sys_options` SET `value` = 'on' WHERE `name` = 'sys_template_cache_js_enable';

UPDATE `sys_options` SET `order` = '59' WHERE `name` = 'sys_template_cache_compress_enable';

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'cache');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_template_cache_minify_css_enable', '_adm_stg_cpt_option_sys_template_cache_minify_css_enable', 'on', 'checkbox', '', '', '', 57),
(@iCategoryId, 'sys_template_cache_minify_js_enable', '_adm_stg_cpt_option_sys_template_cache_minify_js_enable', 'on', 'checkbox', '', '', '', 58);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'system');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_site_cover_code', '', '<style>\r\n    /*--- General ---*/\r\n  \r\n    @media (max-width:1024px) {\r\n        #bx-menu-main-bar-wrapper,\r\n        #bx-toolbar {\r\n            display:none;\r\n        }\r\n        #bx-content-wrapper {\r\n            border:none;\r\n        }\r\n    }\r\n\r\n    #bx-content-wrapper {\r\n        padding-top: 0px;\r\n        padding-bottom: 4rem;\r\n    }\r\n\r\n    .bx-page-wrapper,\r\n    #bx-content-container,\r\n    #bx-content-main {\r\n        width: 100%;\r\n        margin: 0px;\r\n        padding: 0px;\r\n    }\r\n\r\n    /*--- Splash ---*/\r\n\r\n    .bx-splash {\r\n        position: relative;\r\n    }\r\n\r\n    .bx-spl-preload {\r\n        position: absolute;\r\n\r\n        top: 0px;\r\n        left: 0px;\r\n        width: 1px;\r\n        height: 1px;\r\n\r\n        overflow: hidden;\r\n    }\r\n\r\n    .bx-spl-slide {\r\n        position: relative;\r\n        display: block;\r\n\r\n        overflow: hidden;\r\n    }\r\n\r\n    .bx-spl-bg {\r\n        position: relative;\r\n\r\n        width: 100%;\r\n        height: 100%;\r\n    }\r\n\r\n    .bx-spl-container {\r\n        position: relative;\r\n\r\n        top: 0px;\r\n        left: 0px;\r\n        width: 100%;\r\n        height: 100%;\r\n    }\r\n\r\n    .bx-spl-content {\r\n        position: relative;\r\n        width:100%;\r\n    }\r\n\r\n    .bx-spl-slide .bx-spl-content h1 {\r\n        font-size: 2.25rem;\r\n    }\r\n\r\n    .bx-spl-slide .bx-spl-content h3 {\r\n        font-size: 1.5rem;\r\n    }\r\n\r\n    .bx-spl-white-text-all * {\r\n        color: #fff;\r\n    }\r\n\r\n    /*--- Join button ---*/\r\n\r\n    .bx-spl-join,\r\n    .bx-spl-join {\r\n        display: inline-block;\r\n        border-color: #fff;\r\n    }\r\n    .bx-spl-join a,\r\n    .bx-spl-join a {\r\n        display: block;\r\n        padding-left: 3.75rem;\r\n        padding-right: 3.75rem;\r\n        text-decoration: none;\r\n    }\r\n\r\n    /*--- Slides ---*/\r\n\r\n    #bx-spl-slide01 .bx-spl-container,\r\n    #bx-spl-slide03 .bx-spl-container {\r\n        position: absolute;\r\n        display: -webkit-flex;\r\n        display: flex;\r\n        -webkit-align-items: center;\r\n        align-items: center;\r\n    }\r\n    #bx-spl-slide01 .bx-spl-content,\r\n    #bx-spl-slide03 .bx-spl-content {\r\n        text-align: center;\r\n        text-shadow: 0px 0px 0.25rem #000;\r\n    }\r\n    #bx-spl-slide02 .bx-spl-content h3,\r\n    #bx-spl-slide04 .bx-spl-content h3 {\r\n        text-align: center;\r\n    }\r\n\r\n    /*--- Slide 1 ---*/\r\n\r\n    #bx-spl-slide01 {\r\n        width:100%;\r\n        height:100vh;\r\n    }\r\n    #bx-spl-slide01 .bx-spl-bg {\r\n        background-position: center center;\r\n        background-repeat: no-repeat;\r\n        background-size: cover;\r\n    }\r\n    #bx-spl-slide01 .bx-spl-slide-video {\r\n        object-fit: cover;\r\n\r\n        width: 100%;\r\n        height: 100vh;\r\n    }\r\n\r\n    /*--- Slide 2 ---*/\r\n\r\n    #bx-spl-slide02 .bx-spl-bg {\r\n        background-color: #fff;\r\n        height: 35vw;\r\n        max-height:400px;\r\n    }\r\n    #bx-spl-slide02 .bx-spl-image {\r\n        position: absolute;\r\n        width: 60%;\r\n        height: 100%;\r\n        background-position: center bottom;\r\n        background-repeat: no-repeat;\r\n        background-size: contain;\r\n    }\r\n    #bx-spl-slide02 .bx-spl-content {\r\n        position: absolute;\r\n        display: -webkit-flex;\r\n        -webkit-align-items: center;\r\n        -webkit-justify-content: center;\r\n        display: flex;\r\n        align-items: center;\r\n        justify-content: center;\r\n\r\n        top: 0px;\r\n        right: 0px;\r\n        width: 40%;\r\n        height: 100%;\r\n    }\r\n    @media (max-width:720px) {\r\n        #bx-spl-slide02.bx-spl-slide .bx-spl-content h3 {\r\n            font-size: 0.8rem;\r\n        }\r\n    }\r\n\r\n    /*--- Slide 3 ---*/\r\n\r\n    #bx-spl-slide03 {\r\n        width:100%;\r\n        height:150vh;\r\n    }\r\n    #bx-spl-slide03 .bx-spl-bg {\r\n        background-position: center center;\r\n        background-repeat: repeat;\r\n        background-attachment: fixed;\r\n        background-size:100%;\r\n    }\r\n\r\n    /*--- Slide 4 ---*/\r\n\r\n    #bx-spl-slide04 {\r\n        width:100%;\r\n        height:100vh;\r\n    }\r\n    #bx-spl-slide04 .bx-spl-bg {\r\n        background-color:#fff;\r\n    }\r\n    #bx-spl-slide04 .bx-spl-container {\r\n        display: -webkit-flex;\r\n        -webkit-align-items: center;\r\n        -webkit-justify-content: center;\r\n        display: flex;\r\n        align-items: center;\r\n        justify-content: center;\r\n    }\r\n    #bx-spl-slide04 .bx-spl-content {\r\n        width: 50%;\r\n        margin-left: auto;\r\n        margin-right: auto;\r\n    }\r\n    @media (max-width:720px) {\r\n        #bx-spl-slide04 .bx-spl-content {\r\n            width: 90%;\r\n        }\r\n    }\r\n    #bx-spl-slide04 .bx-spl-content h3 {\r\n        margin-top: 0px;\r\n        margin-bottom: 2rem;\r\n    }\r\n</style>\r\n<div id="skrollr-body" class="bx-splash bx-def-color-bg-page">\r\n	<div id="bx-spl-preload" class="bx-spl-preload">\r\n		<img src="<bx_image_url:cover01.jpg />">\r\n		<img src="<bx_image_url:cover02.jpg />">\r\n		<img src="<bx_image_url:cover03.jpg />">\r\n	</div>\r\n\r\n	<div id="bx-spl-slide01" class="bx-spl-slide">\r\n		<div class="bx-spl-bg" style="background-image:url(<bx_image_url:cover01.jpg />);">\r\n			<video class="bx-spl-slide-video bx-def-media-phone-hide bx-def-media-tablet-hide" poster="<bx_image_url:cover01.jpg />" loop autoplay muted>\r\n				<source src="<bx_image_url:cover01.mp4 />" type="video/mp4">\r\n				<source src="<bx_image_url:cover01.webm />" type="video/webm">\r\n			</video>\r\n		</div>\r\n		<div class="bx-spl-container">\r\n			<div class="bx-spl-content bx-spl-white-text-all" data-anchor-target="#bx-spl-slide01 .bx-spl-bg" data-top="opacity:1" data-top-center="opacity:0">\r\n				<h1 class="bx-def-font-semibold"><bx_text:_sys_txt_splash_slide01_title /></h1>\r\n				<h3 class="bx-def-font-normal"><bx_text:_sys_txt_splash_slide01_desc /></h3>\r\n				<div class="bx-spl-join bx-def-margin-topbottom bx-def-border bx-def-round-corners">\r\n					<a class="bx-def-padding-sec-topbottom bx-def-font-h3 bx-def-font-normal" href="__join_link__"><bx_text:_sys_txt_splash_btn_join /></a>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</div>\r\n\r\n	<div id="bx-spl-slide02" class="bx-spl-slide">\r\n		<div class="bx-spl-bg">\r\n			<div class="bx-spl-container">\r\n				<div class="bx-spl-image" style="background-image:url(<bx_image_url:cover02.jpg />);" data-bottom-top="bottom:-100%;" data-100-center-center="bottom:0%;"></div>\r\n				<div class="bx-spl-content" data-bottom-top="right:-50%;" data-100-center-center="right:0%;">\r\n					<h3 class="bx-def-font-normal"><bx_text:_sys_txt_splash_slide02_txt /></h3>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</div>\r\n\r\n	<div id="bx-spl-slide03" class="bx-spl-slide">\r\n		<div class="bx-spl-bg" style="background-image:url(<bx_image_url:cover03.jpg />)"></div>\r\n		<div class="bx-spl-container">\r\n			<div class="bx-spl-content bx-spl-white-text-all">\r\n				<h3 class="bx-def-font-normal"><bx_text:_sys_txt_splash_slide03_txt /></h3>\r\n				<div class="bx-spl-join bx-def-margin-topbottom bx-def-border bx-def-round-corners">\r\n					<a class="bx-def-padding-sec-topbottom bx-def-font-h3 bx-def-font-normal" href="__join_link__"><bx_text:_sys_txt_splash_btn_join /></a>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</div>\r\n	<div id="bx-spl-slide04" class="bx-spl-slide">\r\n		<div class="bx-spl-bg">\r\n            <div class="bx-spl-container" data-anchor-target="#bx-spl-slide03 .bx-spl-bg" data-100-center-bottom="opacity:0; transform:scale(0.7,0.7);" data-200-end="opacity:1; transform:scale(1,1);">\r\n                <div class="bx-spl-content">\r\n                    <h3 class="bx-def-font-normal"><bx_text:_sys_txt_splash_slide04_txt /></h3>\r\n                    <div class="bx-spl-login">__login_form__</div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n	</div>\r\n	<script type="text/javascript">\r\n		$(document).ready(function () {\r\n\r\n            // workaround for iOS 7 \r\n            if (!!navigator.userAgent.match(/i(Pad|Phone|Pod).+(Version\\/7\\.\\d+ Mobile)/i)) {\r\n                var aSelVh = {\r\n                    ''1'': ''#bx-spl-slide01, #bx-spl-slide01 .bx-spl-slide-video, #bx-spl-slide04'',\r\n                    ''1.5'': ''#bx-spl-slide03''\r\n                };\r\n                var aSelVw = {\r\n                    ''0.35'': ''#bx-spl-slide02 .bx-spl-bg''\r\n                };\r\n                function fixMobileSafariViewport() {\r\n                    $.each(aSelVh, function (sVal, sSel) {\r\n                        $(sSel).css(''height'', window.innerHeight * parseFloat(sVal));\r\n                    });\r\n                    $.each(aSelVw, function (sVal, sSel) {\r\n                        $(sSel).css(''height'', window.innerWidth * parseFloat(sVal));\r\n                    });                \r\n                }\r\n                // listen to portrait/landscape changes\r\n                window.addEventListener(''orientationchange'', fixMobileSafariViewport, true);\r\n                fixMobileSafariViewport();\r\n            }\r\n\r\n			skrollr.init();\r\n		});\r\n	</script>\r\n</div>', 'text', '', '', '', 25),

(@iCategoryId, 'sys_site_cover_enabled', '', 'on', 'checkbox', '', '', '', 26);



-- last step is to update current version


UPDATE `sys_modules` SET `version` = '8.0.0-B1' WHERE `version` = '8.0.0-A11' AND `name` = 'system';

