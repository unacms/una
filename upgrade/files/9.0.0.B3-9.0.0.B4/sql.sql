

DROP TABLE IF EXISTS `sys_objects_social_sharing`;



CREATE TABLE IF NOT EXISTS `sys_objects_file_handlers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `preg_ext` text NOT NULL,
  `active` tinyint(4) NOT NULL,
  `order` int(11) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

TRUNCATE TABLE `sys_objects_file_handlers`;

INSERT INTO `sys_objects_file_handlers` (`object`, `title`, `preg_ext`, `active`, `order`, `override_class_name`, `override_class_file`) VALUES
('sys_ms_viewer', '_sys_file_handlers_ms_viewer', '/\\.(doc|docx|xls|xlsx|ppt|pptx)$/i', 1, 1, 'BxTemplFileHandlerMsViewer', ''),
('sys_google_viewer', '_sys_file_handlers_google_viewer', '/\\.(pdf|doc|docx|xls|xlsx|ppt|pptx|ai|svg|ps|tif|tiff)$/i', 1, 2, 'BxTemplFileHandlerGoogleViewer', ''),
('sys_images_viewer', '_sys_file_handlers_images_viewer', '/\\.(jpg|jpeg|png|gif|webp)$/i', 1, 3, 'BxTemplFileHandlerImagesViewer', ''),
('sys_code_viewer', '_sys_file_handlers_code_viewer', '/\\.(1st|aspx|asp|json|js|jsp|java|php|xml|html|htm|rdf|xsd|xsl|xslt|sax|rss|cfm|js|asm|pl|prl|bas|b|vbs|fs|src|cs|ws|cgi|bat|py|c|cpp|cc|cp|h|hh|cxx|hxx|c++|m|lua|swift|sh|as|cob|tpl|lsp|x|cmd|rb|cbl|pas|pp|vb|f|perl|jl|lol|bal|pli|css|less|sass|saas|bcc|coffee|jade|j|tea|c#|sas|diff|pro|for|sh|bsh|bash|twig|csh|lisp|lsp|cobol|pl|d|git|rb|hrl|cr|inp|a|go|as3|m|sql|md|txt|csv)$/i', 1, 4, 'BxTemplFileHandlerCodeViewer', '');



ALTER TABLE  `sys_options` CHANGE  `type`  `type` ENUM('value',  'digit',  'text',  'checkbox',  'select',  'combobox',  'file',  'image',  'list',  'rlist',  'rgb',  'rgba') NOT NULL DEFAULT  'digit';



DELETE FROM `sys_options` WHERE `name` IN('sys_email_attachable_email_templates', 'sys_embedly_api_key', 'sys_embedly_api_pattern');


SET @iCategoryIdHidden = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdHidden, 'sys_email_attachable_email_templates', '_adm_stg_cpt_option_sys_email_attachable_email_templates', '', 'digit', '', '', '', 31);


SET @iCategoryIdGeneral = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'general');

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES

(@iCategoryIdGeneral, 'sys_embedly_api_key', '_adm_stg_cpt_option_sys_embedly_api_key', '', 'digit', '', '', '', 80),
(@iCategoryIdGeneral, 'sys_embedly_api_pattern', '_adm_stg_cpt_option_sys_embedly_api_pattern', '/((https?:\\/\\/(.*yfrog\\..*\\/.*|www\\.flickr\\.com\\/photos\\/.*|flic\\.kr\\/.*|twitpic\\.com\\/.*|www\\.twitpic\\.com\\/.*|twitpic\\.com\\/photos\\/.*|www\\.twitpic\\.com\\/photos\\/.*|.*imgur\\.com\\/.*|twitgoo\\.com\\/.*|i.*\\.photobucket\\.com\\/albums\\/.*|s.*\\.photobucket\\.com\\/albums\\/.*|media\\.photobucket\\.com\\/image\\/.*|www\\.mobypicture\\.com\\/user\\/.*\\/view\\/.*|moby\\.to\\/.*|xkcd\\.com\\/.*|www\\.xkcd\\.com\\/.*|imgs\\.xkcd\\.com\\/.*|www\\.asofterworld\\.com\\/index\\.php\\?id=.*|www\\.asofterworld\\.com\\/.*\\.jpg|asofterworld\\.com\\/.*\\.jpg|www\\.qwantz\\.com\\/index\\.php\\?comic=.*|23hq\\.com\\/.*\\/photo\\/.*|www\\.23hq\\.com\\/.*\\/photo\\/.*|.*dribbble\\.com\\/shots\\/.*|drbl\\.in\\/.*|.*\\.smugmug\\.com\\/.*|.*\\.smugmug\\.com\\/.*#.*|picasaweb\\.google\\.com.*\\/.*\\/.*#.*|picasaweb\\.google\\.com.*\\/lh\\/photo\\/.*|picasaweb\\.google\\.com.*\\/.*\\/.*|img\\.ly\\/.*|www\\.tinypic\\.com\\/view\\.php.*|tinypic\\.com\\/view\\.php.*|www\\.tinypic\\.com\\/player\\.php.*|tinypic\\.com\\/player\\.php.*|www\\.tinypic\\.com\\/r\\/.*\\/.*|tinypic\\.com\\/r\\/.*\\/.*|.*\\.tinypic\\.com\\/.*\\.jpg|.*\\.tinypic\\.com\\/.*\\.png|meadd\\.com\\/.*\\/.*|meadd\\.com\\/.*|.*\\.deviantart\\.com\\/art\\/.*|.*\\.deviantart\\.com\\/gallery\\/.*|.*\\.deviantart\\.com\\/#\\/.*|fav\\.me\\/.*|.*\\.deviantart\\.com|.*\\.deviantart\\.com\\/gallery|.*\\.deviantart\\.com\\/.*\\/.*\\.jpg|.*\\.deviantart\\.com\\/.*\\/.*\\.gif|.*\\.deviantart\\.net\\/.*\\/.*\\.jpg|.*\\.deviantart\\.net\\/.*\\/.*\\.gif|www\\.fotopedia\\.com\\/.*\\/.*|fotopedia\\.com\\/.*\\/.*|photozou\\.jp\\/photo\\/show\\/.*\\/.*|photozou\\.jp\\/photo\\/photo_only\\/.*\\/.*|instagr\\.am\\/p\\/.*|instagram\\.com\\/p\\/.*|skitch\\.com\\/.*\\/.*\\/.*|img\\.skitch\\.com\\/.*|www\\.questionablecontent\\.net\\/|questionablecontent\\.net\\/|www\\.questionablecontent\\.net\\/view\\.php.*|questionablecontent\\.net\\/view\\.php.*|questionablecontent\\.net\\/comics\\/.*\\.png|www\\.questionablecontent\\.net\\/comics\\/.*\\.png|twitrpix\\.com\\/.*|.*\\.twitrpix\\.com\\/.*|www\\.someecards\\.com\\/.*\\/.*|someecards\\.com\\/.*\\/.*|some\\.ly\\/.*|www\\.some\\.ly\\/.*|pikchur\\.com\\/.*|achewood\\.com\\/.*|www\\.achewood\\.com\\/.*|achewood\\.com\\/index\\.php.*|www\\.achewood\\.com\\/index\\.php.*|www\\.whosay\\.com\\/.*\\/content\\/.*|www\\.whosay\\.com\\/.*\\/photos\\/.*|www\\.whosay\\.com\\/.*\\/videos\\/.*|say\\.ly\\/.*|ow\\.ly\\/i\\/.*|mlkshk\\.com\\/p\\/.*|lockerz\\.com\\/s\\/.*|pics\\.lockerz\\.com\\/s\\/.*|d\\.pr\\/i\\/.*|www\\.eyeem\\.com\\/p\\/.*|www\\.eyeem\\.com\\/a\\/.*|www\\.eyeem\\.com\\/u\\/.*|giphy\\.com\\/gifs\\/.*|gph\\.is\\/.*|frontback\\.me\\/p\\/.*|www\\.fotokritik\\.com\\/.*\\/.*|fotokritik\\.com\\/.*\\/.*|vid\\.me\\/.*|galeri\\.uludagsozluk\\.com\\/.*|gfycat\\.com\\/.*|tochka\\.net\\/.*|.*\\.tochka\\.net\\/.*|4cook\\.net\\/recipe\\/.*|.*youtube\\.com\\/watch.*|.*\\.youtube\\.com\\/v\\/.*|youtu\\.be\\/.*|.*\\.youtube\\.com\\/user\\/.*|.*\\.youtube\\.com\\/.*#.*\\/.*|m\\.youtube\\.com\\/watch.*|m\\.youtube\\.com\\/index.*|.*\\.youtube\\.com\\/profile.*|.*\\.youtube\\.com\\/view_play_list.*|.*\\.youtube\\.com\\/playlist.*|www\\.youtube\\.com\\/embed\\/.*|youtube\\.com\\/gif.*|www\\.youtube\\.com\\/gif.*|.*twitch\\.tv\\/.*|.*twitch\\.tv\\/.*\\/b\\/.*|www\\.ustream\\.tv\\/recorded\\/.*|www\\.ustream\\.tv\\/channel\\/.*|www\\.ustream\\.tv\\/.*|ustre\\.am\\/.*|qik\\.com\\/video\\/.*|qik\\.com\\/.*|qik\\.ly\\/.*|.*revision3\\.com\\/.*|.*\\.dailymotion\\.com\\/video\\/.*|.*\\.dailymotion\\.com\\/.*\\/video\\/.*|collegehumor\\.com\\/video:.*|collegehumor\\.com\\/video\\/.*|www\\.collegehumor\\.com\\/video:.*|www\\.collegehumor\\.com\\/video\\/.*|telly\\.com\\/.*|www\\.telly\\.com\\/.*|break\\.com\\/.*\\/.*|www\\.break\\.com\\/.*\\/.*|vids\\.myspace\\.com\\/index\\.cfm\\?fuseaction=vids\\.individual&videoid.*|www\\.myspace\\.com\\/index\\.cfm\\?fuseaction=.*&videoid.*|www\\.metacafe\\.com\\/watch\\/.*|www\\.metacafe\\.com\\/w\\/.*|blip\\.tv\\/.*\\/.*|.*\\.blip\\.tv\\/.*\\/.*|video\\.google\\.com\\/videoplay\\?.*|.*viddler\\.com\\/v\\/.*|liveleak\\.com\\/view\\?.*|www\\.liveleak\\.com\\/view\\?.*|animoto\\.com\\/play\\/.*|video214\\.com\\/play\\/.*|dotsub\\.com\\/view\\/.*|www\\.overstream\\.net\\/view\\.php\\?oid=.*|www\\.livestream\\.com\\/.*|new\\.livestream\\.com\\/.*|www\\.worldstarhiphop\\.com\\/videos\\/video.*\\.php\\?v=.*|worldstarhiphop\\.com\\/videos\\/video.*\\.php\\?v=.*|bambuser\\.com\\/v\\/.*|bambuser\\.com\\/channel\\/.*|bambuser\\.com\\/channel\\/.*\\/broadcast\\/.*|www\\.schooltube\\.com\\/video\\/.*\\/.*|bigthink\\.com\\/ideas\\/.*|bigthink\\.com\\/series\\/.*|sendables\\.jibjab\\.com\\/view\\/.*|sendables\\.jibjab\\.com\\/originals\\/.*|jibjab\\.com\\/view\\/.*|www\\.xtranormal\\.com\\/watch\\/.*|socialcam\\.com\\/v\\/.*|www\\.socialcam\\.com\\/v\\/.*|v\\.youku\\.com\\/v_show\\/.*|v\\.youku\\.com\\/v_playlist\\/.*|www\\.snotr\\.com\\/video\\/.*|snotr\\.com\\/video\\/.*|www\\.clipfish\\.de\\/.*\\/.*\\/video\\/.*|www\\.myvideo\\.de\\/watch\\/.*|www\\.vzaar\\.com\\/videos\\/.*|vzaar\\.com\\/videos\\/.*|www\\.vzaar\\.tv\\/.*|vzaar\\.tv\\/.*|vzaar\\.me\\/.*|.*\\.vzaar\\.me\\/.*|coub\\.com\\/view\\/.*|coub\\.com\\/embed\\/.*|www\\.streamio\\.com\\/api\\/v1\\/.*|streamio\\.com\\/api\\/v1\\/.*|vine\\.co\\/v\\/.*|www\\.vine\\.co\\/v\\/.*|www\\.viddy\\.com\\/video\\/.*|www\\.viddy\\.com\\/.*\\/v\\/.*|www\\.tudou\\.com\\/programs\\/view\\/.*|tudou\\.com\\/programs\\/view\\/.*|sproutvideo\\.com\\/videos\\/.*|embed\\.minoto-video\\.com\\/.*|iframe\\.minoto-video\\.com\\/.*|play\\.minoto-video\\.com\\/.*|dashboard\\.minoto-video\\.com\\/main\\/video\\/details\\/.*|api\\.minoto-video\\.com\\/publishers\\/.*\\/videos\\/.*|www\\.brainshark\\.com\\/.*\\/.*|brainshark\\.com\\/.*\\/.*|23video\\.com\\/.*|.*\\.23video\\.com\\/.*|goanimate\\.com\\/videos\\/.*|brainsonic\\.com\\/.*|.*\\.brainsonic\\.com\\/.*|lustich\\.de\\/videos\\/.*|web\\.tv\\/.*|.*\\.web\\.tv\\/.*|mynet\\.com\\/video\\/.*|www\\.mynet\\.com\\/video\\/|izlesene\\.com\\/video\\/.*|www\\.izlesene\\.com\\/video\\/|alkislarlayasiyorum\\.com\\/.*|www\\.alkislarlayasiyorum\\.com\\/.*|59saniye\\.com\\/.*|www\\.59saniye\\.com\\/.*|zie\\.nl\\/video\\/.*|www\\.zie\\.nl\\/video\\/.*|app\\.ustudio\\.com\\/embed\\/.*\\/.*|bale\\.io\\/.*|www\\.allego\\.com\\/.*|clipter\\.com\\/c\\/.*|sendvid\\.com\\/.*|www\\.snappytv\\.com\\/.*|snappytv\\.com\\/.*|frankly\\.me\\/.*|streamable\\.com\\/.*|ticker\\.tv\\/v\\/.*|videobio\\.com\\/playerjs\\/.*|clippituser\\.tv\\/.*|www\\.clippituser\\.tv\\/.*|www\\.whitehouse\\.gov\\/photos-and-video\\/video\\/.*|www\\.whitehouse\\.gov\\/video\\/.*|wh\\.gov\\/photos-and-video\\/video\\/.*|wh\\.gov\\/video\\/.*|www\\.hulu\\.com\\/watch.*|www\\.hulu\\.com\\/w\\/.*|www\\.hulu\\.com\\/embed\\/.*|hulu\\.com\\/watch.*|hulu\\.com\\/w\\/.*|.*crackle\\.com\\/c\\/.*|www\\.funnyordie\\.com\\/videos\\/.*|www\\.funnyordie\\.com\\/m\\/.*|funnyordie\\.com\\/videos\\/.*|funnyordie\\.com\\/m\\/.*|www\\.vimeo\\.com\\/groups\\/.*\\/videos\\/.*|www\\.vimeo\\.com\\/.*|vimeo\\.com\\/groups\\/.*\\/videos\\/.*|vimeo\\.com\\/.*|vimeo\\.com\\/m\\/#\\/.*|player\\.vimeo\\.com\\/.*|www\\.ted\\.com\\/talks\\/.*\\.html.*|www\\.ted\\.com\\/talks\\/lang\\/.*\\/.*\\.html.*|www\\.ted\\.com\\/index\\.php\\/talks\\/.*\\.html.*|www\\.ted\\.com\\/index\\.php\\/talks\\/lang\\/.*\\/.*\\.html.*|www\\.ted\\.com\\/talks\\/|.*nfb\\.ca\\/film\\/.*|thedailyshow\\.cc\\.com\\/videos\\/.*|www\\.thedailyshow\\.com\\/watch\\/.*|www\\.thedailyshow\\.com\\/full-episodes\\/.*|www\\.thedailyshow\\.com\\/collection\\/.*\\/.*\\/.*|yahoo\\.com\\/movies\\/.*|.*\\.yahoo\\.com\\/movies\\/.*|thecolbertreport\\.cc\\.com\\/videos\\/.*|www\\.colbertnation\\.com\\/the-colbert-report-collections\\/.*|www\\.colbertnation\\.com\\/full-episodes\\/.*|www\\.colbertnation\\.com\\/the-colbert-report-videos\\/.*|www\\.comedycentral\\.com\\/videos\\/index\\.jhtml\\?.*|www\\.theonion\\.com\\/video\\/.*|theonion\\.com\\/video\\/.*|wordpress\\.tv\\/.*\\/.*\\/.*\\/.*\\/|www\\.traileraddict\\.com\\/trailer\\/.*|www\\.traileraddict\\.com\\/clip\\/.*|www\\.traileraddict\\.com\\/poster\\/.*|www\\.trailerspy\\.com\\/trailer\\/.*\\/.*|www\\.trailerspy\\.com\\/trailer\\/.*|www\\.trailerspy\\.com\\/view_video\\.php.*|fora\\.tv\\/.*\\/.*\\/.*\\/.*|www\\.spike\\.com\\/video\\/.*|www\\.gametrailers\\.com\\/video.*|gametrailers\\.com\\/video.*|www\\.koldcast\\.tv\\/video\\/.*|www\\.koldcast\\.tv\\/#video:.*|mixergy\\.com\\/.*|video\\.pbs\\.org\\/video\\/.*|www\\.zapiks\\.com\\/.*|www\\.trutv\\.com\\/video\\/.*|www\\.nzonscreen\\.com\\/title\\/.*|nzonscreen\\.com\\/title\\/.*|app\\.wistia\\.com\\/embed\\/medias\\/.*|wistia\\.com\\/.*|.*\\.wistia\\.com\\/.*|.*\\.wi\\.st\\/.*|wi\\.st\\/.*|confreaks\\.net\\/videos\\/.*|www\\.confreaks\\.net\\/videos\\/.*|confreaks\\.com\\/videos\\/.*|www\\.confreaks\\.com\\/videos\\/.*|video\\.allthingsd\\.com\\/video\\/.*|videos\\.nymag\\.com\\/.*|aniboom\\.com\\/animation-video\\/.*|www\\.aniboom\\.com\\/animation-video\\/.*|grindtv\\.com\\/.*\\/video\\/.*|www\\.grindtv\\.com\\/.*\\/video\\/.*|ifood\\.tv\\/recipe\\/.*|ifood\\.tv\\/video\\/.*|ifood\\.tv\\/channel\\/user\\/.*|www\\.ifood\\.tv\\/recipe\\/.*|www\\.ifood\\.tv\\/video\\/.*|www\\.ifood\\.tv\\/channel\\/user\\/.*|logotv\\.com\\/video\\/.*|www\\.logotv\\.com\\/video\\/.*|lonelyplanet\\.com\\/Clip\\.aspx\\?.*|www\\.lonelyplanet\\.com\\/Clip\\.aspx\\?.*|streetfire\\.net\\/video\\/.*\\.htm.*|www\\.streetfire\\.net\\/video\\/.*\\.htm.*|sciencestage\\.com\\/v\\/.*\\.html|sciencestage\\.com\\/a\\/.*\\.html|www\\.sciencestage\\.com\\/v\\/.*\\.html|www\\.sciencestage\\.com\\/a\\/.*\\.html|link\\.brightcove\\.com\\/services\\/player\\/bcpid.*|bcove\\.me\\/.*|wirewax\\.com\\/.*|www\\.wirewax\\.com\\/.*|canalplus\\.fr\\/.*|www\\.canalplus\\.fr\\/.*|www\\.vevo\\.com\\/watch\\/.*|www\\.vevo\\.com\\/video\\/.*|pixorial\\.com\\/watch\\/.*|www\\.pixorial\\.com\\/watch\\/.*|spreecast\\.com\\/events\\/.*|www\\.spreecast\\.com\\/events\\/.*|showme\\.com\\/sh\\/.*|www\\.showme\\.com\\/sh\\/.*|.*\\.looplogic\\.com\\/.*|on\\.aol\\.com\\/video\\/.*|on\\.aol\\.com\\/playlist\\/.*|videodetective\\.com\\/.*\\/.*|www\\.videodetective\\.com\\/.*\\/.*|khanacademy\\.org\\/.*|www\\.khanacademy\\.org\\/.*|.*vidyard\\.com\\/.*|www\\.veoh\\.com\\/watch\\/.*|veoh\\.com\\/watch\\/.*|.*\\.univision\\.com\\/.*\\/video\\/.*|.*\\.vidcaster\\.com\\/.*|muzu\\.tv\\/.*|www\\.muzu\\.tv\\/.*|vube\\.com\\/.*\\/.*|www\\.vube\\.com\\/.*\\/.*|.*boxofficebuz\\.com\\/video\\/.*|www\\.godtube\\.com\\/featured\\/video\\/.*|godtube\\.com\\/featured\\/video\\/.*|www\\.godtube\\.com\\/watch\\/.*|godtube\\.com\\/watch\\/.*|mediamatters\\.org\\/mmtv\\/.*|www\\.clikthrough\\.com\\/theater\\/video\\/.*|www\\.clipsyndicate\\.com\\/video\\/playlist\\/.*\\/.*|www\\.srf\\.ch\\/play\\/.*\\/.*\\/.*\\/.*\\?id=.*|www\\.mpora\\.com\\/videos\\/.*|mpora\\.com\\/videos\\/.*|vice\\.com\\/.*|www\\.vice\\.com\\/.*|videodonor\\.com\\/video\\/.*|api\\.lovelive\\.tv\\/v1\\/.*|www\\.hurriyettv\\.com\\/.*|www\\.hurriyettv\\.com\\/.*|video\\.uludagsozluk\\.com\\/.*|www\\.ign\\.com\\/videos\\/.*|ign\\.com\\/videos\\/.*|www\\.askmen\\.com\\/video\\/.*|askmen\\.com\\/video\\/.*|video\\.esri\\.com\\/.*|www\\.zapkolik\\.com\\/video\\/.*|.*\\.iplayerhd\\.com\\/playerframe\\/.*|.*\\.iplayerhd\\.com\\/player\\/video\\/.*|plays\\.tv\\/video\\/.*|espn\\.go\\.com\\/video\\/clip.*|espn\\.go\\.com\\/.*\\/story.*|abcnews\\.com\\/.*\\/video\\/.*|abcnews\\.com\\/video\\/playerIndex.*|abcnews\\.go\\.com\\/.*\\/video\\/.*|abcnews\\.go\\.com\\/video\\/playerIndex.*|washingtonpost\\.com\\/wp-dyn\\/.*\\/video\\/.*\\/.*\\/.*\\/.*|www\\.washingtonpost\\.com\\/wp-dyn\\/.*\\/video\\/.*\\/.*\\/.*\\/.*|www\\.boston\\.com\\/video.*|boston\\.com\\/video.*|www\\.boston\\.com\\/.*video.*|boston\\.com\\/.*video.*|www\\.facebook\\.com\\/photo\\.php.*|www\\.facebook\\.com\\/video\\.php.*|www\\.facebook\\.com\\/.*\\/posts\\/.*|fb\\.me\\/.*|www\\.facebook\\.com\\/.*\\/photos\\/.*|www\\.facebook\\.com\\/.*\\/videos\\/.*|cnbc\\.com\\/id\\/.*\\?.*video.*|www\\.cnbc\\.com\\/id\\/.*\\?.*video.*|cnbc\\.com\\/id\\/.*\\/play\\/1\\/video\\/.*|www\\.cnbc\\.com\\/id\\/.*\\/play\\/1\\/video\\/.*|cbsnews\\.com\\/video\\/watch\\/.*|plus\\.google\\.com\\/.*|www\\.google\\.com\\/profiles\\/.*|google\\.com\\/profiles\\/.*|www\\.cnn\\.com\\/video\\/.*|edition\\.cnn\\.com\\/video\\/.*|money\\.cnn\\.com\\/video\\/.*|today\\.msnbc\\.msn\\.com\\/id\\/.*\\/vp\\/.*|www\\.msnbc\\.msn\\.com\\/id\\/.*\\/vp\\/.*|www\\.msnbc\\.msn\\.com\\/id\\/.*\\/ns\\/.*|today\\.msnbc\\.msn\\.com\\/id\\/.*\\/ns\\/.*|msnbc\\.msn\\.com\\/.*\\/watch\\/.*|www\\.msnbc\\.msn\\.com\\/.*\\/watch\\/.*|nbcnews\\.com\\/.*|www\\.nbcnews\\.com\\/.*|multimedia\\.foxsports\\.com\\/m\\/video\\/.*\\/.*|msn\\.foxsports\\.com\\/video.*|www\\.globalpost\\.com\\/video\\/.*|www\\.globalpost\\.com\\/dispatch\\/.*|theguardian\\.com\\/.*\\/video\\/.*\\/.*\\/.*\\/.*|www\\.theguardian\\.com\\/.*\\/video\\/.*\\/.*\\/.*\\/.*|guardian\\.co\\.uk\\/.*\\/video\\/.*\\/.*\\/.*\\/.*|www\\.guardian\\.co\\.uk\\/.*\\/video\\/.*\\/.*\\/.*\\/.*|bravotv\\.com\\/.*\\/.*\\/videos\\/.*|www\\.bravotv\\.com\\/.*\\/.*\\/videos\\/.*|dsc\\.discovery\\.com\\/videos\\/.*|animal\\.discovery\\.com\\/videos\\/.*|health\\.discovery\\.com\\/videos\\/.*|investigation\\.discovery\\.com\\/videos\\/.*|military\\.discovery\\.com\\/videos\\/.*|planetgreen\\.discovery\\.com\\/videos\\/.*|science\\.discovery\\.com\\/videos\\/.*|tlc\\.discovery\\.com\\/videos\\/.*|video\\.forbes\\.com\\/fvn\\/.*|distrify\\.com\\/film\\/.*|muvi\\.es\\/.*|video\\.foxnews\\.com\\/v\\/.*|video\\.foxbusiness\\.com\\/v\\/.*|www\\.reuters\\.com\\/video\\/.*|reuters\\.com\\/video\\/.*|live\\.huffingtonpost\\.com\\/r\\/segment\\/.*\\/.*|video\\.nytimes\\.com\\/video\\/.*|www\\.nytimes\\.com\\/video\\/.*\\/.*|nyti\\.ms\\/.*|www\\.vol\\.at\\/video\\/.*|vol\\.at\\/video\\/.*|www\\.spiegel\\.de\\/video\\/.*|spiegel\\.de\\/video\\/.*|www\\.zeit\\.de\\/video\\/.*|zeit\\.de\\/video\\/.*|www\\.rts\\.ch\\/play\\/tv\\/.*|soundcloud\\.com\\/.*|soundcloud\\.com\\/.*\\/.*|soundcloud\\.com\\/.*\\/sets\\/.*|soundcloud\\.com\\/groups\\/.*|snd\\.sc\\/.*|open\\.spotify\\.com\\/.*|spoti\\.fi\\/.*|play\\.spotify\\.com\\/.*|www\\.last\\.fm\\/music\\/.*|www\\.last\\.fm\\/music\\/+videos\\/.*|www\\.last\\.fm\\/music\\/+images\\/.*|www\\.last\\.fm\\/music\\/.*\\/_\\/.*|www\\.last\\.fm\\/music\\/.*\\/.*|www\\.mixcloud\\.com\\/.*\\/.*\\/|www\\.radionomy\\.com\\/.*\\/radio\\/.*|radionomy\\.com\\/.*\\/radio\\/.*|www\\.hark\\.com\\/clips\\/.*|www\\.rdio\\.com\\/#\\/artist\\/.*\\/album\\/.*|www\\.rdio\\.com\\/artist\\/.*\\/album\\/.*|www\\.zero-inch\\.com\\/.*|.*\\.bandcamp\\.com\\/|.*\\.bandcamp\\.com\\/track\\/.*|.*\\.bandcamp\\.com\\/album\\/.*|freemusicarchive\\.org\\/music\\/.*|www\\.freemusicarchive\\.org\\/music\\/.*|freemusicarchive\\.org\\/curator\\/.*|www\\.freemusicarchive\\.org\\/curator\\/.*|www\\.npr\\.org\\/.*\\/.*\\/.*\\/.*\\/.*|www\\.npr\\.org\\/.*\\/.*\\/.*\\/.*\\/.*\\/.*|www\\.npr\\.org\\/.*\\/.*\\/.*\\/.*\\/.*\\/.*\\/.*|www\\.npr\\.org\\/templates\\/story\\/story\\.php.*|huffduffer\\.com\\/.*\\/.*|www\\.audioboom\\.com\\/boos\\/.*|audioboom\\.com\\/boos\\/.*|boo\\.fm\\/b.*|www\\.xiami\\.com\\/song\\/.*|xiami\\.com\\/song\\/.*|www\\.saynow\\.com\\/playMsg\\.html.*|www\\.saynow\\.com\\/playMsg\\.html.*|grooveshark\\.com\\/.*|radioreddit\\.com\\/songs.*|www\\.radioreddit\\.com\\/songs.*|radioreddit\\.com\\/\\?q=songs.*|www\\.radioreddit\\.com\\/\\?q=songs.*|www\\.gogoyoko\\.com\\/song\\/.*|hypem\\.com\\/premiere\\/.*|bop\\.fm\\/s\\/.*\\/.*|clyp\\.it\\/.*|www\\.dnbradio\\.com\\/.*|dnbradio\\.com\\/.*))|(https:\\/\\/(picasaweb\\.google\\.com.*\\/.*\\/.*#.*|picasaweb\\.google\\.com.*\\/lh\\/photo\\/.*|picasaweb\\.google\\.com.*\\/.*\\/.*|skitch\\.com\\/.*\\/.*\\/.*|img\\.skitch\\.com\\/.*|vidd\\.me\\/.*|vid\\.me\\/.*|gfycat\\.com\\/.*|.*youtube\\.com\\/watch.*|.*\\.youtube\\.com\\/v\\/.*|youtu\\.be\\/.*|.*\\.youtube\\.com\\/playlist.*|www\\.youtube\\.com\\/embed\\/.*|youtube\\.com\\/gif.*|www\\.youtube\\.com\\/gif.*|screen\\.yahoo\\.com\\/.*\\/.*|animoto\\.com\\/play\\/.*|video214\\.com\\/play\\/.*|www\\.streamio\\.com\\/api\\/v1\\/.*|streamio\\.com\\/api\\/v1\\/.*|vine\\.co\\/v\\/.*|www\\.vine\\.co\\/v\\/.*|mixbit\\.com\\/v\\/.*|www\\.brainshark\\.com\\/.*\\/.*|brainshark\\.com\\/.*\\/.*|23video\\.com\\/.*|.*\\.23video\\.com\\/.*|brainsonic\\.com\\/.*|.*\\.brainsonic\\.com\\/.*|www\\.reelhouse\\.org\\/.*|reelhouse\\.org\\/.*|www\\.allego\\.com\\/.*|clipter\\.com\\/c\\/.*|app\\.devhv\\.com\\/oembed\\/.*|sendvid\\.com\\/.*|clipmine\\.com\\/video\\/.*|clipmine\\.com\\/embed\\/.*|clippituser\\.tv\\/.*|www\\.clippituser\\.tv\\/.*|www\\.vimeo\\.com\\/.*|vimeo\\.com\\/.*|player\\.vimeo\\.com\\/.*|yahoo\\.com\\/movies\\/.*|.*\\.yahoo\\.com\\/movies\\/.*|app\\.wistia\\.com\\/embed\\/medias\\/.*|wistia\\.com\\/.*|.*\\.wistia\\.com\\/.*|.*\\.wi\\.st\\/.*|wi\\.st\\/.*|.*\\.looplogic\\.com\\/.*|khanacademy\\.org\\/.*|www\\.khanacademy\\.org\\/.*|.*vidyard\\.com\\/.*|.*\\.stream\\.co\\.jp\\/apiservice\\/.*|.*\\.stream\\.ne\\.jp\\/apiservice\\/.*|api\\.lovelive\\.tv\\/v1\\/.*|video\\.esri\\.com\\/.*|mix\\.office\\.com\\/watch\\/.*|mix\\.office\\.com\\/mix\\/.*|mix\\.office\\.com\\/embed\\/.*|mix\\.office\\.com\\/MyMixes\\/Details\\/.*|.*\\.iplayerhd\\.com\\/playerframe\\/.*|.*\\.iplayerhd\\.com\\/player\\/video\\/.*|plays\\.tv\\/video\\/.*|www\\.facebook\\.com\\/photo\\.php.*|www\\.facebook\\.com\\/video\\.php.*|www\\.facebook\\.com\\/.*\\/posts\\/.*|fb\\.me\\/.*|www\\.facebook\\.com\\/.*\\/photos\\/.*|www\\.facebook\\.com\\/.*\\/videos\\/.*|plus\\.google\\.com\\/.*|soundcloud\\.com\\/.*|soundcloud\\.com\\/.*\\/.*|soundcloud\\.com\\/.*\\/sets\\/.*|soundcloud\\.com\\/groups\\/.*|open\\.spotify\\.com\\/.*|play\\.spotify\\.com\\/.*|bop\\.fm\\/s\\/.*\\/.*|bop\\.fm\\/p\\/.*|bop\\.fm\\/a\\/.*|clyp\\.it\\/.*|sfx\\.io\\/.*)))/i', 'text', '', '', '', 81);


SET @iIdActionFeature = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='system' AND `Name`='feature' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionFeature;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionFeature;

SET @iIdActionViewView = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='system' AND `Name`='view_view' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionViewView;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionViewView;

SET @iIdActionViewViewViewers = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='system' AND `Name`='view_view_viewers' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionViewViewViewers;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionViewViewViewers;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'feature', NULL, '_sys_acl_action_feature', '', 0, 0);
SET @iIdActionFeature = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'view_view', NULL, '_sys_acl_action_view_view', '', 0, 0);
SET @iIdActionViewView = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'view_view_viewers', NULL, '_sys_acl_action_view_view_viewers', '', 0, 0);
SET @iIdActionViewViewViewers = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES

-- feature 
(@iModerator, @iIdActionFeature),
(@iAdministrator, @iIdActionFeature),

-- view view
(@iUnauthenticated, @iIdActionViewView),
(@iAccount, @iIdActionViewView),
(@iStandard, @iIdActionViewView),
(@iUnconfirmed, @iIdActionViewView),
(@iPending, @iIdActionViewView),
(@iModerator, @iIdActionViewView),
(@iAdministrator, @iIdActionViewView),
(@iPremium, @iIdActionViewView),

-- view view viewers
(@iModerator, @iIdActionViewViewViewers),
(@iAdministrator, @iIdActionViewViewViewers);



ALTER TABLE  `sys_files` CHANGE  `remote_id`  `remote_id` VARCHAR( 128 ) NOT NULL;

ALTER TABLE  `sys_images` CHANGE  `remote_id`  `remote_id` VARCHAR( 128 ) NOT NULL;

ALTER TABLE  `sys_images_custom` CHANGE  `remote_id`  `remote_id` VARCHAR( 128 ) NOT NULL;

ALTER TABLE  `sys_images_resized` CHANGE  `remote_id`  `remote_id` VARCHAR( 128 ) NOT NULL;

ALTER TABLE  `sys_cmts_images` CHANGE  `remote_id`  `remote_id` VARCHAR( 128 ) NOT NULL;

ALTER TABLE  `sys_cmts_images_preview` CHANGE  `remote_id`  `remote_id` VARCHAR( 128 ) NOT NULL;

ALTER TABLE  `sys_transcoder_queue_files` CHANGE  `remote_id`  `remote_id` VARCHAR( 128 ) NOT NULL;



SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'sys_settings_storage_change' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

INSERT INTO `sys_alerts_handlers` (`name`, `service_call`) VALUES 
('sys_settings_storage_change', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:37:"alert_response_process_storage_change";s:5:"class";s:13:"TemplServices";}');
SET @iHandler = LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler);



CREATE TABLE IF NOT EXISTS `sys_objects_feature` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  `is_on` tinyint(4) NOT NULL default '1',
  `is_undo` tinyint(4) NOT NULL default '1',
  `base_url` varchar(256) NOT NULL default '',
  `trigger_table` varchar(32) NOT NULL,
  `trigger_field_id` varchar(32) NOT NULL,
  `trigger_field_author` varchar(32) NOT NULL,
  `trigger_field_flag` varchar(32) NOT NULL,
  `class_name` varchar(32) NOT NULL default '',
  `class_file` varchar(256) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



DELETE FROM `sys_menu_templates` WHERE `id` IN(17, 18);

INSERT INTO `sys_menu_templates` (`id`, `template`, `title`, `visible`) VALUES
(17, 'menu_inline_sbtn.html', '_sys_menu_template_title_inline_sbtn', 1),
(18, 'menu_icon_buttons_hor.html', '_sys_menu_template_title_icon_buttons_hor', 1);



DELETE FROM `sys_objects_menu` WHERE `object` = 'sys_social_sharing';

INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_social_sharing', '_sys_menu_title_social_sharing', 'sys_social_sharing', 'system', 18, 0, 1, 'BxTemplMenuSocialSharing', '');

DELETE FROM `sys_menu_sets` WHERE `set_name` = 'sys_social_sharing';

INSERT INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('sys_social_sharing', 'system', '_sys_menu_set_title_sys_social_sharing', 0);

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_social_sharing';

INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_social_sharing', 'system', 'social-sharing-facebook', '_sys_menu_item_title_system_social_sharing_facebook', '_sys_menu_item_title_social_sharing_facebook', 'https://www.facebook.com/sharer/sharer.php?u={url_encoded}', '', '_blank', 'facebook', '', 2147483646, 1, 1, 1),
('sys_social_sharing', 'system', 'social-sharing-googleplus', '_sys_menu_item_title_system_social_sharing_googleplus', '_sys_menu_item_title_social_sharing_googleplus', 'https://plus.google.com/share?url={url_encoded}', '', '_blank', 'google-plus', '', 2147483646, 1, 1, 2),
('sys_social_sharing', 'system', 'social-sharing-twitter', '_sys_menu_item_title_system_social_sharing_twitter', '_sys_menu_item_title_social_sharing_twitter', 'https://twitter.com/share?url={url_encoded}', '', '_blank', 'twitter', '', 2147483646, 1, 1, 3),
('sys_social_sharing', 'system', 'social-sharing-pinterest', '_sys_menu_item_title_system_social_sharing_pinterest', '_sys_menu_item_title_social_sharing_pinterest', 'http://pinterest.com/pin/create/button/?url={url_encoded}&media={img_url_encoded}&description={title_encoded}', '', '_blank', 'pinterest', '', 2147483646, 1, 1, 4);



UPDATE `sys_objects_grid` SET `sorting_fields` = 'file_name,added' WHERE `object` IN('sys_studio_strg_files', 'sys_studio_strg_images');



-- last step is to update current version

UPDATE `sys_modules` SET `version` = '9.0.0-B4' WHERE (`version` = '9.0.0.B3' OR `version` = '9.0.0-B3') AND `name` = 'system';

