<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

class BxDolStudioCart extends BxDol implements iBxDolSingleton
{
    public static $sIDiv = ':';
    public static $sPDiv = '_';
    private $sSessionKey = 'bx-std-str-cart';
    private $bAllowAccumulate = false;

    public function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct ();
    }

    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    public static function getInstance()
    {
        if (!isset($GLOBALS['bxDolClasses'][__CLASS__])) {
            $sClass = __CLASS__;
            $GLOBALS['bxDolClasses'][__CLASS__] = new $sClass();
        }

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    /**
     * Conver items to array with necessary structure.
     *
     * @param  string/array $mixed - string with cart items divided with (:) or an array of cart items.
     * @return array        with items.
     */
    public static function items2array($mixed)
    {
        $aResult = array();
        if(empty($mixed))
            return $aResult;

        $sClass = __CLASS__;
        if(is_string($mixed))
            $aItems = explode($sClass::$sIDiv, $mixed);
        else if(is_array($mixed))
            $aItems = $mixed;
        else
            $aItems = array();

        foreach($aItems as $sItem) {
            $aItem = explode($sClass::$sPDiv, $sItem);
            $aResult[] = array('vendor' => $aItem[0], 'item_id' => $aItem[1], 'item_count' => $aItem[2]);
        }

        return $aResult;
    }

	public function exists($sVendor, $iItemId)
	{
		$sDiv = BxDolStudioCart::$sPDiv;

		$sCartItems = $this->getItems();
		return strpos($sCartItems, $sVendor . $sDiv . $iItemId . $sDiv) !== false;
	}

    public function add($sVendor, $iItemId, $iItemCount)
    {
        $sDiv = BxDolStudioCart::$sPDiv;

        $sCartItem = $sVendor . $sDiv . $iItemId . $sDiv . $iItemCount;
        $sCartItems = $this->getItems();

        if(strpos($sCartItems, $sVendor . $sDiv . $iItemId . $sDiv) !== false) {
            if($this->bAllowAccumulate)
                $sCartItems = preg_replace("'" . $sVendor . $sDiv . $iItemId . $sDiv . "([0-9])+'e", "'" . $sVendor . $sDiv . $iItemId . $sDiv ."' . (\\1 + " . $iItemCount . ")",  $sCartItems);
        } else
            $sCartItems = empty($sCartItems) ? $sCartItem : $sCartItems . BxDolStudioCart::$sIDiv . $sCartItem;

        $this->setItems($sCartItems);
    }

    public function delete($sVendor, $iItemId = 0)
    {
        $sPattern = "'" . $sVendor . (!empty($iItemId) ? "_" . $iItemId : "_[0-9]+") . "_[0-9]+:?'";

        $sCartItems = $this->getItems();
        $sCartItems = trim(preg_replace($sPattern, "", $sCartItems), BxDolStudioCart::$sIDiv);
        $this->setItems($sCartItems);
    }

    public function getCount($sVendor = '')
    {
        if($sVendor == '') {
            $sItems = $this->getItems();
            $aItems = $this->items2array($sItems);
            return count($aItems);
        }

        $aVendors = $this->parseByVendor();
        if(!isset($aVendors[$sVendor]))
            return 0;

        return count($aVendors[$sVendor]);
    }

    public function getByVendor($sVendor)
    {
        $aVendors = $this->parseByVendor();
        if(!isset($aVendors[$sVendor]) || empty($aVendors[$sVendor]))
            return array();

        return $aVendors[$sVendor];
    }

    public function parseByVendor()
    {
        $sItems = $this->getItems();
        return $this->parseBy($this->items2array($sItems), 'vendor');
    }

    protected function getItems()
    {
        return BxDolSession::getInstance()->getValue($this->sSessionKey);
    }

    protected function setItems($sItems)
    {
        BxDolSession::getInstance()->setValue($this->sSessionKey, $sItems);
    }

    protected function parseBy($aItems, $sKey)
    {
        $aResult = array();
        foreach($aItems as $aItem)
            if(isset($aItem[$sKey]))
                $aResult[$aItem[$sKey]][] = $aItem;

        return $aResult;
    }
}

/** @} */
