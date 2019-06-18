SET @sName = 'bx_payment';


-- PROVIDERS
UPDATE `bx_payment_providers_options` SET `check_type`='EmailOrEmpty', `check_error`='_sys_form_account_input_email_error' WHERE `name`='strp_cancellation_email';
