<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @SWG\Definition(
 *   definition="CreateRegions",
 *   type="object",
 *   required={"name", "location","supervisor"},
 *   @SWG\Property(property="name", type="string"),
 *   @SWG\Property(property="location", type="string"),
  *   @SWG\Property(property="supervisor", type="string")
 * )
 */

/**
 * @SWG\Definition(
 *   definition="UpdateRegions",
 *   type="object",
 *   required={"name", "location","supervisor"},
 *   allOf={
 *       @SWG\Schema(ref="#/definitions/CreateRegions"),
 *   }
 * )
 */

/**
 * @SWG\Definition(
 *   definition="Regions",
 *   type="object",
 *   required={"name", "location","supervisor"},
 *   allOf={
 *       @SWG\Schema(ref="#/definitions/CreateRegions"),
 *       @SWG\Schema(
 *           required={"id"},
 *           @SWG\Property(property="id", format="int64", type="integer")
 *       )
 *   }
 * )
 */

class Regions extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%regions}}';
    }

    public function rules()
    {
        return [
            [['name', 'location','supervisor'], 'required'],
            ['name', 'string', 'max' => 255],
            [['location','supervisor'], 'string']
        ];
    }
}