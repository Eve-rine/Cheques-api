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
        $dataProvider->query->having(['status' => 'complete']);
        return $this->apiCollection([
            'count'      => $dataProvider->count,
            'dataModels' => $dataProvider->models,
        ], $dataProvider->totalCount);
    }

    public function actionCreated()
    {
        $dataRequest['Events'] = Yii::$app->request->getBodyParams();
        $model = new Events();
        if($model->load($dataRequest)) {
            if (Yii::$app->user->identity->id != 1) {
               return $this->apiPermission();
            }else{

            $model->category = 'allday';
            $model->status = 'complete';
             $model->dateMonth = date("M");
            $model->created_by = Yii::$app->user->identity->id; 
            $model->save(false);
            return $this->apiCreated($model);
        }
        }
        return $this->apiValidate($model->errors);
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
               if ($model->month != null) {
                $month_arr = $model->month;
                $year = date("Y");
                foreach ($month_arr as $month) {
                    // start day
                    $now = strtotime("{$year}-{$month}-01");
                    // end day
                    $end = strtotime('-1 second', strtotime('+1 month', $now));
                    $monthYear = strtotime(date("Y-m-d"));
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
                   foreach ($weekends as $timestamp) {
                     $weekendDates = new Events();
                     $weekendDates->title = "Weekend blocked";
                     $weekendDates->bgColor = '#da2f24';
                      $weekendDates->category = 'allday';
                     $weekendDates->start = date("yy-m-d", $timestamp);
                     $weekendDates->end = date("yy-m-d", $timestamp);
                     $weekendDates->dateMonth = date("M", $timestamp);
                     $weekendDates->status = 'complete';
                     $weekendDates->created_by = Yii::$app->user->identity->id;
                     $weekendDates->save(false);
                   }
                }
               }
            }
            $model->title="Weekend blocked";
             $model->category = 'allday';
            $model->status = 'complete';
            $model->created_by = Yii::$app->user->identity->id; 
            $model->dateMonth = date("M", strtotime($model->start));
            $model->start = date("yy-m-d");
            $model->end = date("yy-m-d");
            $model->bgColor = '#da2f24';
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

    // public function actionDelete($id)
    // {
    //     if (Yii::$app->user->identity->id != 1) {
    //            return $this->apiPermission();
    //         }else{
    //     if($this->findModel($id)->delete()) {
    //         return $this->apiDeleted(true);
    //     }
    // }
    //     return $this->apiDeleted(false);
    // }

    public function actionDelete($id)
    {
        $dataRequest['Events'] = Yii::$app->request->getBodyParams();
        $model= Events::findOne(['id'=>$id]);
        if($model->load($dataRequest)) {
            if (Yii::$app->user->identity->id != 1) {
               return $this->apiPermission();
            }else{
                $model->status='cancelled';
            $model->created_by = Yii::$app->user->identity->id; 
            $model->save(false);
            return $this->apiUpdated($model);
            }
        }

        return $this->apiValidate($model->errors);
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
