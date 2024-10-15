<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Courses Courses
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Courses module representation.
 */
class BxCoursesTemplate extends BxBaseModGroupsTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_courses';
        parent::__construct($oConfig, $oDb);
    }

    public function getCounters($aCounters)
    {
        $aTmplVars = [];
        foreach([BX_COURSES_CND_USAGE_ST, BX_COURSES_CND_USAGE_AT] as $iUsage) {
            $sUsage = $this->_oConfig->getUsageI2S($iUsage);

            $aTmplVarsCounters = [true];
            $bTmplVarsCounters = !empty($aCounters[$sUsage]) && is_array($aCounters[$sUsage]);
            if($bTmplVarsCounters) {
                $aTmplVarsCounters['bx_repeat:counters_' . $sUsage] = [];
                foreach($aCounters[$sUsage] as $sModule => $iCount)
                    $aTmplVarsCounters['bx_repeat:counters_' . $sUsage][] = [
                        'title' => _t('_' . $sModule), 
                        'value' => $iCount
                    ];
            }

            $aTmplVars['bx_if:show_' . $sUsage] = [
                'condition' => $bTmplVarsCounters,
                'content' => $aTmplVarsCounters
            ];
        }

        return $this->parseHtmlByName('counters.html', $aTmplVars);
    }
    
    public function entryStructureByLevel($aContentInfo, $aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

        if(!isset($aParams['level']))
            return '';

        $iContentId = (int)$aContentInfo[$CNF['FIELD_ID']];
        $iProfileId = bx_get_logged_profile_id();

        $oPermalink = BxDolPermalinks::getInstance();

        $iLevel = (int)$aParams['level'];
        $iSelected = (int)$aParams['selected'];
        $iStart = isset($aParams['start']) ? (int)$aParams['start'] : 0;
        $iPerPage = isset($aParams['per_page']) ? (int)$aParams['per_page'] : 0;

        $aNodes = $this->_oDb->getContentStructure([
            'sample' => 'entry_id_full', 
            'entry_id' => $iContentId, 
            'level' => $iLevel, 
            'status' => 'active',
            'start' => $iStart, 
            'per_page' => $iPerPage ? $iPerPage + 1 : 0
        ]);

        if(empty($aNodes) || !is_array($aNodes))
            return '';

        $iLevelMax = $this->_oConfig->getContentLevelMax();
        $aLevelToNodePl = $this->_oConfig->getContentLevel2Node(false);

        $sTmplKeysSelected = $this->_bIsApi ? 'selected' : 'bx_if:selected';
        $sTmplKeysCounters = $this->_bIsApi ? 'counters' : 'bx_repeat:counters';
        
        $aTmplVarsNodes = [];
        foreach($aNodes as $iKey => $aNode) {
            $aTmplVarsCounters = [];
            for($i = $iLevel + 1; $i <= $iLevelMax; $i++)
                $aTmplVarsCounters[] = [
                    'cn_title' => $aLevelToNodePl[$i],
                    'cn_value' => $aNode['cn_l' . $i]
                ];

            $bSelected = $aNode['node_id'] == $iSelected;
            $sLink = BX_DOL_URL_ROOT . $oPermalink->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&' . $CNF['FIELD_ID'] . '=' . $iContentId, [
                'parent_id' => $aNode['node_id']
            ]);
            $aNode = array_merge($aNode, [
                $sTmplKeysSelected => $this->_bIsApi ? $bSelected : [
                    'condition' => $bSelected,
                    'content' => [true]
                ],
                'index' => $iKey,
                'link' => $this->_bIsApi ? bx_api_get_relative_url($sLink) : $sLink,
                'status' => $this->_getNodeStatus($iProfileId, $iContentId, $aNode['node_id']),
                $sTmplKeysCounters => $aTmplVarsCounters
            ]);

            $aTmplVarsNodes[] = $this->_bIsApi ? $aNode : [
                'node' => $this->parseHtmlByName('node_l' . $iLevel . '.html', $aNode)
            ];
        }

        if($this->_bIsApi)
            return $aTmplVarsNodes;

        $oPaginate = new BxTemplPaginate([
            'start' => $iStart,
            'per_page' => $iPerPage,
            'on_change_page' => "return !loadDynamicBlockAutoPaginate(this, '{start}', '{per_page}')"
        ]);
        $oPaginate->setNumFromDataArray($aTmplVarsNodes);

        return $this->parseHtmlByName('nodes_l' . $iLevel . '.html', [
            'level' => $iLevel,
            'bx_repeat:nodes' => $aTmplVarsNodes,
            'paginate' => $oPaginate->getSimplePaginate()
        ]);
    }

    /**
     * For 1 level bases structure ( Max Level = 1)
     */
    public function entryStructureByParentMl1($aContentInfo, $aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

        $iContentId = (int)$aContentInfo[$CNF['FIELD_ID']];
        $iParentId = isset($aParams['parent_id']) ? (int)$aParams['parent_id'] : 0;
        $iProfileId = bx_get_logged_profile_id();

        $aNodes = $this->_oDb->getContentStructure([
            'sample' => 'entry_id_full', 
            'entry_id' => $iContentId, 
            'parent_id' => $iParentId,
            'status' => 'active'
        ]);

        if(empty($aNodes) || !is_array($aNodes))
            return $this->_bIsApi ? [] : '';

        $sJsObject = $this->_oConfig->getJsObject('entry');
        $oPermalink = BxDolPermalinks::getInstance();
        
        $iLevelMax = $this->_oConfig->getContentLevelMax();

        $aTmplKeysShowPass = $this->_bIsApi ? 'show_pass' : 'bx_if:show_pass';

        $aTmplVarsNodes = [];
        foreach($aNodes as $iKey => $aNode) {
            list($iPassPercent, $sPassProgress, $sPassStatus, $sPassTitle) = $this->_getNodePass($iProfileId, $iContentId, $aNode);

            $sLink = BX_DOL_URL_ROOT . $oPermalink->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY_NODE'] . '&id=' . $iContentId, [
                'node_id' => $aNode['node_id']
            ]);

            $bShowPass = !empty($sPassTitle);

            $aNode = array_merge($aNode, [
                'level_max' => $iLevelMax,
                'index' => $iKey,
                'link' => $this->_bIsApi ? bx_api_get_relative_url($sLink) : $sLink,
                'pass_percent' => $iPassPercent,
                'pass_progress' => $sPassProgress,
                'pass_status' => $sPassStatus,
                $aTmplKeysShowPass => $this->_bIsApi ? $bShowPass : [
                    'condition' => $bShowPass,
                    'content' => [
                        'js_object' => $sJsObject,
                        'id' => $aNode['node_id'],
                        'pass_href' => $sLink,
                        'pass_title' => $sPassTitle,
                    ]
                ]
            ]);

            $aTmplVarsNodes[] = $this->_bIsApi ? $aNode : [
                'node' => $this->parseHtmlByName('ml' . $iLevelMax . '_node_l' . $aNode['level'] . '.html', $aNode)
            ];
        }

        if($this->_bIsApi)
            return $aTmplVarsNodes;

        return $this->parseHtmlByName('ml' . $iLevelMax . '_nodes_l' . $aNode['level'] . '.html', [
            'level_max' => $iLevelMax,
            'bx_repeat:nodes' => $aTmplVarsNodes
        ]);
    }

    /**
     * For 2 levels bases structure ( Max Level = 2)
     */
    public function entryStructureByParentMl2($aContentInfo, $aParams = [])
    {
        if(empty($aParams['parent_id']))
            return $this->_bIsApi ? [] : '';

        return $this->entryStructureByParentMl1($aContentInfo, $aParams);
    }

    /**
     * For 3 levels bases structure ( Max Level = 3)
     */
    public function entryStructureByParentMl3($aContentInfo, $aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParams['parent_id']))
            return '';

        $iContentId = (int)$aContentInfo[$CNF['FIELD_ID']];
        $iParentId = (int)$aParams['parent_id'];
        $iProfileId = bx_get_logged_profile_id();

        $aNodes = $this->_oDb->getContentStructure([
            'sample' => 'entry_id_full', 
            'entry_id' => $iContentId, 
            'parent_id' => $iParentId,
            'status' => 'active'
        ]);

        if(empty($aNodes) || !is_array($aNodes))
            return '';

        $sJsObject = $this->_oConfig->getJsObject('entry');
        $oPermalink = BxDolPermalinks::getInstance();

        $aInputs = [];
        foreach($aNodes as $aNode) {
            $aInputs['node_' . $aNode['node_id']] = [
                'type' => 'block_header',
                'caption' => bx_process_output($aNode['title']),
                'collapsed' => false,
                'attrs' => ['id' => 'node_' . $aNode['node_id'], 'class' => ''],
            ];

            $aSubNodes = $this->_oDb->getContentStructure([
                'sample' => 'entry_id_full', 
                'entry_id' => $iContentId, 
                'parent_id' => (int)$aNode['node_id'], 
                'status' => 'active'
            ]);

            if(!empty($aSubNodes) && is_array($aSubNodes)) {
                $aTmplVarsNodes = [];
                foreach($aSubNodes as $iKey => $aSubNode) {
                    list($iPassPercent, $sPassProgress, $sPassStatus, $sPassTitle) = $this->_getNodePass($iProfileId, $iContentId, $aSubNode);

                    $sLink = BX_DOL_URL_ROOT . $oPermalink->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY_NODE'] . '&id=' . $iContentId, [
                        'node_id' => $aSubNode['node_id']
                    ]);

                    $aTmplVarsNodes[] = [
                        'node' => $this->parseHtmlByName('ml3_node_l' . $aSubNode['level'] . '.html', array_merge($aSubNode, [
                            'index' => $iKey,
                            'link' => $sLink,
                            'pass_percent' => $iPassPercent,
                            'pass_progress' => $sPassProgress,
                            'pass_status' => $sPassStatus,
                            'bx_if:show_pass' => [
                                'condition' => $sPassTitle,
                                'content' => [
                                    'js_object' => $sJsObject,
                                    'id' => $aSubNode['node_id'],
                                    'pass_href' => $sLink,
                                    'pass_title' => $sPassTitle,
                                ]
                            ]
                        ]))
                    ];
                }

                $sInput = 'node_' . $aNode['node_id'] . '_subnodes';
                $aInputs[$sInput] = [
                    'type' => 'custom',
                    'name' => $sInput,
                    'caption' => '',
                    'content' => $this->parseHtmlByName('ml3_nodes_l' . $aSubNode['level'] . '.html', [
                        'bx_repeat:nodes' => $aTmplVarsNodes
                    ]),
                ];
            }
            else
                $aInputs['node_' . $aNode['node_id']]['collapsed'] = true;
        }

        $oForm = new BxTemplFormView([
            'form_attrs' => [
                'id' => 'bx-courses-structure-by-parent-' . $iParentId,
            ],
            'inputs' => $aInputs,
        ]);
        $oForm->setShowEmptySections(true);

        return $oForm->getCode();
    }

    public function entryNode($aContentInfo, $aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

        if(!isset($aParams['node_id']))
            return '';
        
        $sJsObject = $this->_oConfig->getJsObject('entry');

        $iContentId = (int)$aContentInfo[$CNF['FIELD_ID']];
        $iNodeId = (int)$aParams['node_id'];
        $iUsage = isset($aParams['usage']) && $aParams['usage'] !== false ? (int)$aParams['usage'] : BX_COURSES_CND_USAGE_ST;
        $iProfileId = bx_get_logged_profile_id();

        $aNode = $this->_oDb->getContentNodes([
            'sample' => 'id_full', 
            'id' => $iNodeId,
        ]);

        $aDataItems = $this->_oDb->getContentData([
            'sample' => 'entry_node_ids', 
            'entry_id' => $iContentId,
            'node_id' => $iNodeId,
            'usage' => $iUsage
        ]);

        $sTxtUndefined = _t('_undefined');

        $aTmplVarsItems = [];
        if(!empty($aDataItems) && is_array($aDataItems))
            foreach($aDataItems as $iIndex => $aDataItem) {
                $sImageUrl = '';
                if(($sMethod = 'get_thumb') && bx_is_srv($aDataItem['content_type'], $sMethod))
                    $sImageUrl = bx_srv($aDataItem['content_type'], $sMethod, [$aDataItem['content_id']]);

                $sLink = '';
                if(($sMethod = 'get_link') && bx_is_srv($aDataItem['content_type'], $sMethod))
                    $sLink = bx_srv($aDataItem['content_type'], $sMethod, [$aDataItem['content_id']]);

                $aTmplVarsPass = [true];
                $bTmplVarsPass = $iUsage == BX_COURSES_CND_USAGE_ST && $sLink && !$this->_oModule->isDataPassed($iProfileId, $aDataItem) && ((int)$aNode['passing'] == 0 || $iIndex == 0 || $this->_oModule->isDataPassed($iProfileId, $aDataItems[$iIndex - 1]));
                if($bTmplVarsPass) {
                    $aTmplVarsPass = [
                        'js_object' => $sJsObject,
                        'id' => $aDataItem['id'],
                        'link' => $sLink
                    ];
                }

                $sType = _t('_bx_courses_txt_data_type_' . $aDataItem['content_type']);

                $sTitle = '';
                if(($sMethod = 'get_title') && bx_is_srv($aDataItem['content_type'], $sMethod))              
                    $sTitle = bx_srv($aDataItem['content_type'], $sMethod, [$aDataItem['content_id']]);
                if(!$sTitle && ($sMethod = 'get_text') &&  bx_is_srv($aDataItem['content_type'], $sMethod))
                    $sTitle = bx_srv($aDataItem['content_type'], $sMethod, [$aDataItem['content_id']]);
                if(!$sTitle)
                    $sTitle = $sTxtUndefined;

                $bTmplVarsShowLink = $iUsage == BX_COURSES_CND_USAGE_AT && $sLink;
                $aTmplVarsShowLink = $bTmplVarsShowLink ? [
                    'link' => $sLink,
                    'title' => $sTitle
                ] : [true];

                $aTmplVarsItems[] = [
                    'bx_if:show_image' => [
                        'condition' => $sImageUrl,
                        'content' => [
                            'image' => $sImageUrl
                        ]
                    ],
                    'bx_if:show_image_empty' => [
                        'condition' => !$sImageUrl,
                        'content' => [
                            'type' => $sType
                        ]
                    ],
                    'type' => $sType,
                    'bx_if:show_link' => [
                        'condition' => $bTmplVarsShowLink,
                        'content' => $aTmplVarsShowLink
                    ],
                    'bx_if:show_text' => [
                        'condition' => !$bTmplVarsShowLink,
                        'content' => [
                            'title' => $sTitle,
                        ]
                    ],
                    'bx_if:show_pass' => [
                        'condition' => $bTmplVarsPass,
                        'content' => $aTmplVarsPass
                    ]
                ];
            }

        $sMiName = 'node-data-';
        $sMiLink = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY_NODE'] . '&id=' . $iContentId, [
            'node_id' => $iNodeId,
            'usage' => ''
        ]);

        $oMenu = new BxTemplMenu([
            'template' => 'menu_block_submenu_ver.html', 
            'menu_id'=> 'node-data', 
            'menu_items' => [
                ['id' => $sMiName . 'st', 'name' => $sMiName . 'st', 'class' => '', 'link' => $sMiLink . '0', 'target' => '_self', 'title' => _t('_bx_courses_menu_item_title_node_data_steps')],
                ['id' => $sMiName . 'at', 'name' => $sMiName . 'at', 'class' => '', 'link' => $sMiLink . '1', 'target' => '_self', 'title' => _t('_bx_courses_menu_item_title_node_data_attachments')]
            ]
        ]);
        $oMenu->setSelected('', $sMiName . $this->_oConfig->getUsageI2S($iUsage));

        return [
            'content' => $this->parseHtmlByName('node_view.html', [
                'index' => $aNode['order'],
                'sample' => _t('_bx_courses_txt_sample_l' . $aNode['level'] . '_single'),
                'title' => $aNode['title'],
                'text' => $aNode['text'],
                'bx_repeat:items' => $aTmplVarsItems
            ]),
            'menu' => $oMenu
        ];
    }

    protected function _getNodeStatus($iProfileId, $iContentId, $iNodeId)
    {
        if($this->_oModule->isNodePassed($iProfileId, $iNodeId))
            return _t('_bx_courses_txt_status_completed');

        if($this->_oModule->isNodeStarted($iProfileId, $iNodeId))
            return _t('_bx_courses_txt_status_in_process');
        
        return _t('_bx_courses_txt_status_not_started');
    }

    protected function _getNodePass($iProfileId, $iContentId, $aNode)
    {
        $iTotal = 0;
        $iPassCount = $iPassPercent = 0;
        $sPassStatus = $sPassTitle = '';
        if(($iTotal = $this->_oModule->getDataTotalByNode($aNode)) != 0) {
            $aUserTrack = $this->_oDb->getContentData([
                'sample' => 'user_track', 
                'entry_id' => $iContentId, 
                'node_id' => $aNode['node_id'], 
                'profile_id' => $iProfileId
            ]);

            $iPassCount = count($aUserTrack);
            $iPassPercent = round(100 * $iPassCount/$iTotal);
            
            if($iPassCount == 0) {
                $sPassStatus = '_bx_courses_txt_status_not_started';
                $sPassTitle = '_bx_courses_txt_pass_start';
            }
            else {
                if($iPassCount != $iTotal) {
                    $sPassStatus = '_bx_courses_txt_status_in_process';
                    $sPassTitle = '_bx_courses_txt_pass_continue';
                }
                else {
                    $sPassStatus = '_bx_courses_txt_status_completed';
                    $sPassTitle = '_bx_courses_txt_pass_again';
                }
            }
        }

        return [
            $iPassPercent,
            _t('_bx_courses_txt_n_m_steps', $iPassCount, $iTotal),
            _t($sPassStatus ? $sPassStatus : '_undefined'),
            _t($sPassTitle)
        ];
    }
}

/** @} */
