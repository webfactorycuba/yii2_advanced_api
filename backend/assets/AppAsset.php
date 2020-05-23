<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'leaflet/libs/leaflet.css',
        'leaflet/src/leaflet.draw.css',
        'leaflet/plugins/leaflet-sidebar.css',
        'leaflet/plugins/Control.OSMGeocoder.css',
        'leaflet/plugins/leaflet-search.css'
    ];
    public $js = [
        'leaflet/libs/leaflet-src.js',

        'leaflet/src/Leaflet.draw.js',
        'leaflet/src/Leaflet.Draw.Event.js',
        'leaflet/src/Toolbar.js',
        'leaflet/src/Tooltip.js',
        'leaflet/src/Control.Draw.js',

        'leaflet/src/ext/GeometryUtil.js',
        'leaflet/src/ext/LatLngUtil.js',
        'leaflet/src/ext/LineUtil.Intersect.js',
        'leaflet/src/ext/Polygon.Intersect.js',
        'leaflet/src/ext/Polyline.Intersect.js',
        'leaflet/src/ext/TouchEvents.js',

        'leaflet/src/draw/DrawToolbar.js',

        'leaflet/src/draw/handler/Draw.Feature.js',
        'leaflet/src/draw/handler/Draw.SimpleShape.js',
        'leaflet/src/draw/handler/Draw.Polyline.js',
        'leaflet/src/draw/handler/Draw.Marker.js',
        'leaflet/src/draw/handler/Draw.CircleMarker.js',
        'leaflet/src/draw/handler/Draw.Circle.js',
        'leaflet/src/draw/handler/Draw.Polygon.js',
        'leaflet/src/draw/handler/Draw.Rectangle.js',

        'leaflet/src/edit/EditToolbar.js',

        'leaflet/src/edit/handler/EditToolbar.Edit.js',
        'leaflet/src/edit/handler/EditToolbar.Delete.js',
        'leaflet/src/edit/handler/Edit.Poly.js',
        'leaflet/src/edit/handler/Edit.SimpleShape.js',
        'leaflet/src/edit/handler/Edit.CircleMarker.js',
        'leaflet/src/edit/handler/Edit.Circle.js',
        'leaflet/src/edit/handler/Edit.Rectangle.js',

        'leaflet/plugins/Leaflet.Control.Custom.js',
        'leaflet/plugins/Leaflet.CountrySelect.js',
        'leaflet/plugins/leaflet-sidebar.js',
        'leaflet/plugins/Control.OSMGeocoder.js',
        'leaflet/plugins/leaflet-search.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
