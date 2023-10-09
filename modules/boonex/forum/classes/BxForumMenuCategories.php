<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxForumMenuCategories extends BxTemplMenu
{
    protected $_sModule;
    protected $_oModule;

    protected $_iMenuItemsMin;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_sModule = 'bx_forum';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
        $this->_bDisplayAddons = true;
    }

    public function getMenuItems ()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        
        $aItems = [];
        
        $aCategoriest = bx_srv('system', 'categories_list', ['bx_forum_cats', ['show_empty' => true]], 'TemplServiceCategory');
        
        if (!($o = BxDolCategory::getObjectInstance('bx_forum_cats')))
            return $aItems;

		$aCategories = $o->getCategoriesList(false, true);
        
		if(!isset($aCategories['bx_repeat:cats']))
			return $aItems;
        
        $iCount = 0;
        foreach ($aCategories['bx_repeat:cats'] as $sKey => $aCategory) {
            $iCount +=  $aCategories['bx_repeat:cats'][$sKey]['num'];
        }
        
        $aItems[] =  [
            'class_add' => 'bx-psmi-show-0' .  (bx_get('category') == '' ? ' bx-menu-item-active' : ''),
            'name' => 'show-0',
            'title' => _t('_bx_forum_txt_all_categories'),
            'link' => BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']),
            'bx_if:onclick' => [
                'condition' => false,
                'content' => [
                    'onclick' => 'javascript:',
                ]
            ],
            'attrs' => '',
            'bx_if:image' => array (
                'condition' => false,
                'content' => [],
            ),
            'bx_if:image_inline' => array (
                'condition' => false,
                'content' => [],
            ),
            'bx_if:icon' => array (
                'condition' => true,
                'content' => ['icon' => 'swatchbook'],
            ),
            'bx_if:icon-a' => array (
                'condition' => false,
                'content' => [],
            ),
            'bx_if:icon-html' => array (
                'condition' => false,
                'content' => [],
            ),
            'bx_if:addon' => [
                'condition' => true,
                'content' => ['addon' => $iCount]
            ]
        ];

        foreach ($aCategories['bx_repeat:cats'] as $sKey => $aCategory) {
            $aCategoryData = $this->_oModule->_oDb->getCategories(['type' => 'by_category', 'category' => $aCategory['value']]);
            if(empty($aCategoryData) || (!empty($aCategoryData['visible_for_levels']) && BxDolAcl::getInstance()->isMemberLevelInSet($aCategoryData['visible_for_levels']))) {
                
                $aCategories['bx_repeat:cats'][$sKey]['icon'] = $this->_oTemplate->getImage(isset($aCategoryData['icon']) ? $aCategoryData['icon'] : 'folder', array('class' => 'sys-icon sys-colored'));
                
                if (!isset($aCategoryData['icon']) || $aCategoryData['icon'] == '')
                    $aCategoryData['icon'] = 'folder';
                
                list($sIcon, $sIconUrl, $sIconA, $sIconHtml) = BxTemplFunctions::getInstance()->getIcon($aCategoryData['icon']);
                
                $aItems[] =  [
                    // TODO
                    'class_add' => 'bx-psmi-show-' . $aCategories['bx_repeat:cats'][$sKey]['value'] . (bx_get('category') == $aCategories['bx_repeat:cats'][$sKey]['value'] ? ' bx-menu-item-active' : ''),
                    'name' => 'show-' . $aCategories['bx_repeat:cats'][$sKey]['value'],
                    'title' => $aCategories['bx_repeat:cats'][$sKey]['name'],
                    'link' => $aCategories['bx_repeat:cats'][$sKey]['url'],
                    'bx_if:onclick' => [
                        'condition' => false,
                        'content' => [
                            'onclick' => 'javascript:',
                        ]
                    ],
                    'attrs' => '',
                    'bx_if:image' => array (
                        'condition' => (bool)$sIconUrl,
                        'content' => array('icon_url' => $sIconUrl),
                    ),
                    'bx_if:image_inline' => array (
                        'condition' => false,
                        'content' => array('image' => ''),
                    ),
                    'bx_if:icon' => array (
                        'condition' => (bool)$sIcon,
                        'content' => array('icon' => $sIcon),
                    ),
                    'bx_if:icon-a' => array (
                        'condition' => (bool)$sIconA,
                        'content' => array('icon-a' => $sIconA),
                    ),
                    'bx_if:icon-html' => array (
                        'condition' => (bool)$sIconHtml,
                        'content' => array('icon' => $sIconHtml),
                    ),
                    'bx_if:addon' => [
                        'condition' => true,
                        'content' => ['addon' => $aCategories['bx_repeat:cats'][$sKey]['num']]
                    ]
                ];
            }
        }

        if(empty($aItems) || !is_array($aItems))
            return $aItems;

        $iMaxNum = count($aItems);
        $iMenuItemsMin = (int)getParam('bx_forum_visible_categories');
        if($iMaxNum <= $iMenuItemsMin)
            return $aItems;

        $mixedCollpsed = $this->getUserChoiceCollapsed();
        $bCollpsed = $mixedCollpsed === false || $mixedCollpsed == 1;

        for($i = $iMenuItemsMin; $i < $iMaxNum; $i++)
            $aItems[$i]['class_add'] .= ' bx-mi-aux' . ($bCollpsed ? ' bx-mi-hidden' : '');

        $aShowMoreLinks = [
            'more' => ['title' => '_sys_show_more', 'icon' => 'chevron-down', 'class' => $bCollpsed ? '' : 'bx-mi-hidden'],
            'less' => ['title' => '_sys_show_less', 'icon' => 'chevron-up', 'class' => !$bCollpsed ? '' : 'bx-mi-hidden']
        ];

        foreach($aShowMoreLinks as $sLink => $aLink)
            $aItems[] = array(
                'class_add' => 'bx-psmi-show-' . $sLink . ' ' . $aLink['class'],
                'name' => 'show-' . $sLink,
                'title' => _t($aLink['title']),
                'link' => 'javascript:void(0)',
                'bx_if:onclick' => [
                    'condition' => true,
                    'content' => [
                        'onclick' => 'bx_menu_show_more_less(this, \'' . $this->_sObject . '\', \'.bx-menu-object-' . $this->_sObject . '\')',
                    ]
                ],
                'attrs' => '',
                'bx_if:image' => [
                    'condition' => false,
                    'content' => ['icon_url' => ''],
                ],
                'bx_if:image_inline' => [
                    'condition' => false,
                    'content' => ['image' => ''],
                ],
                'bx_if:icon' => [
                    'condition' => true,
                    'content' => ['icon' => $aLink['icon']],
                ],
                'bx_if:icon-html' => [
                    'condition' => false,
                    'content' => ['icon-a' => ''],
                ],
                'bx_if:icon-a' => [
                    'condition' => false,
                    'content' => ['icon-a' => ''],
                ],
                'bx_if:addon' => [
                    'condition' => false,
                    'content' => []
                ]
            );

        return $aItems;
    }

    protected function getMenuItemsRaw ()
    {
        $aItems = $this->_oQuery->getMenuItemsBy(array(
            'type' => 'set_name', 
            'set_name' => $this->_aObject['set_name']
        ));

        $aDuplicates = $this->_oQuery->getMenuItemsBy(array(
            'type' => 'set_name_duplicates', 
            'set_name' => $this->_aObject['set_name']
        ));

        $oProfile = BxDolProfile::getInstance();
        if(!$oProfile)
            return array();

        $sModule = $oProfile->getModule();

        $aResult = array();
        foreach($aItems as $aItem) {
            if(in_array($aItem['name'], $aDuplicates) && $aItem['module'] != $sModule)
                continue;
            
            $aResult[$aItem['name']] = $aItem;
        }

        return $aResult;
    }
    
    protected function _getMenuItem($a)
    {
        $mixedResult = parent::_getMenuItem($a);

        if($mixedResult !== false && !empty($mixedResult['link']) && strpos($mixedResult['link'], 'javascript:') === false)
            $mixedResult['link'] = bx_append_url_params($mixedResult['link'], [
                'owner' => 1
            ]);

        return $mixedResult;
    }
}

/** @} */
