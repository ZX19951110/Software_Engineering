<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 22:46
 */
namespace app\models;
use yii\db\ActiveRecord;
class MovieTag extends ActiveRecord{
    public static function tableName()
    {
        return "{{movie_tag}}";
    }
    public static function find(){
        return new Query(get_called_class());
    }
}