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
    protected $_aHtmlIds;

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

        $this->CNF = array(
            // modules icon
            'ICON_POLLS_ANSWERS' => 'vote-yea',
            'ICON_POLLS_RESULTS' => 'poll-h',

            // database tables
            'TABLE_POLLS' => $aModule['db_prefix'] . 'polls',
            'TABLE_POLLS_ANSWERS' => $aModule['db_prefix'] . 'polls_answers',
            'TABLE_POLLS_ANSWERS_VOTES' => $aModule['db_prefix'] . 'polls_answers_votes',
            'TABLE_POLLS_ANSWERS_VOTES_TRACK' => $aModule['db_prefix'] . 'polls_answers_votes_track',
            
            'TABLE_LINKS' => $aModule['db_prefix'] . 'links',
            'TABLE_LINKS2CONTENT' => $aModule['db_prefix'] . 'links2content',

            // database fields
            'FIELD_POLL_ID' => 'id',
            'FIELD_POLL_AUTHOR_ID' => 'author_id',
            'FIELD_POLL_CONTENT_ID' => 'content_id',
            'FIELD_POLL_TEXT' => 'text',
            'FIELD_POLL_ANSWERS' => 'answers',
            
            'FIELD_ATTACH_LINK_CONTROLS' => 'controls',

            // some params
            'PARAM_POLL_ENABLED' => true,
            'PARAM_POLL_HIDDEN_RESULTS' => false,
            'PARAM_POLL_ANONYMOUS_VOTING' => true,
            'PARAM_MULTICAT_ENABLED' => false,
            'PARAM_LINKS_ENABLED' => false,
            'PARAM_LINKS_LIMIT' => 'sys_attach_links_max',

            // objects
            'OBJECT_VOTES_POLL_ANSWERS' => $this->_sName . '_poll_answers',
            'OBJECT_FORM_ATTACH_LINK' => $this->_sName . '_attach_link',
            'OBJECT_FORM_DISPLAY_ATTACH_LINK_ADD' => $this->_sName . '_attach_link_add',
            

            // styles
            'STYLES_POLLS_EMBED_CLASS' => 'body.bx-page-iframe.bx-def-color-bg-page',
            'STYLES_POLLS_EMBED_CONTENT' => array(
                'background-color' => '#ffffff'
            ),
        );

        $this->_aJsClasses = array(
            'poll' => $this->_sClassPrefix . 'Polls',
            'links' => $this->_sClassPrefix . 'Links',
            'categories' => $this->_sClassPrefix . 'Categories'
        );

        $this->_aJsObjects = array(
            'poll' => 'o' . $this->_sClassPrefix . 'Polls',
            'links' => 'o' . $this->_sClassPrefix . 'Links',
            'categories' => 'o' . $this->_sClassPrefix . 'Categories'
        );

        $sPrefix = str_replace('_', '-', $this->_sName);
        $this->_aHtmlIds = array(
            'add_poll_popup' =>  $sPrefix . '-add-poll-popup',
            'add_poll_form_field' => $sPrefix . '-add-poll-form-field',
            'polls_showcase' => $sPrefix . '-polls-showcase-',
            'poll' => $sPrefix . '-poll-',
            'poll_content' => $sPrefix . '-poll-content-',
            'poll_view_menu' => $sPrefix . '-poll-view-menu',
            'poll_view_link_answers' => $sPrefix . '-poll-view-answers-',
            'poll_view_link_results' => $sPrefix . '-poll-view-results-',
            
            'attach_link_popup' =>  $sPrefix . '-attach-link-popup',
            'attach_link_form_field' => $sPrefix . '-attach-link-form-field-',
            'attach_link_item' => $sPrefix . '-attach-link-item-',
        );
        
        $this->_aPregPatterns = array(
            "meta_title" => "/<title>(.*)<\/title>/",
            "meta_description" => "/<meta[\s]+[^>]*?name[\s]?=[\s\"\']+description[\s\"\']+content[\s]?=[\s\"\']+(.*?)[\"\']+.*?>/",
            "url" => "/(([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%#\/\.\w\-_!\(\)]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?/"
        );

        $this->_bAttachmentsInTimeline = false;
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
