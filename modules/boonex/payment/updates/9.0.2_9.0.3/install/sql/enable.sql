SET @sName = 'bx_payment';

-- OPTIONS
UPDATE `sys_options` SET `extra`='a:2:{s:6:"module";s:10:"bx_payment";s:6:"method";s:33:"get_options_default_currency_code";}' WHERE `name`='bx_payment_default_currency_code';