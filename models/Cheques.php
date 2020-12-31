<?php

namespace app\models;

use yii\db\ActiveRecord;

class Cheques extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%cheques}}';
    }

   

    public function rules()
    {
        return [
            [['payee', 'amount', 'pay_date','cheque_type'], 'required'],
            ['payee', 'string', 'max' => 50],
            ['amount', 'string', 'max' => 50],
            ['pay_date', 'string', 'max' => 50],
            ['cheque_no', 'string', 'max' => 50],
            ['approval', 'string', 'max' => 50],
            ['status', 'string', 'max' => 50],
            ['batch_id', 'string', 'max' => 50],
            ['bank_id', 'string', 'max' => 50],
            ['branch_id', 'string', 'max' => 50],
            ['account_id', 'string', 'max' => 50],
            ['cheque_type', 'string', 'max' => 50],
            ['created_at', 'string', 'max' => 50],
            ['created_at', 'string', 'max' => 50],
            ['updated_at', 'string', 'max' => 50],
            ['created_by', 'string', 'max' => 50],
        ];
    }
}