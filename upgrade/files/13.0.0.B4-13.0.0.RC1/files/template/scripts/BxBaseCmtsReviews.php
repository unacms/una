<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * @see BxDolCmtsReviews
 */
class BxBaseCmtsReviews extends BxDolCmtsReviews
{
    function __construct( $sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);

        $this->_sTmplNameItemContent = 'review_content.html';

        $this->_sJsObjClass = 'BxDolCmtsReviews';
    }

    public function getStylePrefix()
    {
        return $this->_sStylePrefix;
    }
    
    public function getComment($mixedCmt, $aBp = array(), $aDp = array())
    {
        $sResult = parent::getComment($mixedCmt, $aBp, $aDp);
        if(empty($sResult))
            return $sResult;

        $sMood = $this->_getMood($mixedCmt, $aDp);
        return $this->_oTemplate->parseHtmlByContent($sResult, array(
            'bx_if:show_mood' => array(
                'condition' => !empty($sMood),
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'mood' => $sMood
                )
            )
        ));
    }
    
    function getRatingBlock($aDp = array())
    {
        $mixedResult = $this->isViewAllowed();
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return $mixedResult;

        $aBp = array();
        $this->_getParams($aBp, $aDp);

        $iId = $this->getId();
        $aStats = $this->_oQuery->getReviewsStats($iId);

        if(!empty($aStats) && is_array($aStats) && (int)$aStats['count'] > 0)
            $sContent = $this->_oTemplate->parseHtmlByName('reviews_rating_block.html', array(
                'style_prefix' => $this->_sStylePrefix,
                'system' => $this->_sSystem,
                'id' => $iId,
                'content' => $this->_oTemplate->parseHtmlByName('review_mood_legend.html', array_merge(array(
                    'style_prefix' => $this->_sStylePrefix,
                    'html_id' => $this->getRatingLegendId(),
                    'value' => (float)$aStats['avg'],
                    'bx_if:show_init' => array(
                        'condition' => false,
                        'content' => array()
                    )
                ), $this->_getTmplVarsStars())),
                'script' => $this->getJsScript($aBp, $aDp)
            ));
        else
            $sContent = MsgBox(_t('_cmt_rvw_txt_empty'));

        $sCaption = _t($this->_aT['block_rating_title']);
        return $aDp['in_designbox'] ? DesignBoxContent($sCaption, $sContent) : array(
            'title' => $sCaption,
            'content' => $sContent
        );
    }

    protected function _getForm($sAction, $iId)
    {
        $oForm = parent::_getForm($sAction, $iId);
        
        switch($sAction) {
            case BX_CMT_ACTION_POST:
                if(!empty($iId))
                    unset($oForm->aInputs['cmt_mood']);
                break;

            case BX_CMT_ACTION_EDIT:
                $aCmt = $this->getCommentSimple($iId);
                if(!empty($aCmt['cmt_parent_id']))
                    unset($oForm->aInputs['cmt_mood']);
                break;
        }

        return $oForm;
    }

    protected function _getContent($aCmt, $aBp = [], $aDp = [])
    {
        $sMood = $this->_getMood($aCmt, $aDp);
        $sContent = parent::_getContent($aCmt, $aBp, $aDp);
        return $this->_oTemplate->parseHtmlByContent($sContent, [
            'bx_if:show_mood' => [
                'condition' => !empty($sMood),
                'content' => [
                    'style_prefix' => $this->_sStylePrefix,
                    'mood' => $sMood
                ]
            ]
        ]);
    }

    protected function _getMood(&$aCmt, $aDp = array())
    {
        if(!is_array($aCmt))
            $aCmt = $this->getCommentSimple((int)$aCmt);

        if((int)$aCmt['cmt_parent_id'] > 0)
            return '';

        $sHtmlId = $this->getMoodLegendId($aCmt['cmt_id']);
        return $this->_oTemplate->parseHtmlByName('review_mood_legend.html', array_merge(array(
            'style_prefix' => $this->_sStylePrefix,
            'html_id' => $sHtmlId,
            'value' => (int)$aCmt['cmt_mood'],
            'bx_if:show_init' => array(
                'condition' => isset($aDp['dynamic_mode']) && (bool)$aDp['dynamic_mode'],
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'js_object' => $this->_sJsObjName,
                    'html_id' => $sHtmlId,
                )
            )
        ), $this->_getTmplVarsStars()));
    }

    protected function _getTmplVarsStars() {
        $aTmplVarsStars = $aTmplVarsSlider = array();
        for($i = $this->_iMoodMinValue; $i <= $this->_iMoodMaxValue; $i++) {
            $aTmplVarsStars[] = array(
                'style_prefix' => $this->_sStylePrefix,
                'value' => $i
            );

            $aTmplVarsSlider[] = array(
                'style_prefix' => $this->_sStylePrefix
            );
        }

        return array(
            'bx_repeat:stars' => $aTmplVarsStars,
            'bx_repeat:slider' => $aTmplVarsSlider
        );
    }
}

/** @} */
