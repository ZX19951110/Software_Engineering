<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 21:08
 */
namespace app\models;
use yii\db\ActiveRecord;
class MovieArtist extends ActiveRecord{
    public static function tableName()
    {
        return "{{movie_artist}}";
    }
    public static function find(){
        return new Query(get_called_class());
    }
}