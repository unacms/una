<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Timeline Timeline
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolPrivacy');
bx_import('BxBaseModNotificationsConfig');

class BxTimelineConfig extends BxBaseModNotificationsConfig
{
    protected $_bAllowDelete;

    protected $_iRssLength;
    protected $_iCharsDisplayMax;

    protected $_sStorageObject;
    protected $_sTranscoderObjectPreview;
    protected $_sTranscoderObjectView;
    protected $_aImageUploaders;

    protected $_sMenuItemManage;
    protected $_sMenuItemActions;

    protected $_bJsMode;
    protected $_aShareDefaults;

    protected $_iTimelineVisibilityThreshold;
    protected $_aPregPatterns;

    /**
     * Constructor
     */
    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array (
        	// objects
            'OBJECT_COMMENTS' => $this->_sName,
        
        	// some language keys
            'T' => array (
                'txt_sample_single' => '_bx_timeline_txt_sample',
            	'txt_sample_comment_single' => '_bx_timeline_txt_sample_comment_single',
            ),
        );

        $this->_aPrefixes = array(
        	'style' => 'bx-tl',
        	'language' => '_bx_timeline',
        	'option' => 'bx_timeline_',
        	'common_post' => 'timeline_common_'
        );

        $this->_aObjects = array(
        	'alert' => $this->_sName,
        	'comment' => $this->CNF['OBJECT_COMMENTS'],
        	'vote' => $this->_sName,
        	'storage' => $this->_sName . '_photos',
        	'transcoder_preview' => $this->_sName . '_photos_preview',
        	'transcoder_view' => $this->_sName . '_photos_view',
        	'conn_subscriptions' => 'sys_profiles_subscriptions',
        	'menu_item_manage' => $this->_sName . '_menu_item_manage',
        	'menu_item_actions' => $this->_sName . '_menu_item_actions'
        );
        
        $this->_aHandlerDescriptor = array('module_name' => '', 'module_method' => '', 'module_class' => '', 'groupable' => '', 'group_by' => '');
        $this->_sHandlersMethod = 'get_timeline_data';

        $this->_aImageUploaders = array($this->_sName . '_simple');

        $this->_bJsMode = false;
        $this->_aJsClass = array(
            'main' => 'BxTimelineMain',
            'view' => 'BxTimelineView',
            'post' => 'BxTimelinePost',
            'share' => 'BxTimelineShare'
        );
        $this->_aJsObjects = array(
            'view' => 'oTimelineView',
            'post' => 'oTimelinePost',
            'share' => 'oTimelineShare'
        );

        $sHtmlPrefix = str_replace('_', '-', $this->_sName);
        $this->_aHtmlIds = array(
            'view' => array(
        		'main' => $sHtmlPrefix,
        		'item' => $sHtmlPrefix . '-item-',
                'menu_popup' => $sHtmlPrefix . '-menu-popup-',
                'photo_popup' => $sHtmlPrefix . '-photo-popup',
            ),
            'post' => array(
                'attach_link_popup' =>  $sHtmlPrefix . '-attach-link-popup',
                'attach_link_form_field' => $sHtmlPrefix . '-attach-link-form_field',
                'attach_link_item' => $sHtmlPrefix . '-attach-link-item-',
            ),
            'share' => array(
                'main' => $sHtmlPrefix . '-share-',
                'counter' => $sHtmlPrefix . '-share-counter-',
                'by_popup' => $sHtmlPrefix . '-share-by-',
            )
        );

        $this->_aShareDefaults = array(
            'show_do_share_as_button' => false,
            'show_do_share_as_button_small' => false,
            'show_do_share_icon' => true,
            'show_do_share_label' => false,
            'show_counter' => true
        );

        $this->_iTimelineVisibilityThreshold = 0;

        $this->_aPregPatterns = array(
            "meta_title" => "/<title>(.*)<\/title>/",
            "meta_description" => "/<meta[\s]+[^>]*?name[\s]?=[\s\"\']+description[\s\"\']+content[\s]?=[\s\"\']+(.*?)[\"\']+.*?>/",
            "url" => "/((https?|ftp|news):\/\/)?([a-z]([a-z0-9\-]*\.)+(aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel|[a-z]{2})|(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))(\/[a-z0-9_\-\.~]+)*(\/([a-z0-9_\-\.]*)(\?[a-z0-9+_\-\.%=&amp;]*)?)?(#[a-z][a-z0-9_]*)?/"
        );

    }

    public function init(&$oDb)
    {
    	parent::init($oDb);

    	$sOptionPrefix = $this->getPrefix('option');
        $this->_bAllowDelete = getParam($sOptionPrefix . 'enable_delete') == 'on';

        $this->_aPerPage = array(
    		'default' => 10,
        	'profile' => (int)getParam($sOptionPrefix . 'events_per_page_profile'),
        	'account' => (int)getParam($sOptionPrefix . 'events_per_page_account'),
        	'home' => (int)getParam($sOptionPrefix . 'events_per_page_home')
    	);

        $this->_iRssLength = (int)getParam($sOptionPrefix . 'rss_length');
        $this->_iCharsDisplayMax = (int)getParam($sOptionPrefix . 'chars_display_max');
    }

    public function isAllowDelete()
    {
        return $this->_bAllowDelete;
    }

    public function getUploaders($sType)
    {
        $aResult = '';

        switch($sType) {
            case 'image':
                $aResult = $this->_aImageUploaders;
                break;
        }

        return $aResult;
    }

    public function getRssLength()
    {
        return $this->_iRssLength;
    }

    public function getCharsDisplayMax()
    {
        return $this->_iCharsDisplayMax;
    }

    public function isJsMode()
    {
        return $this->_bJsMode;
    }

    public function getJsMode()
    {
        return $this->_bJsMode;
    }

    public function setJsMode($bJsMode)
    {
        $this->_bJsMode = $bJsMode;
    }

    public function getShareDefaults()
    {
        return $this->_aShareDefaults;
    }

    public function getPregPattern($sType)
    {
        return $this->_aPregPatterns[$sType];
    }

    /**
     * Ancillary functions
     */
    public function getViewUrl($iOwnerId)
    {
        bx_import('BxDolPermalinks');
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=timeline-view&id=' . $iOwnerId);
    }

    public function getItemViewUrl(&$aEvent)
    {
        bx_import('BxDolPermalinks');
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=timeline-item&id=' . $aEvent['id']);
    }

    public function isSystem($sType, $sAction)
    {
        $sPrefix = $this->getPrefix('common_post');
        return strpos($sType, $sPrefix) === false && !empty($sAction);
    }
}

/** @} */
