<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

define('BX_DOL_PAGINATE_PER_PAGE_DEFAULT', 10);

/**
 * Paginage for any content.
 * It is used to create paginate, configuring it via input parameters.
 * Paginate don't support total number of pages, moreover it is not recommended to count all records - it slows down the site.
 * To correctly determine last page we need to pass number of available records on the current page plus one - so we always know if next page is available.
 *
 *
 * Two types of paginate presentation is supported:
 * - getPaginate() - to get default paginate, it is better to use it on the whole page.
 * - getSimplePaginate() - to get limited paginate, it is better to use in some boxes, where availabel space is limited or for ajax paginate.
 *
 *
 * The list of available input parameters:
 *
 *
 * Parameters:
 * - start - position of the first item.
 * - num - number of available items on the page, it should be number of items per page + 1 (+1 is needed to correctly determine last page). It is possible to set this value automatically @see setNumFromDataArray.
 * - per_page - number of items displayed on the page.
 * - page_url - page URL to go through pages, special markers are automatically replaced.
 * - on_change_page - JavaScript code to be called on change page.
 * - info - display info.
 * - view_all_url - URL for 'view all' page. This url is not showed by default. It is convinient to use with @see getSimplePaginate.
 * - view_all_caption - optional caption for 'view all' link.
 *
 *
 * Available markers to replace in 'page_url' and 'on_change_page' parameters:
 * - {per_page} - current number of items to display per page.
 * - {start} - the number to display items starting from.
 *
 *
 * Example of usage:
 * @code
 * $oPaginate = new BxDolPaginate(array(
 *      'start' => 0,
 *      'num' => 11,
 *      'per_page' => 10,
 *      'on_change_page' => 'changePage({start}, {per_page})'
 * ));
 * $oPaginate->getPaginate();
 * @endcode
 *
 *
 * Memberships/ACL:
 * Doesn't depend on user's membership.
 *
 *
 * Alerts:
 * no alerts available
 *
 */
abstract class BxDolPaginate extends BxDol
{
    protected static $_isCssAdded = false;

    protected $_oTemplate;

    protected $_iStart; ///< start display items from this number
    protected $_iNum; ///< available results, you need to query per_page + 1 results, so paginate can determine last page
    protected $_iPerPage; ///< results per page

    protected $_sPageUrl; ///< page url of next/prev page, special markers will be replaced here automatically
    protected $_sOnChangePage; ///< on click of next/prev page, special markers will be replaced here automatically
    protected $_bInfo; ///< show displayed items info

    protected $_sViewAllUrl; ///< view "all results" url, for "simple" paginate
    protected $_sViewAllCaption; ///< 'view all' link caption

    protected $_sButtonsClass; ///< add this class to buttons class attribute
    protected $_sPaginateClass; ///< add this class to whole paginate container div

    /**
     * Constructor
     */
    public function __construct($aParams, $oTemplate = null)
    {
        parent::__construct();

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();

        if (isset($aParams['count']))
           trigger_error ('Paginate "count" is deprecated - use "num" instead: ' . get_class($this), E_USER_ERROR);

        //--- Main settings ---//
        $this->_iStart = isset($aParams['start']) && (int)$aParams['start'] > 0 ? (int)$aParams['start'] : 0;
        $this->_iNum = isset($aParams['num']) ? (int)$aParams['num'] : 0;
        $this->_iPerPage = isset($aParams['per_page']) && (int)$aParams['per_page'] > 0 ? (int)$aParams['per_page'] : BX_DOL_PAGINATE_PER_PAGE_DEFAULT;

        $this->_bInfo = isset($aParams['info']) ? (bool)$aParams['info'] : true;
        $this->_sButtonsClass = isset($aParams['buttons_class']) ? $aParams['buttons_class'] : '';
        $this->_sViewAllUrl = isset($aParams['view_all_url']) ? $aParams['view_all_url'] : false;
        $this->_sViewAllCaption = isset($aParams['view_all_caption']) ? $aParams['view_all_caption'] : _t('_sys_paginate_view_all');

        $this->_sPaginateClass = isset($aParams['paginate_class']) ? $aParams['paginate_class'] : '';

        // page url
        $this->_sPageUrl = isset($aParams['page_url']) ? $aParams['page_url'] : BX_DOL_URL_ROOT;

        // on click (js mode)
        $this->_sOnChangePage = isset($aParams['on_change_page']) ? $aParams['on_change_page'] : '';
    }

    /**
     * Get number of available results per page. If it is not last page,
     * then this number is number of result per page plus one.
     * @return integer
     */
    public function getNum()
    {
        return $this->_iNum;
    }

    /**
     * Set number of available items on the page directly from data array.
     * Since data array should contain additional record - we will pop last item from array automatically.
     * @return nothing.
     */
    public function setNumFromDataArray(&$a, $isAutoPopLastElement = true)
    {
        if ($a && is_array($a))
            $this->_iNum = count($a);
        else
            $this->_iNum = 0;

        if ($this->_iNum > $this->_iPerPage)
            array_pop($a);
    }

    /**
     * Set number of available items on the page,
     * it should be number of items per page + 1 (+1 is needed to correctly determine last page).
     * It is possible to set this value automatically @see setNumFromDataArray.
     * @param $i positive integer.
     * @return true on success or false if $i param isn't correct.
     */
    public function setNum($i)
    {
        if ($i >= 0) {
            $this->_iNum = (int)$i;
            return true;
        }
        return false;
    }

    /**
     * Position, the data is showing from.
     * @return integer.
     */
    public function getStart()
    {
        return $this->_iStart;
    }

    /**
     * Set the starting position, the data is showing from.
     * @param $i positive integer.
     * @return true on siccess or false on error.
     */
    public function setStart($i)
    {
        if ($i >= 0) {
            $this->_iStart = (int)$i;
            return true;
        }
        return false;
    }

    /**
     * Get number of records per page.
     * @return integer.
     */
    public function getPerPage()
    {
        return $this->_iPerPage;
    }

    /**
     * Set number of records per page.
     * @param $i positive integer.
     * @return integer.
     */
    public function setPerPage($i)
    {
        if ((int)$i > 0) {
            $this->_iPerPage = $iPerPage;
            return true;
        }
        return false;
    }

    /**
     * Set string to pass to 'onclick' for change page button.
     * The following markers are replaced automatically:
     * - {per_page}
     * - {start}
     *
     * @param $i positive integer.
     * @return integer.
     */
    public function setOnChangePage($s)
    {
        $this->_sOnChangePage = $s;
    }

    /**
     * @return true if previous page is available, or false if not
     */
    public function isPrevAvail ()
    {
        return $this->_iStart > 0 ? true : false;
    }

    /**
     * @return true if next page is available, or false if not
     */
    public function isNextAvail ()
    {
        return $this->_iNum > $this->_iPerPage ? true : false;
    }

    /**
     * Get default paginate, it is better to use it on the whole page.
     * @param $iStart - @see setStart.
     * @param $iNum - @see setNum and @see setNumFromDataArray.
     * @param $iPerPage - @see setPerPage.
     * @return HTML string.
     */
    public function getPaginate($iStart = -1, $iNum = -1, $iPerPage = -1)
    {
        $this->setNum($iNum);
        if (!$this->_iNum)
            return '';

        $this->setStart($iStart);
        $this->setPerPage($iPerPage);

        if (0 == $this->getStart() && !$this->isNextAvail ())
            return '';

        $aReplacement = $this->_getReplacement();

        //--- Previous Page button ---//
        $sPrevLnkUrl = 'javascript:void(0);';
        $sPrevLnkClick = '';
        $sPrevClassAdd = ' bx-btn-disabled';
        if ($this->isPrevAvail()) {
            $iStartPrev = $this->_iStart - $this->_iPerPage > 0 ? $this->_iStart - $this->_iPerPage : 0;
            $aReplacementLink = array_merge($aReplacement, array('start' => $iStartPrev));
            $sPrevLnkUrl = $this->_getPageChangeUrl($aReplacementLink);
            $sPrevLnkClick = $this->_getPageChangeOnClick($aReplacementLink);
            $sPrevClassAdd = '';
        }

        //--- Next Page button ---//
        $sNextLnkUrl = 'javascript:void(0);';
        $sNextLnkClick = '';
        $sNextClassAdd = ' bx-btn-disabled';
        if ($this->isNextAvail()) {
            $aReplacementLink = array_merge($aReplacement, array('start' => $this->_iStart + $this->_iPerPage));
            $sNextLnkUrl = $this->_getPageChangeUrl($aReplacementLink);
            $sNextLnkClick = $this->_getPageChangeOnClick($aReplacementLink);
            $sNextClassAdd = '';
        }

        $sClassAdd = ($this->_sButtonsClass ? ' ' . $this->_sButtonsClass : '');

        $aVariables = array(
            'bx_if:info' => array (
                'condition' => (bool)$this->_bInfo,
                'content' => array(
                    'text' => _t('_sys_paginate_info', $this->_iStart + 1, $this->_iStart + ($this->isNextAvail () ? $this->_iPerPage : $this->_iNum)),
                ),
            ),
            'bx_if:view_all' => array(
                'condition' => (bool)$this->_sViewAllUrl,
                'content' => array(
                    'lnk_url' => $this->_sViewAllUrl,
                    'lnk_title' => $this->_sViewAllCaption,
                    'lnk_content' => $this->_sViewAllCaption,
                ),
            ),
            'btn_prev' => '<a href="' . $sPrevLnkUrl . '" ' . $sPrevLnkClick . ' class="bx-paginate-btn-prev bx-btn' . $sClassAdd . $sPrevClassAdd . '"><i class="sys-icon sys-icon-bigger angle-double-left"></i></a>',
            'btn_next' => '<a href="' . $sNextLnkUrl . '" ' . $sNextLnkClick . ' class="bx-paginate-btn-next bx-btn' . $sClassAdd . $sNextClassAdd . '"><i class="sys-icon sys-icon-bigger angle-double-right"></i></a>',
            'paginate_class' => $this->_sPaginateClass,
        );

        $this->addCssJs();
        return $this->_oTemplate->parseHtmlByName('paginate.html', $aVariables);
    }

    /**
     * Get limited paginate, it is better to use in some boxes, where availabel space is limited or for ajax paginate.
     * @param $sViewAllUrl - url to page for 'view all' link.
     * @param $iStart - @see setStart.
     * @param $iNum - @see setNum and @see setNumFromDataArray.
     * @param $iPerPage - @see setPerPage.
     * @return HTML string.
     */
    public function getSimplePaginate($sViewAllUrl = '', $iStart = -1, $iNum = -1, $iPerPage = -1)
    {
        if ($sViewAllUrl)
            $this->_sViewAllUrl = $sViewAllUrl;

        $this->_bInfo = false;
        $this->_sButtonsClass .= ($this->_sButtonsClass ? ' ' : '') . 'bx-btn-small bx-btn-symbol-small';
        $this->_sPaginateClass = 'bx-paginate-simple';

        return $this->getPaginate($iStart, $iNum, $iPerPage);
    }

    public function addCssJs ()
    {
        if (self::$_isCssAdded)
            return false;
        $this->_oTemplate->addCss('paginate.css');
        self::$_isCssAdded = true;
        return true;
    }

    protected function _getReplacement()
    {
        return array(
            'start' => $this->_iStart,
            'per_page' => $this->_iPerPage,
        );
    }

    protected function _getPageChangeUrl($aReplacement)
    {
        return $this->_oTemplate->parseHtmlByContent($this->_sPageUrl, $aReplacement, array('{', '}'));
    }

    protected function _getPageChangeOnClick($aReplacement)
    {
        return !empty($this->_sOnChangePage) ? 'onclick="javascript:' . $this->_oTemplate->parseHtmlByContent($this->_sOnChangePage, $aReplacement, array('{', '}')) . '; return false;"' : '';
    }

}

/** @} */
