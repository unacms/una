-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_shopify' WHERE `Name` IN ('bx_shopify', 'bx_shopify_reactions');


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_shopify' WHERE `name`='bx_shopify';
