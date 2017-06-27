/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup	Messenger Messenger
 * @ingroup	UnaModules
 * @{
 */
 
/**
 * Builder class
 * Adapt messenger main page for different devises
 */ 

var oJotWindowBuilder = (function(){
	var _oPrivate = {
				sLeftAreaName: '.bx-messanger-items-list',
				sRightAreaName: '.bx-messenger-main-block', //left column of the body without header
				sLeftTopBlockArea: '#bx-messangger-block-head',
				sBothColumnsParent: '.bx-layout-row',
				sInfoUsersArea: '.bx-messenger-top-user-info',
				sChatInfoBlock: '.bx-messenger-block-info',
				sBlockHeaderArea: '.bx-messenger-block > .bx-db-header',
				sToolbar: '#bx-toolbar',
				oLeftCol: null,
				oRightCol: null,
				iLeftSize: '30%',
				iRightSize: '70%',
				sActiveType:'both',
				iMainAreaHeight:null, //area of the body without header with both columns
				iLeftAreaHeight:null, //left column header height
				iRightAreaHeight:null, //right column header height
				iResizeTimeout:null,
				
				updateLeftHeight:function(){					
					this.iLeftAreaHeight = $(this.sLeftTopBlockArea).outerHeight();
					$(this.sLeftAreaName).height(this.iMainAreaHeight - this.iLeftAreaHeight);
				},
				
				updateRightHeight:function(){
						this.iRightAreaHeight = $(this.sInfoUsersArea).length ? $(this.sInfoUsersArea).outerHeight() : $(this.sBlockHeaderArea).outerHeight();	
												
						if (this.iRightAreaHeight == null) return ;
						
						if ($(this.sChatInfoBlock).length && this.iRightAreaHeight != null)
								this.iRightAreaHeight = this.iRightAreaHeight + $(this.sChatInfoBlock).outerHeight();
				
						$(this.sRightAreaName).height(this.iMainAreaHeight - this.iRightAreaHeight);
				},
					
				init:function(){
						var iParent = $(this.sBothColumnsParent).width();
						
						if (this.oLeftCol !== null || this.oRightCol !== null) return ;
						
						this.oLeftCol = $(this.sBothColumnsParent + ' > div').first();
						this.oRightCol = $(this.sBothColumnsParent + ' > div').last();
						
						this.iLeftSize = this.oLeftCol.outerWidth()*100/iParent + '%' || this.iLeftSize;
						this.iRightSize = this.oRightCol.outerWidth()*100/iParent + '%' || this.iRightSize;	
					
						if (this.isMobile())
								this.sActiveType = 'left';						
					
					},
				isMobile:function(){
						return $(window).width() <= 720;						
					},
			
				changeColumn:function(){
						this.init();
						if (this.sActiveType == 'both'){ 
							if (this.isMobile())
									this.sActiveType = 'left';	
						}			
						else
							this.sActiveType = 'right';

						this.resizeColumns();	
					},
				activateLeft:function(){					
						this.oRightCol.hide();
						this.oLeftCol.width('100%').fadeIn();
						this.updateLeftHeight();						
					},
				activateRight:function(){
						this.oLeftCol.hide();
						this.oRightCol.width('100%').fadeIn();
						this.updateRightHeight();											
					},
					
				activateBoth:function(){
						this.oLeftCol.width(this.iLeftSize).fadeIn('slow');						
						this.oRightCol.width(this.iRightSize).fadeIn('slow');
						
						this.updateLeftHeight();
						this.updateRightHeight();
					},
				onResizeWindow:function(){
						 this.init();
						 
						 if (this.isMobile())
							 this.sActiveType = this.sActiveType == 'both' ? this.sActiveType = 'left' : this.sActiveType;
						 else 
							 this.sActiveType = 'both';
					
						this.iMainAreaHeight = $(window).height() - $(this.sToolbar).outerHeight();						
						this.resizeColumns();
					},
				resizeColumns:function(){
						switch(this.sActiveType){
							case 'left' : this.activateLeft(); break;
							case 'right' : this.activateRight(); break;
							 default:
								this.activateBoth();	
						}					
					}
		};
		
	return {
			resizeWindow:function(){				
				 clearTimeout(_oPrivate.iResizeTimeout);
				_oPrivate.iResizeTimeout = setTimeout(
														function(){
																	_oPrivate.onResizeWindow()
																  }, 300);
			},
			changeColumn:function(){
				_oPrivate.changeColumn();
			},
			
			isMobile:function(){
				return _oPrivate.isMobile();
			}
		}
})();

/** @} */
