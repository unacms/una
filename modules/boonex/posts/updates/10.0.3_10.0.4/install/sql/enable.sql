SET @sName = 'bx_posts';


-- CATEGORY
UPDATE `sys_objects_category` SET `join`='INNER JOIN `sys_profiles` ON (`sys_profiles`.`id` = ABS(`bx_posts_posts`.`author`))' WHERE `object`='bx_posts_cats';
