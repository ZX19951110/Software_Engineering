<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 22:33
 */
namespace app\controllers;
use app\models\MovieComment;
use yii\base\Controller;
use Yii;
use yii\db\Exception;

class MoviecommentController extends Controller{
    public function actionAdd(){
        if(!isset($_COOKIE['user_id'])){
            return json_encode(['status' => "1", 'message' => "Please Log In" ]);
        }
        $movie_id = Yii::$app->request->get("movie_id");
        $comment = Yii::$app->request->get("comment");
        $movie_comment = new MovieComment();
        $movie_comment->movie_id = $movie_id;
        $movie_comment->comment = $comment;
        $movie_comment->rank = Yii::$app->request->get("rank")?Yii::$app->request->get("rank"):0;
        $movie_comment->user_id = $_COOKIE['user_id'];
        $movie_comment->good = 0;
        try{
            $movie_comment->save();
            return json_encode(["status" => "0", 'data' => "Success"]);
        }catch (Exception $e){
            return json_encode(['status' => "1", 'message' => $e->getMessage() ]);
        }
    }
}