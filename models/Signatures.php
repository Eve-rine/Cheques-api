<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "signatures".
 *
 * @property int $sig_id
 * @property float $min_amount
 * @property float $max_amount
 * @property int $signatory_one
 * @property string $type_one
 * @property int $signatory_two
 * @property string $type_two
 * @property int $signatory_three
 * @property string $type_three
 */
class Signatures extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'signatures';
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
            [['min_amount', 'max_amount', 'signatory_one','signatory_two','signatory_three','account_id'], 'required'],
            [['min_amount', 'max_amount'], 'number'],
            [['signatory_one', 'signatory_two', 'signatory_three','account_id'], 'integer'],
            [['type_one', 'type_two', 'type_three','status','created_at','updated_at'], 'string', 'max' => 50],

            ['signatory_two', 'compare','compareAttribute'=>"signatory_one",'operator'=>"!="],
            ['signatory_two', 'compare','compareAttribute'=>"signatory_three",'operator'=>"!="],
            ['signatory_three', 'compare','compareAttribute'=>"signatory_one",'operator'=>"!="],     
['max_amount', 'compare', 'operator' => '>=', 'compareAttribute' => 'min_amount', 'skipOnError' => ['min_amount', 'max_amount']],
        ];
    }

public function getAccount()
{
    return $this->hasOne(Accounts::className(), ['account_id' => 'account_id']);
    
}

}
