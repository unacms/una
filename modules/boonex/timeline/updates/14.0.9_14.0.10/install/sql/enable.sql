SET @sName = 'bx_timeline';


-- MENUS
UPDATE `sys_menu_items` SET `link`='page.php?i=timeline-view&profile_id={member_id}' WHERE `set_name`='sys_add_content_links' AND `module`=@sName AND `name`='create-item';
