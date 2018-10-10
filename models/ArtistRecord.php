<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 20:13
 */
namespace app\models;
use yii\db\ActiveRecord;
class ArtistRecord extends ActiveRecord{
    public static function tableName()
    {
        return "{{artist}}";
    }
    public static function find(){
        return new Query(get_called_class());
    }
}