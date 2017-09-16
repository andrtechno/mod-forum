<?php
namespace panix\mod\forum\controllers;

use Yii;
use panix\mod\forum\models\Categories;
class DefaultController extends \panix\engine\controllers\WebController {

    public function actionIndex() {
        $this->dataModel = Categories::find()->roots()->all();
        $this->pageName = Yii::t('forum/default', 'MODULE_NAME');
        $this->render('index', array(
            'categories' => $this->dataModel,
        ));
    }

    public function actionView($id) {
        $this->pageName = Yii::t('forum/default', 'MODULE_NAME');
        $this->dataModel = Categories::find()
                ->published()
                //->with('parents')
                ->one($id);


        if (!$this->dataModel)
            throw new CHttpException(404);


        //$this->dataModel->saveCounters(array('views' => 1));



        $ancestors = $this->dataModel->ancestors()->excludeRoot()->all();
        $this->breadcrumbs = array($this->pageName => array('/forum'));
        foreach ($ancestors as $c)
            $this->breadcrumbs[$c->name] = $c->getUrl();

        $this->breadcrumbs[] = $this->dataModel->name;
        $this->render('view', array(
            'model' => $this->dataModel,
        ));
    }

    public function actionAddCat($parent_id) {
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
            throw new CHttpException(404, Yii::t('forum/admin', 'NO_FOUND_CATEGORY'));

        $parent = Categories::model()->findByPk($parent_id);


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

    public function actionQuote() {
        if (Yii::$app->request->isAjaxRequest) {
            $result = array();

            $post = ForumPosts::model()->findByPk($_GET['post_id']);

            $result['quote_html'] = $this->renderPartial('partials/_ajax_quote_html', array('post' => $post), true, false);
            echo CJSON::encode($result);
            Yii::$app->end();
        } else {
            throw new CHttpException(500);
        }
    }


}
