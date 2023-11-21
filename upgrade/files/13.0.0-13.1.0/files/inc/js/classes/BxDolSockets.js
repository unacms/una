/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolSockets(oOptions)
{
    this._sKey = oOptions.sKey;
    this._sHost = oOptions.sHost;
    this._sPort = oOptions.sPort;
    this._sObject = oOptions.sObject === undefined ? 'oBxDolSockets' : oOptions.sObject;

    this._oPusher = new Pusher(this._sKey, {
        wsHost: oOptions.sHost,
        wsPort: oOptions.sPort,
        forceTLS: false, 
        enabledTransports: ['ws', 'wss'],
        cluster:''
    });
}

BxDolSockets.prototype.subscribe = function(module, content_id, event, cb)
{
    if(!this._oPusher)
        return;

    var channel = this._oPusher.subscribe(module + '_' + content_id);
    channel.bind(event, function(data) {
        cb(data)
    });
}

/** @} */
