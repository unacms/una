<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * File preview object, it's used to preview different files in browser which browse usually doesn't support.
 * 
 *
 * @section file_handler_add Adding new files handler:
 *
 *
 * Add record to 'sys_objects_file_handlers' table:
 *
 * - object: name of the file handler object, in the format: vendor prefix, underscore, module prefix, underscore, internal identifier or nothing; for example: bx_abode_pdf - custom file handler from Abode to display PDF files in browser.
 * - title: file handler title
 * - override_class_name: user defined class name which is derived from one of base file handler class.
 * - override_class_file: the location of the user defined class, leave it empty if class is located in system folders.
 *
 *
 * @section example Example of usage
 *
 * Display file preview:
 *
 * @code
 *  $oFileHandler = BxDolFileHandler::getObjectInstanceByFile('hello.pdf'); // get file preview class
 *  if ($oFileHandler) // check if preview is available for gived file
 *      echo $oFileHandler->display (); // output HTML which will automatically show file preview
 * @endcode
 *
 */
class BxDolFileHandler extends BxDolFactory implements iBxDolFactoryObject
{
    protected $_sObject;
    protected $_aObject;

    /**
     * Constructor
     * @param $aObject array of file handler options
     */
    protected function __construct($aObject)
    {
        parent::__construct();

        $this->_sObject = $aObject['object'];
        $this->_aObject = $aObject;
    }

    /**
     * Get file handler object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject)
    {
        if (isset($GLOBALS['bxDolClasses'][__CLASS__.'!'.$sObject]))
            return $GLOBALS['bxDolClasses'][__CLASS__.'!'.$sObject];

        $aObjects = BxDolFileHandlerQuery::getObjects();
        if (!$aObjects || !isset($aObjects[$sObject]))
            return false;

        $aObject = $aObjects[$sObject];

        if (empty($aObject['override_class_name']))
            return false;

        $sClass = $aObject['override_class_name'];
        if (!empty($aObject['override_class_file']))
            require_once(BX_DIRECTORY_PATH_ROOT . $aObject['override_class_file']);

        $o = new $sClass($aObject);
        if (!$o->isActive())
            return false;

        return ($GLOBALS['bxDolClasses'][__CLASS__.'!'.$sObject] = $o);
    }

    /**
     * Get file handler object instance by file name
     * @param $sFileName file name
     * @return object instance or false on error
     */
    static public function getObjectInstanceByFile($sFileName)
    {
        if (!($aObjects = BxDolFileHandlerQuery::getObjects()))
            return false;

        $sObject = false;
        foreach ($aObjects as $sObject => $aObject)
            if (preg_match($aObject['preg_ext'], $sFileName))
                return self::getObjectInstance($sObject);

        return false;
    }
    
    /**
     * Display file preview
     */
    public function display ($sFileUrl, $aFile)
    {
        // override this function in particular class
    }

    /**
     * Check if file preview is available
     */
    public function isActive ()
    {
        return $this->_aObject['active'];
    }
}

/** @} */
