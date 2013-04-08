
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `ClassName`, `ClassPath`)
VALUES ('bx_sites', '_bx_sites', 'BxSitesSearchResult', 'modules/boonex/sites/classes/BxSitesSearchResult.php');

UPDATE `sys_modules` SET `version` = '1.0.3' WHERE `uri` = 'sites' AND `version` = '1.0.2';

