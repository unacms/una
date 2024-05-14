<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolAI extends BxDolFactory implements iBxDolSingleton
{
    
    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

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
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolAI();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }
    
    public function chat($sEndpoint, $sModel, $sApiKey, $aParams, $aMessages)
    {
         $aData = array_merge(
             [
                'model' => $sModel,
                'messages' => $aMessages
            ], 
            $aParams
        );
        $sRv = bx_file_get_contents($sEndpoint, $aData, "post-json", ["Authorization: Bearer ".$sApiKey, 'Content-Type: application/json', 'OpenAI-Beta: assistants=v1']);

        $aRv = json_decode($sRv, true);

        return $aRv['choices'][0]['message']['content'];
    }
}