<?php

namespace app\modules\versiSatu\controllers;

use app\components\Controller;
use app\models\Banks;
use yii\web\NotFoundHttpException;
use app\models\search\BanksSearch;
use yii\data\ActiveDataProvider;
use Yii;

class BanksController extends Controller
{
    public function actionIndex()
    {

    $query = Banks::find()->orderBy(['bank_id' => SORT_DESC]);

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => 6, //set page size here
        ]

    ]);
      return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);
    }

    public function actionCreate()
    {
        $dataRequest['Banks'] = Yii::$app->request->getBodyParams();
        $model = new Banks();
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
        $dataRequest['Banks'] = Yii::$app->request->getBodyParams();
        $model = $this->findModel($id);
        if($model->load($dataRequest) && $model->save()) {
            return $this->apiUpdated($model);
        }

        return $this->apiValidate($model->errors);
    }

    public function actionView($id)
    {
               $search['BanksSearch'] = Yii::$app->request->queryParams;
        $searchModel  = new BanksSearch();
        $dataProvider = $searchModel->search($search);
        $dataProvider->query->having(['bank_id' => $id]);
        return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);
    }


    protected function findModel($id)
    {
        if(($model = Banks::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Resource not found');
        }
    }

           public function actionSearch($id=null)
    {

            $search = Banks::find()
                 ->andFilterWhere(['like', 'bank_name', $id])            
                ->all(); 

            return $search;

    //               $search['BanksSearch'] = Yii::$app->request->queryParams;
    //     $searchModel  = new BanksSearch();
    //     $dataProvider = $searchModel->search($search);
    //    $dataProvider->query->having(['bank_name' => $id]);


    //     return $this->apiCollection([
    //         'count'      => $dataProvider->count,
    //         'dataModels' => $dataProvider->models,
    //     ], $dataProvider->totalCount);
       
    }

}
