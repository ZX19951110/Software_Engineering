<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 22:30
 */
namespace app\models;
use yii\db\ActiveRecord;
class MovieComment extends ActiveRecord{
    public static function tableName()
    {
        return "{{movie_comment}}";
    }
    public static function find(){
        return new Query(get_called_class());
    }
}