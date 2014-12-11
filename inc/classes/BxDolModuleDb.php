<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Base class for all modules DB classes
 */
class BxDolModuleDb extends BxDolModuleQuery
{
    protected $_sPrefix;

    /**
     * Constructor
     */
    public function __construct($oConfig)
    {
        parent::__construct();

        if (is_a($oConfig,'BxDolModuleConfig'))
            $this->_sPrefix = $oConfig->getDbPrefix();
        else
            trigger_error ('It is impossible to create BxDolModuleDb class instance without prefix: ' . get_class($this), E_USER_ERROR);
    }

    public function getPrefix()
    {
        return $this->_sPrefix;
    }

}

/** @} */
