SET @sName = 'bx_payment';


-- GRIDS
UPDATE `sys_grid_actions` SET `icon`='pencil-alt' WHERE `object`='bx_payment_grid_commissions' AND `type`='single' AND `name`='edit';
UPDATE `sys_grid_actions` SET `icon`='pencil-alt' WHERE `object`='bx_payment_grid_invoices' AND `type`='single' AND `name`='edit';
