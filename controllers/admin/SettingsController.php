<?php

namespace panix\mod\forum\controllers\admin;

use Yii;
use panix\mod\forum\models\SettingsForm;

class SettingsController extends \panix\engine\controllers\AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('app', 'SETTINGS');
        /* $this->breadcrumbs = array(
          $this->module->name => $this->module->adminHomeUrl,
          $this->pageName
          ); */

        $model = new SettingsForm;

        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->validate()) {
            if ($model->validate()) {
                $model->save();
            }
            $this->refresh();
        }

        return $this->render('index', array('model' => $model));
    }

}
