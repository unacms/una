<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

bx_import('BxDolForm');

class BxDolSearchExtended extends BxDolFactory implements iBxDolFactoryObject
{
    public static $SEARCHABLE_TYPES = array(
        'text', 'textarea', 'number', 'slider',
        'select', 'radio_set',
        'checkbox_set', 'select_multiple',
        'checkbox', 'switcher',
        'datepicker', 'datetime',
        'location', 'location_radius'
    );

    public static $TYPE_TO_TYPE_SEARCH = array(
        'text' => array('text', 'text_range'),
        'textarea' => array('text'),
        'number' => array('text'),
        'slider' => array('text_range', 'text'),
        'text_auto' => array('text_auto'),
        'select' => array('checkbox_set', 'select_multiple', 'select'),
        'radio_set' => array('checkbox_set', 'select_multiple', 'select'),
        'checkbox_set' => array('checkbox_set', 'select_multiple', 'select'),
        'select_multiple' => array('checkbox_set', 'select_multiple', 'select'),
        'checkbox' => array('checkbox', 'switcher'),
        'switcher' => array('checkbox', 'switcher'),
        'datepicker' => array('datepicker_range', 'datepicker_range_age'),
        'datetime' => array('datetime_range', 'datetime_range_age'),
        'location' => array('location', 'location_radius')
    );

    public static $TYPE_TO_OPERATOR = array(
        'text' => array('like', '=', 'between'),
        'textarea' => array('like', '='),
        'number' => array('='),
        'slider' => array('between', '='),
        'text_auto' => array('in'),
        'select' => array('in'),
        'radio_set' => array('in'),
        'checkbox_set' => array('and'), 
        'select_multiple' => array('and'),
        'checkbox' => array('='),
        'switcher' => array('='),
    	'datepicker' => array('between'), 
    	'datetime' => array('between'),
        'location' => array('locate')
    );

    protected $_oDb;

    protected $_sObject;
    protected $_aObject;

    protected $_bFilterMode;

    /**
     * Constructor
     * @param $aObject array of search options
     */
    protected function __construct($aObject)
    {
        parent::__construct();

        $this->_oDb = new BxDolSearchExtendedQuery($this->_aObject);

        $this->_sObject = $aObject['object'];
        $this->_aObject = $aObject;

        $this->_bFilterMode = false;
        if(isset($this->_aObject['filter']))
            $this->_bFilterMode = (int)$this->_aObject['filter'] != 0;
    }

    /**
     * Get editor object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject, $oTemplate = false)
    {
        if(isset($GLOBALS['bxDolClasses']['BxDolSearchExtended!' . $sObject]))
            return $GLOBALS['bxDolClasses']['BxDolSearchExtended!' . $sObject];

        $aObject = BxDolSearchExtendedQuery::getSearchObject($sObject);
        if(!$aObject || !is_array($aObject))
            return false;

        $sClass = 'BxTemplSearchExtended';
        if(!empty($aObject['class_name'])) {
            $sClass = $aObject['class_name'];
            if(!empty($aObject['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aObject['class_file']);
        }

        $o = new $sClass($aObject, $oTemplate);
        return ($GLOBALS['bxDolClasses']['BxDolSearchExtended!' . $sObject] = $o);
    }

    static public function actionGetAuthors()
    {
        self::getMention("@");
    }
    
    static public function actionGetHashtags()
    {
        self::getMention("#");
    }
    
    static public function actionGetMention()
    {
        self::getMention(bx_get('symbol'));
    }
    
    static function getMention($sSymbol)
    {
        $a = bx_get_base_url_inline();
        $aResult = [];
        
        if($sSymbol == '@'){
            $aResult = BxDolService::call('system', 'profiles_search', array(bx_get('term')), 'TemplServiceProfiles');
            foreach ($aResult as &$aItem) {
                $aItem['symbol'] = bx_get('symbol');
            }
        }

        if($sSymbol == '#'){
            $aData = BxDolMetatags::getMetatagsDataByTerm('keywords', 'keyword', bx_get('term'));
            foreach ($aData as $aItem) {
                $aResult[] = ['label' => $aItem['meta'], 'value' => $aItem['id'], 'url' => $aItem['url'], 'symbol' => bx_get('symbol')];
            }
        }

        bx_alert('search', 'get_mention', 0, 0, array('params' => $a[1], 'override_result' => &$aResult));
        
        header('Content-Type:text/javascript; charset=utf-8');
        echo json_encode($aResult);
    }

    static public function encodeConditions($aConditions) 
    {
        return urlencode(base64_encode(json_encode($aConditions)));
    }

    static public function decodeConditions($sConditions) 
    {
        return json_decode(base64_decode(urldecode($sConditions)), true);
    }

    public function isEnabled()
    {
        return isset($this->_aObject['active']) && (int)$this->_aObject['active'] != 0;
    }

    public function clean()
    {
        return $this->cleanFields() & $this->cleanSortableFields();
    }
    
    public function cleanFields()
    {
        return $this->_oDb->deleteFields(array('object' => $this->_sObject)) !== false;
    }
    
    public function cleanSortableFields()
    {
        return $this->_oDb->deleteSortableFields(array('object' => $this->_sObject)) !== false;
    }

    public function reset()
    {   
        $this->resetFields();
        $this->resetSortableFields();
        return true;
    }
    
    public function resetFields()
    {
        if(!$this->cleanFields())
            return false;
        
        $this->_aObject['fields'] = BxDolSearchExtendedQuery::getSearchFields($this->_aObject);
        return true;
    }
    
    public function resetSortableFields()
    {
        if(!$this->cleanSortableFields())
            return false;
        
        $this->_aObject['fields'] = BxDolSearchExtendedQuery::getSearchSortableFields($this->_aObject);
        return true;
    }
}

/** @} */
