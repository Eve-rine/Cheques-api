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
            'pageSize' => 2, //set page size here
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
        return $this->apiItem($this->findModel($id));
    }


    protected function findModel($id)
    {
        if(($model = Banks::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Resource not found');
        }
    }
        public function actionExcel() {  
        $searchModel = new BanksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $exporter = new Spreadsheet(['dataProvider' => $dataProvider,
             'columns' => [
                [
                    'attribute' => 'bank_name',
                    'label' => 'Bank',
                    'content' => function($model){
                        return $model->bank_name;
                    }
                ],
                [
                    'attribute' => 'head_office_number',
                    'label' => 'Head office number',
                    'content' => function($model){
                        return $model->head_office_number;
                    }
                ],
                [
                    'attribute' => 'head_office_address',
                    'label' => 'Head office address',
                    'content' => function($model){
                        return $model->head_office_address;
                    }
                ],
                [
                    'attribute' => 'head_office_email',
                    'label' => 'Head office email',
                    'content' => function($model){
                        return $model->head_office_email;
                    }
                ],
                [
                    'attribute' => 'created_at',
                    'content' => function($model){
                        return date('d M, Y', $model->created_at);
                    }
                ],
                [
                    'attribute' => 'created by',
                    'content' => function($model){
                        return Username::createdBy($id = $model->created_by);
                    }
                ]
              ],
    ]);
        return $exporter->send('items.xlsx');
    }
    public function actionPdf() {  
        $searchModel = new BanksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
     $content = $this->renderPartial('/banking/_list_banks',['dataProvider'=>$dataProvider]);
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
        'cssInline' => '.kv-heading-1{font-size:18px}', 
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
    
    // return the pdf output as per the destination setting
     $pin = (new \yii\db\Query())
             ->select(['pin'])
             ->from('users')
             ->where(['id' => Yii::$app->user->identity->id])
             ->one();
     $password = Yii::$app->security->validateData($pin['pin'],'123456',false);
     $pdf->getApi()->setProtection(array(),$password);
    return $pdf->render(); 
    }
}
