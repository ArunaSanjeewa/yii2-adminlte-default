<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;



class IoniconsAsset extends AssetBundle
{
  
    // public $sourcePath = '@vendor/bower-asset/adminlte/plugins';
    public $sourcePath = '@vendor/almasaeed2010/adminlte/bower_components/';

    public $css = [
        'ionicons/css/ionicons.css',
        'font-awesome/css/font-awesome.min.css'
    ];

    public $js = [
        'chart.js/Chart.js',
        'fastclick/lib/fastclick.js',
    ];

    
}