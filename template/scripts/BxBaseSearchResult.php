<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

// TODO: clean it more carefully !

bx_import('BxDolSearch');
class BxBaseSearchResult extends BxDolSearchResult 
{
    public $isError;

    protected $sBrowseUrl; ///< currect browse url, used for paginate and other links in browsing

    protected $sUnitTemplate = 'unit.html'; ///< common template to try to use for displaying one item

    protected $aGetParams = array(); ///< get params to keep in paginate and other browsing links

    protected $iDesignBoxTemplate = BX_DB_PADDING_DEF; ///< design box ID to warp result in

    protected $aConstants;

    function __construct($oFunctions = false) 
    {
        parent::__construct();

        if ($oFunctions) {
            $this->oFunctions = $oFunctions;
        } else {
            bx_import('BxTemplFunctions');
            $this->oFunctions = BxTemplFunctions::getInstance();
        }
    }

    function getMain() 
    {
        // override this to return main module class
    }

    function displayResultBlock () 
    {
        $sCode = '';
        $aData = $this->getSearchData();
        if ($this->aCurrent['paginate']['num'] > 0) {
            $sCode .= $this->addCustomParts();
            foreach ($aData as $aValue) {
                $sCode .= $this->displaySearchUnit($aValue);
            }
            $sCode = '<div class="bx-search-result-block bx-clearfix">' . $sCode . '</div>';

        }
        return $sCode;
    }

    function displaySearchBox ($sCode, $sPaginate = '') 
    {
        $sTitle = _t($this->aCurrent['title']);

        $sCode = $this->oFunctions->designBoxContent($sTitle, $sCode . $sPaginate, $this->iDesignBoxTemplate, $this->getDesignBoxMenu());

        if (!isset($_GET['searchMode']))
            $sCode = '<div id="page_block_' . $this->id . '" class="bx-clearfix">' . $sCode . '</div>';

        return $sCode;
    }

    function displaySearchUnit ($aData) 
    {
        $oMain = $this->getMain();
        return $oMain->_oTemplate->unit($aData, $this->bProcessPrivateContent, $this->sUnitTemplate);
    }

    function getDesignBoxMenu () 
    {
        if (false === ($sLink = $this->getRssPageUrl ()))
            return false;

        return array(
            array('name' => 'rss', 'title' => _t('_sys_menu_title_rss'), 'link' => $sLink, 'icon' => 'rss')
        );
    }

    function getRssPageUrl () 
    {
        if (!isset($this->aCurrent['rss']) || !$this->aCurrent['rss']['link']) 
            return false;

        bx_import('BxDolPermalinks');
        $oPermalinks = BxDolPermalinks::getInstance();
        return BX_DOL_URL_ROOT . bx_append_url_params($oPermalinks->permalink($this->aCurrent['rss']['link']), 'rss=1');
    }

    function showAdminActionsPanel($sWrapperId, $aButtons, $sCheckboxName = 'entry', $bSelectAll = true, $bSelectAllChecked = false, $sCustomHtml = '') 
    {
        $aBtns = array();
        foreach ($aButtons as $k => $v) {
            if(is_array($v)) {
                $aBtns[] = $v;
                continue;
            }
            $aBtns[] = array(
                'type' => 'submit',
                'name' => $k,
                'value' => '_' == $v[0] ? _t($v) : $v,
                'onclick' => '',
            );
        }
        $aUnit = array(
            'bx_repeat:buttons' => $aBtns,
            'bx_if:customHTML' => array(
                'condition' => strlen($sCustomHtml) > 0,
                'content' => array(
                    'custom_HTML' => $sCustomHtml,
                )
            ),
            'bx_if:selectAll' => array(
                'condition' => $bSelectAll,
                'content' => array(
                    'wrapperId' => $sWrapperId,
                    'checkboxName' => $sCheckboxName,
                    'checked' => ($bSelectAll && $bSelectAllChecked ? 'checked="checked"' : '')
                )
            ),
        );
        return BxDolTemplate::getInstance()->parseHtmlByName('adminActionsPanel.html', $aUnit, array('{','}'));
    }

    function showAdminFilterPanel($sFilterValue, $sInputId = 'filter_input_id', $sCheckboxId = 'filter_checkbox_id', $sFilterName = 'filter', $sOnApply = '') 
    {
        $sFilter = _t('_sys_admin_filter');
        $sApply = _t('_sys_admin_apply');

        $sFilterValue = bx_html_attribute($sFilterValue);
        $isChecked = $sFilterValue ? ' checked="checked" ' : '';

        $sJsContent = "";
        if(empty($sOnApply)) {
            $sOnApply = 'on_filter_apply(this)';
            ob_start();
?>
    <script type="text/javascript">
        function on_filter_apply (e) {
            var s = ('' + document.location).replace (/[&]{0,1}<?=$sFilterName;?>=.*/, ''); // remove filter
            s = s.replace(/page=\d+/, 'page=1'); // goto 1st page
            if (e.checked && $('#<?=$sInputId;?>').val().length > 2)
                s += (-1 == s.indexOf('?') ? '?' : '&') + '<?=$sFilterName;?>=' + $('#<?=$sInputId;?>').val(); // append filter
            document.location = s;
        }
        function on_filter_key_up (e) {
            if (13 == e.keyCode) {
                $('#<?php echo $sCheckboxId; ?>').attr('checked', 'checked');
                on_filter_apply($('#<?php echo $sCheckboxId; ?>').get(0));
                return false;
            } else {
                $('#<?php echo $sCheckboxId; ?>').attr('checked', '');
                return true;
            }
        }
    </script>
<?
            $sJsContent = ob_get_clean();
        }

        return <<<EOF
    {$sJsContent}
    <div class="admin_filter_panel">
        <table>
            <tr>
                <td>{$sFilter}</td>
                <td>
                    <div class="input_wrapper input_wrapper_text">
                        <input type="text" id="{$sInputId}" value="{$sFilterValue}" class="form_input_text" onkeypress="return on_filter_key_up(event)" />
                        <div class="input_close input_close_text">&nbsp;</div>
                    </div>
                </td>
                <td><input type="checkbox" id="{$sCheckboxId}" $isChecked onclick="{$sOnApply}" /></td>
                <td><label for="{$sCheckboxId}">{$sApply}</label></td>
            </tr>
        </table>
    </div>
EOF;
    }

    function showPagination($bAdmin = false, $bChangePage = true, $bPageReload = true)
    {
        $oMain = $this->getMain();
        $oConfig = $oMain->_oConfig;

        $sUrlStart = $this->getCurrentUrl(array(), false);

        bx_import('BxTemplPaginate');
        $oPaginate = new BxTemplPaginate(array(
            'page_url' => $sUrlStart,
            'num' => $this->aCurrent['paginate']['num'],
            'per_page' => $this->aCurrent['paginate']['perPage'],
            'start' => $this->aCurrent['paginate']['start'],
        ));

        return $oPaginate->getPaginate();
    }

    /**
     * Get current browse URL with current page and additional params
     * @param $aAdditionalParams set custom additional params as key value pair
     * @param $bReplacePagesParams replace paginate params with current values or leave markers for use in paginate class
     * @return ready to use URL string with BX_DOL_URL_ROOT added in the beginning
     */
    protected function getCurrentUrl($aAdditionalParams = array(), $bReplacePagesParams = true) 
    {
        bx_import('BxDolPermalinks');
        $oPermalinks = BxDolPermalinks::getInstance();

        // base url
        $sUrlStart = BX_DOL_URL_ROOT . $oPermalinks->permalink($this->sBrowseUrl);

        // add pages params
        $sUrlStart = bx_append_url_params($sUrlStart, array (
            'start' => $bReplacePagesParams ? (int)$this->aCurrent['paginate']['start'] : '{start}',
            'per_page' => $bReplacePagesParams ? (int)$this->aCurrent['paginate']['perPage'] : '{per_page}',
        ));

        // add additional params
        foreach ($this->aGetParams as $sGetParam) {
            $sValue = false;
            if (isset($aAdditionalParams[$sGetParam]))
                $sValue = $aAdditionalParams[$sGetParam];
            elseif (false !== bx_get($sGetParam))
                $sValue = bx_get($sGetParam);
            if (false !== $sValue)
                $sUrlStart = bx_append_url_params($sUrlStart, $sGetParam . '=' . rawurlencode($sValue));
        }

        return $sUrlStart;
    }
    
    function showPaginationAjax($sBlockId) 
    {
        bx_import('BxTemplPaginate');
        $oPaginate = new BxTemplPaginate(array(
            'page_url' => 'javascript:void(0);',
            'num' => $this->aCurrent['paginate']['num'],
            'per_page' => $this->aCurrent['paginate']['perPage'],
            'start' => $this->aCurrent['paginate']['start'],
        ));

        return $oPaginate->getSimplePaginate(false, -1, -1, false);
    }

    function clearFilters ($aPassParams = array(), $aPassJoins = array()) 
    {
        //clear sorting
        $this->aCurrent['sorting'] = 'last';

        //clear restrictions
        foreach ($this->aCurrent['restriction'] as $sKey => $aValue) {
            if (!in_array($sKey, $aPassParams))
                $this->aCurrent['restriction'][$sKey]['value'] = '';
        }

        //clear unnecessary joins (remains only profile join)
        $aPassJoins[] = 'profile';
        $aTemp = array();
        foreach ($aPassJoins as $sValue) {
            if (isset($this->aCurrent['join'][$sValue]) && is_array($this->aCurrent['join'][$sValue]))
                $aTemp[$sValue] = $this->aCurrent['join'][$sValue];
        }
        $this->aCurrent['join'] = $aTemp;
    }

    function fillFilters ($aParams) 
    {
        // transform all given values to fields values
        if (is_array($aParams)) {
            foreach ($aParams as $sKey => $mixedValue) {
                if (isset($this->aCurrent['restriction'][$sKey]))
                    $this->aCurrent['restriction'][$sKey]['value'] = $mixedValue;
            }
        }
    }

    /**
     * Set design box template id to use to wrap search results in
     */
    function setDesignBoxTemplateId ($i) 
    {
        $this->iDesignBoxTemplate = $i;
    }
}

/** @} */
