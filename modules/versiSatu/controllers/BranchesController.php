<?php

namespace app\modules\versiSatu\controllers;

use app\components\Controller;
use app\models\Branches;
use yii\web\NotFoundHttpException;
use app\models\search\BranchesSearch;
use Yii;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;


class BranchesController extends Controller
{
    public function actionIndex($id = null)
    {
// return new ActiveDataProvider([
//     'pagination' => [
//         'pageSize' => 10,
//     ],
// ]);;
             $query = Branches::find();

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => 3, //set page size here
        ]

    ]);
      return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);

    }
    public function actionCreate()
    {
        $dataRequest['Branches'] = Yii::$app->request->getBodyParams();
        $model = new Branches();
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
        $dataRequest['Branches'] = Yii::$app->request->getBodyParams();
        $model = $this->findModel($id);
        if($model->load($dataRequest)) {
             $model->created_by = Yii::$app->user->identity->id; 
            $model->save(false);
            return $this->apiUpdated($model);
        }

        return $this->apiValidate($model->errors);
    }

 

      public function actionView($id)
    {
        $search['BranchesSearch'] = Yii::$app->request->queryParams;
        $searchModel  = new BranchesSearch();
        $dataProvider = $searchModel->search($search);
        $dataProvider->query->having(['branch_id' => $id]);
        return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);
    }


    protected function findModel($id)
    {
        if(($model = Branches::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Resource not found');
        }
    }

        public function actionSearch($id=null)
    {

            $search = Branches::find()
                ->orFilterWhere(['like', 'branch_name', $id])
                 ->orFilterWhere(['like', 'bank_id', $id])
                  ->orFilterWhere(['like', 'contact_person_name', $id])
                   ->orFilterWhere(['like', 'contact_person_number', $id])
                    ->orFilterWhere(['like', 'contact_person_email', $id])
                     ->orFilterWhere(['like', 'created_at', $id])
                      ->orFilterWhere(['like', 'updated_at', $id])
                       ->orFilterWhere(['like', 'created_by', $id])
                ->orFilterWhere(['like', 'status', $id])
                ->all(); 

            return $search;
       
    }
}
