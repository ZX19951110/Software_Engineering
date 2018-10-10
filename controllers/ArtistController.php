<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 20:16
 */

namespace app\controllers;
use app\models\ArtistRecord;
use Yii;
use yii\db\Exception;
use yii\web\Controller;

class ArtistController extends Controller {
    public function actionAdd(){
        if(!isset($_COOKIE['admin_type'])){
            return json_encode(['status' => "1", 'message' => "You Are Not Admin" ]);
        }
        if($_COOKIE['admin_type'] != "1"){
            return json_encode(['status' => "1", 'message' => "You Are Not Admin" ]);
        }
        $artist = new ArtistRecord();
        $artist->name = Yii::$app->request->get("name");
        $artist->description = Yii::$app->request->get("description");
        $artist->position = Yii::$app->request->get("position");
        //todo: 上传图片并生成图片URI的JSON字符串
        $pictures = [];
        $artist->pictures = $pictures?json_encode($pictures, JSON_UNESCAPED_SLASHES):json_encode(["./pics/artist/default.jpg"], JSON_UNESCAPED_SLASHES);
        try{
            $artist->save();
            return json_encode(["status" => "0", 'data' => "Success"]);
        }catch (Exception $e){
            return json_encode(['status' => "1", 'message' => $e->getMessage() ]);
        }

    }
    public function actionGet(){
        $name = Yii::$app->request->get("name");
        try {
            $res = ArtistRecord::find()->andFilterWhere(['like', 'name', $name])->orderBy(["artist_id" => SORT_DESC])->asArray()->all();
            foreach($res as &$artist){
                $artist['pictures'] = json_decode($artist['pictures']);
            }
            return json_encode(["status" => "0", 'data' => $res]);
        }catch (Exception $e){
            return json_encode(['status' => "1", 'message' => $e->getMessage() ]);
        }
    }
}