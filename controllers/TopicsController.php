<?php

namespace panix\mod\forum\controllers;

use Yii;
use panix\mod\forum\models\Topics;
use panix\mod\forum\models\Categories;
use panix\mod\forum\models\Posts;
class TopicsController extends \panix\engine\controllers\WebController {
    public $model;
    public function actionAdd($id) {

        $this->pageName = Yii::t('forum/default', 'MODULE_NAME');

        $model = new Topics;
        $model->category_id = $id;


        $category = Categories::findOne($id);


      //  $ancestors = $category->ancestors()->excludeRoot()->all();
        $this->breadcrumbs[] = [
            'label'=>$this->pageName,
            'url'=>['/forum']
];
       // foreach ($ancestors as $c){
           // $this->breadcrumbs[$c->name] = $c->getUrl();
      //  }
        $this->breadcrumbs[] = $category->name;
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->validate()) {
                if ($model->save()) {


                    $post = new Posts;
                    $post->topic_id = $model->id;
                    $post->text = $model->text;
                    $post->save(false);

                    $model->category->count_topics++;
                    $model->category->last_post_user_id = $post->user_id;
                    $model->category->last_post_id = $post->id;
                    $model->category->last_topic_id = $model->id;

                    $model->category->saveNode(false);
                    $ancestors = $model->category->ancestors()->all();
                    if ($ancestors) {
                        foreach ($ancestors as $category) {
                            $category->count_topics++;
                            $category->last_post_user_id = $post->user_id;
                            $category->last_post_id = $post->id;
                            $category->last_topic_id = $model->id;
                            $category->saveNode(false);
                        }
                    }
                }
                return $this->redirect(array('/forum/default/view', 'id' => $_GET['id']));
        
        }
        return $this->render('addtopic', array('model' => $model, 'category' => $category));
    }

    public function actionView($id) {
        $this->pageName = Yii::t('forum/default', 'MODULE_NAME');
        $this->model = Topics::findOne($id);


        if (!$this->model)
            $this->error404();


        $this->model->updateCounters(['views' => 1]);


        /*$this->breadcrumbs = array(
            $this->pageName => array('/forum'),
            $this->model->category->name => $this->model->category->getUrl(),
            $this->model->title
        );*/


        $providerPosts = new \yii\data\ArrayDataProvider([
                    'allModels'=>$this->model->posts, 
            'pagination' => array(
                'pageSize' => 10,
            )
                ]
        );

        return $this->render('view', array(
            'model' => $this->model,
            'providerPosts' => $providerPosts
        ));
    }

    public function actionAddreply() {
        if (!Yii::$app->user->isGuest) {
            $postModel = new Posts;
            $view = (Yii::$app->request->isAjax) ? '_form_addreply' : '_form_addreply';
            $request = Yii::$app->request;

            if ($request->isPost && $request->isAjax) { // && $request->isAjaxRequest
                $postModel->attributes = $request->getPost('ForumPosts');

                if ($postModel->validate()) {
                    if ($postModel->save()) {

                        $postModel->topic->date_update = date('Y-m-d H:i:s', CMS::time());
                        $postModel->topic->save(false);

                        $postModel->topic->category->last_post_user_id = $postModel->user_id;
                        $postModel->topic->category->last_post_id = $postModel->id;
                        $postModel->topic->category->last_topic_id = $postModel->topic_id;

                        $postModel->topic->category->count_posts++;


                        $postModel->topic->category->saveNode(false, false, false);
                        $ancestors = $postModel->topic->category->ancestors()->findAll();
                        if ($ancestors) {
                            foreach ($ancestors as $category) {
                                $category->last_post_user_id = $postModel->user_id;
                                $category->last_post_id = $postModel->id;
                                $category->last_topic_id = $postModel->topic_id;
                                $category->count_posts++;
                                $category->saveNode(false);
                            }
                        }
                    }
                    Yii::$app->user->setFlash('success', 'Success!!!');
                } else {
                    print_r($postModel->getErrors());
                    die;
                }
                $this->render($view, array(
                    'model' => $postModel
                ));
            }
        } else {
            throw new Exception('NOPauth');
        }
    }

    public function actionEditpost($id) {
        if (Yii::$app->request->isAjax) {

            $cs = Yii::$app->getClientScript();
            $cs->scriptMap = array(
                //  'jquery.yiigridview.js'=>false,
                // 'jquery.js' => false,
                //'jquery.min.js' => false,
              //  'common.js' => false,
            );

            $result = array();

            $post = Posts::findOne($id);
            $request = Yii::$app->request;

            if ($request->isPost && $request->isAjax) { // && $request->isAjaxRequest
                $post->attributes = $request->post('Posts');
                if (!empty($post->edit_reason)) {
                    $post->edit_user_id = Yii::$app->user->getId();
                }
                if ($post->validate()) {
                    if ($post->save()) {
                        $result['post'] = $this->renderPartial('_posts_content', array(
                            'data' => $post
                                ), true, false);
                        $result['message'] = Yii::t('forum/default','POST_EDITED');
                        $result['id'] = $id;
                        echo CJSON::encode($result);
                        Yii::$app->end();
                    }
                }
            }
            return $this->render('_form_editpost', array(
                'model' => $post
                    ), false, true);
        } else {
            throw new CHttpException(500);
        }
    }

}
