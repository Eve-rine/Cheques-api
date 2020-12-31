<?php

namespace app\modules\versiSatu\controllers;

use app\components\Controller;
use app\models\Cheques;
use app\models\Batch;
use yii\web\NotFoundHttpException;
use app\models\search\ChequesSearch;
use Yii;
use app\components\PdfbSix;
use yii\data\ActiveDataProvider;
use app\models\Authorize;


class ChequesController extends Controller
{
    public function actionIndex()
    {
        $search['ChequesSearch'] = Yii::$app->request->queryParams;
        $searchModel  = new ChequesSearch();
        $dataProvider = $searchModel->search($search);

        return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);
    }
     public function actionPrint($id)
    {
        $batch = (new \yii\db\Query())
             ->select(['batch_id'])
             ->from('batch')
             ->where(['batch_id' => $id])
             ->one();

        $searchModel = new ChequesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['batch_id' => $batch])
        ->andWhere(['status' => 'complete'])
        ->andWhere(['print_status' => 10]);
        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('/cheques/download', [
                'dataProvider' => $dataProvider,
            ]);
        $batch= Batch::findOne(['batch_id'=>$id]);
        $batch->status = 'printed';
        $batch->save(false);
        foreach (Cheques::find()->where(['batch_id' => $batch])->all() as $cheque) {
        $model= Cheques::findOne(['cheque_id'=>$cheque->cheque_id]);
        $model->status = 'printed';
        // return $model;
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

        public function actionRecord($id) {        
        
        $batch = (new \yii\db\Query())
             ->select(['batch_id'])
             ->from('batch')
             ->where(['batch_id' => $id])
             ->one();
        $searchModel = new ChequesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['batch_id'=>$batch])
         ->andWhere(['print_status' => 10]);
        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('/cheques/record', [
                'dataProvider' => $dataProvider,
            ]);
        // setup kartik\mpdf\Pdf component

        // instantiate Pdf object (kartik\mpdf\Pdf)
        $pdf = new PdfbSix([
            // set to use UTF8 encode only
            'mode' => PdfbSix::MODE_UTF8,
            
            // A4 paper format
            'format' => PdfbSix::FORMAT_B6,
            
            // portrait orientation
            'orientation' => PdfbSix::ORIENT_LANDSCAPE,
            
            // stream to browser inline
            'destination' => PdfbSix::DEST_BROWSER,
            
            // your html content input
            'content' => $content,   

            'cssInline' => '.cheque-view{background-color: #fff;border: 1px solid #888;height: 29.7cm},',
            
            // call mPDF methods on the fly
            'options' => ['title' => 'Krajee Report Title'],            
        ]);
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $pdf->options = array_merge($pdf->options , [
            'fontDir' => array_merge($fontDirs, [Yii::getAlias('@webroot').'/swagger/fonts']),  // make sure you refer the right physical path
            'fontdata' => array_merge($fontData, [
                'cheque' => [
                    'R' => 'micrenc.ttf',
                    'I' => 'micr-encoding.regular.ttf',
                ]
            ])
        ]);

        return $pdf->render();        
    } 

        public function actionNumber($id)
    {
        $dataRequest['Cheques'] = Yii::$app->request->getBodyParams();
        $model = $this->findModel($id);
        if($model->load($dataRequest)) {
            $cheque_no = str_pad($model->cheque_no,6,"0",STR_PAD_LEFT);
            $model->cheque_no = $cheque_no;
            $model->status = 'closed';
            $row = (new \yii\db\Query())
            ->from('cheques')
            ->where(['like', 'cheque_no', $cheque_no])
            ->andWhere(['like', 'account_id', $model->account_id])
            ->one();
            if ($row == null) {
                $model->save(false);
            }else{
                return $this->apiChequeNumber($model);
            }
            return $this->apiUpdated($model);
        }

        return $this->apiValidate($model->errors);
    }

    public function actionUpdate($id)
    {
        $dataRequest['Cheques'] = Yii::$app->request->getBodyParams();
        $model = $this->findModel($id);
        if($model->load($dataRequest)) {
            if ($model->signatories_approved != null) {
                $currentApproved = rtrim($model->signatories_approved,'</SIGNS>');
                $currentApproved = ltrim($currentApproved,'<SIGNS>');
                $currentApproved = explode("PIMS",$currentApproved);
                $count=count($currentApproved);
                if ($count >= 1 ) {
                   return $this->apiEdited($model);
                }
            }
            $model->save(false);
            $query=Cheques::find()->where(['batch_id'=>$model->batch_id]);
            $batch = Batch::find()->where(['batch_id' => $model->batch_id])->one();
            $batch->amount = $query->sum('amount');
            $model->created_by = Yii::$app->user->identity->id; 
            $batch->save(false);
            return $this->apiUpdated($model);
        }

        return $this->apiValidate($model->errors);
    }


    public function actionView($id)
    {
        $batch = (new \yii\db\Query())
             ->select(['batch_id'])
             ->from('batch')
             ->where(['batch_id' => $id])
             ->one();
        $searchModel = new ChequesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['batch_id'=>$batch]);

        return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);
    }

    public function actionVerify($id)
    {
        $model=new Authorize;
        $dataRequest['Authorize'] = Yii::$app->request->post();
        $model->id=$id;
        if ($model->load($dataRequest)) {
            if ($model->validate()) {
            $user = Yii::$app->user->identity->id;
            $cheque= $this->findModel($id);
            $currentApproved = rtrim($cheque->signatories_approved,'</SIGNS>');
            $currentApproved = ltrim($currentApproved,'<SIGNS>');
            $currentApproved = explode("PIMS",$currentApproved);
            if ($model->authorization_status=='Approve') {
                if(in_array($user, $currentApproved)){
                    return $this->apiSigned($model);
                }else{
                    $currentApproved[].=$user;
                }
                $newapproved='<SIGNS>';
                foreach ($currentApproved as $value) {
                    $value!=null?$newapproved .= $value.'PIMS':null;
                }
                $cheque->signatories_approved = rtrim($newapproved, 'PIMS').'</SIGNS>';
                 //bank account
                $minSignatories = (new \yii\db\Query())->select(['minimum_signatories'])->from('accounts')->where(['account_number' => $cheque->account_id])->one();
                //signatory account
                $chequeSignatory = (new \yii\db\Query())->from('signatories')->where(['user_id' => Yii::$app->user->identity->id])->andWhere(['account_id' => $cheque->account_id])->exists();
                if ($chequeSignatory == null) {
                    return $this->apiSignatory($model);
                }
                $array = rtrim($newapproved,'</SIGNS>');
                $array = ltrim($array,'<SIGNS>');
                $array = explode("PIMS",$array); 
                $count = count($array);
                $cheque->approval_count=$count;
                 if ($count >= $minSignatories) {
                    if ($cheque->amount <= 299999 && $count==2) {
                        $cheque->print_status=10;
                    }elseif ($cheque->amount >= 300000 && $count==3) {
                        $cheque->print_status=10;
                    }
                 }
            }
            if($model->authorization_status=='Decline'){
               if(in_array($user, $currentApproved)){
                foreach (array_keys($currentApproved,$user) as $key) {
                        unset($currentApproved[$key]); 
                        $cheque->print_status=9;
                        $count = count($currentApproved);
                        if ($count < 1) {
                             $cheque->signatories_approved=null;
                             $cheque->approval_count = null;
                         }else{
                            $cheque->approval_count=$count;
                         }
                        $message = 'You have successfuly declined this cheque';
                    }
               }else{
                $message = 'You have not signed this cheque';
               }
            }
            if ($cheque->save(false)) {
                if($model->authorization_status=='Decline'){
                    return $this->apiCancel($model,$message);
                }else{
                    return $this->apiDecline($model);
                }
                return $this->apiAuthorize($model);
            }
        }else{
             return $this->apiAuth($model);
        }
            
        }
    }
       public function actionFilter($id)
    {
        $str_arr = preg_split ("/\,/", $id);
        $searchModel = new ChequesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (sizeof($str_arr) == 1) {
            $dataProvider->query->orFilterWhere(['print_status' => $str_arr[0]]);
        }elseif (sizeof($str_arr) == 2) {
            $dataProvider->query->orFilterWhere(['print_status' => $str_arr[0]])
            ->orFilterWhere(['=', 'print_status', $str_arr[1]]);
        }elseif (sizeof($str_arr) == 3) {
            $dataProvider->query->orFilterWhere(['print_status' => $str_arr[0]])
            ->orFilterWhere(['=', 'print_status', $str_arr[1]])
            ->orFilterWhere(['=', 'print_status', $str_arr[2]]);
        }
        return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);
    }

    protected function findModel($id)
    {
        if(($model = Cheques::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Resource not found');
        }
    }
}

