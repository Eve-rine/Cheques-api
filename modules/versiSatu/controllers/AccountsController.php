<?php

namespace app\modules\versiSatu\controllers;

use app\components\Controller;
use app\models\Accounts;
use app\models\Signatories;
use yii\web\NotFoundHttpException;
use app\models\search\AccountsSearch;
use Yii;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use app\models\DynamicModel as Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use app\components\Numbers;



class AccountsController extends Controller
{
    public function actionIndex($id = null)
    {
             $query = Accounts::find();

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
        $model = new Accounts(); 
            $modelsSignatory = [new Signatories];   
        if ($model->load(Yii::$app->request->post())) {  

        $modelsSignatory = Model::createMultiple(Signatories::classname());
            Model::loadMultiple($modelsSignatory, Yii::$app->request->post()); 
            $valid = $model->validate();
           // $valid = Model::validateMultiple($modelsSignatory) && $valid;
            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                 try {
                    $model->status = 'active';
                    $modelSign->created_by=Yii::$app->user->identity->id;
                    if ($flag = $model->save(false)) {
                        foreach ($modelsSignatory as $i =>$modelSign) {
                            $modelSign->account_id = $model->account_id;
                             
                                  $modelSign->status = "complete";
                                  $modelSign->created_by=Yii::$app->user->identity->id;

                            if (! ($flag = $modelSign->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                $transaction->commit();
             return $this->apiCreated($model);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }   
        }
      return $this->apiValidate($model->errors);
        
    }
}

        public function actionUpdate($id)

    {

        $model = $this->findModel($id);
        $modelsSignatory = $model->signatory;
        if ($model->load(Yii::$app->request->post())) {
        $oldIDs = ArrayHelper::map($modelsSignatory, 'signatory_id', 'signatory_id');
          // print_r($oldIDs);
        $modelsSignatory = Model::createMultiple(Signatories::classname(), $modelsSignatory,'signatory_id');
            Model::loadMultiple($modelsSignatory, Yii::$app->request->post());
     $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsSignatory, 'account_id', 'account_id')));
            // validate all models
            $valid = $model->validate();
            // $valid = Model::validateMultiple($modelsSignatory) && $valid;
            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (!empty($deletedIDs)) {
                            Signatories::deleteAll(['account_id' => $deletedIDs]);
                        }

                        foreach ($modelsSignatory as $modelSign) {

                            if ($modelSign->account_id==null){
                                $modelSign->account_id = $model->account_id;
                             
                                  $modelSign->status = "complete";
                                  $modelSign->created_by=Yii::$app->user->identity->id; 

                            }
                              
                            if (! ($flag = $modelSign->save(false))) {
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


    public function actionView($id)

    {

        $model = $this->findModel($id);
         $modelsSignatory = $model->signatories;

$search['AccountsSearch'] = Yii::$app->request->queryParams;
        $searchModel  = new AccountsSearch();
        $dataProvider = $searchModel->search($search);
        $dataProvider->query->having(['account_id' => $id]);
        return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);

    }

        public function actionUpload($id)
    {
        $dataRequest['Accounts'] = Yii::$app->request->getBodyParams();
        $model= Accounts::findOne(['account_id'=>$id]);
        if($model->load($dataRequest)) {
            $model->cheque = UploadedFile::getInstanceByName('cheque');
            $baseName = Numbers::randomString();
            $newfile = $baseName.'.'.$model->cheque->extension;
            $model->cheque->saveAs(Yii::getAlias('@webroot').'/cheques/'. $newfile);
            $model->cheque = $newfile;
            $model->status = 10;
            $model->created_by = Yii::$app->user->identity->id; 
            $model->save(false);
            return $this->apiUpdated($model);
        }

        return $this->apiValidate($model->errors);
    }

    protected function findModel($id)
    {
        if(($model = Accounts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Resource not found');
        }
    }

        public function actionSearch($id=null)
    {

            $out = Accounts::find()
                ->orFilterWhere(['like', 'account_name', $id])
                 ->orFilterWhere(['like', 'bank_id', $id])
                ->orFilterWhere(['like', 'account_number', $id])
                 ->orFilterWhere(['like', 'kra_pin', $id])
                  ->orFilterWhere(['like', 'status', $id])
                   ->orFilterWhere(['like', 'created_at', $id])
                      ->orFilterWhere(['like', 'updated_at', $id])
                       ->orFilterWhere(['like', 'created_by', $id])
                ->all(); 

            return $out;
       
    }
}
