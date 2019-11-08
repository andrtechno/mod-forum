<?php

namespace panix\mod\forum\controllers\admin;

use panix\engine\behaviors\nestedsets\NestedSetsBehavior;
use Yii;
use panix\engine\Html;
use panix\engine\CMS;
use panix\mod\forum\models\Categories;
use panix\engine\controllers\AdminController;
use yii\web\Response;

class DefaultController extends AdminController {


    public function actions()
    {
        return [
            'rename-node' => [
                'class' => 'panix\engine\behaviors\nestedsets\actions\RenameNodeAction',
                'modelClass' => Categories::class,
            ],
            'move-node' => [
                'class' => 'panix\engine\behaviors\nestedsets\actions\MoveNodeAction',
                'modelClass' => Categories::class,
            ],
            'switch-node' => [
                'class' => 'panix\engine\behaviors\nestedsets\actions\SwitchNodeAction',
                'modelClass' => Categories::class,
            ],
            'delete-node' => [
                'class' => 'panix\engine\behaviors\nestedsets\actions\DeleteNodeAction',
                'modelClass' => Categories::class,
            ],
        ];
    }

    public function actionIndex() {
        $this->pageName = $this->module->name;
        $this->breadcrumbs = array($this->pageName);
        $model = new Categories;
        $this->buttons = [
            [
                'icon' => 'add',
                'label' => Yii::t('forum/default', 'CREATE_CATEGORY'),
                'url' => ['create'],
                'options' => ['class' => 'btn btn-success']
            ]
        ];
        return $this->render('index', ['model' => $model]);
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

    /**
     * @return array
     */
    public function actionSwitchNode2() {
        /**
         * @var Categories|NestedSetsBehavior $node
         */
        Yii::$app->response->format = Response::FORMAT_JSON;
        $node = Categories::findOne(Yii::$app->request->get('id'));
        $node->switch = ($node->switch == 1) ? 0 : 1;
        $node->saveNode();
        return [
            'switch' => $node->switch,
            'message' => Yii::t('shop/Category', ($node->switch) ? 'CATEGORY_TREE_SWITCH_OFF' : 'CATEGORY_TREE_SWITCH_ON')
        ];
    }

    /**
     * Drag-n-drop nodes
     */
    public function actionMoveNode2()
    {
        /**
         * @var NestedSetsBehavior|Categories $node
         * @var NestedSetsBehavior|Categories $target
         */
        $node = Categories::findModel(Yii::$app->request->get('id'));
        $target = Categories::findOne(Yii::$app->request->get('ref'));


        $pos = (int) Yii::$app->request->get('position');

        if ($pos == 1) {

            $childs = $target->children()->all();
            if (isset($childs[$pos - 1]) && $childs[$pos - 1]['id'] != $node->id) {
                // die('moveAfter');
                $node->moveAfter($childs[$pos - 1]);
            }
        }elseif($pos == 2){
            $childs = $target->children()
                //->orderBy(['lft'=>SORT_DESC])
                ->all();
            // echo count($childs);die;
            // if (isset($childs[$pos - 1]) && $childs[$pos - 1]['id'] != $node->id) {
            // die('moveAfter');


            if (isset($childs[$pos - 1]) && $childs[$pos - 1]['id'] != $node->id) {
                $node->moveAfter($childs[$pos - 1]);
            }

        } else{
            $node->moveAsFirst($target);
        }

        $node->rebuildFullPath();
        $node->saveNode(false);
    }
    public function actionRenameNode2()
    {
        /**
         * @var NestedSetsBehavior|Categories $model
         */
        if (strpos(Yii::$app->request->get('id'), 'j1_') === false) {
            $id = Yii::$app->request->get('id');
        } else {
            $id = str_replace('j1_', '', Yii::$app->request->get('id'));
        }

        $model = Categories::findOne((int)$id);
        if ($model) {
            $model->name = Yii::$app->request->get('text');
            $model->slug = CMS::slug($model->name);
            if ($model->validate()) {
                $model->saveNode(false);
                $success = true;
                $message = Yii::t('shop/Category', 'CATEGORY_TREE_RENAME');
            } else {
                $success = false;
                $message = Yii::t('shop/Category', 'ERROR_CATEGORY_TREE_RENAME');
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'message' => $message,
                'success' => $success
            ];

        }
    }

    public function actionCreateNode2() {
        /**
         * @var Categories|NestedSetsBehavior $model
         * @var Categories|NestedSetsBehavior $parent
         */
        $model = new Categories;
        $parent = Categories::findOne((int) $_GET['parent_id']);

        $model->name = $_GET['text'];
        $model->slug = CMS::slug($model->name);
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
     * @param integer $id
     */
    public function actionDelete2($id) {
        /** @var Categories|NestedSetsBehavior $model */
        $model = Categories::findOne($id);

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
        /** @var Categories|NestedSetsBehavior $model */
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
        return $this->redirect(['create']);
    }

    public function getAddonsMenu() {
        return [
            [
                'label' => Yii::t('app', 'SETTINGS'),
                'url' => ['/admin/forum/settings/index'],
                'icon' => Html::icon('icon-settings'),
            ],
        ];
    }

}
