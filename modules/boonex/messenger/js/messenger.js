/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup	Messenger Messenger
 * @ingroup	UnaModules
 * @{
 */
 
/**
 * Main messenger js file.
 */
var oMessenger = (function($){
	var _oMessenger = null;
	
	function oMessenger(oOptions){
		
		//list of selectors
		this.sJotsBlock = '.bx-messenger-block.jots',
		this.sMessangerBox = '#bx-messenger-message-box',
		this.sSendButton = '.bx-messenger-post-box-send-button > button',
		this.sTalkBlock = '.bx-messenger-conversation-block',
		this.sMainTalkBlock = '.bx-messenger-main-block',
		this.sTalkList = '.bx-messenger-conversations',
		this.sJot = '.bx-messenger-jots',
		this.sTalkListJotSelector = this.sTalkList + ' ' + this.sJot,
		this.sItemsList = '.bx-messanger-items-list',
		this.sSendArea = '.bx-messenger-text-box',
		this.sChatInfoBlock = '.bx-messenger-block-info',
		this.sLotInfo = '.bx-messenger-jots-snip-info',
		this.sLotsListBlock = '.bx-messanger-items-list',
		this.sLotSelector = '.bx-messenger-jots-snip',
		this.sLotsListSelector = this.sLotsListBlock + ' ' + this.sLotSelector,
		this.sUserTopInfo = '.bx-messenger-top-user-info',
		this.sUserSelectorBlock = '#bx-messenger-add-users',
		this.sUserSelector = this.sUserSelectorBlock + ' input[name="users[]"]',
		this.sUserSelectorInput = '#bx-messenger-add-users-input',
		this.sActiveLotClass = 'active',
		this.sUnreadLotClass = 'unread-lot',
		this.sStatus = '.bx-messenger-status';
		this.sBubble = '.bubble',
		this.sTypingArea = '.bx-messenger-conversations-typing span',
		this.sTypingAreaParent = '#bx-messenger-typing',
		
		//globa class options
		this.oUsersTemplate	= null,
		this.iTimer = null,
		this.iMaxLength = (oOptions && oOptions.max) || 0,
		this.iStatus = 1, // online
		this.iScrollDownSpeed = 1500;
		this.iHideUnreadBadge = 1000;
		this.iRunSearchInterval = 500, // seconds
		this.iMinHeightToStartLoading = 0, // scroll height to start history loading 
		this.iMinTimeBeforeToStartLoadingPrev = 500, // 2 seconds before to start loading history
		this.iTypingUsersTitleHide = 1000, //hide typing users div when users stop typing
		this.iLoadTimout = 0,
		this.iFilterType = 0,		
		this.aUsers = [],
		this.soundFile = 'modules/boonex/messenger/data/notify.wav'; //beep file, occurs when message received
		
		// Emoj config
		if (oOptions && oOptions.emoji)
				this.emojiPicker = new EmojiPicker(oOptions.emoji);
		
		// Lot's(Chat's) settings 
		this.oSettings = {
							'type'	: 'public',
							'url'	: '',
							'title' : document.title || '',
							'lot'	: 0,
							'user_id': (oOptions && oOptions.user_id) || 0 
						};
		
		// Real-time WebSockets framework class
		this.oRTWSF = (oOptions && oOptions.oRTWSF) || window.oRTWSF || null;
		
		// main messenger window builder
		this.oJotWindowBuilder = null;
	}

	/**
	* Init current chat/talk/lot settings
	*/
	oMessenger.prototype.initJotSettings = function(oOptions){		
		var	_this = this;
			oMessageBox = $(this.sMessangerBox);
			
			this.oSettings.url = oOptions.url || window.location.href,
			this.oSettings.type = oOptions.type || this.oSettings.type,
			this.oSettings.lot = oOptions.lot || 0,
			this.oSettings.name = oOptions.name || '';
			
			// init smiles			
			if (this.emojiPicker != undefined)				
				this.emojiPicker.discover();	  		
			
			$(this.sSendArea).on('keydown', function(oEvent){
				var iKeyCode = oEvent.keyCode || oEvent.which;		
							
					if (iKeyCode == 13){ 
						 if (oEvent.shiftKey !== true){ 												
								$(_this.sSendButton).click();
								oEvent.preventDefault();						
							}
					}
				
				if (_this.oRTWSF != undefined)
						_this.oRTWSF.typing({
								lot	:_this.oSettings.lot, 
								name:_this.oSettings.name, 
								user_id:_this.oSettings.user_id});
			});
			
			$(this.sSendButton).on('click', function(){
				_this.sendMessage(oMessageBox.val());
				oMessageBox.val('');
			});
				
			$(_this.sTalkBlock).scroll(function(){
				if ($(this).scrollTop() <= _this.iMinHeightToStartLoading){
					_this.iLoadTimout = setTimeout(function(){
						_this.updateJots('prev');
					}, _this.iMinTimeBeforeToStartLoadingPrev);
				}
				else
					clearTimeout(_this.iLoadTimout);				
			});	
			
			
			$(window).on('beforeunload', function(e){
				if (_this.oRTWSF != undefined)
						_this.oRTWSF.end({
											user_id:oOptions.user_id
										 });
			});
			
			/* Init users Jot template  begin */
				this.loadMembersTemplate();
			/* Init users Jot template  end */
			
			_this.updateScrollPosition('bottom');			
	}
	
	/**
	* Update status of the member
	*@param object oData changed profile's settings
	*/
	oMessenger.prototype.updateStatuses = function(oData){
		var sClass = 'offline';
	
		switch(oData.status){
			case 1:
				sClass = 'online';
				break;
			case 2:
				sClass = 'away';
				break;
			default:
				sClass = 'offline';
		}

		$('b[data-user-status="' + oData.user_id + '"]').removeClass('online offline away').addClass(sClass).attr('title', _t('_bx_messenger_' + sClass));		
	}
	
	/**
	* Load logged member's message template	
	*/
	oMessenger.prototype.loadMembersTemplate = function(){
		var _this = this;
		
		if (_this.oUsersTemplate == null)
			$.get('modules/?r=messenger/load_members_template', 
				function(oData){
					if (oData != undefined && oData.data.length){					
						_this.oUsersTemplate = $(oData.data);						
					}
			}, 'json');	
	}
	
	/**
	* Search for lot
	*@param int iType type of the lot
	*@param string sText keyword for filter
	*/
	oMessenger.prototype.searchByItems = function(iType, sText){
		var _this = this,
			iFilterType	= iType || this.iFilterType;			
		
		clearTimeout(_this.iTimer);		
		this.iTimer = setTimeout(function() {
			bx_loading($(_this.sItemsList), true);
			$.get('modules/?r=messenger/search', {param:sText || '', type:iFilterType}, 
					function(oData){
						if (parseInt(oData.code) == 1) 
							window.location.reload();
						else
						if (!parseInt(oData.code)){					
									$(_this.sItemsList).html(oData.html).fadeIn();
									_this.iFilterType = iFilterType;								
								}
							}, 'json');	
		}, _this.iRunSearchInterval);	
	}
	
	/**
	* Create lot
	*@param object oOptions lot options
	*/
	oMessenger.prototype.createLot = function(oOptions){
		var _this = this,
			oParams = oOptions || {};
		
		bx_loading($(_this.sMainTalkBlock), true);		
		$.post('modules/?r=messenger/create_lot', {profile:oParams.user || 0, lot:oParams.lot || 0}, function(oData){			
			bx_loading($(_this.sMainTalkBlock), false);				
				if (parseInt(oData.code) == 1) 
						window.location.reload();
				else		
				if (!parseInt(oData.code)){
					
					$(_this.sJotsBlock).parent().html(oData.html).bxTime();
					
					_this.updateScrollPosition('bottom');
					_this.initUsersSelector(oParams.lot !== undefined ? 'edit' : '');
					
					if (_this.oJotWindowBuilder != undefined) _this.oJotWindowBuilder.changeColumn();
				}
		}, 'json');	
	}
	
	oMessenger.prototype.onSaveParticipantsList = function(iLotId){
		var _this = this;
		if (iLotId)
				$.post('modules/?r=messenger/save_lots_parts', {lot:iLotId, participants:_this.getPatricipantsList()}, function(oData){
						if (parseInt(oData.code) == 1) 
							window.location.reload();

						alert(oData.message);
						if (!parseInt(oData.code)){
							_this.searchByItems();
							_this.loadTalk(iLotId);
						}
						
				}, 'json');
	}
	
	oMessenger.prototype.onLeaveLot = function(iLotId){
		var _this = this;
		if (iLotId)
			$.post('modules/?r=messenger/leave', {lot:iLotId}, function(oData){
				if (parseInt(oData.code) == 1) 
					window.location.reload();
				
				alert(oData.message);
				if (!parseInt(oData.code))
						_this.searchByItems();						
					}, 'json');
	}
	
	oMessenger.prototype.onMuteLot = function(iLotId){
		var _this = this;
		$.post('modules/?r=messenger/mute', {lot:iLotId}, function(oData){
				if (parseInt(oData.code) == 1) 
					window.location.reload();

				if (!parseInt(oData.code)){
					if ($(_this.sChatInfoBlock + ' i.bell-slash-o').length)
						$(_this.sChatInfoBlock + ' i.bell-slash-o').removeClass('bell-slash-o').addClass('bell-o');
					else
						$(_this.sChatInfoBlock + ' i.bell-o').removeClass('bell-o').addClass('bell-slash-o');								
				}
			}, 'json');
	}
	
	oMessenger.prototype.onDeleteLot = function(iLotId){
		var _this = this;
		if (iLotId)
				$.post('modules/?r=messenger/delete', {lot:iLotId}, function(oData){						
					if (parseInt(oData.code) == 1) 
							window.location.reload();
		
						if (!parseInt(oData.code)){
							_this.searchByItems();
							_this.loadDefaultData();
						}
						
						alert(oData.message);
						
				}, 'json');
	}	
	
	/**
	* Load history for selected lot
	*@param int iLotId lot id
	*@param object el selected lot
	*/
	oMessenger.prototype.loadTalk = function(iLotId, el){
		var _this = this;
		
		if (this.isActiveLot(iLotId) && _this.oJotWindowBuilder != undefined && !this.oJotWindowBuilder.isMobile()) return ;
		
		bx_loading($(this.sMainTalkBlock), true);
		$.post('modules/?r=messenger/load_talk', {id:iLotId}, function(oData){
			bx_loading($(_this.sMainTalkBlock), false);
				if (parseInt(oData.code) == 1) 
							window.location.reload();
				else
				if (!parseInt(oData.code)){
					$(_this.sJotsBlock).parent().html(oData.html).fadeIn(function(){
						if (_this.oJotWindowBuilder != undefined) 
								_this.oJotWindowBuilder.changeColumn();							
					}).bxTime();
								
					$(el).addClass(_this.sActiveLotClass).siblings().removeClass(_this.sActiveLotClass).end().
							find(_this.sLotInfo).removeClass(_this.sUnreadLotClass).end().
							find(_this.sBubble).fadeOut(_this.iHideUnreadBadge).end();
							
					
					/*  copy current update member status to the top of the chat */
					var iUser = $(el).find(_this.sStatus).data('user-status');
					if (parseInt(iUser)){
						var classList = $(el).find(_this.sStatus).attr('class').split(/\s+/);						
						if (typeof classList[1] !== 'undefined'){							
							$('b[data-user-status="' + iUser + '"]').
								removeClass('online offline away').
								addClass(classList[1]).
								attr('title', _t('_bx_messenger_' + classList[1]));
						}
					}
					
					if (_this.oJotWindowBuilder != undefined && _this.oJotWindowBuilder.isMobile()) 
							_this.correctUserStatus();
					
					_this.updateScrollPosition('bottom');				
				}
		}, 'json');	
	}	
	
	/**
	* Change view of the lot participants if viewer uses mobile devise
	*/
	oMessenger.prototype.correctUserStatus = function(){
		var _this = this;
		$('.bx-messenger-block.jots .bx-messenger-participants-usernames').each(function(){
			$('.bx-messenger-status', $(this)).prependTo($('.name', $(this))).end().find('.status').remove();
		});
	}
	
	oMessenger.prototype.loadJotsForLot = function(iLotId){
		var _this = this;		
		
		bx_loading($(this.sMainTalkBlock), true);		
		$.post('modules/?r=messenger/load_jots', {id:iLotId}, function(oData){
			bx_loading($(_this.sMainTalkBlock), false);
			if (parseInt(oData.code) == 1) 
					window.location.reload();
						
			if (!parseInt(oData.code)){			
					
					$(_this.sMainTalkBlock).html(oData.html).fadeIn().bxTime();
					
					_this.updateScrollPosition('bottom');					
					
					if (_this.oJotWindowBuilder != undefined) 
							_this.oJotWindowBuilder.changeColumn();
				}
		}, 'json');	
	}
		
	oMessenger.prototype.sendPushNotification = function(oData){		
			$.post('modules/?r=messenger/send_push_notification', oData);
	}

	/**
	* Main send message function, occurs when member send message
	*/
	oMessenger.prototype.sendMessage = function(sMessage){
		var _this = this, 
			oParams = this.oSettings,
			msgTime = new Date();
		
		oParams.message = $.trim(sMessage);
		oParams.participants = _this.getPatricipantsList();		
		oParams.tmp_id = msgTime.getTime();
		
			if (!oParams.message.length) return;
		else
			if (oParams.message.length > this.iMaxLength) 
				oParams.message = oParams.message.substr(0, this.iMaxLength);

		var oMessage = _this.oUsersTemplate.clone().
						attr('data-tmp', oParams.tmp_id). 
						find('time').attr('datetime', msgTime.toISOString()).end(). 
						find('.bx-messenger-jots-message').text(oParams.message).addClass('new').end().
						fadeTo(100, 0.1).fadeTo(200, 1.0).
						bxTime();	
		
		// append content of the message to history page
		$(_this.sTalkList).append(oMessage);
		$(_this.sSendArea).html('');
		
		_this.updateScrollPosition('bottom');		

		// save message to the server and broadcast to all participants
		$.post('modules/?r=messenger/send', oParams, function(oData){
			if (parseInt(oData.code) == 1) 
					window.location.reload();
				
			if (!parseInt(oData.code)){
				if (oData.lot_id != undefined){
					_this.oSettings.lot = parseInt(oData.lot_id);
					_this.searchByItems();
				}

				if (parseInt(oData.jot_id) &&  oData.tmp_id != undefined)
					$(_this.sTalkList).find('[data-tmp="' + oData.tmp_id + '"]').attr('data-id', oData.jot_id);			

				if (_this.oRTWSF != undefined)
					_this.oRTWSF.message({
											lot: _this.oSettings.lot, 
											name: _this.oSettings.name,
											user_id: _this.oSettings.user_id,
										});
				}
			}, 'json');			
			
	}		
	
	/**
	* Get all participants from users selector area
	*/
	oMessenger.prototype.getPatricipantsList = function(){ 
		var list = [];
		
		if ($(this.sUserSelector).length){
			$(this.sUserSelector).each(function(){
				list.push($(this).val());
			});
		} 
		else if ($(this.sUserTopInfo).length){
			var iUserId = parseInt($(this.sUserTopInfo).data('user-id'));
			if (iUserId)
					list.push(iUserId);
		}
		
		return list;
	}
	
	/**
	* Move lot's brief to the top of the left side when new message received into it
	*@param object oData lot's settings
	*/
	oMessenger.prototype.upLotsPosition = function(oData){		
		var _this = this,
			lot = parseInt(oData.lot), 
			oLot = $('div[data-lot=' + lot + ']'),
			oNewLot = undefined;
			
		$.get('modules/?r=messenger/update_lot_brief', {lot_id: lot}, 
						function(oData){
									if (!parseInt(oData.code)){					
										oNewLot = $(oData.html);
										if (lot && !_this.isActiveLot(lot)){
												if (!oLot.is(':first-child'))
														oLot.fadeOut('slow', function(){					
															oLot.remove();
															$(_this.sLotsListBlock).prepend(oNewLot).fadeIn('slow');
														});	
												else
													oLot.replaceWith(oNewLot);													
											}			
									}
								}, 'json');
	}
	
	/**
	* Show member's typing area when member is typing a message
	*@param object oData profile info
	*/
	oMessenger.prototype.showTyping = function(oData) {	
		var _this = this,
			sName = oData.name != undefined ? (oData.name).toLowerCase() : '';
	
		if (oData.lot != undefined && this.isActiveLot(oData.lot)){			
			if (!~this.aUsers.indexOf(sName)) 
							this.aUsers.push(sName);
			
			$(this.sTypingArea).text(this.aUsers.join(','));			
			$(this.sTypingAreaParent).fadeIn();
		}
		
		clearTimeout(this.iTimer);	
		this.iTimer = setTimeout(function(){
			$(_this.sTypingAreaParent).fadeOut().find(_this.sTypingArea).html('');
			_this.aUsers = [];
		},_this.iTypingUsersTitleHide);				
	};
	
	/**
	* Check if specified lot is currntly active
	*@param int iId profile id 
	*@return boolean
	*/
	oMessenger.prototype.isActiveLot = function(iId){
		return parseInt(this.oSettings.lot) == iId;	
	}

	/**
	* Search for lot by participants list
	*@param int iId profile id 
	*/	
	oMessenger.prototype.findLotByParticipantsList = function(){
		var _this = this;
		$.post('modules/?r=messenger/find_lot', {participants:this.getPatricipantsList()}, 
			function(oData){
					_this.loadJotsForLot(parseInt(oData.lotId));
			}, 
		'json');
	}
	
	/**
	* Correct scroll position in history area depends on loaded messages (old history or new just received)
	*@param string sPosition position name
	*@param string sEff name of the effect for load 
	*@param object oObject any history item near which to place the scroll 
	*/
	oMessenger.prototype.updateScrollPosition = function(sPosition, sEff, oObject){
		var iPosition = 0,
			sEffect = sEff,
			iHeight = $(this.sTalkBlock).prop('scrollHeight'),
			_this = this;
				
		switch(sPosition){
			case 'top':
					iPosition = 0;
					break;
			case 'bottom':
					iPosition = iHeight;
					break;
			case 'position':
					iPosition = oObject != undefined ? oObject.position().top : 0;
					break;
		}
		
		if (sEffect == 'slow')
			$(this.sTalkBlock).animate({
											scrollTop: iPosition,
										 }, _this.iScrollDownSpeed);
		else 
			$(this.sTalkBlock).scrollTop(iPosition);	
	}
	
	/**
	* Sound when message received
	*/
	oMessenger.prototype.beep = function(){
		var playSound = null;
		try{
			playSound = new Audio(this.soundFile); 			
			if (!document.hasFocus()) {
				playSound.play();
			}
			
		}catch(e){
			console.log('Sound is not supported in your browser');			
		}		
	}
	
	/**
	* Upodate history area, occurs when new messages are received(move scroll to the very bottom) or member loads the history(move scroll to the very top)
	*@param string sLoad option shows just received or old from history
	*/
	oMessenger.prototype.updateJots = function(sLoad){
		var _this = this;
			sShow = sLoad || 'new',
			oObjects = $(this.sTalkListJotSelector),
			iStart = sShow == 'new' ? oObjects.last().data('id') : oObjects.first().data('id');
		
			if (sLoad == 'prev')
			   bx_loading($(this.sTalkBlock), true);		   
		   
			$.post('modules/?r=messenger/update', {url: this.oSettings.url, type: this.oSettings.type, start: iStart, lot: this.oSettings.lot, load:sShow}, 
			function(oData){
				var oList = $(_this.sTalkList);
				
				if (!parseInt(oData.code)){
						if (iStart == undefined) oList.html('');
								
						if (sShow == 'new'){
							$(oData.html).filter(_this.sJot).each(function(){								
								if ($('div[data-id="' + $(this).data('id') + '"]', oList).length !== 0)
									$(this).remove();									
							}).appendTo(oList);						
							_this.beep();														
						}	
						else 
							oList.prepend(oData.html);							
								
						oList.find(_this.sJot + ':hidden').fadeIn(function(){
								$(this).css('display', 'table-row');
						}).bxTime();
									 
						_this.updateScrollPosition(
							sShow == 'new' ? 'bottom' : 'position', 
							sShow == 'new' ? 'slow' : '',
							sShow == 'new' ? null : $(oObjects.first())
						);
				}
				
				if (sLoad == 'prev'){
					bx_loading($(_this.sTalkBlock), false);					
				}				
					
			}, 'json');
	}
	
	/**
	* Init user selector are when create or edit participants list of the lot
	*@param boolean bMode if used for edit or to create new lot
	*/
	oMessenger.prototype.initUsersSelector = function(bMode){
			var _this = this;
			
			$(_this.sUserSelectorInput).
				autocomplete({
								source: 'modules/?r=messenger/get_auto_complete',
								minLength: 1,
								width: 250,
								select: function(e, ui) {
															$(this).val(ui.item.value);
															$(this).trigger('selectuser', ui.item);
															e.preventDefault();
														}	      
							}).on({
								keyup : function(e, ui) {
									  if(/(188|13)/.test(e.which)) 
											$(this).trigger('selectuser', ui); 
								},					
								selectuser: function(e, item){
								  $(this).hide();						  
									  if (item != undefined)
											$(this).before('<b class="bx-def-color-bg-hl bx-def-round-corners">' +
															'<img class="bx-def-thumb bx-def-thumb-size bx-def-margin-sec-right" src="' + item.icon + '" /><span>'+ item.value + '</span>' + 
															'<input type="hidden" name="users[]" value="'+ item.id +'" /></b>');
									  
									  if (bMode != 'edit')
											_this.findLotByParticipantsList();
									  
									  $(this).show().val('').focus();
								}
							}).focus();
			
			$(_this.sUserSelectorBlock).on('click', 'b', function(){
					$(this).remove();					
					if (bMode != 'edit')
							_this.findLotByParticipantsList();
					
					$(_this.sUserSelectorInput).focus();
			});
	};
	
	/**
	* Retrns object with public methods 
	*/
	return {
		/**
		* Init main Lot settings and object to work with (settings, real-time frame work, page builder and etc...)
		*@param object oOptions options
		*/
		init:function(oOptions){			
			var _this = this;
			if (_oMessenger != null) return true; 
				
			_oMessenger = new oMessenger(oOptions);
			
			/* Init sockets settings begin*/	
			if (_oMessenger.oRTWSF != undefined){	

				_oMessenger.oRTWSF.onTyping = function(oData){
					_this.onTyping(oData);
				};		

				_oMessenger.oRTWSF.onMessage = function(oData){
					_this.onMessage(oData);
				};
				
				_oMessenger.oRTWSF.onStatusUpdate = function(oData){					
					_this.onStatusUpdate(oData);
				};				
				
				_oMessenger.oRTWSF.onServerResponse = function(oData){					
					_this.onServerResponse(oData);
				};				
				
				_oMessenger.oRTWSF.getSettings = function(){
					return $.extend({status:_oMessenger.iStatus}, _oMessenger.oSettings);
				};
				
				_oMessenger.oRTWSF.initSettings({
					user_id	:oOptions.user_id, 
					status	:1
				});
			}else{
				console.log('Real-time frameworks was not initialized');
				return false;
			}			
			/* Init connector settings end */			
			return true;
		},

		/**
		* Init Lot settings only (occurs when member selects any lot from lots list)
		*@param object oOptions options
		*/
		initJotSettings: function(oOptions){
			this.init()
			_oMessenger.initJotSettings(oOptions);			
		},
		initMessengerPage:function(iProfileId, oMessenger){
			this.init();
			
			_oMessenger.oJotWindowBuilder = oMessenger || window.oJotWindowBuilder;
			
			if (typeof oMessengerMemberStatus !== 'undefined'){
				oMessengerMemberStatus.init(function(iStatus){
					_oMessenger.iStatus = iStatus;
					if (_oMessenger.oRTWSF != undefined)
						this.oRTWSF.updateStatus({
											user_id:_oMessenger.oSettings.user_id,
											status:iStatus,
										 });
					});
			}
		
			if (_oMessenger.oJotWindowBuilder != undefined){
				if (!_oMessenger.oJotWindowBuilder.isMobile()){
					if ($(_oMessenger.sLotsListSelector).length > 0 && !iProfileId) 
						$(_oMessenger.sLotsListSelector).first().click();
					else 
						_oMessenger.createLot({user:iProfileId});
				  }			

				$(window).resize(function(){
					_oMessenger.oJotWindowBuilder.resizeWindow();
				});
				_oMessenger.oJotWindowBuilder.resizeWindow();
			}
			else{
				console.log('Page Builder was not initialized');
			}
		},
		
		// init public methods
		loadTalk:function(iLotId, oEl){
			_oMessenger.loadTalk(iLotId, oEl);
			return this;
		},
		searchByItems:function(sText){
			_oMessenger.searchByItems(_oMessenger.iFilterType, sText);
			return this;
		},
		createLot:function createLot(oObject){
			_oMessenger.createLot(oObject);
			return this;
		},	
		onSaveParticipantsList:function(iLotId){ 
			_oMessenger.onSaveParticipantsList(iLotId);
			return this;
		},
		onLeaveLot: function(iLotId) {
			_oMessenger.onLeaveLot(iLotId);
			return this;
		},	
		onMuteLot: function(iLotId){
			_oMessenger.onMuteLot(iLotId);
			return this;
		},
		onDeleteLot: function(iLotId){ 
			_oMessenger.onDeleteLot(iLotId);
			return this;
		},
		showLotsByType: function(iLotId){
			_oMessenger.searchByItems(iLotId);
			return this;
		},
		/**
		* Methods below occur when messenger gets data from the server
		*/
		onTyping: function(oData){
			_oMessenger.showTyping(oData);
			return this;
		},		
		onMessage: function(oData){
			_oMessenger.upLotsPosition(oData);
			_oMessenger.updateJots();
			return this;
		},
		onStatusUpdate: function(oData){
			_oMessenger.updateStatuses(oData);			
			return this;
		},
		onServerResponse: function(oData){
			_oMessenger.sendPushNotification(oData);			
			return this;
		}
		
	}
	
})(jQuery);

/** @} */
