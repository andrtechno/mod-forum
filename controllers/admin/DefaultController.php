<?php

namespace panix\mod\forum\controllers\admin;

use Yii;
use panix\engine\Html;
use panix\mod\forum\models\Categories;
use panix\engine\controllers\AdminController;

class DefaultController extends AdminController {

    public function actionIndex() {
        $this->pageName = $this->module->name;
        $this->breadcrumbs = array($this->pageName);
        $model = new Categories;

        return $this->render('index', array('model' => $model));
    }

    /**
     * Create or update new page
     * @param bool $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id = false) {
        /** @var Categories|\panix\engine\behaviors\nestedsets\NestedSetsBehavior $model */
        $model = Categories::findModel($id);



        $isNewRecord = ($model->isNewRecord) ? true : false;
        $this->breadcrumbs[] = [
            'label' => $this->module->name,
            'url' => ['index']
        ];
        $this->breadcrumbs[] = ($model->isNewRecord) ? $model::t('PAGE_TITLE') : Html::encode($model->name);
        $this->pageName = ($model->isNewRecord) ? $model::t('PAGE_TITLE') : $model::t('PAGE_TITLE');


        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->validate()) {
            if (Yii::$app->request->get('parent_id')) {
                $parent = Categories::findOne(Yii::$app->request->get('parent_id'));
            } else {
                $parent = Categories::findOne(1);
            }
            if ($model->getIsNewRecord()) {
                if($parent){
                    $model->appendTo($parent);
                }else{
                    $model->saveNode();
                }
            } else {
                $model->saveNode();
            }
            return $this->redirect(['index']);

        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDeleteFile() {
        echo CJSON::encode(array('success' => 'true', 'key' => $_POST['key']));
        die;
    }

    public function actionSwitchNode() {
        //$switch = $_GET['switch'];
        $node = Categories::findOne($_GET['id']);
        $node->switch = ($node->switch == 1) ? 0 : 1;
        $node->saveNode();
        echo \yii\helpers\Json::encode(array(
            'switch' => $node->switch,
            'message' => Yii::t('ShopModule.admin', 'CATEGORY_TREE_SWITCH', $node->switch)
        ));
        die;
    }

    /**
     * Drag-n-drop nodes
     */
    public function actionMoveNode() {
        $node = Categories::findOne($_GET['id']);
        $target = Categories::findOne($_GET['ref']);

        if ((int) $_GET['position'] > 0) {
            $pos = (int) $_GET['position'];
            $childs = $target->children()->all();
            if (isset($childs[$pos - 1]) && $childs[$pos - 1] instanceof ForumCategories && $childs[$pos - 1]['id'] != $node->id)
                $node->moveAfter($childs[$pos - 1]);
        } else
            $node->moveAsFirst($target);

        $node->rebuildFullPath()->saveNode(false);
    }

    public function actionRenameNode() {


        if (strpos($_GET['id'], 'j1_') === false) {
            $id = $_GET['id'];
        } else {
            $id = str_replace('j1_', '', $_GET['id']);
        }

        $model = Categories::findOne((int) $id);
        if ($model) {
            $model->name = $_GET['text'];
            $model->slug = CMS::translit($model->name);
            if ($model->validate()) {
                $model->saveNode(false, false);
                $message = Yii::t('shop/admin', 'CATEGORY_TREE_RENAME');
            } else {
                $message = $model->getError('slug');
            }
            echo \yii\helpers\Json::encode(array(
                'message' => $message
            ));
            die;
        }
    }

    public function actionCreateNode() {
        $model = new Categories;
        $parent = Categories::model()->findByPk((int) $_GET['parent_id']);

        $model->name = $_GET['text'];
        $model->slug = CMS::translit($model->name);
        if ($model->validate()) {

            $model->appendTo($parent);
            $message = Yii::t('ShopModule.admin', 'CATEGORY_TREE_CREATE');
        } else {
            $message = $model->getError('slug');
        }
        echo \yii\helpers\Json::encode(array(
            'message' => $message
        ));
        die;
    }

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionDelete($id) {
        $model = Categories::model()->findByPk($id);

        //Delete if not root node
        if ($model && $model->id != 1) {
            foreach (array_reverse($model->descendants()->all()) as $subCategory) {
                $subCategory->deleteNode();
            }
            $model->deleteNode();
        }
    }

    //TODO need multi language add and test
    public function actionCreateRoot() {
        $model = new Categories;
        $model->name = 'Каталог продукции';
        $model->lft = 1;
        $model->rgt = 2;
        $model->level = 1;
        $model->slug = 'root';
        $model->full_path = '';
        $model->image = NULL;
        $model->switch = 1;
        $model->saveNode();
        $this->redirect(array('create'));
    }

    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('app', 'SETTINGS'),
                'url' => array('/admin/forum/settings/index'),
                'icon' => Html::icon('icon-settings'),
            ),
        );
    }

}
