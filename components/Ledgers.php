<?php
namespace app\components;
use app\models\Ledger;
use app\models\Accounts;
use yii\helpers\Html;
use Yii;
class Ledgers
{
	public static function newEntryCash() 
	{
		     $ledger_cash_opening = new Ledger();
         $ledger_cash_opening->transaction_date = date('d-M-Y',strtotime('first day of this month'));
         $ledger_cash_opening->value_date = date('d-M-Y',strtotime('first day of this month'));
         $ledger_cash_opening->naration = 'Balance B/FW';
         $ledger_cash_opening->running_balance = 0;
         $ledger_cash_opening->month_year = date('M, Y');
         $ledger_cash_opening->created_date = date('d M, Y');
         $ledger_cash_opening->start_month = strtotime('first day of this month');
         $ledger_cash_opening->end_month = strtotime('last day of this month');
         $ledger_cash_opening->status=5;
         $ledger_cash_opening->created_by=Yii::$app->user->identity->id;
         $ledger_cash_opening->save(false);
	}
	public static function recalculateCash($balance) 
	{
        $query = (new \yii\db\Query())->select(['amount'])->from('ledgers')->where(['status' => 9])->andWhere(['<=', 'value_date', $balance['value_date']])->orderBy('value_date');
        $totalAmount=$query->sum('amount');
        if ($query!=null) {
          $active =Ledger::find()->where(['status' => 5])->limit(1)->one();
          $ActiveFigures =Ledger::find()->where(['month_year' => $active->month_year])->one();
          $running_balance=$ActiveFigures->running_balance;
          $UpdatedBalance= Ledger::find()->orderBy(['ledger_id' => SORT_DESC])->one();

         $UpdatedBalance->running_balance = $totalAmount+$running_balance;
         $UpdatedBalance->save(false);
        }
	}
    
	
}