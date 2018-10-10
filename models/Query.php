<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 13:53
 */
namespace app\models;
use yii\db\ActiveQuery;
class Query extends ActiveQuery{
    public function getAll(){
        return parent::all();
    }
}