    DELETE FROM 
        `sys_objects_actions` 
    WHERE
        `Caption` = '_Simle Messenger'
            AND 
        `Type` = 'Profile';

    DELETE FROM 
        `sys_injections`
    WHERE
        `name`  = 'bx_simple_messenger_core_init'
            AND
        `key`   = 'injection_header';

    DROP TABLE IF EXISTS `[db_prefix]messages`; 
    DROP TABLE IF EXISTS `[db_prefix]privacy`; 

    SET @iActionId := (SELECT `ID` FROM `sys_acl_actions` WHERE `Name` = 'use simple messenger' LIMIT 1);
    DELETE FROM `sys_acl_actions` WHERE `Name` = 'use simple messenger';
    DELETE FROM `sys_acl_matrix` WHERE `IDAction` = @iActionId;

    DELETE FROM  
        `sys_menu_member` 
    WHERE
        `Name`   = 'bx_simple_messenger'
            AND
        `Type`   = 'linked_item'
            AND
        `Parent` = 4;

    DELETE FROM `sys_privacy_actions` WHERE `module_uri` = 'simple_messenger' AND `name` = 'contact';

    --
    -- `sys_options_cats` ;
    --

    SET @iKategId = (SELECT `id` FROM `sys_options_cats` WHERE `name` = 'Simple messenger' LIMIT 1);
    DELETE FROM `sys_options_cats` WHERE `id` = @iKategId;

    --
    -- `sys_options` ;
    --

    DELETE FROM `sys_options` WHERE `kateg` = @iKategId;

    -- 
    -- `sys_menu_admin`;
    --

    DELETE FROM 
        `sys_menu_admin` 
    WHERE
        `name` = 'Simple messenger';

    SET @iHandlerId = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_simple_messenger'  LIMIT 1);

    DELETE FROM
        `sys_alerts_handlers`
    WHERE
        `id` = @iHandlerId;

    DELETE FROM `sys_alerts` WHERE `handler_id` =  @iHandlerId ;

    DELETE FROM 
        `sys_permalinks` 
    WHERE
        `standard`  = 'modules/?r=simple_messenger/';

    DELETE FROM 
        `sys_options` 
    WHERE
        `Name` = 'bx_simple_messenger_permalinks';