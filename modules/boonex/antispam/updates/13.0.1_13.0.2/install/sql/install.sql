SET @sName = 'bx_antispam';


-- TABLES
UPDATE `bx_antispam_dnsbl_rules` SET `recheck`='https://surbl.org/surbl-analysis' WHERE `chain`='uridns' AND `zonedomain`='multi.surbl.org.';
