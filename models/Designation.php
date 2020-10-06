<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_designation".
 *
 * @property int $id
 * @property string $name
 */
class Designation extends \yii\db\ActiveRecord
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_designation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','login'], 'required'],
            [['login'], 'integer'],
            [['name'], 'string', 'max' => 70],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'login' => 'login',
        ];
    }
    public static function getList() {
        return array('' => '') + ArrayHelper::map(self::find()->all(),'id','name'); 
    }
   

}
