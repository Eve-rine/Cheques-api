<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "currency".
 *
 * @property int $currency_id
 * @property string $voucher_no
 * @property string $currency_code
 * @property string $currency_name
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currency';
    }

          public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
        ]; 
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['voucher_no', 'currency_code', 'currency_name'], 'required'],
            [['voucher_no', 'currency_code'], 'string', 'max' => 50],
            [['currency_name','status','created_at','updated_at','created_by'], 'string', 'max' => 250],
        ];
    }

}
