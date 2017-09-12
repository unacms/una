<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    ElasticSearch ElasticSearch
 * @ingroup     UnaModules
 *
 * @{
 */

class BxElsConfig extends BxBaseModGeneralConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array (

            // module icon
            'ICON' => 'search col-blue2',

            // database fields
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
        	'FIELD_LINK' => 'link',
            'FIELD_TITLE' => 'title',
            'FIELD_TEXT' => 'text',

            // params
			'PARAM_API_URL' => 'bx_elasticsearch_api_url',

            // objects
        	'OBJECT_FORM_MANAGE' => 'bx_elasticsearch_manage',
            'OBJECT_FORM_MANAGE_DISPLAY_INDEX' => 'bx_elasticsearch_manage_index',
        );

        $this->_aJsClasses = array(
            'manage' => 'BxElsManage'
        );

        $this->_aJsObjects = array(
            'manage' => 'oBxElsManage'
        );
    }

    public function getIndex()
    {
        $aUrl = parse_url(BX_DOL_URL_ROOT);
        return $aUrl['host'];
    }
}

/** @} */
