
    function BxSpy()
    {
        // contain page's URL for queries;
        this.sPageReceiver           = '',
        this.updateTimeNotifyHandler = '',
        this.sEventsContainer        = '',
        this.sPageMode               = '',
        this.sActivityType           = '',

        this.iProfileId   = 0;
        this.iUpdateTime  = 0,
        this.iLastEventId = 0,
        this.iEventsCount = 0,
        this.iPerPage     = 0,

        this.iSlideDown   = 0,
        this.iSlideUp     = 0,
        this.bUpdateAllowed = true,

        this.PageUpdate = function()
        {
            if(!this.bUpdateAllowed) {
                return;
            }

            var _sRandom = Math.random();
            var self = this;
            var sExtraParam = '';

            if(this.sActivityType) {
                sExtraParam += '/' + this.sActivityType;
            }
            else {
                sExtraParam += '/all';
            }

            if(this.iProfileId != 0) {
                sExtraParam += '/' + this.iProfileId;
            }

            // check for updates;
            $.get(this.sPageReceiver + 'check_updates/' + this.sPageMode + '/' + this.iLastEventId + sExtraParam + '&_r=' + _sRandom,
                function(sData)
                {
                    var oResponse = eval("(" + sData + ")");
                    var $oWrapper = $('#' + self.sEventsContainer);

                    self.iLastEventId = oResponse.last_event_id;

                    // draw new events;
                    var iEventsCount = oResponse.events.length;
                    if(iEventsCount) {
                        // remove empty block if have some events;
                        $oWrapper.find('.MsgBox').remove();

                        for( var i = 0; i < iEventsCount; i++ )
                        {
                            if(typeof oResponse.events[i].event != 'undefined') {
                                // add new event;
                                $oWrapper.prepend(oResponse.events[i].event);
                                self.iEventsCount++;

                                //set effect;
                                $oWrapper.find('div.spy_events_wrapper:first').slideDown(self.iSlideDown);

                                // remove the latest event;
                                if(self.iEventsCount > self.iPerPage) {
                                    $oWrapper.find('div.spy_events_wrapper:not(:animated):last').slideUp(self.iSlideUp, function()
                                    {
                                        $(this).remove()
                                        self.iEventsCount--;
                                    });
                                }
                            }
                        }
                    }

                    // check updates ;
                    self.updateTimeNotifyHandler = setTimeout(function(){
                        self.PageUpdate();
                    },self.iUpdateTime);
                }
            );
        },

        /**
         * Stop activity
         *
         * @return void
         */
        this.stopActivity = function()
        {
            this.bUpdateAllowed = false;
            // stop the deserted notify procces;
            clearTimeout(this.updateTimeNotifyHandle);
        },

        /**
         * Function will get activity block content;
         *
         * @return : (text) html presentation data;
         */
        this.getActivityBlock = function(iPage, sType)
        {
            var self = this;
            var _sRandom = Math.random();
            var sActivityType = '';

            //destroy notify handler;
            clearTimeout(this.updateTimeNotifyHandler);

            if(typeof sType != 'undefined') {
                sActivityType = sType + '/' ;
            }


            bx_loading(this.sEventsContainer, true);
            $.get(this.sPageReceiver + 'get_member_block/' + sActivityType + '&_r=' + _sRandom + '&page=' + iPage,
                function(sData)
                {
                   $('#' + self.sEventsContainer).html(sData);

                   if(iPage == 1) {
                        self.PageUpdate();
                    }
                }
            );
        }
    }