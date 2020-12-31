<?php
namespace app\components;
use yii\helpers\Html;
use Yii;
class Username
{
  public static function createdBy($id) 
  {
    $created_by = (new \yii\db\Query())
             ->select(['username'])
             ->from('users')
             ->where(['id' => $id])
             ->one();
    return $created_by['username'];
  }
  public static function bankName($id) 
  {
    $bank = (new \yii\db\Query())
             ->select(['bank_name'])
             ->from('banks')
             ->where(['bank_id' => $id])
             ->one();
    return $bank['bank_name'];
  }
  public static function branchName($id)
  {
    $branch = (new \yii\db\Query())
             ->select(['branch_name'])
             ->from('branches')
             ->where(['branch_id' => $id])
             ->one();
    return $branch['branch_name'];
  }
  public static function accountName($id)
  {
    $account = (new \yii\db\Query())
             ->select(['account_number'])
             ->from('accounts')
             ->where(['account_id' => $id])
             ->one();
    return $account['account_number'];
  }
  public static function ledgerAccountName($id)
  {
    $account = (new \yii\db\Query())
             ->select(['account_name'])
             ->from('ledger_accounts')
             ->where(['ledger_account_id' => $id])
             ->one();
    return $account['account_name'];
  }
}