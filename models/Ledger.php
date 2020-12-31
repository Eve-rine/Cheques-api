<?php

namespace app\models;

use yii\db\ActiveRecord;

class Ledger extends ActiveRecord
{
    public $newVal;
    public $oldVal;
    public $field;
    public static function tableName()
    {
        return '{{%ledgers}}';
    }
     public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
        ]; 
    }

    public function rules()
    {
        return [
            [['ledger_id','transaction_date', 'value_date','naration','amount_out','amount_in','amount','ledger_balance','ledger_type','start_month','end_month','status','created_at','updated_at','created_by'], 'required'],
            ['ledger_id', 'string', 'max' => 50],
            ['transaction_date', 'string', 'max' => 50],
            ['value_date', 'string', 'max' => 50],
            ['naration','string','max' => 50],
            ['amount_out','string','max' => 50],
            ['amount_in','string','max' => 50],
            ['amount','string','max' => 50],
            ['ledger_balance','string','max' => 50],
            ['ledger_type','string','max' => 50],
            ['start_month','string','max' => 50],
            ['end_month','string','max' => 50],
            ['month_year','string','max' => 50],
            ['newVal','string','max' => 50],
            ['oldVal','string','max' => 50],
            ['field','string','max' => 50],
            ['status','string','max' => 50],
            ['created_date','string','max' => 50],
            ['created_at', 'string', 'max' => 50],  
            ['updated_at', 'string', 'max' => 50],
            ['created_by', 'string', 'max' => 50],
        ];
    }
}