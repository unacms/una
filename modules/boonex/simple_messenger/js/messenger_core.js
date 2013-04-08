
    function BxSimpleMessenger()
    {
        // contain some chat box's settings;
        this.chatBoxSettings = new Object(),
        // contain all defined chat's boxes ;
        this.oDefinedChatBoxes  = new Object(),
        this.systemMessages = new Object(),
        this.isSent = true,
        this.bFirstRender = false,
        this.isProcessed  = true;
        this.mCorkHandler = '';
        this.isProcessedBlocked = false;

        /**
         * Function will send message from logged member ;
         *
         * @param  : e (system event) ;
         * @param  : evElement (object) (link on current field);
         * @param  : iRecipientId (integer) - recipient's Id;
         */
        this.sendMessage = function(e, evElement, iRecipientId)
        {
            var self = this;

            if(!this.isSent) {
                alert(this.systemMessages.waitMessage);
                return;
            }

            if( !e ) {
                if( window.event ) { //Internet Explorer
                  e = window.event;
                }
                else { //total failure, we have no way of referencing the event
                  return;
                }
            }

            var n = e.keyCode ? e.keyCode : e.charCode;

            if (n == 13) { //Enter
                var sMessage = $.trim($(evElement).attr('value'));
                if ( !sMessage ) {
                    alert(this.systemMessages.emptyMessage);
                }
                else {
                    var _sRandom = Math.random();
                    this.isSent = false;

                    this.isProcessedBlocked =  true;
                    // stop the deserted notify procces;
                    clearTimeout(this.chatBoxSettings.updateTimeNotifyHandler);
                    this.chatBoxSettings.updateTimeNotifyHandler = '';

                    // send new message ;
                    $.post(this.chatBoxSettings.sPageReceiver + '/send_message/' + iRecipientId + '&_r=' + _sRandom, {message : encodeURIComponent(sMessage)},
                        function(sAnswer)
                        {
                            self.isSent = true;
                            var _self = self;

                            if(sAnswer) {
                                alert(sAnswer);
                            }
                            else {
                                // clear input field ;
                                $(evElement).attr('value', '');

                                self.loadMessageBlock( iRecipientId,
                                    function()
                                    {
                                        // show the chat's box if he is not visible;
                                        var $el = $('#' + self.chatBoxSettings.sHistoryBlockPrefix + iRecipientId);
                                        if($el.css('visibility') == 'hidden') {
                                            self.showChatWindow(self.chatBoxSettings.sHistoryBlockPrefix + iRecipientId, $el.parent());
                                        }
                                    }
                                );
                            }

                            self.isProcessedBlocked =  false;
                            self.messageNotification();
                        }
                    );
                }
            }
        },

        /**
         * Function will load message block ;
         *
         * @param  : iRecipientId (integer) - recipient id;
         * @return : Html presentation data ;
         */
        this.loadMessageBlock = function(iRecipientId, callback)
        {
            var self = this;

            // if chat box not defined;
            if ( !this.checkChatBox(iRecipientId) ) {
                var _sRandom = Math.random();

                // get new chat box ;
                $.get(this.chatBoxSettings.sPageReceiver + '/get_chat_box/' + iRecipientId + '&_r=' + _sRandom,
                    function(sData)
                    {
                        // procces received data;
                        self.proccesReceivedData(sData);

                        if(typeof callback != 'undefined') {
                            callback();
                        }
                    }
                );
            }
            else {
                if(typeof callback != 'undefined') {
                        callback();
                }
            }
        },

        /**
         * Function will check new messages for current member ;
         *
         * @param : callback (string) - callback function's name;
         */
        this.messageNotification = function(callback)
        {
            if(this.isProcessedBlocked) {
                return;
            }

            // if data not processed start the cork function;
            if(!this.isProcessed) {
                this.cork();
            }
            else {
                var self = this;
                var iDefinedChatBoxes = this.oDefinedChatBoxes.boxes.length;
                var iLastMessageId = 0;
                var _sRandom = Math.random();

                var sRegisteredChatBoxes = '';

                // procces all registered chat's boxes;
                for( var i = 0; i < iDefinedChatBoxes; i++ )
                {
                    // need define the last message's Id for current chat box;
                    iLastMessageId = this.oDefinedChatBoxes.boxes[i].last_message;
                    sRegisteredChatBoxes += this.oDefinedChatBoxes.boxes[i].box_id + ':' + iLastMessageId + ',';
                }

                // script will back chat's boxes;
                $.get(this.chatBoxSettings.sPageReceiver + '/new_messages' + '&_r=' + _sRandom, {'registered_chat_boxes': [sRegisteredChatBoxes]},
                    function(sData){

                        self.proccesReceivedData(sData);

                        if(typeof callback != 'undefined') {
                            callback();
                        }

                        if(self.isProcessed && !self.isProcessedBlocked) {
                            // start notificator again ;
                            self.chatBoxSettings.updateTimeNotifyHandler = setTimeout(function(){
                                self.messageNotification();
                            },self.chatBoxSettings.updateTime);
                        }
                    }
                );
            }
        },

        this.cork = function()
        {
            var self = this;

            if(this.isProcessed && !self.isProcessedBlocked) {
                clearInterval(this.mCorkHandler);
                this.messageNotification();
            }
            else {
                this.mCorkHandler = setTimeout(function(){
                    self.cork();
                }, 100);
            }
        },

        /**
         * Function will stop the active flashing signal;
         *
         * @param : iChatBoxIndex (integer) - chat box's index;
         */
        this.stopBlink = function(iChatBoxIndex)
        {
            if( typeof this.oDefinedChatBoxes.boxes[iChatBoxIndex] != 'undefined'){
                clearInterval(this.oDefinedChatBoxes.boxes[iChatBoxIndex].blink_handler);
                this.oDefinedChatBoxes.boxes[iChatBoxIndex].blink_handler ='';
                this.oDefinedChatBoxes.boxes[iChatBoxIndex].blink_value   ='';
            }
        },

        /**
         * Function will set the blink mode for reciving block;
         *
         * @param : iChatBoxIndex (integer) - object id;
         * @param : oBlockId (object);
         */
        this.setBlinkMode = function(iChatBoxIndex, $oBlock)
        {
            var self = this;

            // call the flash effect;
            var rHandler = setInterval(
                function()
                {
                    // define the chat box's blink handler;
                    self.oDefinedChatBoxes.boxes[iChatBoxIndex].blink_handler = rHandler;

                    // define the first value for flash effect;
                    if(!self.oDefinedChatBoxes.boxes[iChatBoxIndex].blink_value) {
                        self.oDefinedChatBoxes.boxes[iChatBoxIndex].blink_value = 0;
                    }

                    var $oParents = $oBlock.parents('.' + self.chatBoxSettings.sChatBox + ':first');
                    // set chat box's background as colored;
                    $oParents.addClass('active_chat_block_blink_effect');

                    // set chat box's background as not colored;
                    setTimeout(
                        function()
                        {
                            $oParents.removeClass('active_chat_block_blink_effect');

                            //increment the blink value;
                            self.oDefinedChatBoxes.boxes[iChatBoxIndex].blink_value++;

                            if( self.oDefinedChatBoxes.boxes[iChatBoxIndex].blink_value >= self.chatBoxSettings.iMaxBlinkCounter) {
                                if( !$oParents.hasClass('active_chat_block_blink_effect') ) {
                                    $oParents.addClass('active_chat_block_blink_effect');
                                }

                                self.stopBlink(iChatBoxIndex);
                                return;
                            }
                        },
                        500
                    );

                }, 1000
            );
        },

        /**
         * Function will scroll content;
         *
         * @param : oEl (object) - message's container;
         */
        this.elScroll = function(oEl)
        {
            // define the chat boxe's childrens height;
            iHeight = this.getChatBoxContentHeight(oEl);
            oEl.scrollTop(iHeight);
        },

        /**
         * Function will procces and evaluate received code (data must will be in JSON format);
         *
         * @param : sData (string) - data that need to procces and eveluate ;
         */
        this.proccesReceivedData = function(sData)
        {
            if(this.isProcessedBlocked) {
                return;
            }

            // if page receiver return some content;
            if (sData)  {
                this.isProcessed = false;
                var self = this;

                var oResponse = eval("(" + sData + ")");
                var iResponseCount = oResponse.senders.length;

                for( var i = 0; i < iResponseCount; i++ )
                {
                    // define sender's id;
                    var iSenderId  = oResponse.senders[i].sender_id;

                    if(!iSenderId) {
                        continue;
                    }

                    // define the object index;
                    var iChatBoxIndex   = this.getChatBoxObjectIndex(iSenderId);

                    // set as defined received chat box if not registered;
                    var bBoxUndefined = false;
                    if ( !this.checkChatBox(iSenderId) )
                    {
                        this.oDefinedChatBoxes.boxes[this.oDefinedChatBoxes.boxes.length] =
                            {
                                'box_id'             : iSenderId,
                                'status'             : oResponse.senders[i].sender_status,
                                'status_text_update' : oResponse.senders[i].status_update_time,
                                'blink_handler'      : 0,
                                'blink_value'        : 0,
                                'last_message'       : parseInt(oResponse.senders[i].last_message),
                                'count_messages'     : oResponse.senders[i].count_messages
                            }

                            bBoxUndefined = true;
                    }

                    // check received data ;
                    if ( oResponse.senders[i].chat_box &&  bBoxUndefined) {
                        // draw new chat box in predefined output's block;
                        $('#' + this.chatBoxSettings.sOutputBlockId).append(oResponse.senders[i].chat_box);
                    }

                    var $oWrapper = $('#' + this.chatBoxSettings.sHistoryBlockPrefix + iSenderId);
                    var $el = $oWrapper.find('div.messages_section:first');

                    // if isset new messages for current chat box;
                    if (oResponse.senders[i].messages_list) {
                        // if chat box's messages come in section as `message_block`;
                        if ( oResponse.senders[i].messages_list != 'built-in') {

                            var iChatBoxHeight =  $el.outerHeight();
                            var iOldChatBoxContentHeight = this.getChatBoxContentHeight($el);
                            var sVisibleState = this.getVisibility($el);

                            if( (sVisibleState == 'hidden' && !this.oDefinedChatBoxes.boxes[iChatBoxIndex].blink_handler) || bBoxUndefined) {
                                // send the flesh signals;
                                this.setBlinkMode(iChatBoxIndex, $el);
                            }

                            // add received messages into current chat box;
                            $el.append(oResponse.senders[i].messages_list);

                            iOldChatBoxContentHeight = iOldChatBoxContentHeight - iChatBoxHeight;
                            if(iOldChatBoxContentHeight <= $el.scrollTop() && sVisibleState == 'visible') {
                                this.elScroll($el);
                            }

                            // count all chat boxe's messages, if count of messages more than  25 for example (see this.iNumberVisibleMessages in BxSimpleMessengerModule.php);
                            // function will delete all odd strings from the current chat box ;
                            if(this.oDefinedChatBoxes.boxes[iChatBoxIndex].count_messages > this.chatBoxSettings.iNumberVisibleMessages) {

                                var $oKids = $('#' + this.chatBoxSettings.sHistoryBlockPrefix + oResponse.senders[i].sender_id + ' div.messages_section').children();
                                var iKidsCount = $oKids.length;

                                var iRowsDelete = iKidsCount - this.chatBoxSettings.iNumberVisibleMessages;
                                this.oDefinedChatBoxes.boxes[iChatBoxIndex].count_messages -= iRowsDelete;

                                for( var j = 0; j < iRowsDelete; j++ )
                                {
                                    $('#' + this.chatBoxSettings.sHistoryBlockPrefix + oResponse.senders[i].sender_id + ' div.messages_section > div:first-child').remove();
                                }
                            }
                        }
                        else {
                            if(this.bFirstRender && oResponse.senders[i].messages_list == 'built-in') {
                                if( this.getVisibility($el) != 'visible') {
                                    this.setBlinkMode(iChatBoxIndex, $el);
                                }
                            }
                        }
                    }

                    // check the sender's status text and sender's status;
                    if( typeof this.oDefinedChatBoxes.boxes[iChatBoxIndex] != 'undefined' && !bBoxUndefined){

                        if( this.oDefinedChatBoxes.boxes[iChatBoxIndex].status_text_update != oResponse.senders[i].status_update_time) {
                            this.getStatusText(iSenderId);
                            this.oDefinedChatBoxes.boxes[iChatBoxIndex].status_text_update = oResponse.senders[i].status_update_time;
                        }

                        if( this.oDefinedChatBoxes.boxes[iChatBoxIndex].status != oResponse.senders[i].sender_status) {
                            this.getSenderStatus(iSenderId);
                            this.oDefinedChatBoxes.boxes[iChatBoxIndex].status = oResponse.senders[i].sender_status;
                        }

                        // define the last messages id for current chat box;
                        this.oDefinedChatBoxes.boxes[iChatBoxIndex].last_message = parseInt(oResponse.senders[i].last_message);
                        // sum count of received messages;
                        if(oResponse.senders[i].count_messages) {
                            this.oDefinedChatBoxes.boxes[iChatBoxIndex].count_messages += parseInt(oResponse.senders[i].count_messages);
                        }
                    }
                }

                // all data was processed;
                this.isProcessed = true;
            }

            if(!this.bFirstRender) {
                this.bFirstRender = true;
            }
        },

        /**
         * Function will define obj visibility option;
         *
         */
        this.getVisibility =  function(oObj)
        {
            var sVisibleState = oObj.css('visibility');
            if(sVisibleState == 'inherit') {
                sVisibleState = oObj.parent().css('visibility');
            }

            return sVisibleState;
        },

        /**
         * Function will get the chat box's sub content height;
         * Need for srolling content into chat box;
         *
         * @param  : $oChatBox (object) - chat box;
         * @return : (integer) - sub content height;
         */
        this.getChatBoxContentHeight = function($oChatBox)
        {
            var iChatBoxContentHeight = 0;

            // define the chat boxe's childrens height;
            $oChatBox.children().each(function(){
                iChatBoxContentHeight = iChatBoxContentHeight + $(this).outerHeight();
            });

            return iChatBoxContentHeight;
        },

        /**
         * Function will check received chat box's id in chatBox object;
         * and if find it will return 1 else 0;
         *
         * @param  : iChatBoxId (integer) - chat box's id;
         * @return : (integer) - will return 1 if isset and 0 if not;
         */
        this.checkChatBox = function(iChatBoxId)
        {
            var iDefinedChatBoxes = this.oDefinedChatBoxes.boxes.length;
            for( var i = 0; i < iDefinedChatBoxes; i++ )
            {
                if( this.oDefinedChatBoxes.boxes[i].box_id == iChatBoxId) {
                    return 1;
                }
            }

            return 0;
        },

        /**
         * Function will define the chat box's index;
         *
         * @param  : iSenderId (integer) - sender's id;
         * @return : (integer) - chat box's index;
         */
        this.getChatBoxObjectIndex = function(iSenderId)
        {
            var iDefinedChatBoxes = this.oDefinedChatBoxes.boxes.length;
            var iChatBoxIndex = 0;

            // define current chat box;
            for( var i = 0; i < iDefinedChatBoxes; i++)
            {
                if(this.oDefinedChatBoxes.boxes[i].box_id == iSenderId) {
                    return iChatBoxIndex = i;
                }
            }

            return iChatBoxIndex;
        },

        /**
         * Function will show chat window;
         *
         * @param : sWindowId (string) - window's id;
         */
        this.showChatWindow = function(sWindowId, oParentWindow)
        {  var $el = $('#' + sWindowId);

            // try to define chat box's parent;
            if(typeof oParentWindow == 'undefined') {
                var oParentWindow = $('#' + sWindowId).parent();
            }

            // set as unactivated;
            if( $el.css('visibility') == 'visible' || $el.css('visibility') == 'inherit' ) {
                $(oParentWindow).removeClass('active_chat_block');
                $el.css('visibility', 'hidden');
            }
            else {

                // set as window active;
                var iSenderId = sWindowId.replace(/[^0-9]*/, '');
                var iChatBoxIndex = this.getChatBoxObjectIndex(iSenderId);

                // stop the flash effect if isset;
                this.stopBlink(iChatBoxIndex);

                $(oParentWindow).addClass('active_chat_block');
                $(oParentWindow).removeClass('active_chat_block_blink_effect');

                // set chat box's layout position;
                if(this.chatBoxSettings.sMemberMenuPosition  == 'bottom' ) {
                    $el.css('bottom', this.chatBoxSettings.iParentContainerHeight);
                }
                else {
                   $el.css('top', this.chatBoxSettings.iParentContainerHeight);
                }

                $el.css('visibility', 'visible');

                this.placeTop(sWindowId, this);
            }

            // scroll content;
            var $oMessagesSection = $el.find('div.messages_section:first');
            this.elScroll($oMessagesSection);
        },

        /**
         * Function will block all browser's event actions;
         *
         * @param : oCurrentWindow (object) - current chat's window;
         * @param : calback (object) - calback function's name;
        */
        this.facePlate = function(event, oCurrentWindow, calback)
        {
            jQuery.each(jQuery.browser, function(i) {
                if($.browser.msie){
                    event.cancelBubble = true;
                }
                else{
                   event.stopPropagation();
                }
            });

            if(typeof calback != 'undefined') {
                calback(oCurrentWindow, this);
            }
        },

        /**
         * Function will load the chat window on top of all active chat boxes;
         *
         * @param : vCurrentChatWindow (object) or (string) - current chat's window or chat's window id;
         * @param : self (object)               - link on created object;
         */
        this.placeTop = function(vCurrentChatWindow, self)
        {
            var iZindex     = 0;
            var iTempZindex = 0;

            // get max z-index value;
            $('#' + self.chatBoxSettings.sOutputBlockId + ' div.' + self.chatBoxSettings.sChatBox).each(
                function()
                {
                    iTempZindex = parseInt( $(this).css('z-index') );
                    if(iZindex < iTempZindex) {
                        iZindex = iTempZindex;
                    }
                }
            );

            iZindex += 1;
            if(typeof vCurrentChatWindow != 'object') {
                var $el = $('#' + vCurrentChatWindow);
            }
            else {
               var $el = $(vCurrentChatWindow);
            }

            $el.css('z-index', iZindex);
            $el.parent().css('z-index', iZindex);
        },

        /**
         * Function will close the chat window;
         *
         * @param : iSenderId (integer) - message's sender id;
         */
        this.closeChatWindow = function(iSenderId)
        {
            var _sRandom = Math.random();
            var self = this;

            // send the close singnal;
            $.get(this.chatBoxSettings.sPageReceiver + '/close_window/' + iSenderId, {'_r' : _sRandom},
                function(sData)
                {
                    var iChatBoxCount = self.oDefinedChatBoxes.boxes.length;
                    for( var i=0; i < iChatBoxCount; i++)
                    {
                        if (typeof self.oDefinedChatBoxes.boxes[i] != 'undefined'
                                    && self.oDefinedChatBoxes.boxes[i].box_id == iSenderId) {
                            // remove from registered chat boxes;
                            self.oDefinedChatBoxes.boxes.splice(i,1);

                            // remove from DOM;
                            $('#' + self.chatBoxSettings.sHistoryBlockPrefix + iSenderId).parent().remove();
                        }
                    }

                }
            );
        },

        /**
         * Function will get sender's status text;
         *
         * @param : iSenderId (integer) - sender's id;
         */
        this.getStatusText = function(iSenderId)
        {
            var _sRandom = Math.random();

            $('#' + this.chatBoxSettings.sHistoryBlockPrefix + iSenderId).find('.status_text').load
            (
                this.chatBoxSettings.sPageReceiver + '/get_status_text/' + iSenderId + '&_r=' + _sRandom
            );
        },

        /**
         * Function will get sender's status;
         *
         * @param : iSenderId (integer) - sender's id;
         */
        this.getSenderStatus = function(iSenderId)
        {
            var self = this ;
            var _sRandom = Math.random();

            // get new status image path;
            $.get(this.chatBoxSettings.sPageReceiver + '/get_status/' + iSenderId, {_r : _sRandom},
                function(sImgPath)
                {
                    $('#' + self.chatBoxSettings.sHistoryBlockPrefix + iSenderId).parent().find('.sender_status').attr('src', sImgPath);
                    self.getSenderThumbnail(iSenderId);
                }
            );
        },

        /**
         * Function will get sender's thumbnail image;
         *
         * @param : iSenderId (integer) - sender's id;
         */
        this.getSenderThumbnail = function(iSenderId)
        {
            var _sRandom = Math.random();

            $('#' + this.chatBoxSettings.sHistoryBlockPrefix + iSenderId).parent().find('.sender_thumb').load
            (
                this.chatBoxSettings.sPageReceiver + '/get_sender_thumb/' + iSenderId + '&_r=' + _sRandom
            );
        }
    }

    var oSimpleMessenger = new BxSimpleMessenger();