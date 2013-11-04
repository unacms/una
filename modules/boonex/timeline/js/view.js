function BxTimelineView(oOptions) {
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oTimelineView' : oOptions.sObjName;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : oOptions.iOwnerId;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;

    var $this = this;
    $(document).ready(function() {
    	$this.initMasonry();
    	$('.bx-tl-item').resize(function() {
    		$this.reloadMasonry();
    	});
    	$('img.bx-tl-item-image').load(function() {
    		$this.reloadMasonry();
    	});
    });
}

BxTimelineView.prototype = new BxTimelineMain();

BxTimelineView.prototype.initSlider = function(oOptions) {
	var $this = this;
	var oSlider = $(this.sIdView + ' .bx-tl-timeline-slider');
	if(oSlider.length > 0) {
		oSlider.slider({
			min: oOptions.min,
			max: oOptions.max,
			value: oOptions.value,
			slide: function(e, ui) {
			    $(ui.handle).html(ui.value);
			},
			change: function(e, ui) {
				$this.changeTimeline(e, ui);
			}
		});

		$('.ui-slider-handle', oSlider).html(oSlider.slider('value'));
	}
};

BxTimelineView.prototype.changePage = function(oElement, iStart, iPerPage) {
	this._oRequestParams.start = iStart;
    this._oRequestParams.per_page = iPerPage;

    this._getPosts(oElement, 'page');
};

BxTimelineView.prototype.changeFilter = function(oLink) {
    var sId = $(oLink).attr('id');

    this._oRequestParams.start = 0;
    this._oRequestParams.filter = sId.substr(sId.lastIndexOf('-') + 1, sId.length);

    this._getTimeline(oLink);
    this._getPosts(oLink, 'filter');
};

BxTimelineView.prototype.changeTimeline = function(oEvent, oUi) {
	this._oRequestParams.start = 0;
    this._oRequestParams.timeline = oUi.value;

	this._getPosts($(oUi.handle), 'timeline');
};

BxTimelineView.prototype.deletePost = function(oLink, iId) {
    var $this = this;
    var oView = $(this.sIdView);
    var oData = this._getDefaultData();
    oData['post_id'] = iId;

    this.loadingInBlock(oLink, true);

    $.post(
        this._sActionsUrl + 'delete/',
        oData,
        function(oData) {
        	$this.loadingInBlock(oLink, false);

            if(oData.code == 0)
                $($this.sIdItem + oData.id).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                    $(this).remove();

                    if(oView.find('.bx-tl-item').length != 0) {
                    	$this.reloadMasonry();
                    	return;
                    } 

                    $this.destroyMasonry();
                    oView.find('.bx-tl-load-more').hide();
                    oView.find('.bx-tl-empty').show();
                });                        
        },
        'json'
    );
};

BxTimelineView.prototype.showMoreContent = function(oLink) {
	$(oLink).parent('span').next('span').show().prev('span').remove();
	this.reloadMasonry();
};

BxTimelineView.prototype.showPostMenu = function(oLink) {
	var sId = $(oLink).parents('.bx-tl-item-menu:first').children('.bx-db-menu-popup:hidden').attr('id');
	bx_menu_popup_inline('#' + sId, oLink);
};

BxTimelineView.prototype.showComments = function(oLink, sSystem, iId) {
	var $this = this;

    var oData = this._getDefaultData();
    oData['id'] = iId;
    oData['system'] = sSystem;

    if(oLink)
    	this.loadingInBlock(oLink, true);

    if($(this.sIdComments + iId + ':hidden').length > 0) {
    	$(oLink).next(this.sIdComments + iId + ':hidden').bx_anim('show', this._sAnimationEffect, this._iAnimationSpeed);
    	return;
    }

    jQuery.post (
        this._sActionsUrl + 'get_comments',
        oData,
        function(oData) {
        	if(oLink)
        		$this.loadingInBlock(oLink, false);

        	if(!oData.content)
        		return;

        	$(oLink).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
        		$(this).parents('.bx-tl-comments-link:first').after($(oData.content).hide()).next($this.sIdComments + iId + ':hidden').bxTime().bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
    		});
        },
        'json'
    );
};

BxTimelineView.prototype._getPosts = function(oElement, sAction) {
    var $this = this;
    var oView = $(this.sIdView);

	switch(sAction) {
		case 'page':
			this.loadingInButton(oElement, true);
			break;

		default:
			this.loadingInBlock(oElement, true);
			break;
	}

    jQuery.post(
        this._sActionsUrl + 'get_posts/',
        this._getDefaultData(),
        function(oData) {
        	if($.trim(oData.load_more).length > 0)
        		oView.find('.bx-tl-load-more').replaceWith(oData.load_more);

        	if($.trim(oData.items).length > 0)
	        	switch(sAction) { 
	        		case 'page':
	        			$this.loadingInButton(oElement, false);
	        			$this.appendMasonry($(oData.items).bxTime());
			            break;

	        		default:
	        			$this.loadingInBlock(oElement, false);

	        			oView.find('.bx-tl-items').bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
			                $(this).html(oData.items).show().bxTime();

			                if($this.isMasonryEmpty()) {
			                	$this.destroyMasonry();
			                	return;
			                }

			                if($this.isMasonry())
			                	$this.reloadMasonry();
			        		else
			        			$this.initMasonry();
			            });
		            	break;
	            }
        },
        'json'
    );
};

BxTimelineView.prototype._getTimeline = function(oElement) {
    var $this = this;
    var oView = $(this.sIdView);

    this.loadingInBlock(oElement, true);

    jQuery.post (
        this._sActionsUrl + 'get_timeline/',
        this._getDefaultData(),
        function(oData) {                                    
        	$this.loadingInBlock(oElement, false);

        	if($.trim(oData.timeline).length > 0)
        		oView.find('.bx-tl-timeline').replaceWith(oData.timeline);
        },
        'json'
    );
};
