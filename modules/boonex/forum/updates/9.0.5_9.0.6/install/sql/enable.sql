SET @sName = 'bx_forum';


-- SETTINGS
UPDATE `sys_options` SET `value`='12' WHERE `name`='bx_forum_per_page_browse';
UPDATE `sys_options` SET `value`='6' WHERE `name`='bx_forum_per_page_index';
UPDATE `sys_options` SET `value`='6' WHERE `name`='bx_forum_per_page_profile';


-- METATAGS
UPDATE `sys_objects_metatags` SET `table_mentions`='bx_forum_meta_mentions' WHERE `object`=@sName;
