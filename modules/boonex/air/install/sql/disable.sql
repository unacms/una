SET @sName = 'bx_air';


-- PAGES & BLOCKS
DELETE FROM `sys_objects_page` WHERE `module` = @sName;
DELETE FROM `sys_pages_blocks` WHERE `module` = @sName;