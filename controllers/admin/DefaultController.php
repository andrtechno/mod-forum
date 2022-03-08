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
        $this->view->params['breadcrumbs'] = array($this->pageName);
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
        $this->view->params['breadcrumbs'][] = [
            'label' => $this->module->name,
            'url' => ['index']
        ];
        $this->view->params['breadcrumbs'][] = ($model->isNewRecord) ? $model::t('CREATE_CATEGORY') : Html::encode($model->name);
        $this->pageName = ($isNewRecord) ? $model::t('CREATE_CATEGORY') : $model::t('UPDATE_CATEGORY');


        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->validate()) {
            if (Yii::$app->request->get('parent_id')) {
                $parent = Categories::findOne(Yii::$app->request->get('parent_id'));
            } else {
                //$parent = Categories::findOne(1);
               // CMS::dump($model->parent()->one());
               // die;
                $parent=false;
                if(!$model->getIsNewRecord() && $model->parent()){
                    $parent = $model->parent()->one();
                    if(!$model->parent()->count()){
                        //  $parent = $model->parent()->one();
                        $model->lft = 1;
                        $model->rgt = 2;
                        $model->level = 1;
                        $model->slug = 'root111';
                        $model->full_path = '';
                        $model->image = NULL;
                        $model->switch = 1;
                    }
                }


            }


            if(!$model->getIsNewRecord()){
                $model->saveNode();
            } else{
                if($parent){
                    $model->appendTo($parent);
                }else{
                    $model->saveNode();
                }
            }

            return $this->redirect(['index']);

        }
        return $this->render('update', ['model' => $model]);
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
            $message = Yii::t('shop/admin', 'CATEGORY_TREE_CREATE');
        } else {
            $message = $model->getError('slug');
        }
        echo \yii\helpers\Json::encode(array(
            'message' => $message
        ));
        die;
    }
    public function actionCreate()
    {
        return $this->actionUpdate(false);
    }

    //TODO need multi language add and test
    public function actionSection() {
        /** @var Categories|NestedSetsBehavior $model */
        $model = new Categories;
      //  $model->name = 'Каталог продукции2';
        $model->lft = 1;
        $model->rgt = 2;
        $model->level = 1;
        $model->slug = 'root';
        $model->full_path = '';
        $model->image = NULL;
        $model->switch = 1;
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->validate()) {
            $model->saveNode();
            return $this->redirect(['index']);
        }
        return $this->render('section', ['model' => $model]);


    }

    public function getAddonsMenu() {
        return [
            [
                'label' => Yii::t('app/default', 'SETTINGS'),
                'url' => ['/admin/forum/settings/index'],
                'icon' => Html::icon('icon-settings'),
            ],
        ];
    }

}
