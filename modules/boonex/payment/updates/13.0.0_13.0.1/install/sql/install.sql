SET @sName = 'bx_payment';


-- TABLES
UPDATE `bx_payment_providers` SET `for_single`='1' WHERE `name`='chargebee_v3';
