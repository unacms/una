
    function BxDataMigration()
    {
        var sPathToModule;
        var sLoadingImg;

        /**
         *  Function will get ajax query;
         *
         * @return : (text) - html presentation data;
         */
        this.moveData = function(sModule)
        {
            var oWrapper = $('#callback');

            oWrapper.html('<img id="loading_img" src="' + this.sLoadingImg + '" />');

            //loading.gif
            var _sRandom = Math.random();
            $.get(this.sPathToModule + sModule + '&_random=' + _sRandom , function(sData){
                oWrapper.find('#loading_img').remove();
                oWrapper.html(sData);
            });
        }
    }

    var oDataMigration = new BxDataMigration();