<?php

namespace app\modules\versiSatu\controllers;

use Yii;
use app\models\Currency;
use app\models\search\CurrencySearch;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CurrencyController implements the CRUD actions for Currency model.
 */
class CurrencyController extends Controller
{
  public function actionIndex()
    {
        $search['CurrencySearch'] = Yii::$app->request->queryParams;
        $searchModel  = new CurrencySearch();
        $dataProvider = $searchModel->search($search);

        return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);
    }

    public function actionCreate()
    {
        $dataRequest['Currency'] = Yii::$app->request->getBodyParams();
        $model = new Currency();
        if($model->load($dataRequest)) {
            $model->status = 'complete';
            $model->created_by = Yii::$app->user->identity->id; 
          $model->save(false);
            return $this->apiCreated($model);
        }

        return $this->apiValidate($model->errors);
    }


    public function actionUpdate($id)
    {
        $dataRequest['Currency'] = Yii::$app->request->getBodyParams();
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
        if(($model = Currency::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Resource not found');
        }
    }
}
