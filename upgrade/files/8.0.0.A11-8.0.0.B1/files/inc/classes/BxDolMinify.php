<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

use MatthiasMullie\Minify;

require_once(BX_DIRECTORY_PATH_PLUGINS . 'matthiasmullie/path-converter/Converter.php');
require_once(BX_DIRECTORY_PATH_PLUGINS . 'matthiasmullie/minify/Minify.php');
require_once(BX_DIRECTORY_PATH_PLUGINS . 'matthiasmullie/minify/CSS.php');
require_once(BX_DIRECTORY_PATH_PLUGINS . 'matthiasmullie/minify/JS.php');

class BxDolMinify extends BxDol implements iBxDolSingleton
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
