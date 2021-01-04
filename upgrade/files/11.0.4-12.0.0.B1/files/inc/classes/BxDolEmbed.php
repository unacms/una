<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Embed provider.
 *
 * Create an embed from a link.
 *
 * @section editor_create Creating the Editor object:
 *
 *
 * Add record to 'sys_objects_embeds' table:
 *
 * - object: name of the embed object, in the format: vendor prefix, underscore, module prefix, underscore, internal identifier or nothing
 * - title: title of the embed provider, dmay be isplayed in the Studio.
 * - override_class_name: user defined class name which is derived from one of base editor classes.
 * - override_class_file: the location of the user defined class, leave it empty if class is located in system folders.
 *
 *
 * @section example Example of usage
 *
 * Generate HTML for a link which will be converted to an embed later:
 *
 * @code
 *  $oEmbed = BxDolEmbed::getObjectInstance(); // get default embed object instance
 *  if ($oEmbed) // check if editor is available for using
 *      echo $oEmbed->getLinkHTML ('https://una.io', 'UNA.IO'); // output HTML which will be automatically converted into embed upon page load
 *  else
 *      echo '<a href="https://una.io">UNA.IO</a>';
 * @endcode
 *
 */
class BxDolEmbed extends BxDolFactoryObject
{
    protected $_bCssJsAdded = false;

    /**
     * Get editor object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject = false, $oTemplate = false)
    {
        if (!$sObject)
            $sObject = getParam('sys_embed_default');

        return parent::getObjectInstanceByClassNames($sObject, $oTemplate, 'BxDolEmbed', 'BxDolEmbedQuery');
    }

    /**
     * Print HTML which will be automatically converted into embed upon page load
     * @param $sLink - link
     * @param $sTitle - title or use link for the title if omitted
     * @param $sMaxWidth - try to restrict max width of embed (works in supported embed providers only)
     */
    public function getLinkHTML ($sLink, $sTitle = '', $sMaxWidth = '')
    {
        // override this function in particular editor class
    }

    /**
     * Add css/js files which are needed for embed display and functionality.
     */
    public function addJsCss ()
    {
        // override this function in particular editor class
    }
}

/** @} */
