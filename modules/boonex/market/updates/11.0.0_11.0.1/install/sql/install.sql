-- FORMS
UPDATE `sys_form_inputs` SET `values`='', `type`='text' WHERE `object`='bx_market' AND `name`='price_recurring';


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `key`='bx_market_prices';

DELETE FROM `sys_form_pre_values` WHERE `Key`='bx_market_prices';


-- VOTES
UPDATE `sys_objects_vote` SET `ClassName` = 'BxMarketVoteReactions', `ClassFile` = 'modules/boonex/market/classes/BxMarketVoteReactions.php' WHERE `Name`='bx_market_reactions';
