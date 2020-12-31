<?php

namespace app\models;

use yii\db\ActiveRecord;

class Events extends ActiveRecord
{
    public $month;
    public static function tableName()
    {
        return '{{%events}}';
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
            [['title','start', 'end','dragBgColor'], 'required'],
            ['title', 'string', 'max' => 50],
            ['start', 'string', 'max' => 50],
            ['end', 'string', 'max' => 50],
            ['month', 'string', 'max' => 50],
            ['status', 'string', 'max' => 50],
            ['dragBgColor', 'string', 'max' => 50],
            ['created_at', 'string', 'max' => 50],
            ['updated_at', 'string', 'max' => 50],
            ['created_by', 'string', 'max' => 50],
        ];
    }
}