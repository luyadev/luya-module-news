<?php

namespace luya\news\frontend\controllers;

use Yii;
use luya\news\models\Article;
use luya\news\models\Cat;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;

/**
 * News Module Default Controller contains actions to display and render views with predefined data.
 *
 * @author Basil Suter <basil@nadar.io>
 */
class DefaultController extends \luya\web\Controller
{
    /**
     * Get Article overview.
     *
     * The index action will return an active data provider object inside the $provider variable:
     *
     * ```php
     * foreach ($provider->models as $item) {
     *     var_dump($item);
     * }
     * ```
     *
     * @return string
     */
    public function actionIndex()
    {
        $provider = new ActiveDataProvider([
            'query' => Article::find()->andWhere(['is_deleted' => false, 'is_online' => true, 'is_archived' => false])->with(['createUser']),
            'sort' => [
                'defaultOrder' => $this->module->articleDefaultOrder,
            ],
            'pagination' => [
                'route' => $this->module->id,
                'params' => ['page' => Yii::$app->request->get('page')],
                'defaultPageSize' => $this->module->articleDefaultPageSize,
            ],
        ]);
        
        return $this->render('index', [
            'model' => Article::class,
            'provider' => $provider,
        ]);
    }

    /**
     * Get Archive overview.
     *
     * The archive action will return an active data provider object inside the $provider variable:
     *
     * ```php
     * foreach ($provider->models as $item) {
     *     var_dump($item);
     * }
     * ```
     *
     * @return string
     * @since 4.0.0
     */
    public function actionArchive()
    {
        $provider = new ActiveDataProvider([
            'query' => Article::find()->andWhere(['is_deleted' => false, 'is_online' => true, 'is_archived' => true])->with(['createUser']),
            'sort' => [
                'defaultOrder' => $this->module->articleDefaultOrder,
            ],
            'pagination' => [
                'route' => $this->module->id,
                'params' => ['page' => Yii::$app->request->get('page')],
                'defaultPageSize' => $this->module->articleDefaultPageSize,
            ],
        ]);
        
        return $this->render('archive', [
            'model' => Article::class,
            'provider' => $provider,
        ]);
    }
    
    /**
     * Get all articles for a given categorie ids string seperated by command.
     *
     * @param string $ids The categorie ids: `1,2,3`
     * @return \yii\web\Response|string
     */
    public function actionCategories($ids)
    {
        $ids = explode(",", Html::encode($ids));
        
        if (!is_array($ids)) {
            throw new NotFoundHttpException();
        }
        
        $provider = new ActiveDataProvider([
            'query' => Article::find()->where(['in', 'cat_id', $ids])->andWhere(['is_deleted' => false, 'is_online' => true, 'is_archived' => false])->with(['createUser']),
            'sort' => [
                'defaultOrder' => $this->module->articleDefaultOrder,
            ],
            'pagination' => [
                'route' => $this->module->id,
                'params' => ['page' => Yii::$app->request->get('page')],
                'defaultPageSize' => $this->module->articleDefaultPageSize,
            ],
        ]);
        
        return $this->render('categories', [
            'provider' => $provider,
        ]);
    }

    /**
     * Get the category Model for a specific ID.
     *
     * The most common way is to use the active data provider object inside the $provider variable:
     *
     * ```php
     * foreach ($provider->getModels() as $cat) {
     *     var_dump($cat);
     * }
     * ```
     *
     * Inside the Cat Object you can then retrieve its articles:
     *
     * ```php
     * foreach ($model->articles as $item) {
     *
     * }
     * ```
     *
     * or customize the where query:
     *
     * ```php
     * foreach ($model->getArticles()->where(['timestamp', time())->all() as $item) {
     *
     * }
     * ```
     *
     * @param integer $categoryId
     * @return \yii\web\Response|string
     */
    public function actionCategory($categoryId)
    {
        $model = Cat::findOne($categoryId);
        
        if (!$model) {
            throw new NotFoundHttpException();
        }
        
        $provider = new ActiveDataProvider([
            'query' => $model->getArticles()->andWhere(['is_deleted' => false, 'is_online' => true, 'is_archived' => false])->with(['createUser']),
            'sort' => [
                'defaultOrder' => $this->module->categoryArticleDefaultOrder,
            ],
            'pagination' => [
                'route' => $this->module->id,
                'params' => ['page' => Yii::$app->request->get('page')],
                'defaultPageSize' => $this->module->categoryArticleDefaultPageSize,
            ],
        ]);
        
        return $this->render('category', [
            'model' => $model,
            'provider' => $provider,
        ]);
    }
    
    /**
     * Detail Action of an article by Id.
     *
     * @param integer $id
     * @param string $title
     * @return \yii\web\Response|string
     */
    public function actionDetail($id, $title)
    {
        $model = Article::findOne(['id' => $id, 'is_deleted' => false, 'is_online' => true]);
        
        if (!$model) {
            throw new NotFoundHttpException();
        }
        
        return $this->render('detail', [
            'model' => $model,
        ]);
    }

    public function actionPreview($id, $hash)
    {
        $model = Article::findOne(['id' => $id, 'is_deleted' => false]);
        
        if (!$model) {
            throw new NotFoundHttpException();
        }

        if ($hash != $model->getPreviewHash()) {
            throw new NotFoundHttpException("Invalid preview hash");
        }

        return $this->render('detail', [
            'model' => $model,
        ]);
    }
}
