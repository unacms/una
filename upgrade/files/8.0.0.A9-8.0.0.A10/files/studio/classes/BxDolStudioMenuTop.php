<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

bx_import('BxDol');
bx_import('BxBaseStudioLauncher');

define('BX_DOL_STUDIO_MT_LEFT', 'left');
define('BX_DOL_STUDIO_MT_CENTER', 'center');
define('BX_DOL_STUDIO_MT_RIGHT', 'right');

define('BX_DOL_STUDIO_MTB_CIRCLE', 'circ');
define('BX_DOL_STUDIO_MTB_RECTANGLE', 'rect');

class BxDolStudioMenuTop extends BxDol
{
    protected $aItems;
    protected $aVisible;

    function __construct()
    {
        parent::__construct();

        $this->aVisible = array(
            BX_DOL_STUDIO_MT_LEFT => false,
            BX_DOL_STUDIO_MT_CENTER => false,
            BX_DOL_STUDIO_MT_RIGHT => false
        );

        $this->aItems[BX_DOL_STUDIO_MT_LEFT] = array(
            'edit' => array(
                'name' => 'edit',
                'icon' => 'move',
                'onclick' => $this->getJsObject() . '.clickEdit(this);',
                'title' => '_adm_tmi_cpt_edit'
            ),
            'favorite' => array(
                'name' => 'favorite',
                'icon' => 'star',
                'onclick' => $this->getJsObject() . '.clickFavorite(this);',
                'title' => '_adm_tmi_cpt_favorite'
            ),
            'extensions' => array(
                'name' => 'extensions',
                'icon' => 'plus',
                'link' => BX_DOL_URL_STUDIO . 'store.php?page=goodies',
                'title' => '_adm_tmi_cpt_extensions'
            ),
        );

        $aMatch = array();
        $iResult = preg_match("/^(https?:\/\/)?(.*)\/$/", BX_DOL_URL_ROOT, $aMatch);
        $this->aItems[BX_DOL_STUDIO_MT_CENTER] = $iResult ? $aMatch[2] : '';

        $this->aItems[BX_DOL_STUDIO_MT_RIGHT] = array(
            'site' => array(
                'name' => 'site',
                'icon' => 'home',
                'link' => BX_DOL_URL_ROOT,
                'title' => '_adm_tmi_cpt_site'
            ),
            'logout' => array(
                'name' => 'logout',
                'icon' => 'power-off',
                'link' => BX_DOL_URL_ROOT . 'logout.php',
                'onclick' => $this->getJsObject() . ".clickLogout(this);",
                'title' => '_adm_tmi_cpt_logout'
            )
        );
    }

    function setVisible($sPosition, $bValue)
    {
        $this->aVisible[$sPosition] = $bValue;
    }

    function setVisibleAll()
    {
        $this->aVisible = array(
            BX_DOL_STUDIO_MT_LEFT => true,
            BX_DOL_STUDIO_MT_CENTER => true,
            BX_DOL_STUDIO_MT_RIGHT => true
        );
    }
}

/** @} */
