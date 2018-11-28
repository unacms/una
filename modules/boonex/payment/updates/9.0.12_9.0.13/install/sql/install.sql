SET @sName = 'bx_payment';


-- GRIDS
UPDATE `sys_grid_actions` SET `icon`='far credit-card' WHERE `object`='bx_payment_grid_carts' AND `name`='continue';
