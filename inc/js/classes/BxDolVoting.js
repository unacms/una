/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

function BxDolVoting (sUrl, sSystem, iObjId, sId, sIdSlider, iSize, iMax)
{
    this._sUrl = sUrl;
    this._sSystem = sSystem;
    this._iObjId = iObjId;
    this._sId = sId;
    this._sIdSlider = sIdSlider;
    this._iSize = iSize;
    this._iMax = iMax;
    this._iSaveWidth = -1;
}

BxDolVoting.prototype.over = function (i)
{
    var e = this._e(this._sIdSlider)
    this._iSaveWidth = parseInt(e.style.width);
    e.style.width = i*this._iSize + 'px';
}

BxDolVoting.prototype.setRate = function (fRate)
{
    var e = this._e(this._sIdSlider);
    e.style.width = fRate*this._iSize + 'px';
}

BxDolVoting.prototype.setCount = function (iCount)
{
    var e = this._e(this._sId);
    var eb = e.getElementsByTagName('b')[0];
    if (eb != undefined) {
        var a = eb.innerHTML.match(/(\d+)/);
        eb.innerHTML = eb.innerHTML.replace(a[1], iCount);
    }
}

BxDolVoting.prototype.out = function ()
{
    var e = this._e(this._sIdSlider)
    e.style.width = parseInt(this._iSaveWidth) + 'px';
}

BxDolVoting.prototype.vote = function (i)
{
    var $this = this;
    var h = function (s)
    {
        if (!s.length)
        {
            $this.onvotefail();
            return;
        }
        var a = s.match(/([0-9\.]+),([0-9\.]+)/);
        $this._iSaveWidth = i*$this._iSize;
        $this.setRate(i);
        $this.setCount(a[2]);
        $this.onvote(a[1], a[2]);
    }

    jQuery.post(this._sUrl+'vote.php?vote_send_result='+i+'&id='+this._iObjId+'&sys='+this._sSystem, h);
}

BxDolVoting.prototype.onvote = function (fRate, iCount)
{

}

BxDolVoting.prototype.onvotefail = function ()
{

}

BxDolVoting.prototype._e = function (s)
{
    return document.getElementById(s);
}


BxDolVoting.prototype.sendRequest = function (sUrl, h)
{
    jQuery.get(sUrl, h);
}
