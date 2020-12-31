<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\Cheques;



class Batch extends ActiveRecord
{
    public $cheque_batch;
    public static function tableName()
    {
        return '{{%batch}}';
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
            // [['cheques', 'amount'], 'required'],
            ['batch_id', 'string', 'max' => 50],
            ['cheques', 'integer', 'max' => 50],
            ['cheque_batch', 'integer', 'max' => 50],
            ['amount', 'string', 'max' => 50],
            ['status', 'string', 'max' => 50],
            ['bank_id', 'string', 'max' => 50],
            ['branch_id', 'string', 'max' => 50],
            ['account_id', 'string', 'max' => 50],
            ['created_at', 'string', 'max' => 50],
            ['updated_at', 'string', 'max' => 50],
            ['created_by', 'string', 'max' => 50],
        ];
    }

    public function getCheque()
{
    return $this->hasMany(Cheques::className(), ['batch_id' => 'batch_id']);
    
}
}