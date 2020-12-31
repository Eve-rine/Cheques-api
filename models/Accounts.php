<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\Banks;
use app\models\Branches;
use app\models\Signatories;


/**
 * @SWG\Definition(
 *   definition="CreateAccounts",
 *   type="object",
 *   required={{"bank_id","branch_id","account_name","account_number","kra_pin","minimum_signatories"},
 {"user_id","account_id","type"}},
 *   @SWG\Property(property="bank_id", type="string"),
 *   @SWG\Property(property="branch_id", type="string"),
  *   @SWG\Property(property="account_name", type="string"),
   *   @SWG\Property(property="account_number", type="string"),
    *   @SWG\Property(property="kra_pin", type="string"),
    *   @SWG\Property(property="minimum_signatories", type="string"),
     *   @SWG\Property(property="user_id", type="string"),
 *   @SWG\Property(property="account_id", type="string"),
 @SWG\Property(property="type", type="string")
 * )
 */


/**
 * @SWG\Definition(
 *   definition="UpdateAccounts",
 *   type="object",
 *   required={"bank_id", "branch_id","account_name","account_number","kra_pin","minimum_signatories"},
 *   allOf={
 *       @SWG\Schema(ref="#/definitions/CreateAccounts"),
 *   }
 * )
 */

/**
 * @SWG\Definition(
 *   definition="Accounts",
 *   type="object",
 *   required={"bank_id", "branch_id","account_name","account_number","kra_pin","minimum_signatories"},
 *   allOf={
 *       @SWG\Schema(ref="#/definitions/CreateBanks"),
 *       @SWG\Schema(
 *           required={"account_id"},
 *           @SWG\Property(property="id", format="int64", type="integer")
 *       )
 *   }
 * )
 */

class Accounts extends ActiveRecord
{
    public $signatories;

    public static function tableName()
    {
        return '{{%accounts}}';
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
            [['bank_id', 'branch_id','account_name','account_number','kra_pin','minimum_signatories'], 'safe'],
            [['account_name','account_number','kra_pin','minimum_signatories','status','created_by','created_at','updated_at'],'string', 'max' => 255],
            [['bank_id','branch_id'], 'integer'],
            ['cheque', 'file']

        ];
    }

public function getBranch()
{
    return $this->hasOne(Branches::className(), ['branch_id' => 'branch_id']);
    
}
public function getBank()
{
    return $this->hasOne(Banks::className(), ['bank_id' => 'bank_id']);
    
}
public function getSignatory()
{
    return $this->hasMany(Signatories::className(), ['account_id' => 'account_id']);
    
}
}