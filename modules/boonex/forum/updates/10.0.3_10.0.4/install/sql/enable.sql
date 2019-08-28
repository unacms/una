SET @sName = 'bx_forum';


-- CATEGORY
UPDATE `sys_objects_category` SET `join`='INNER JOIN `sys_profiles` ON (`sys_profiles`.`id` = ABS(`bx_forum_discussions`.`author`))' WHERE `object`='bx_forum_cats';
