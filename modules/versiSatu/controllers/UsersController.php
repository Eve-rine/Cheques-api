<?php

namespace app\modules\versiSatu\controllers;

use app\components\Controller;
use app\models\search\UsersSearch;
use app\models\Security;
use app\models\User;
use Yii;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $search['UsersSearch'] = Yii::$app->request->queryParams;
        $searchModel  = new UsersSearch();
        $dataProvider = $searchModel->search($search);
        return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);
    }
    public function actionVerify()
    {
        $model=new Security;
        $dataRequest['Security'] = Yii::$app->request->post();
        if ($model->load($dataRequest)) {
            if ($model->validate()) {
           $user= User::findOne(['id'=>Yii::$app->user->identity->id]);
           $user->pin = Yii::$app->security->hashData($model->doc_pin,'123456',false);
           $user->save(false);
        }else{
             return $this->apiAuth($model);
        }
            
        }
    }
}
