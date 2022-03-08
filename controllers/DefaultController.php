<?php

namespace panix\mod\forum\controllers;

use Yii;
use panix\mod\forum\models\Categories;
use panix\engine\controllers\WebController;


class DefaultController extends WebController
{
    public $model;


    public function beforeAction($action)
    {

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $this->model = Categories::find()->roots()->all();
        $this->pageName = Yii::t('forum/default', 'MODULE_NAME');
        return $this->render('index', array(
            'categories' => $this->model,
        ));
    }

    public function actionView($id)
    {

        $this->pageName = Yii::t('forum/default', 'MODULE_NAME');
        $this->model = Categories::findOne($id);


        if (!$this->model)
            $this->error404();


        //$this->dataModel->saveCounters(array('views' => 1));


        $ancestors = $this->model->ancestors()->excludeRoot()->all();
        $this->view->params['breadcrumbs'][] = [
            'label' => $this->pageName,
            'url' => ['/forum']
        ];
        foreach ($ancestors as $c) {
            $this->view->params['breadcrumbs'][] = [
                'label' => $c->name,
                'url' => $c->getUrl()
            ];
        }

        $this->view->params['breadcrumbs'][] = $this->model->name;
        return $this->render('view', array(
            'model' => $this->model,
        ));
    }

    public function actionAddCat($parent_id)
    {
        //  if ($new === true)
        //     $model = new ForumCategories;
        // else {
        //     $model = ForumCategories::model()
        //            ->findByPk($_GET['parent_id']);
        //  }
        //if (!$model)
        //    throw new CHttpException(404, Yii::t('forum/admin', 'NO_FOUND_CATEGORY'));
        // $oldImage = $model->image;

        $model = new Categories;

        if (!$model)
            $this->error404(Yii::t('forum/admin', 'NO_FOUND_CATEGORY'));

        $parent = Categories::find()->one($parent_id);


        if (Yii::$app->request->isPost) {
            $model->attributes = $_POST['ForumCategories'];

            if ($model->validate()) {

                if ($model->getIsNewRecord()) {
                    $model->appendTo($parent);
                } else {
                    $model->saveNode();
                }
            }
        }
        $this->render('addcat', array('model' => $model));
    }

    public function actionQuote()
    {
        if (Yii::$app->request->isAjax) {
            $result = array();

            $post = Posts::find()->one($_GET['post_id']);

            $result['quote_html'] = $this->renderPartial('partials/_ajax_quote_html', array('post' => $post), true, false);
            echo CJSON::encode($result);
            Yii::$app->end();
        } else {
            throw new CHttpException(500);
        }
    }


}
