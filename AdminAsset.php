<?php

/**
 *
 * @author PIXELION CMS development team <dev@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 */

namespace panix\mod\forum;

use panix\engine\web\AssetBundle;

class AdminAsset extends AssetBundle
{

    public $sourcePath = __DIR__ . '/assets/admin';

    public $js = [
        'js/tree.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
