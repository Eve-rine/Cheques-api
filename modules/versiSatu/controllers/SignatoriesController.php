<?php

namespace app\modules\versiSatu\controllers;

use app\components\Controller;
use app\models\Signatories;
use yii\web\NotFoundHttpException;
use app\models\search\SignatoriesSearch;
use Yii;

class SignatoriesController extends Controller
{
    public function actionIndex()
    {
        $search['SignatoriesSearch'] = Yii::$app->request->queryParams;
        $searchModel  = new SignatoriesSearch();
        $dataProvider = $searchModel->search($search);

        return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);
    }

    public function actionCreate()
    {
        $dataRequest['Signatories'] = Yii::$app->request->getBodyParams();
        $model = new Signatories();
        if($model->load($dataRequest) ) {
            $model->status="active";
              $model->user_id=2;
                   $model->created_by=Yii::$app->user->identity->id;
            $model->save(false);


            return $this->apiCreated($model);
        }

        return $this->apiValidate($model->errors);
    }

    public function actionUpdate($id)
    {
        $dataRequest['Signatories'] = Yii::$app->request->getBodyParams();
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
        if(($model = Signatories::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Resource not found');
        }
    }
}
