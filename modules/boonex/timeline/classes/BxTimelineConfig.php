<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Timeline Timeline
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxDolPrivacy');

class BxTimelineConfig extends BxBaseModNotificationsConfig
{
    protected $_bAllowDelete;

    protected $_iRssLength;
    protected $_iCharsDisplayMax;

    protected $_sStorageObject;
    protected $_sTranscoderObjectPreview;
    protected $_sTranscoderObjectView;
    protected $_aPhotoUploaders;
    protected $_aVideoUploaders;

    protected $_sMenuItemManage;
    protected $_sMenuItemActions;

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
	        // database fields
        	'FIELD_ID' => 'id',
        	'FIELD_LOCATION_PREFIX' => 'location',

        	// objects
            'OBJECT_COMMENTS' => $this->_sName,
        
        	// some language keys
            'T' => array (
                'txt_sample_single' => '_bx_timeline_txt_sample',
        		'txt_sample_single_ext' => '_bx_timeline_txt_sample_ext',
            	'txt_sample_comment_single' => '_bx_timeline_txt_sample_comment_single',
            ),
        );

        $this->_aPrefixes = array(
        	'style' => 'bx-tl',
        	'language' => '_bx_timeline',
        	'option' => 'bx_timeline_',
        	'common_post' => 'timeline_common_'
        );

        $this->_aObjects = array_merge($this->_aObjects, array(
        	'comment' => $this->CNF['OBJECT_COMMENTS'],
        	'vote' => $this->_sName,
        	'metatags' => $this->_sName,

        	'storage_photos' => $this->_sName . '_photos',
        	'storage_videos' => $this->_sName . '_videos',
        	'transcoder_photos_preview' => $this->_sName . '_photos_preview',
        	'transcoder_photos_view' => $this->_sName . '_photos_view',
        	'transcoder_videos_poster' => $this->_sName . '_videos_poster',
        	'transcoder_videos_mp4' => $this->_sName . '_videos_mp4',
        	'transcoder_videos_webm' => $this->_sName . '_videos_webm',

        	'menu_item_manage' => $this->_sName . '_menu_item_manage',
        	'menu_item_actions' => $this->_sName . '_menu_item_actions',
        	'menu_post_attachments' => $this->_sName . '_menu_post_attachments',

        	'form_post' => $this->_sName . '_post',
        	'form_attach_link' => $this->_sName . '_attach_link',
        	'form_display_post_add' => $this->_sName . '_post_add',
        	'form_display_attach_link_add' => $this->_sName . '_attach_link_add'
        ));

        $this->_aHandlerDescriptor = array('module_name' => '', 'module_method' => '', 'module_class' => '', 'groupable' => '', 'group_by' => '');
        $this->_sHandlersMethod = 'get_timeline_data';

        $this->_aPhotoUploaders = array($this->_sName . '_simple_photo');
        $this->_aVideoUploaders = array($this->_sName . '_simple_video');

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
        	"url" => "/(([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%\/\.\w\-_]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?/"
        );
    }

    public function init(&$oDb)
    {
    	parent::init($oDb);

    	$sOptionPrefix = $this->getPrefix('option');
        $this->_bAllowDelete = getParam($sOptionPrefix . 'enable_delete') == 'on';

        $this->_aPerPage = array(
    		'default' => (int)getParam($sOptionPrefix . 'events_per_page'),
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
        $aResult = array();

        switch($sType) {
            case 'photo':
                $aResult = $this->_aPhotoUploaders;
                break;
			case 'video':
                $aResult = $this->_aVideoUploaders;
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
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=timeline-view&id=' . $iOwnerId);
    }

    public function getItemViewUrl(&$aEvent)
    {
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=timeline-item&id=' . $aEvent['id']);
    }

    public function isSystem($sType, $sAction)
    {
        $sPrefix = $this->getPrefix('common_post');
        return strpos($sType, $sPrefix) === false && !empty($sAction);
    }
    public function getSystemData(&$aEvent)
    {
		$sHandler = $aEvent['type'] . '_' . $aEvent['action'];
        if(!$this->isHandler($sHandler))
            return false;

        $aHandler = $this->getHandlers($sHandler);
        if(empty($aHandler['module_name']) || empty($aHandler['module_class']) || empty($aHandler['module_method']))
        	return false; 

		return BxDolService::call($aHandler['module_name'], $aHandler['module_method'], array($aEvent), $aHandler['module_class']);
    }
    public function getSystemDataByDescriptor($sType, $sAction, $iObjectId)
    {
    	$aDescriptor = array(
    		'type' => $sType, 
    		'action' => $sAction,
    		'object_id' => $iObjectId
    	);
    	return $this->getSystemData($aDescriptor);
    }
}

/** @} */
