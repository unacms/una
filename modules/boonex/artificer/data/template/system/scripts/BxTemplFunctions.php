<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

bx_import('BxDolDesigns');

class BxTemplFunctions extends BxBaseFunctions
{
    public function __construct($oTemplate = null)
    {
        parent::__construct($oTemplate);
    }

    function getMainLogo($aParams = array())
    {
        $oDesigns = BxDolDesigns::getInstance();

        $sTitle = getParam('site_title');
        $bTitle = !empty($sTitle);

        $sAlt = $oDesigns->getSiteLogoAlt();
        if(empty($sAlt) && $bTitle)
            $sAlt = $sTitle;
        $sAltAttr = bx_html_attribute($sAlt, BX_ESCAPE_STR_QUOTE);

        $bTmplVarsShowTitle = $bTitle;
        $aTmplVarsShowImage = [];
        $aTmplVarsShowImageMini = [];

        $bDefault = false;
        if(($sFileUrl = $this->getMainLogoUrl()) !== false || ($bDefault = (!$bTitle && ($sFileUrl = $this->_oTemplate->getImageUrl('logo-generic.svg')) != ''))) {
            $bTmplVarsShowTitle = false;
            $aTmplVarsShowImage = [
                'class' => '',
                'style' => '',
                'src' => $sFileUrl,
                'alt' => $sAltAttr
            ];

            $iLogoHeight = (int)$oDesigns->getSiteLogoHeight();
            $sHeight = $iLogoHeight > 0 ? 'height:' . round($iLogoHeight/16, 3) . 'rem;' : '';

            if($bDefault) {
                //--- Default Logo
                list($iDlWidth, $iDlHeight) = bx_get_svg_image_size($sFileUrl);
                $fDlAspectRation = $iDlHeight ? $iDlWidth / $iDlHeight : BxDolDesigns::$fLogoAspectRatioDefault;

                $iLogoWidth = $iLogoHeight * $fDlAspectRation;

                //--- Default Logo Mini
                $aTmplVarsShowImage['class'] = 'hidden lg:block';

                if(($sFileUrl = $this->_oTemplate->getImageUrl('mark-generic.svg')) != '') {
                    list($iMlWidth, $iMlHeight) = bx_get_svg_image_size($sFileUrl);
                    $fMlAspectRation = $iMlHeight ? $iMlWidth / $iMlHeight : BxDolDesigns::$fLogoAspectRatioDefault;
                    
                    $imlLogoWidth = $iLogoHeight * $fMlAspectRation;
                    $sMlWidth = $imlLogoWidth > 0 ? 'width:' . round($imlLogoWidth/16, 3) . 'rem;' : '';

                    $aTmplVarsShowImageMini = [
                        'class' => 'block lg:hidden',
                        'style' => $sMlWidth . ' ' . $sHeight,
                        'src' => $sFileUrl,
                        'alt' => $sAltAttr
                    ];
                }
            }
            else
                $iLogoWidth = $oDesigns->getSiteLogoWidth();

            $sWidth = $iLogoWidth > 0 ? 'width:' . round($iLogoWidth/16, 3) . 'rem;' : '';

            $aTmplVarsShowImage['style'] = $sWidth . ' ' . $sHeight;
        }

        $aAttrs = [
            'href' => BX_DOL_URL_ROOT, 
            'title' => $sAltAttr
        ];
        if(!empty($aParams['attrs']) && is_array($aParams['attrs']))
            $aAttrs = array_merge($aAttrs, $aParams['attrs']);

        return $this->_oTemplate->parseHtmlByName('logo_main.html', [
            'attrs' => bx_convert_array2attrs($aAttrs),
            'bx_if:show_title' => [
                'condition' => $bTmplVarsShowTitle,
                'content' => [
                    'logo' => $sTitle,
                ]
            ],
            'bx_if:show_image' => [
                'condition' => !empty($aTmplVarsShowImage),
                'content' => $aTmplVarsShowImage
            ],
            'bx_if:show_image_mini' => [
                'condition' => !empty($aTmplVarsShowImageMini),
                'content' => $aTmplVarsShowImageMini
            ]
        ]);
    }

    public function TemplPageAddComponent($sKey)
    {
        $mixedResult = false;

        switch($sKey) {
            case 'sys_header_width':
                $mixedResult = '';
                if(getParam('bx_artificer_header_stretched') != 'on')
                    $mixedResult = parent::TemplPageAddComponent($sKey);
                break;

            case 'sys_site_search':
                $oSearch = new BxTemplSearch();
                $oSearch->setLiveSearch(true);
                $mixedResult = $oSearch->getForm(BX_DB_PADDING_DEF, false, true) . $oSearch->getResultsContainer();
                break;

            default:
                $mixedResult = parent::TemplPageAddComponent($sKey);
        }

        return $mixedResult;
    }    

    protected function getInjFooterPopupMenus() 
    {
        $sContent = '';

        $oSearch = new BxTemplSearch();
        $oSearch->setLiveSearch(true);
        $sContent .= $this->_oTemplate->parsePageByName('search.html', array(
            'search_form' => $oSearch->getForm(BX_DB_CONTENT_ONLY),
            'results' => $oSearch->getResultsContainer(),
        ));

        if(isLogged())
            $sContent .= $this->_oTemplate->getMenu('sys_add_content');

        return $sContent;
    }

    protected function getInjFooterSidebarSite() 
    {
        $oSearch = new BxTemplSearch();
        $oSearch->setLiveSearch(true);
        $sSearch = $oSearch->getForm(BX_DB_CONTENT_ONLY);

        $sLogoUrl = $this->getMainLogoUrl();
        $bLogoUrl = !empty($sLogoUrl);

        $sLogoText = BxDolDesigns::getInstance()->getSiteLogoAlt();
        if(empty($sLogoText))
            $sLogoText = getParam('site_title');

        return $this->_oTemplate->parsePageByName('sidebar_site.html', array(
            'bx_if:show_logo_image' => array(
                'condition' => $bLogoUrl,
                'content' => array(
                    'logo_url' => $sLogoUrl,
                    'logo_text' => $sLogoText
                )
            ),
            'bx_if:show_logo_text' => array(
                'condition' => !$bLogoUrl,
                'content' => array(
                    'logo_text' => $sLogoText
                )
            ),
            'search_form' => $sSearch,
        ));
    }

    protected function getInjFooterSidebarAccount() 
    {
        $oProfile = BxDolProfile::getInstance();
        if(!$oProfile)
            return '';

        $sSwitcher = '';
        if(($aSwitcher = bx_srv('system', 'account_profile_switcher', [], 'TemplServiceProfiles')) !== false) 
            $sSwitcher = $aSwitcher['content'];

        return $this->_oTemplate->parsePageByName('sidebar_account.html', [
            'active_profile' => $oProfile->getUnit(),
            'menu_notifications' => BxDolMenu::getObjectInstance('sys_account_notifications')->getCode(),
            'profile_switcher' => $sSwitcher,
            'bx_if:multiple_profiles_mode' => [
                'condition' => BxDolAccount::isAllowedCreateMultiple($oProfile->id()),
                'content' => [
                    'url_switch_profile' => BxDolPermalinks::getInstance()->permalink('page.php?i=account-profile-switcher')
        	]
            ]
        ]);
    }
}

/** @} */
