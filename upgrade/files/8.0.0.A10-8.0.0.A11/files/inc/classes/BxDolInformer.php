<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

define('BX_INFORMER_ALERT', 1);
define('BX_INFORMER_INFO', 2);
define('BX_INFORMER_ERROR', 3);

/**
 * @page objects
 * @section informer Informer
 * @ref BxDolInformer
 */

/**
 * Informer.
 *
 * It displays alerts or information messages in clearly visible area on the page to let user know important information.
 *
 * @section example Example of usage
 *
 * Adding message to informer:
 *
 * @code
 *  $oInformer = BxDolInformer::getInstance(); // get object instance
 *  if ($oInformer) // check if Informer is available for using
 *      echo $oInformer->add ('my_id', 'Some important information here', BX_INFORMER_ALERT); // add an alert message
 * @endcode
 *
 */
class BxDolInformer extends BxDol
{
    protected $_aMessages = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        if (isset($GLOBALS['bxDolClasses']['BxDolInformer']))
            trigger_error ('Multiple instances are not allowed for the BxDolInformer class.', E_USER_ERROR);

        parent::__construct();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses']['BxDolInformer']))
            trigger_error('Clone is not allowed for the BxDolInformer class.', E_USER_ERROR);
    }

    /**
     * Get Informer object instance
     * @return object instance or false on error
     */
    public static function getInstance($oTemplate = false)
    {
        if (isset($GLOBALS['bxDolClasses']['BxDolInformer']))
            return $GLOBALS['bxDolClasses']['BxDolInformer'];

        $o = new BxTemplInformer($oTemplate);

        return ($GLOBALS['bxDolClasses']['BxDolInformer'] = $o);
    }

    /**
     * Add message to informer.
     * @param $sId - message id
     * @param $sMsg - message text
     * @param $iType - message type: BX_INFORMER_ALERT, BX_INFORMER_INFO or BX_INFORMER_ERROR
     */
    public function add ($sId, $sMsg, $iType = BX_INFORMER_INFO)
    {
        $this->_aMessages[$sId] = array (
            'id' => $sId,
            'msg' => $sMsg,
            'type' => $iType,
        );
    }

    /**
     * Remove message from informer.
     * @param $sId - message id
     */
    public function remove ($sId)
    {
        unset($this->_aMessages[$sId]);
    }

    /**
     * Add permanent messages which are displayed on every page.
     */
    protected function _addPermanentMessages ()
    {
        // add account & profile related permament messages
        if (isLogged()) {
            $oAccount = BxDolAccount::getInstance();
            if ($oAccount)
                $oAccount->addInformerPermanentMessages($this);
                
            $oProfile = BxDolProfile::getInstance();
            if ($oProfile)
                $oProfile->addInformerPermanentMessages($this);
        }

        // add permament messages from modules
        bx_alert('system', 'informer_permament_messages', 0, false, array('informer' => $this));
    }

}

/** @} */
