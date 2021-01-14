<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\Branches;
use app\models\Accounts;

/**
 * @SWG\Definition(
 *   definition="CreateBanks",
 *   type="object",
 *   required={"bank_name", "head_office_address","head_office_number","head_office_email","bank_code"},
 *   @SWG\Property(property="bank_name", type="string"),
 *   @SWG\Property(property="head_office_address", type="string"),
  *   @SWG\Property(property="head_office_number", type="string"),
   *   @SWG\Property(property="head_office_email", type="string"),
    *   @SWG\Property(property="bank_code", type="string"),
 * )
 */

/**
 * @SWG\Definition(
 *   definition="UpdateBanks",
 *   type="object",
 *   required={"bank_name", "head_office_address","head_office_number","head_office_email","bank_code"},
 *   allOf={
 *       @SWG\Schema(ref="#/definitions/CreateBanks"),
 *   }
 * )
 */

/**
 * @SWG\Definition(
 *   definition="Banks",
 *   type="object",
 *   required={"bank_name", "head_office_address","head_office_number","head_office_email","bank_code"},
 *   allOf={
 *       @SWG\Schema(ref="#/definitions/CreateBanks"),
 *       @SWG\Schema(
 *           required={"bank_id"},
 *           @SWG\Property(property="id", format="int64", type="integer")
 *       )
 *   }
 * )
 */

class Banks extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%banks}}';
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
           
              [['bank_name','bank_code','head_office_number','head_office_address','head_office_email'], 'required'],
            [['bank_name','bank_code','head_office_number', 'head_office_address','head_office_email','status','created_at','updated_at','created_by'],'string', 'max' => 255],
           
        ];
    }

    public function getBranchy()
{
    return $this->hasMany(Branches::className(), ['bank_id' => 'bank_id']);
}
    public function getAccounts()
{
    return $this->hasMany(Accounts::className(), ['bank_id' => 'account_id']);
}

}