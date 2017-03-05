<?php
namespace sbs\web;

use yii\web\AssetBundle;

/**
 * Class AdminLtePluginAsset
 * @since 0.1
 */
class AdminLtePluginAsset extends AssetBundle
{
    public $sourcePath = '@bower/admin-lte/plugins';
    public $css = [
        'iCheck/square/blue.css',
        // more plugin CSS here
    ];
    public $js = [
        'iCheck/icheck.min.js',
        'slimScroll/jquery.slimscroll.min.js',
        'fastclick/fastclick.js',
        // more plugin Js here
    ];
    public $depends = [
        'sbs\web\AdminLteAsset',
    ];
}
