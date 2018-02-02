/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    MapJoined Display last joined users on map
 * @ingroup     UnaModules
 *
 * @{
 */

function BxMapJoined(oOptions) {
    this._iMapPointsLastId = 0;
    this._oMap = null;
    this._sActionsUri = oOptions.sActionUri;
    this._sPathToJsonData = oOptions.sPathToJsonData == undefined ? '' : oOptions.sPathToJsonData;
    this._sPathToDotImage = oOptions.sPathToDotImage == undefined ? {} : oOptions.sPathToDotImage;
    this._iIntervalCheckNewInSeconds = oOptions.iIntervalCheckNewInSeconds == undefined ? 5 : new Number(oOptions.iIntervalCheckNewInSeconds);
    this._fCenterMapLonCoordinate = oOptions.fCenterMapLonCoordinate == undefined ? 2.896372 : new Number(oOptions.fCenterMapLonCoordinate);
    this._fCenterMapLatCoordinate = oOptions.fCenterMapLatCoordinate == undefined ? 44.60240 : new Number(oOptions.fCenterMapLatCoordinate);
    this._fMapZoom = oOptions.fMapZoom == undefined ? 2 : new Number(oOptions.fMapZoom);
    var $this = this;
    $(document).ready(function () {
        $this.init();
    });
}

BxMapJoined.prototype.init = function () {
    var $this = this;

    var oCanvas = document.createElement('canvas');
    var oContext = oCanvas.getContext('2d');
    var iPixelRatio = ol.has.DEVICE_PIXEL_RATIO;
    var oPattern = (function () {
        oCanvas.width = oCanvas.height = 10 * iPixelRatio;
        oContext.fillStyle = 'white';
        oContext.fillRect(0, 0, oCanvas.width, oCanvas.height);
        oContext.fillStyle = 'rgb(214, 227, 241)';
        oContext.beginPath();
        oContext.arc(5 * iPixelRatio, 5 * iPixelRatio, 3 * iPixelRatio, 0, 2 * Math.PI);
        oContext.fill();
        return oContext.createPattern(oCanvas, 'repeat');
    }());

    var oFill = new ol.style.Fill();
    var oStyle = new ol.style.Style({
        fill: oFill,
        stroke: new ol.style.Stroke({
            color: '#fff',
            width: 1
        })
    });

    var getStackedStyle = function () {
        oFill.setColor(oPattern);
        return oStyle;
    };

    var oBaseVectorLayer = new ol.layer.Vector({
        source: new ol.source.Vector({
            url: $this._sPathToJsonData,
            format: new ol.format.GeoJSON()
        }),
        style: getStackedStyle
    });

    $this._oMap = new ol.Map({
        layers: [oBaseVectorLayer],
        target: document.getElementById('BxMapJoinedContainer'),
        view: new ol.View({
            center: ol.proj.fromLonLat([$this._fCenterMapLonCoordinate, $this._fCenterMapLatCoordinate]),
            zoom: $this._fMapZoom
        }),
        controls: [],
        interactions: ol.interaction.defaults({
            dragRotate: false,
            dragPan: false,
            pinchRotate: false,
            pinchZoom: false,
            doubleClickZoom: false,
            dragAndDrop: false,
            keyboardPan: false,
            keyboardZoom: false,
            mouseWheelZoom: false,
            pointer: false,
            select: false
        })
    });

    $this.addPoints();
}

BxMapJoined.prototype.addPoints = function () {

    var $this = this;
    var aPoints = new Array();
    $.getJSON($this._sActionsUri + 'GetMapPoints/' + this._iMapPointsLastId + '/', function (aMapPoints) {
        $.each(aMapPoints, function (key, value) {
            if (this._iMapPointsLastId < new Number(value.id))
                this._iMapPointsLastId = new Number(value.id);
            var oPoint = new ol.Feature({
                geometry: new ol.geom.Point(ol.proj.fromLonLat([new Number(value.lng), new Number(value.lat)]))
            });
            oPoint.setStyle(new ol.style.Style({
                image: new ol.style.Icon(({
                    crossOrigin: 'anonymous',
                    src: $this._sPathToDotImage
                }))
            }));
            aPoints.push(oPoint);
        });

        if (aPoints.length > 0) {
            var oPointSource = new ol.source.Vector({
                features: aPoints
            });

            var oPointsVectorLayer = new ol.layer.Vector({
                source: oPointSource
            });
            $this._oMap.addLayer(oPointsVectorLayer);
        }
        setTimeout(function () {
            $this.addPoints();
        },  $this._iIntervalCheckNewInSeconds*1000);
    });
}


