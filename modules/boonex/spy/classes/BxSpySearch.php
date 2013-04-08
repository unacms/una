<?php

    /***************************************************************************
    *                            Dolphin Smart Community Builder
    *                              -------------------
    *     begin                : Mon Mar 23 2006
    *     copyright            : (C) 2007 BoonEx Group
    *     website              : http://www.boonex.com
    * This file is part of Dolphin - Smart Community Builder
    *
    * Dolphin is free software; you can redistribute it and/or modify it under
    * the terms of the GNU General Public License as published by the
    * Free Software Foundation; either version 2 of the
    * License, or  any later version.
    *
    * Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
    * without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    * See the GNU General Public License for more details.
    * You should have received a copy of the GNU General Public License along with Dolphin,
    * see license.txt file; if not, write to marketing@boonex.com
    ***************************************************************************/
    require_once( BX_DIRECTORY_PATH_ROOT . 'templates/tmpl_' . $GLOBALS['tmpl'] . '/scripts/BxTemplSearchResultText.php');
    require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolPaginate.php' );
    require_once( 'BxSpyModule.php' );

    class BxSpySearch extends BxTemplSearchResultText
    {
        var $oSpyObject;
        var $aModule;

        /**
         * Class constructor ;
         */
        function BxSpySearch($oSpyObject = null)
        {
            // call the parent constructor ;
            parent::BxTemplSearchResultText();

            if(!$oSpyObject) {
                $this -> oSpyObject = BxDolModule::getInstance('bx_spy');
            }
            else {
                $this -> oSpyObject = $oSpyObject;
            }

            // init some needed db table's fields ;

            /* main settings for shared modules
               ownFields - fields which will be got from main table ($this->aCurrent['table'])
               searchFields - fields which using for full text key search
               join - array of join tables
                    join array (
                        'type' - type of join
                        'table' - join table
                        'mainField' - field from main table for 'on' condition
                        'onField' - field from joining table for 'on' condition
                        'joinFields' - array of fields from joining table
                    )
            */

            $this -> aCurrent = array (

                // module name ;
                'name'  => 'spy',
                'title' => '_bx_spy',
                'table' => $this -> oSpyObject -> _oDb -> sTablePrefix . 'data',

                'ownFields'     => array('id', 'sender_id', 'lang_key', 'params', 'date'),

                'join' => array(
                    'profile' => array(
                        'type' => 'left',
                        'table' => 'Profiles',
                        'mainField' => 'sender_id',
                        'onField' => 'ID',
                        'joinFields' => array('NickName'),
                    ),
                ),

                'restriction' => array (
                    'global'   => array('value'=>'', 'field'=>'', 'operator'=>'='),
                    'friends'  => array('value' => '', 'field' => 'friend_id', 'operator'=>'=', 'table' => $this -> oSpyObject -> _oDb -> sTablePrefix . 'friends_data'),
                    'no_my'    => array('value'=>'', 'field'=>'sender_id', 'operator'=>'<>', 'table' => $this -> oSpyObject -> _oDb -> sTablePrefix . 'data'),
                    'over_id'  => array('value'=>'', 'field'=>'id', 'operator'=>'>', 'table' => $this -> oSpyObject -> _oDb -> sTablePrefix . 'data'),
                    'type'     => array('value'=>'', 'field'=>'type', 'operator'=>'=', 'table' => $this -> oSpyObject -> _oDb -> sTablePrefix . 'data'),
                    'only_me'  => array('value'=>'', 'field'=>'recipient_id', 'operator'=>'=', 'table' => $this -> oSpyObject -> _oDb -> sTablePrefix . 'data'),
                    'viewed'   => array('value'=>'', 'field'=>'viewed', 'operator'=>'in', 'table' => $this -> oSpyObject -> _oDb -> sTablePrefix . 'data'),
                ),

                'paginate' => array( 'perPage' => $this -> oSpyObject -> _oConfig -> iPerPage, 'page' => 1, 'totalNum' => 10, 'totalPages' => 1),
                'sorting' => 'last',
                'view' => 'full',
                'ident' => 'id'
            );
        }

        /**
         * Function will generate page's pagination;
         *
         * @param  : $sModulePath (string) - path to current module;
         * @return : (text) - html presentation data;
         */
        function showPagination($sModulePath, $sScript = null)
        {
            $aParameters['settings'] = array(
                'count'             => $this -> aCurrent['paginate']['totalNum'],
                'per_page'          => $this -> aCurrent['paginate']['perPage'],
                'page'              => $this -> aCurrent['paginate']['page'],
                'per_page_changer'  => true,
                'page_reloader'     => true,
            );

            $aParameters['settings']['page_url']            = $sModulePath . '&page={page}&per_page={per_page}';
            $aParameters['settings']['on_change_page']      = $sScript ? $sScript : null;
            $aParameters['settings']['on_change_per_page']  = null;

            $oPaginate = new BxDolPaginate( array_shift($aParameters) );
            $sPaginate = '<div class="clear_both"></div>' . $oPaginate -> getSimplePaginate(null, -1, -1, false);

            return $sPaginate;
        }

        function getAlterOrder ()
        {/*
            return array(
                'groupBy' => " GROUP BY `{$this -> oSpyObject -> _oDb -> sTablePrefix}events`.`id`",
            );*/
        }

        function getLimit () {
            if( isset($this->aCurrent['paginate']['unlimit']) ) {
                return;
            }
            else if( isset($this->aCurrent['paginate']['limit']) ){
                return 'LIMIT ' . $this->aCurrent['paginate']['limit'];
            }
            else if (isset($this->aCurrent['paginate'])) {
                $sqlFrom = ( $this->aCurrent['paginate']['page'] - 1 ) * $this->aCurrent['paginate']['perPage'];
                $sqlTo = $this->aCurrent['paginate']['perPage'];
                return 'LIMIT ' . $sqlFrom .', '.$sqlTo;
            }
        }
    }