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
bx_import('BxDolModuleConfig');

class BxTimelineConfig extends BxDolModuleConfig
{
    protected $_oDb;

    protected $_sAlertSystemName;
    protected $_sCommentSystemName;
    protected $_sVoteSystemName;

    protected $_bAllowDelete;

    protected $_iPerPageProfile;
    protected $_iPerPageAccount;
    protected $_iRssLength;
    protected $_iCharsDisplayMax;

    protected $_aHandlers;
    protected $_aHandlersHidden;

    protected $_sStorageObject;
    protected $_sTranscoderObjectPreview;
    protected $_sTranscoderObjectView;
    protected $_aImageUploaders;

	protected $_sConnObjSubscriptions;

	protected $_sMenuItemManage;
	protected $_sMenuItemActions;

    protected $_bJsMode;
    protected $_aJsClass;
    protected $_aJsObjects;

    protected $_aHtmlIds;
    protected $_aShareDefaults;

	protected $_sAnimationEffect;
    protected $_iAnimationSpeed;
    protected $_sStylePrefix;
	protected $_sCommonPostPrefix;
	protected $_iPrivacyViewDefault;
	protected $_iTimelineVisibilityThreshold;

    /**
     * Constructor
     */
    public function __construct($aModule)
    {
        parent::BxDolModuleConfig($aModule);

        $this->_sAlertSystemName = $this->_sName;
        $this->_sCommentSystemName = $this->_sName;
        $this->_sVoteSystemName = $this->_sName;

        $this->_aHandlersHidden = array();
        $this->_aHandlers = array();

        $this->_sStorageObject = $this->_sName . '_photos';
        $this->_sTranscoderObjectPreview = $this->_sName . '_photos_preview';
        $this->_sTranscoderObjectView = $this->_sName . '_photos_view';
        $this->_aImageUploaders = array($this->_sName . '_simple');        

		$this->_sConnObjSubscriptions = 'sys_profiles_subscriptions';

		$this->_sMenuItemManage = $this->_sName . '_menu_item_manage';
		$this->_sMenuItemActions = $this->_sName . '_menu_item_actions';

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
        		'item_popup' => $sHtmlPrefix . '-item-popup-',
        	),
        	'post' => array(),
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

        $this->_sAnimationEffect = 'fade';
        $this->_iAnimationSpeed = 'slow';
        $this->_sStylePrefix = 'bx-tl';
        $this->_sCommonPostPrefix = 'timeline_common_';
		$this->_iPrivacyViewDefault = BX_DOL_PG_ALL;
		$this->_iTimelineVisibilityThreshold = 0;
		
    }

    public function init(&$oDb)
    {
        $this->_oDb = &$oDb;

        $this->_bAllowDelete = $this->_oDb->getParam('bx_timeline_enable_delete') == 'on';

        $this->_iPerPageProfile = (int)$this->_oDb->getParam('bx_timeline_events_per_page_profile');
        $this->_iPerPageAccount = (int)$this->_oDb->getParam('bx_timeline_events_per_page_account');
        $this->_iRssLength = (int)$this->_oDb->getParam('bx_timeline_rss_length');

        $this->_iCharsDisplayMax = (int)$this->_oDb->getParam('bx_timeline_chars_display_max');        

		$aHandlers = $this->_oDb->getHandlers();
        foreach($aHandlers as $aHandler) {
        	if($aHandler['type'] === BX_TIMELINE_HANDLER_TYPE_INSERT && !empty($aHandler['content'])) {
        		$aContent = unserialize($aHandler['content']);
        		if(is_array($aContent) && !empty($aContent))
        			$aHandler = array_merge($aHandler, $aContent);
        	}

           $this->_aHandlers[$aHandler['alert_unit'] . '_' . $aHandler['alert_action']] = $aHandler;
        }

		$sHideTimeline = $this->_oDb->getParam('bx_timeline_events_hide');
        if(!empty($sHideTimeline))
            $this->_aHandlersHidden = explode(',', $sHideTimeline);
    }

    public function isAllowDelete()
    {
    	return $this->_bAllowDelete;
    }
    public function getSystemName($sType)
    {
    	$sResult = '';

    	switch($sType) {
    		case 'alert':
    			$sResult = $this->_sAlertSystemName;
    			break;
    		case 'comment':
    			$sResult = $this->_sCommentSystemName;
    			break;
    		case 'vote':
    			$sResult = $this->_sVoteSystemName;
    			break;
    	}

        return $sResult;
    }

	public function getPrefix($sType)
    {
    	$sResult = '';

    	switch($sType) {
    		case 'common_post':
    			$sResult = $this->_sCommonPostPrefix;
    			break;
    		case 'style':
    			$sResult = $this->_sStylePrefix;
    			break;
    	}
    	
        return $sResult;
    }

    public function getObject($sType)
    {
    	$sResult = '';

    	switch($sType) {
    		case 'storage':
    			$sResult = $this->_sStorageObject;
    			break;
    		case 'transcoder_preview':
    			$sResult = $this->_sTranscoderObjectPreview;
    			break;
    		case 'transcoder_view':
    			$sResult = $this->_sTranscoderObjectView;
    			break;
    		case 'conn_subscriptions':
    			$sResult = $this->_sConnObjSubscriptions;
    			break;
    		case 'menu_item_manage':
    			$sResult = $this->_sMenuItemManage;
    			break;
    		case 'menu_item_actions':
    			$sResult = $this->_sMenuItemActions;
    			break;
    	}

        return $sResult;
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

    public function getPerPage($sPage = 'profile')
    {
        $iResult = 10;
        switch($sPage) {
            case 'profile':
                $iResult = $this->_iPerPageProfile;
                break;
            case 'account':
                $iResult = $this->_iPerPageAccount;
                break;
        }

        return $iResult;
    }

	public function getRssLength()
    {
        return $this->_iRssLength;
    }

    public function getCharsDisplayMax()
    {
    	return $this->_iCharsDisplayMax;
    }

	public function isHandler($sKey = '')
    {
        return isset($this->_aHandlers[$sKey]);
    }

    public function getHandlers($sKey = '')
    {
        if($sKey == '')
            return $this->_aHandlers;

        return $this->_aHandlers[$sKey];
    }

	public function getHandlersHidden()
    {
        return $this->_aHandlersHidden;
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

	public function getJsClass($sType)
    {
        return $this->_aJsClass[$sType];
    }

	public function getJsObject($sType)
    {
        return $this->_aJsObjects[$sType];
    }

	public function getHtmlIds($sType, $sKey = '')
    {
    	if(empty($sKey))
    		return $this->_aHtmlIds[$sType];

        return $this->_aHtmlIds[$sType][$sKey];
    }

    public function getShareDefaults()
    {
    	return $this->_aShareDefaults;
    }

	public function getAnimationEffect()
    {
        return $this->_sAnimationEffect;
    }

    public function getAnimationSpeed()
    {
        return $this->_iAnimationSpeed;
    }

    public function getPrivacyViewDefault()
    {
    	return $this->_iPrivacyViewDefault;
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
