/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup	Messenger Messenger
 * @ingroup	UnaModules
 * @{
 */
 
/**
 * Primus class to work with the server and main messenger class
 */ 
var oRTWSF = (function(){
	var _oPrimus = null,
		_sIP = '0.0.0.0';
	
	return {
		/**
		* Init Primus with provided settings and attaches listeners on event emitters from the server
		*@param object oOptions options
		*/
		init:function(oOptions){
			var _this = this;			
				_this._sIP = oOptions.ip || _this._sIP;
			
			if (_oPrimus == null)
					_oPrimus = new Primus(oOptions.server || 'https://una.io:5443');				

			// on data received from the server
			_oPrimus.on('data', function(oData) {
					if (typeof oData.action !== "undefined" && !_oPrimus.emit(oData.action, oData))
								console.log('Unknown server response', oData);						
							
					}).on('error', function error(oError) {
						console.log('Primus Error', oError);				
					
					}).on('reconnect scheduled', function (opts) {
						console.log('Reconnecting in %d ms', opts.scheduled);
						console.log('This is attempt %d out of %d', opts.attempt, opts.retries);				
					
					}).on('reconnected', function (opts) {	
						var oSettings = _this.getSettings();				
						
						if (typeof oSettings !== 'undefined' && typeof oSettings.user_id !== 'undefined' && typeof oSettings.status !== 'undefined')
						_this.exec('init', {
							 user_id: oSettings.user_id,
							 status: oSettings.status 
						});	
					}).on('typing', function (oData) {
						_this.onTyping(oData);
					}).on('msg', function (oData){
						_this.onMessage(oData);
					}).on('update_status', function (oData) {
						_this.onStatusUpdate(oData);
					}).on('check_sent', function (oData) {
						_this.onServerResponse(oData);
					}).on('denied', function (oData) {
						console.log('Access Denied for your IP');
					})
			
		},

	 /* Methods occur on received data from the server begin */
		onTyping:function(oData){
			console.log('overwrite it in the main messenger class');
		},
		onMessage:function(oData){
			console.log('overwrite it in the main messenger class');
		},
		onStatusUpdate:function(oData){
			console.log('overwrite it in the main messenger class');
		},
		getSettings:function(){
			console.log('overwrite it in the main messenger class');
		},
		onServerResponse:function(){
			console.log('overwrite it in the main messenger class');
		},
	 /*Methods occur on received data from the server end */
	 
	 /*Methods are called on members' activities in chat window and send data to the server begin */
		initSettings:function(oData){							
			this.exec('init', oData);
		},
		message:function(oData){
			this.exec('msg', oData);
		},
		typing:function(oData){
			this.exec('typing', oData);
		},
		end:function(oData){
			this.exec('before_delete', oData);
			_oPrimus.end();
		},
		updateStatus:function(oData){
			this.exec('update_status', oData);
		},
		exec:function(sParam, oData){
			if (oData != undefined){
				_oPrimus.write($.extend(oData, {action:sParam, ip:this._sIP}));
			}			
			return false;
		}
		/*Methods are called on members' activities in chat window and send data to the server end */
	}	
})();

/** @} */
