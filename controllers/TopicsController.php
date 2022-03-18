<?php

namespace panix\mod\forum\controllers;

use panix\engine\Html;
use Yii;
use panix\mod\forum\models\Topics;
use panix\mod\forum\models\Categories;
use panix\mod\forum\models\Posts;
use panix\engine\controllers\WebController;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class TopicsController extends WebController
{
    public $model;

    public function actions()
    {
        return [
            'topic-close22' => [
                'class' => 'panix\mod\forum\actions\TopicCloseAction',
                'modelClass' => Topics::class,
            ],

        ];
    }

    public function actionClose($id)
    {
        if (Yii::$app->user->can('admin')) {
            $topic = Topics::findOne($id);

            if ($topic) {
                $topic->is_close = ($topic->is_close) ? 0 : 1;
                $topic->save(false);
                return $this->redirect($topic->getUrl());
            }
            // } else {
            //     die('error 403');
        }else{
            throw new ForbiddenHttpException();
        }
    }

    public function actionAdd($id)
    {

        $this->pageName = Yii::t('forum/default', 'MODULE_NAME');

        $model = new Topics;
        $model->category_id = $id;


        $category = Categories::findOne($id);


        //  $ancestors = $category->ancestors()->excludeRoot()->all();

        $this->view->params['breadcrumbs'][] = [
            'label' => Yii::t('forum/default', 'MODULE_NAME'),
            'url' => ['/forum']
        ];


        // foreach ($ancestors as $c){
        // $this->breadcrumbs[$c->name] = $c->getUrl();
        //  }
        $this->view->params['breadcrumbs'][] = $category->name;
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
                return $this->redirect(['view', 'id' => $model->id]);
            }
            return $this->redirect(['/forum/default/view', 'id' => $_GET['id']]);

        }
        return $this->render('addtopic', array('model' => $model, 'category' => $category));
    }

    public function actionView($id)
    {

        $this->pageName = Yii::t('forum/default', 'MODULE_NAME');
        $this->model = Topics::findOne($id);


        if (!$this->model)
            $this->error404();

        $this->view->params['breadcrumbs'][] = [
            'label' => Yii::t('forum/default', 'MODULE_NAME'),
            'url' => ['/forum']
        ];
        $this->view->params['breadcrumbs'][] = [
            'label' => $this->model->category->name,
            'url' => $this->model->category->getUrl()
        ];
        $this->view->params['breadcrumbs'][] = $this->model->title;

        $this->model->updateCounters(['views' => 1]);


        /*$this->breadcrumbs = array(
            $this->pageName => array('/forum'),
            $this->model->category->name => $this->model->category->getUrl(),
            $this->model->title
        );*/


        $providerPosts = new \yii\data\ArrayDataProvider([
                'allModels' => $this->model->posts,
                'pagination' => [
                    'forcePageParam' => false,
                    'pageSize' => 10,
                    'defaultPageSize' => 10,
                    //'pageSizeLimit' => [1],
                ]
            ]
        );

        return $this->render('view', array(
            'model' => $this->model,
            'providerPosts' => $providerPosts
        ));
    }

    public function actionAddReply()
    {
        $result = [];
        $result['success'] = false;
        if (!Yii::$app->user->isGuest) {
            $postModel = new Posts;
            $request = Yii::$app->request;
            $view = ($request->isAjax) ? '_form_addreply' : '_form_addreply';


            if ($request->isPost && $request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($postModel->load($request->post())) {
                    if ($postModel->validate()) {


                        if ($postModel->save()) {
                            /**
                             * @var Topics $topic
                             * @var Categories $categoryData
                             */
                            $topic = $postModel->topic;

                            $postModel->save(false);

                            $topic->last_post_id = $postModel->id;
                            $topic->save(false);


                            $categoryData = $topic->category;


                            $categoryData->last_post_user_id = $postModel->user_id;
                            $categoryData->last_post_id = $postModel->id;
                            $categoryData->last_topic_id = $postModel->topic_id;
                            $categoryData->count_posts++;


                            $categoryData->saveNode();


                            /*$ancestors = $categoryData->ancestors()->findAll();
                            if ($ancestors) {
                                foreach ($ancestors as $category) {
                                    / @var Categories $category /
                                    $category->last_post_user_id = $postModel->user_id;
                                    $category->last_post_id = $postModel->id;
                                    $category->last_topic_id = $postModel->topic_id;
                                    $category->count_posts++;
                                    $category->saveNode();
                                }
                            }*/
                            $result['message'] = 'success';
                            $result['success'] = true;
                        }
                    } else {
                        $result['message'] = 'error';
                        $result['errors'] = $postModel->getErrors();
                    }

                } else {
                    $result['message'] = 'no load data post';
                }
                return $result;
                //return $this->render($view, [
                //    'model' => $postModel
                //]);
            } else {
                throw new ForbiddenHttpException('only ajax');
            }
        } else {
            throw new ForbiddenHttpException();
        }
    }

    public function actionPostDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax && Yii::$app->user->can('admin')) {
            $posts = Yii::$app->request->post('ids');

            $topic = Topics::findOne($id);
            $result['topic_id'] = $topic->id;
            if ($posts) {

                $post = Posts::deleteAll(['id'=>$posts]);
                $result['success'] = true;
                $result['message'] = 'OK';

            } else {
                $result['success'] = false;
                $result['message'] = 'Не выбрано не чего';
            }
            return $this->asJson($result);
        } else {
            throw new ForbiddenHttpException('only ajax & access');
        }
    }

    /**
     * @param $id
     * @return string
     */
    public function actionEditPost($id)
    {
        if (Yii::$app->request->isAjax) {
            $result = [];

            $post = Posts::findOne($id);
            $request = Yii::$app->request;

            if ($request->isPost && $request->isAjax) {
                $post->attributes = $request->post('Posts');
                if (!empty($post->edit_reason)) {
                    $post->edit_user_id = Yii::$app->user->getId();
                }
                if ($post->validate()) {
                    if ($post->save()) {
                        $result['post'] = $this->renderPartial('_posts_content', array(
                            'data' => $post
                        ), true, false);
                        $result['message'] = Yii::t('forum/default', 'POST_EDITED');
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
