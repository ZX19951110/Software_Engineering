<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 15:41
 */
namespace app\models;
use yii\db\ActiveRecord;
class MovieRecord extends ActiveRecord{
    public static function tableName()
    {
        return "{{movie}}";
    }
    public static function find(){
        return new Query(get_called_class());
    }
}