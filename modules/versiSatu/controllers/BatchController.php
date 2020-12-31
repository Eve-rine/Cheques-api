<?php

namespace app\modules\versiSatu\controllers;

use app\components\Controller;
use app\models\Batch;
use app\models\Cheques;
use app\models\Accounts;
use app\models\search\ChequesSearch;
use app\models\Branches;
use app\models\Banks;
use app\models\Events;
use yii\web\NotFoundHttpException;
use app\models\search\BatchSearch;
use Yii;
use kartik\mpdf\Pdf;
use app\components\DynamicModel as Model;
use yii\data\Pagination;
use yii2tech\spreadsheet\Spreadsheet;
use yii\data\ActiveDataProvider;
use app\components\Username;
use app\components\Numbers;
use yii\helpers\ArrayHelper;
class BatchController extends Controller
{
    public function actionIndex($id = null)
    {
        $query = Batch::find()->orderBy(['batch_id' => SORT_DESC]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize' => 20]);
        $dataProvider = $query->offset($id)
            ->limit($pages->limit)
            ->all();
        return $this->apiCollection([
            'count'      => $pages,
            'dataModels' => $dataProvider,
        ], $dataProvider);
    }

           public function actionCreate()
    {
       
        $model = new Batch(); 
            $modelsCheque = [new Cheques];   
        if ($model->load(Yii::$app->request->post())) {  

        $modelsCheque = Model::createMultiple(Cheques::classname());
            Model::loadMultiple($modelsCheque, Yii::$app->request->post()); 
            $valid = $model->validate();
           // $valid = Model::validateMultiple($modelsCheque) && $valid;
            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                 try {
                    $model->batch_id = Numbers::randomString(true);
                    $model->status = 'complete';
                    $model->created_by = Yii::$app->user->identity->id;
                    if ($flag = $model->save(false)) {
                        foreach ($modelsCheque as $modelChequeSave) {
                            $modelChequeSave->batch_id = $model->batch_id;
                            $modelChequeSave->bank_id = $model->bank_id;
                            $modelChequeSave->branch_id = $model->branch_id;
                            $modelChequeSave->account_id = $model->account_id;
                            if ($modelChequeSave['amount'] <= 299999 ) {
                                $modelChequeSave->approval = 2;
                            }else{
                                $modelChequeSave->approval = 3;
                            }
                            $modelChequeSave->created_at = strtotime(date("Y-m-d"));
                            $modelChequeSave->updated_at = strtotime(date("Y-m-d"));
                           $modelChequeSave->status = 'complete';
                            $modelChequeSave->created_by=Yii::$app->user->identity->id;

                            if (! ($flag = $modelChequeSave->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                         $query=Cheques::find()->where(['batch_id'=>$model->batch_id])->andWhere(['print_status' => 9]);
                $batch_update= Batch::findOne(['batch_id'=>$model->batch_id]);
                $batch_update->amount = $query->sum('amount');
                $batch_update->cheques = $query->count();
                $batch_update->save(false);
                $transaction->commit();
             return $this->apiCreated($model);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }   
        }
    
        
    }
      return $this->apiValidate($model->errors);
}
  public function actionUpdate($id)

    {

        $model = $this->findModel($id);
        $modelsCheque = $model->cheque;
        if ($model->load(Yii::$app->request->post())) {
        $oldIDs = ArrayHelper::map($modelsCheque, 'cheque_id', 'cheque_id');
        $modelsCheque = Model::createMultiple(Cheques::classname(), $modelsCheque,'cheque_id');
            Model::loadMultiple($modelsCheque, Yii::$app->request->post());
     $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsCheque, 'cheque_id', 'cheque_id')));
            // validate all models
            $valid = $model->validate();
            // $valid = Model::validateMultiple($modelsCheque) && $valid;
            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (!empty($deletedIDs)) {
                            Cheques::deleteAll(['cheque_id' => $deletedIDs]);
                        }
// return $modelsCheque;
                       foreach ($modelsCheque as $modelChequeSave) {

                            if ($modelChequeSave->cheque_id==null){

                            $modelChequeSave->batch_id = $model->batch_id;
                            $modelChequeSave->bank_id = $model->bank_id;
                            $modelChequeSave->branch_id = $model->branch_id;
                            $modelChequeSave->account_id = $model->account_id;
                             if ($modelChequeSave['amount'] <= 299999 ) {
                                $modelChequeSave->approval = 2;
                            }else{
                                $modelChequeSave->approval = 3;
                            }
                            $modelChequeSave->status = 'complete';
                            $modelChequeSave->created_at = strtotime(date("Y-m-d"));
                            $modelChequeSave->updated_at = strtotime(date("Y-m-d"));
                            $modelChequeSave->created_by=Yii::$app->user->identity->id;


                            }
                              
                            if (! ($flag = $modelChequeSave->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        // return $this->redirect(['index']);
                         return $this->apiUpdated($model);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
        return $this->apiUpdated($model);

    }

       public function actionDownloadExcel($id=null) {
        $exporter = new Spreadsheet(['dataProvider' =>new ActiveDataProvider([
            'query'=>Cheques::find()->andWhere(['batch_id'=>$id])
        ]),
             'columns' => [
                [
                    'attribute' => 'batch_id',
                    'label' => 'Batch reference',

                ],
                'payee',
                [
                    'attribute' => 'amount',

                ],
                [
                    'attribute' => 'pay_date',

                ],
                'cheque_no',
                'status',
                [
                    'attribute' => 'created_at',
 
                ],
                [
                    'attribute' => 'created by',
                             'content' => function($model){
                        return Username::createdBy($id = $model->created_by);
                    }

                ],
                [
                    'attribute' => 'bank_id',
                                       'label' => 'Bank',
                                    'content' => function($model){
                        return Username::bankName($id = $model->bank_id);
                    }

                ],
                [
                    'attribute' => 'branch_id',
                     'label' => 'Branch',
                    // 'content' => function($model){
                    //     return Branches::findAll(['branch_id' =>$model->branch_id])->branch_name;
                    // }
     
                ],
                [
                    'attribute' => 'account_id',
                                'label' => 'Account Name',
                    'content' => function($model){
                        return Username::accountName($id = $model->account_id);
                    }
    
                ],
              ],
    ]);
        return chmod($exporter->send('items.xlsx',0644));
    }


      public function actionDownload($id)
    {
        $batch = (new \yii\db\Query())
             ->select(['batch_id'])
             ->from('batch')
             ->where(['batch_id' => $id])
             ->one();
             return $batch;
        $searchModel = new ChequesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['batch_id' => $batch])
        ->andWhere(['status' => 'complete'])
        ->andWhere(['print_status' => 10]);
        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('/cheques/download', [
                'dataProvider' => $dataProvider,
            ]);
        $batch= Batch::findOne(['batch_id'=>$batch_id]);
        $batch->status = 'printed';
        $batch->save(false);
        foreach (Cheques::find()->where(['batch_id' => $batch])->all() as $cheque) {
        $model= Cheques::findOne(['id'=>$cheque->id]);
        $model->status = 'printed';
        $model->save(false);
        }
        // setup kartik\mpdf\Pdf component
        $pdf = new PdfbSix([

            // set to use core fonts only
            'mode' => PdfbSix::MODE_CORE, 
            // A4 paper format
            'format' => PdfbSix::FORMAT_B6, 
            // portrait orientation
            'orientation' => PdfbSix::ORIENT_LANDSCAPE, 
            // stream to browser inline
            'destination' => PdfbSix::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.cheque-view{background-color: #fff;border: 1px solid #888;height: 29.7cm}', 
             // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
             // call mPDF methods on the fly
            // 'methods' => [ 
            //     'SetHeader'=>['Krajee Report Header'], 
            //     'SetFooter'=>['{PAGENO}'],
            // ]
        ]);
        
        // return the pdf output as per the destination setting
     //    $password = "1234";
     // $pdf->getApi()->setProtection(array(),$password);
        return $pdf->render(); 
    }
       public function actionPdf($id)
    {
        $str_arr = preg_split ("/\,/", $id);  
        $searchModel = new BatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->having(['status' => 'printed'])
            ->andHaving(['>', 'created_at', $str_arr[0]])
            ->andHaving(['<', 'created_at', $str_arr[1]]);
        $content = $this->renderPartial('/batch/_list_batch', [
                'str_arr' => $str_arr,
            ]);
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([

            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.cheque-view{background-color: #fff;border: 1px solid #888;height: 29.7cm}', 
             // set mPDF properties on the fly
            'options' => [
            'title' => 'Medfast Pharmacy',
            'showWatermarkText'=>true,
            'showWatermarkImage'=>false,
        ],
         // call mPDF methods on the fly
        'methods' => [ 
            'SetHeader'=>[''], 
            'SetFooter'=>'Medfast Pharmacy',
            'setWatermarkText'=>['Medfast Pharmacy'], 
            //'setWatermarkImage'=>['/Drozones.png'], 
        ]
        ]);
        
        return $pdf->render(); 
    }

    public function actionView($id)
    {
        return $this->apiItem($this->findModel($id));
    }

    protected function findModel($id)
    {
        if(($model = Batch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Resource not found');
        }
    }
}
