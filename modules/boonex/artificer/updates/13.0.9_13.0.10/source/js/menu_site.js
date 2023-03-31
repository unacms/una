/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Artificer Artificer template
 * @ingroup     UnaModules
 *
 * @{
 */

function BxArtificerMenuMoreAuto(oOptions) {
    BxDolMenuMoreAuto.call(this, oOptions);
}

BxArtificerMenuMoreAuto.prototype = Object.create(BxDolMenuMoreAuto.prototype);
BxArtificerMenuMoreAuto.prototype.constructor = BxArtificerMenuMoreAuto;

BxArtificerMenuMoreAuto.prototype.init = function(bForceInit)
{
    BxDolMenuMoreAuto.prototype.init.call(this);

    var $this = this;
    var oImages = $('#bx-toolbar').find('img');

    if(!oImages.length)
        $(document).ready(function() {
            $this.update(true);
        });
    else
        oImages.bind('load', function() {
            setTimeout(function() {
                $this.update(true);
            }, 100);
        });
};