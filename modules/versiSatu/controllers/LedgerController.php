<?php

namespace app\modules\versiSatu\controllers;

use app\components\Controller;
use app\models\Ledger;
use yii\web\NotFoundHttpException;
use app\models\search\LedgerSearch;
use app\components\Helpers;
use app\components\Ledgers;
use Yii;
use app\components\Username;

class LedgerController extends Controller
{
    public function actionIndex()
    {
        $search['LedgerSearch'] = Yii::$app->request->queryParams;
        $searchModel  = new LedgerSearch();
        $dataProvider = $searchModel->search($search);
        return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);
    }

    public function actionCreate()
    {
        $dataRequest['Ledger'] = Yii::$app->request->getBodyParams();
        $model = new Ledger();
        if($model->load($dataRequest)) {
            $active =Ledger::find()->where(['status' => 5])->one();
            $new_month_exists = (new \yii\db\Query())->select(['month_year'])->from('ledgers')->where(['status' => 5])->one();
            $rows = (new \yii\db\Query())->select(['ledger_id'])->from('ledgers')->where(['status' => 9])->all();
            $amount=$model->amount;
            $model->start_month = strtotime('first day of this month');
            $model->end_month = strtotime('last day of this month');
            $model->value_date = date('d-M-Y');
            $model->month_year = date('M, Y');
            $model->created_date = date('d M, Y');
            $model->created_by = Yii::$app->user->identity->id;
            $model->status = 9;
            $date = strtotime(date('d-M-Y'));
            if ($model->ledger_type=='Money In') {
                 $model->amount_in = $model->amount; 
                 $model->amount = $model->amount;  
                }else{  
                $model->amount_out = $model->amount; 
                $model->amount = '-'.$model->amount;
            }
            if($active == null){
                  Ledgers::newEntryCash();
            }
            if($new_month_exists != null){
          $openingBalance=Ledger::find()->max('value_date');
          $openingBalanceDate =Ledger::find()->where(['value_date' => $openingBalance])->limit(1)->one();
          $ledger_store_opening = new Ledger();
          $ledger_store_opening->naration = 'B/B foward.';
          $ledger_store_opening->value_date = date('d M, Y');
          $ledger_store_opening->start_month = strtotime('first day of this month');
          $ledger_store_opening->end_month = strtotime('last day of this month');
          $ledger_store_opening->running_balance = $openingBalanceDate['running_balance'];
          $ledger_store_opening->month_year = date('M, Y');
          $ledger_store_opening->created_date = date('d M, Y');
          $ledger_store_opening->value_date = $openingBalanceDate['value_date'];
          $ledger_store_opening->status = 5;
          $ledger_store_opening->created_by=Yii::$app->user->identity->id;
   
             }
            
            if ($model->save(false)) {
                //initiate reculculate
            $running_balance = (new \yii\db\Query())->select(['ledger_id','transaction_date', 'value_date','naration','amount_out','amount_in','amount','running_balance','ledger_type','start_month','end_month','month_year'])->from('ledgers')->where(['status' => 9])->orderBy(['ledger_id'=>SORT_DESC ])->all();
                foreach ($running_balance as $balance) {
                   Ledgers::recalculateCash($balance);
                }
            }
            return $this->apiCreated($model);
        }
        return $this->apiValidate($model->errors);
    }


   public function actionView($id)
    {
        $search['LedgerSearch'] = Yii::$app->request->queryParams;
        $searchModel  = new LedgerSearch();
        $dataProvider = $searchModel->search($search);
        $dataProvider->query->having(['account_id' => $id]);
        return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);
    }
    protected function findModel($id)
    {
        if(($model = Ledger::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Resource not found');
        }
    }
}
