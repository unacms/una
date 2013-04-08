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

    require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolConfig.php');

    class BxSimpleMessengerConfig extends BxDolConfig
    {
        // contain Db table's name ;
        var $sTablePrefix;
        var $iUpdateTime;
        var $iVisibleMessages;
        var $iCountRetMessages;
        var $iCountAllowedChatBoxes;
        var $sOutputBlock;
        var $sOutputBlockPrefix;
        var $bSaveChatHistory;
        var $bProccesSmiles;
        var $iBlinkCounter;
        var $sMessageDateFormat;

        /**
         * Class constructor;
         */
        function BxSimpleMessengerConfig( $aModule )
        {
            parent::BxDolConfig($aModule);

            // define the tables prefix ;
            $this -> sTablePrefix = $this -> getDbPrefix();

            // time (in seconds) script checks for messages ;
            $this -> iUpdateTime       = getParam('simple_messenger_update_time');

            // number of visible messages into chat box ;
            $this -> iVisibleMessages  = getParam('simple_messenger_visible_messages');

            // limit of returning messages in message box;
            $this -> iCountRetMessages = 10;

            // flashing signals amount of the non-active window ;
            $this -> iBlinkCounter = getParam('simple_messenger_blink_counter');

            // save messenger's chat history ;
            $this -> bSaveChatHistory = false;

            // allow to procces some smiles code;
            $this -> bProccesSmiles   = getParam('simple_messenger_procces_smiles');

            // contains block's id where the list of messages will be generated ;
            $this -> sOutputBlock = 'extra_area';

            // contain history block's prefix (need for defines the last message);
            $this -> sOutputBlockPrefix = 'messages_history_';

            // number of allowed chat boxes;
            $this -> iCountAllowedChatBoxes  = getParam('simple_messenger_allowed_chatbox');

            $this -> sMessageDateFormat = getLocaleFormat(BX_DOL_LOCALE_DATE, BX_DOL_LOCALE_DB);
        }
    }