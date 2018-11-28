-- PAGES
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:22:"browse_active_profiles";s:6:"params";a:2:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;}}}' WHERE `object`='bx_organizations_active' AND `title`='_bx_orgs_page_block_title_active_profiles';


-- VOTES
UPDATE `sys_objects_vote` SET `TriggerFieldAuthor`='', `ClassName`='BxOrgsVote', `ClassFile`='modules/boonex/organizations/classes/BxOrgsVote.php' WHERE `Name`='bx_organizations';
