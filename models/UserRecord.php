<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 13:04
 */
namespace app\models;
use yii\db\ActiveRecord;

class UserRecord extends ActiveRecord{
    public static function tableName()
    {
        return "{{user}}";
    }

    public function rules()
    {
        return [
            [['name', 'password', 'email'], "required"]
        ];
    }
    public static function find(){
        return new Query(get_called_class());
    }
}