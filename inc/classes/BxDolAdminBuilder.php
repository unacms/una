<?php
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License. 
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details. 
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin, 
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/


class BxDolAdminBuilder {

    var $_aContainers;
    var $_sPostUrl;
    var $_sTable;
    var $_sFieldOrder = '`order`';
    var $_sFieldContainer = '`active`';
    var $_sFieldId = '`id`';
    var $_sFieldTitle = '`title`';

	/**
     * Constructor
	 * @param array $aContainers - containers, id as key and title as value
	 */
	function BxDolAdminBuilder($sTable, $sPostUrl, $aContainers) {
        $this->_sPostUrl = $sPostUrl;
        $this->_sTable = $sTable;
        $this->_aContainers = $aContainers;
	}

    /**
     * Process post actions here
     */
    function handlePostActions ($aData) {        
        switch ($aData['action']) {
            case 'SaveItemsOrder':
                $this->postSaveItemsOrder ($aData);
                break;
        }
    }

    /**
     * Post action: SaveItemsOrder
     */
    function postSaveItemsOrder ($aData) {        
        if (!is_array($aData['cont_names']) || !is_array($aData['cont_data']))
            return;
        foreach ($aData['cont_names'] as $i => $sKey) {
            $aIds = $this->_filterIds(explode(',', $aData['cont_data'][$i]));
            $this->saveItemsOrderForContainer($sKey, $aIds);
        }
        echo 'ok';
    }

    /**
     * Get ready to print builder page
     */
    function getBuilderPage () {

        $sContanersIds = '';
        $aContainersForTemplate = array ();
        foreach ($this->_aContainers as $sKey => $sTitle) {
            $sContanersIds .= '#bx-sort-cont-' . $sKey . ',';
            $aContainersForTemplate[] = array (
                'id' => $sKey,
                'title' => $sTitle,                
                'bx_repeat:items' => $this->_generateItemsForContainer($sKey),
            );
        }
        $a = array (
            'post_url' => $this->_sPostUrl,
            'containers_ids' => substr($sContanersIds, 0, -1),
            'bx_repeat:containers' => $aContainersForTemplate,
        );
        $this->addExternalResources ();
        return $GLOBALS['oAdmTemplate']->parseHtmlByName('mobile_builder_page.html', $a);
    }

    /**
     * Add external recources, like JS and CSS file
     */
    function addExternalResources () {
        $GLOBALS['oAdmTemplate']->addJs(array(
            'ui.core.js', 
            'ui.sortable.js'
        ));
        $GLOBALS['oAdmTemplate']->addCss(array(
            'mobile.css',
            'mobile_builder.css',
        ));
    }

    function _filterIds ($a) {
        $aRet = array ();
        foreach ($a as $iId) {
            if (!$iId || $iId < 0)
                continue;
            $aRet[] = $iId;
        }
        return $aRet;
    }

    function _generateItemsForContainer ($sKey) {
        $a = $this->getItemsForContainer ($sKey);
        foreach ($a as $i => $r) {
            $a[$i]['item'] = $this->getItem($r);
        }
        return $a;
    }

    /**
     * Override this function to return data for particular container, defined in costructor.
     * Each record must have 'id' and 'title' record at least.
     * @param $sKey container id
     * @return array
     */
    function getItemsForContainer ($sKey) {
        // !!! override this
        return array (
            array ('id' => 1, 'title' => 'Opa1'),
            array ('id' => 2, 'title' => 'Opa2'),
        );        
    }


    /**
     * Override this function to return real item for dragging
     * @param $aItem array of item properties from database
     * @return ready html
     */
    function getItem ($aItem) {
        // !!! override this 
        return $aItem['title'];
    }

    /**
     * Override this function to save items order to database, but by default it should be fine in most situations
     * @param $sKey container id
     * @param $aOrderedIds arrayy of ids in the right order
     * @return number of affected rows$aData
     */
    function saveItemsOrderForContainer($sKey, $aOrderedIds) {
        global $MySQL;

        if (!isset($this->_aContainers[$sKey]))
            return false;

        $iRet = 0;
        $i = 1;  
        foreach ($aOrderedIds as $iId) {         
            $iRet += $MySQL->query("UPDATE {$this->_sTable} SET {$this->_sFieldOrder} = $i, {$this->_sFieldContainer} = '$sKey' WHERE {$this->_sFieldId} = " . (int)$iId);
            ++$i;
        }
        return $iRet;
    }
}

