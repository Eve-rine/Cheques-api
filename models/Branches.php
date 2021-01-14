<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\Banks;
use app\models\Accounts;

/**
 * @SWG\Definition(
 *   definition="CreateBranches",
 *   type="object",
 *   required={"branch_name","bank_id","physical_address","contact_person_name","contact_person_number","contact_person_email"},
 *   @SWG\Property(property="branch_name", type="string"),
  *   @SWG\Property(property="bank_id", type="string"),
 *   @SWG\Property(property="physical_address", type="string"),
 *   @SWG\Property(property="contact_person_name", type="string"),
 *   @SWG\Property(property="contact_person_number", type="string"),
 *   @SWG\Property(property="contact_person_email", type="string"),
 * )
 */

/**
 * @SWG\Definition(
 *   definition="UpdateBranches",
 *   type="object",
 *   required={"branch_name","bank_id", "physical_address","contact_person_name","contact_person_number","contact_person_email"},
 *   allOf={
 *       @SWG\Schema(ref="#/definitions/CreateBranches"),
 *   }
 * )
 */

/**
 * @SWG\Definition(
 *   definition="Branches",
 *   type="object",
 *   required={"branch_name","bank_id", "physical_address","contact_person_name","contact_person_number","contact_person_email"},
 *   allOf={
 *       @SWG\Schema(ref="#/definitions/CreateBranches"),
 *       @SWG\Schema(
 *           required={"bank_id"},
 *           @SWG\Property(property="id", format="int64", type="integer")
 *       )
 *   }
 * )
 */

class Branches extends ActiveRecord
{
    public $bank_name;
    public static function tableName()
    {
        return '{{%branches}}';
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
            [['branch_name','bank_id', 'physical_address','contact_person_name','contact_person_number','contact_person_email'], 'required'],
            [['branch_name', 'physical_address','contact_person_name','contact_person_number','contact_person_email','status','created_by','created_at','updated_at'],'string', 'max' => 255],
            [['bank_id'], 'integer'],
            [['bank_name'], 'safe']

        ];
    }

public function getBank()
{
    return $this->hasOne(Banks::className(), ['bank_id' => 'bank_id']);
    
}
    public function getAccounts()
{
    return $this->hasMany(Accounts::className(), ['branch_id' => 'account_id']);
}

public function extraFields()
{
    return ['bank'];
}
}