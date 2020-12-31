<?php
namespace app\components;
use yii\helpers\Html;
use Yii;
class ChequesPrint
{
  public static function costBatch($id) 
  {
    $batch_ref = (new \yii\db\Query())
             ->select(['batch_id'])
             ->from('batch')
             ->where(['batch_id' => $id])
             ->one();
    return $batch_ref['batch_id'];
  }
  public static function costCreatedBy($batch) 
  {
    $created_by = (new \yii\db\Query())
             ->select(['created_by'])
             ->from('cheques')
             ->where(['batch_id' => $batch['batch_id']])
             ->one();
    return $created_by['created_by'];
  }
  public static function batchPayee($batch) 
  {
    $payee = (new \yii\db\Query())
             ->select(['payee'])
             ->from('cheques')
             ->where(['batch_id' => $batch['batch_id']])
             ->one();
    return $payee['payee'];
  }
  public static function batchAmount($batch) 
  {
    $batchAmount = (new \yii\db\Query())
             ->select(['amount'])
             ->from('cheques')
             ->where(['batch_id' => $batch['batch_id']])
             ->one();
    return number_format($batchAmount['amount'],2);
  }
  public static function costCreatedAt($batch) 
  {
    $created_at = (new \yii\db\Query())
             ->select(['created_at'])
             ->from('cheques')
             ->where(['batch_id' => $batch['batch_id']])
             ->one();
    return date('d M, Y',$created_at['created_at']);
  }
  public static function listAmount($batch) 
  {
    $batchAmount = (new \yii\db\Query())
             ->select(['amount'])
             ->from('batch')
             ->where(['batch_id' => $batch['batch_id']])
             ->one();
    return number_format($batchAmount['amount'],2);
  }
  public static function listCreatedBy($batch) 
  {
    $created_by = (new \yii\db\Query())
             ->select(['created_by'])
             ->from('batch')
             ->where(['batch_id' => $batch['batch_id']])
             ->one();
    return $created_by['created_by'];
  }
  public static function listCreatedAt($batch) 
  {
    $created_at = (new \yii\db\Query())
             ->select(['created_at'])
             ->from('batch')
             ->where(['batch_id' => $batch['batch_id']])
             ->one();
    return date('d M, Y',$created_at['created_at']);
  }
}