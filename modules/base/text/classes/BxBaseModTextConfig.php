<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolInformer');

class BxBaseModTextConfig extends BxBaseModGeneralConfig
{
    /**
     * Determine whether Timeline post will use common content (header image + text)
     * or content received from attachments (header image + text + images + videos + polls).
     * By default the first variant is used. 
     * Note. The variable can be removed in future, when all Text Based modules 
     * implement 'attachments' related parameters. 
     */
    protected $_bAttachmentsInTimeline;
    
    protected $_aPregPatterns;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array_merge($this->CNF, [
            // database tables            
            'TABLE_LINKS' => $aModule['db_prefix'] . 'links',
            'TABLE_LINKS2CONTENT' => $aModule['db_prefix'] . 'links2content',

            // database fields
            'FIELD_ATTACH_LINK_CONTENT_ID' => 'content_id',
            'FIELD_ATTACH_LINK_URL' => 'url',
            'FIELD_ATTACH_LINK_CONTROLS' => 'controls',

            // some params
            'PARAM_MULTICAT_ENABLED' => false,
            'PARAM_LINKS_ENABLED' => false,
            'PARAM_LINKS_LIMIT' => 'sys_attach_links_max',

            // objects
            'OBJECT_FORM_ATTACH_LINK' => $this->_sName . '_attach_link',
            'OBJECT_FORM_ATTACH_LINK_DISPLAY_ADD' => $this->_sName . '_attach_link_add',
        ]);

        $sPrefix = str_replace('_', '-', $this->_sName);
        $this->_aPrefixes = [
            'style' => $sPrefix,
        ];

        $this->_aJsClasses = array_merge($this->_aJsClasses, [
            'links' => $this->_sClassPrefix . 'Links',
            'categories' => $this->_sClassPrefix . 'Categories'
        ]);

        $this->_aJsObjects = $this->_aJsObjects = array_merge($this->_aJsObjects, [
            'links' => 'o' . $this->_sClassPrefix . 'Links',
            'categories' => 'o' . $this->_sClassPrefix . 'Categories'
        ]);

        $this->_aHtmlIds = array_merge($this->_aHtmlIds, $this->_aHtmlIdsPoll, [
            'attach_link_popup' =>  $sPrefix . '-attach-link-popup',
            'attach_link_form_field' => $sPrefix . '-attach-link-form-field-',
            'attach_link_item' => $sPrefix . '-attach-link-item-',
        ]);

        $this->_aPregPatterns = array(
            "meta_title" => "/<title>(.*)<\/title>/",
            "meta_description" => "/<meta[\s]+[^>]*?name[\s]?=[\s\"\']+description[\s\"\']+content[\s]?=[\s\"\']+(.*?)[\"\']+.*?>/",
            "url" => "/(([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%#\/\.\w\-_!\(\)]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?/"
        );

        $this->_bAttachmentsInTimeline = false;
    }

    public function isAttachLinks()
    {
        return !empty($this->CNF['TABLE_LINKS']) && $this->CNF['PARAM_LINKS_ENABLED'] == true;
    }

    public function isAttachmentsInTimeline()
    {
        return $this->_bAttachmentsInTimeline;
    }
    
    public function getPregPattern($sType)
    {
        return $this->_aPregPatterns[$sType];
    }

    public function getHtmlIds($sKey = '')
    {
        if(empty($sKey))
            return $this->_aHtmlIds;

        return isset($this->_aHtmlIds[$sKey]) ? $this->_aHtmlIds[$sKey] : '';
    }
}

/** @} */
