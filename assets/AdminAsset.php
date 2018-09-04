<?php

/**
 *
 * @author PIXELION CMS development team <dev@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 */

namespace panix\mod\forum\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle {

    public $sourcePath = '@vendor/panix/mod-forum/assets';
    public $jsOptions = array(
        'position' => \yii\web\View::POS_END
    );
    public $js = [
        'js/tree.js',
    ];

}
