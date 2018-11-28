-- PAGES
UPDATE `sys_pages_blocks` set `content`='a:3:{s:6:"module";s:10:"bx_persons";s:6:"method";s:22:"browse_active_profiles";s:6:"params";a:2:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;}}' WHERE `object`='bx_persons_active' AND `title`='_bx_persons_page_block_title_active_profiles';


-- VOTES
UPDATE `sys_objects_vote` SET `TriggerFieldAuthor`='', `ClassName`='BxPersonsVote', `ClassFile`='modules/boonex/persons/classes/BxPersonsVote.php' WHERE `Name`='bx_persons';
