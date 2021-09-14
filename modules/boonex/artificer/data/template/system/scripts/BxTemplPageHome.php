<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

/**
 * @see BxBasePageHome
 */
class BxTemplPageHome extends BxBasePageHome
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);
    }

    protected function _getBlockRaw($aBlock)
    {
        $sResult = parent::_getBlockRaw($aBlock);

        if(strpos($aBlock['title'], 'splash') !== false) {
            $oPermalink = BxDolPermalinks::getInstance();
            $sLinkTerms = BX_DOL_URL_ROOT . $oPermalink->permalink('page.php?i=terms');
            $sLinkPrivacy = BX_DOL_URL_ROOT . $oPermalink->permalink('page.php?i=privacy');

            $iMembers = 0;
            if($aMembers = BxDolAccountQuery::getInstance()->getAccounts(['type' => 'confirmed']))
                $iMembers = count($aMembers);

            $iPosts = 0;
            $aModules = bx_srv('system', 'get_modules_by_type', ['content']);
            foreach($aModules as $aModule)
                if(BxDolRequest::serviceExists($aModule['name'], 'get_all'))
                    $iPosts += bx_srv($aModule['name'], 'get_all', [['type' => 'all', 'count' => true]]);

            $iComments = BxDolCmtsQuery::getInfoBy(['type' => 'all', 'count' => true]);

            $sResult = BxDolTemplate::getInstance()->parseHtmlByContent($sResult, array(
                'members' => $iMembers,
                'posts' => $iPosts,
                'comments' => $iComments,
                'login_agreement' => _t('_bx_artificer_txt_splash_login_agreement', $sLinkTerms, $sLinkPrivacy)
            ));
        }

        return $sResult;
    }
}

/** @} */
