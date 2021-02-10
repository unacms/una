SET @sName = 'bx_timeline';


-- CONTENT PLACEHOLDERS
DELETE FROM `sys_pages_content_placeholders` WHERE `module`=@sName AND `title`='_bx_timeline_page_content_ph_outline';
SET @iCPHOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_pages_content_placeholders` ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_content_placeholders` (`module`, `title`, `template`, `order`) VALUES
('bx_timeline', '_bx_timeline_page_content_ph_outline', 'block_async_outline.html', @iCPHOrder + 1);


-- SETTINGS
DELETE FROM `sys_options` WHERE `name`='bx_timeline_enable_dynamic_cards';
