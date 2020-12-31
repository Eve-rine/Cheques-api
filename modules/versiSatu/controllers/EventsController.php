<?php

namespace app\modules\versiSatu\controllers;

use app\components\Controller;
use app\models\Events;
use yii\web\NotFoundHttpException;
use app\models\search\EventsSearch;
use app\models\search\DatesSearch;
use app\components\Helpers;
use Yii;

class EventsController extends Controller
{
    public function actionIndex()
    {
        $search['EventsSearch'] = Yii::$app->request->queryParams;
        $searchModel  = new EventsSearch();
        $dataProvider = $searchModel->search($search);
        return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);
    }

    public function actionCreate()
    {
        $dataRequest['Events'] = Yii::$app->request->getBodyParams();
        $model = new Events();
        if($model->load($dataRequest)) {
            if (Yii::$app->user->identity->id != 1) {
               return $this->apiPermission();
            }else{
            if ($model->status == 'Yes') {
                $year = date("Y");
                 $month = date("M");
                    // start day
                    $now = strtotime("{$year}-{$month}-01");
                    // end day
                    $end = strtotime('-1 second', strtotime('+1 month', $now));
                    $day = intval(date("N", $now));
                   $weekends = [];
                   if ($day < 6) {
                     $now += (6 - $day) * 86400;
                   }
                   while ($now <= $end) {
                     $day = intval(date("N", $now));
                     if ($day == 6) {
                       $weekends[] += $now;
                       $now += 86400;
                     }
                     elseif ($day == 7) {
                       $weekends[] += $now;
                       $now += 518400;
                     }
                   }
                     $model->title = "Weekend blocked";
                     $model->dragBgColor = '#da2f24';
                     // $model->start = date("yy-m-d");
                     // $model->end = date("yy-m-d");

            }
            $model->status = 'complete';
            $model->created_by = Yii::$app->user->identity->id; 
            $model->save(false);
            return $this->apiCreated($model);
        }
        }
        return $this->apiValidate($model->errors);
    }

    public function actionUpdate($id)
    {
        $dataRequest['Events'] = Yii::$app->request->getBodyParams();
        $model= Events::findOne(['id'=>$id]);
        if($model->load($dataRequest)) {
            if (Yii::$app->user->identity->id != 1) {
               return $this->apiPermission();
            }else{
            $model->status = 'complete';
            $model->created_by = Yii::$app->user->identity->id; 
            $model->save(false);
            return $this->apiUpdated($model);
            }
        }

        return $this->apiValidate($model->errors);
    }
    public function actionView($id)
    {
        return $this->apiItem($this->findModel($id));
    }

    public function actionDelete($id)
    {
        if (Yii::$app->user->identity->id != 1) {
               return $this->apiPermission();
            }else{
        if($this->findModel($id)->delete()) {
            return $this->apiDeleted(true);
        }
    }
        return $this->apiDeleted(false);
    }

    protected function findModel($id)
    {
        if(($model = Events::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Resource not found');
        }
    }
}
