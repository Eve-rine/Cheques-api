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

       //          $search['BranchesSearch'] = Yii::$app->request->queryParams;
       //  $searchModel  = new BranchesSearch();
       //  $dataProvider = $searchModel->search($search);
       // $dataProvider->pagination = ['pageSize' => 10];


       //  return $this->apiCollection([
       //      'count'      => $dataProvider->count,
       //      'dataModels' => $dataProvider->models,
       //  ], $dataProvider->totalCount);
             $branchy = Branches::find()->with('bank')->asArray()->all();  // fetches the bankss with
return $branchy;

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
    return $this->apiItem($this->findModel($id));

    }
        public function actionSelect($id)
    {
        $search['BranchesSearch'] = Yii::$app->request->queryParams;
        $searchModel  = new BranchesSearch();
        $dataProvider = $searchModel->search($search);
        $dataProvider->query->andWhere(['bank_id'=> $id]);

        return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);
    }


    protected function findModel($id)
    {
        if(($model = Branches::findOne($id)) ->with('bank')->asArray() !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Resource not found');
        }
    }

        public function actionSearch($id=null,$q)
    {

            $search = Branches::find()
                ->orFilterWhere(['like', 'branch_name', $id])
                ->andWhere(['bank_id'=>$q])
                ->all(); 

            return $search;
       
    }
}
