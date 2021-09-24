<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

use MatthiasMullie\Minify;

class BxDolMinify extends BxDolFactory implements iBxDolSingleton
{
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolMinify();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

	public function minifyJs($s)
	{
		$oMinifier = new Minify\JS();
    	$oMinifier->add($s);

    	return $oMinifier->minify();
	}

	public function minifyCss($s)
	{
		$oMinifier = new Minify\CSS();
    	$oMinifier->add($s);

    	return $oMinifier->minify();
	}
}

/** @} */
