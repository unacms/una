<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Payment Payment
 * @ingroup     TridentModules
 *
 * @{
 */

class BxPaymentConfig extends BxBaseModPaymentConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_aUrls = array(
        	'join' => 'page.php?i=payment-join',
        	'cart' => 'page.php?i=payment-cart',
        	'history' => 'page.php?i=payment-history',
        	'orders' => 'page.php?i=payment-orders',
        	'details' => 'page.php?i=payment-details',

        	'return' => 'page.php?i=payment-cart',
        	'return_data' => BX_DOL_URL_ROOT . $this->getBaseUri() . 'finalize_checkout/'
        );

        $this->_aHtmlIds = array(
        	'history' => array(
        		'order_history_view' => $this->_sName . '-order-view-history',
        	),
        	'pending' => array(
        		'order_pending_view' => $this->_sName . '-order-view-pending',
        		'order_pending_process' => $this->_sName . '-order-process',
        	),
        	'processed' => array(
        		'order_processed_view' => $this->_sName . '-order-view-processed',
        		'order_processed_add' => $this->_sName . '-order-add',
        		'order_processed_client_id' => $this->_sName . '-oa-client-id',
        		'order_processed_client' => $this->_sName . '-oa-client',
        		'order_processed_items' => $this->_sName . '-oa-items',	
        	),
        );

        $this->_aPerPage = array(
        	'orders' => 10,
        	'history' => 10
        );
        $this->_aHandlers = array();

        $this->_aJsClasses = array(
        	'cart' => 'BxPaymentCart',
        	'history' => 'BxPaymentOrders',
        	'pending' => 'BxPaymentOrders',
        	'processed' => 'BxPaymentOrders',
        );

        $this->_aJsObjects = array(
        	'cart' => 'oPaymentCart',
        	'history' => 'oPaymentOrders',
        	'pending' => 'oPaymentOrders',
        	'processed' => 'oPaymentOrders',
        );
    }

	public function getLicense()
    {
        list($fMilliSec, $iSec) = explode(' ', microtime());
        $fSeed = (float)$iSec + ((float)$fMilliSec * 100000);
        srand($fSeed);

        $sResult = '';
        for($i=0; $i < 16; ++$i) {
            switch(rand(1,2)) {
                case 1:
                    $c = chr(rand(ord('A'),ord('Z')));
                    break;
                case 2:
                    $c = chr(rand(ord('0'),ord('9')));
                    break;
            }
            $sResult .= $c;
        }

        return $sResult;
    }

    public function getModuleId($mixedId)
    {
    	if(is_int($mixedId))
    		return (int)$mixedId;

		if(is_string($mixedId)) {
            $aInfo = $this->_oDb->getModuleByName($mixedId);
            if(!is_array($aInfo) || empty($aInfo['id']))
            	$aInfo = $this->_oDb->getModuleByUri($mixedId);

            if(is_array($aInfo) && !empty($aInfo['id']))
            	return (int)$aInfo['id'];
        } 

        return 0;
    }

    public function descriptorA2S($a) 
    {
    	return implode($this->getDivider('descriptor'), $a);
    }

	public function descriptorS2A($s) 
    {
    	return explode($this->getDivider('descriptor'), $s);
    }

	/**
     * Conver items to array with necessary structure.
     *
     * @param  string/array $mixed - string with cart items divided with (:) or an array of cart items.
     * @return array        with items.
     */
    public function descriptorsM2A($mixed)
    {
        $aResult = array();

        if(is_string($mixed))
           $aItems = explode($this->getDivider('descriptors'), $mixed);
        else if(is_array($mixed))
           $aItems = $mixed;
        else
            $aItems = array();

        foreach($aItems as $sItem) {
            $aItem = $this->descriptorS2A($sItem);
            $aResult[] = array('vendor_id' => $aItem[0], 'module_id' => $aItem[1], 'item_id' => $aItem[2], 'item_count' => $aItem[3]);
        }

        return $aResult;
    }

	public function http2https($s)
    {
    	if(strncmp($s, 'https://', 8) == 0)
    		return $s;

        return 'https://' . bx_ltrim_str($s, 'http://');
    }
}

/** @} */
