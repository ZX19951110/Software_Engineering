<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 22:47
 */
namespace app\controllers;
use app\models\MovieTag;
use yii\db\Exception;
use yii\web\Controller;
use app\models\MovieRecord;
use Yii;
class MovietagController extends Controller{
    public function actionSelect(){
        $tag = Yii::$app->request->get("tag");
        $movie_ids = MovieTag::find()->where(['tag_name' => $tag])->select("movie_id")->asArray()->all();
        $res = [];
        try {
            foreach ($movie_ids as $movie_id) {
                $movie = MovieRecord::find()->where(["movie_id" => $movie_id])->asArray()->one();
                if ($movie) {
                    $res[] = $movie;
                }
            }
            return json_encode(['status' => "0", 'data' => $res]);
        }catch (Exception $e){
            return json_encode(['status' => "1", 'message' => $e->getMessage()]);
        }
    }
}