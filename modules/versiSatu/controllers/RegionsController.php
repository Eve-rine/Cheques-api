<?php

namespace app\modules\versiSatu\controllers;

use app\components\Controller;
use app\models\Regions;
use yii\web\NotFoundHttpException;
use app\models\search\RegionsSearch;
use Yii;

class RegionsController extends Controller
{
    public function actionIndex()
    {
        $search['RegionsSearch'] = Yii::$app->request->queryParams;
        $searchModel  = new RegionsSearch();
        $dataProvider = $searchModel->search($search);

        return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);
    }

    public function actionCreate()
    {
        $dataRequest['Regions'] = Yii::$app->request->getBodyParams();
        $model = new Regions();
        if($model->load($dataRequest) && $model->save()) {
            return $this->apiCreated($model);
        }

        return $this->apiValidate($model->errors);
    }

    public function actionUpdate($id)
    {
        $dataRequest['Regions'] = Yii::$app->request->getBodyParams();
        $model = $this->findModel($id);
        if($model->load($dataRequest) && $model->save()) {
            return $this->apiUpdated($model);
        }

        return $this->apiValidate($model->errors);
    }

    public function actionView($id)
    {
        return $this->apiItem($this->findModel($id));
    }

    public function actionDelete($id)
    {
        if($this->findModel($id)->delete()) {
            return $this->apiDeleted(true);
        }
        return $this->apiDeleted(false);
    }

    protected function findModel($id)
    {
        if(($model = Regions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Resource not found');
        }
    }
}
