
SET @iGlCategID = (SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'Blogs');

DELETE FROM `sys_options` WHERE `kateg` = @iGlCategID;

INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`) VALUES
('blogAutoApproval', 'on', @iGlCategID, 'Enable AutoApproval of Blogs', 'checkbox', '', '', 1),
('blog_step', '10', @iGlCategID, 'How many blogs showing on page', 'digit', '', '', 2),
('max_blogs_on_home', '3', @iGlCategID, 'Maximum number of Blogs to show on homepage', 'digit', '', '', 3),
('max_blog_preview', '256', @iGlCategID, 'Maximum length of Blog preview', 'digit', '', '', 4),
('bx_blogs_iconsize', '45', @iGlCategID, 'Size of post icons', 'digit', '', '', 5),
('bx_blogs_thumbsize', '110', @iGlCategID, 'Size of post thumbs', 'digit', '', '', 6),
('bx_blogs_bigthumbsize', '340', @iGlCategID, 'Size of post bit thumbs', 'digit', '', '', 7),
('bx_blogs_imagesize', '800', @iGlCategID, 'Size of post full images', 'digit', '', '', 8);

