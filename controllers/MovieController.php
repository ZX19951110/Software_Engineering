<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 15:42
 */
namespace app\controllers;
use app\models\MovieComment;
use Yii;
use app\models\MovieRecord;
use yii\db\Exception;
use yii\web\Controller;
use app\models\MovieArtist;
use app\models\ArtistRecord;
use app\models\MovieTag;
class MovieController extends Controller{
    public function actionAdd(){
        if(!isset($_COOKIE['admin_type'])){
            return json_encode(['status' => "1", 'message' => "You Are Not Admin" ]);
        }
        if($_COOKIE['admin_type'] != "1"){
            return json_encode(['status' => "1", 'message' => "You Are Not Admin" ]);
        }
        $artists = json_decode(Yii::$app->request->get("artists"));
        $artist_ids = [];
        foreach ($artists as $artist){
            $artist_entity = ArtistRecord::find()->where(['name' => $artist])->asArray()->one();
            if($artist_entity){
                $artist_ids[] = $artist_entity['artist_id'];
            }
            else{
                return json_encode(['status' => "1", 'message' => "Artist Not In Database" ]);
            }
        }
        $tags = json_decode(Yii::$app->request->get('tags'));
        $movie = new MovieRecord();
        $movie->name = Yii::$app->request->get("name");
        $movie->time = Yii::$app->request->get("time");
        $movie->year = Yii::$app->request->get("year");
        $movie->description = Yii::$app->request->get("description");
        //todo: 生成封面URI
        $cover = null;
        $movie->cover = $cover?$cover:"./pics/movie/cover/default.jpg";
        //todo: 生成截图URI
        $snapshot = [];
        $default_snapshots = [
            "./pics/movie/snapshot/default1.jpg",
            "./pics/movie/snapshot/default2.jpg",
            "./pics/movie/snapshot/default3.jpg",
        ];
        $movie->snapshot = $snapshot?json_encode($snapshot, JSON_UNESCAPED_SLASHES):json_encode($default_snapshots, JSON_UNESCAPED_SLASHES);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $movie->save();
            $conds = [
                "name" => $movie->name,
                "time" => $movie->time,
                "year" => $movie->year,
                "description" => $movie->description,
                "cover" => $movie->cover,
                "snapshot" => $movie->snapshot
            ];
            $movie_id = MovieRecord::find()->select("movie_id")->where($conds)->asArray()->one()['movie_id'];
            if(!$movie_id) throw new Exception('Fail');
            foreach ($artist_ids as $artist_id){
                $movie_artist = new MovieArtist();
                $movie_artist->movie_id = $movie_id;
                $movie_artist->artist_id = $artist_id;
                $movie_artist->save();
            }
            foreach ($tags as $tag){
                $movie_tag = new MovieTag();
                $movie_tag->movie_id = $movie_id;
                $movie_tag->tag_name = $tag;
                $movie_tag->save();
            }
            $transaction->commit();
            return json_encode(['status' => "0", 'data' => "Success"]);
        }catch (Exception $e){
            $transaction->rollBack();
            return json_encode(['status' => "1", 'message' => $e->getMessage()]);
        }
    }
    public function actionList(){
        $name = Yii::$app->request->get("name");
        try{
            if($name) {
                $res = MovieRecord::find()->andFilterWhere(["like", "name", $name])->asArray()->all();
            }
            else{
                $res = MovieRecord::find()->asArray()->all();
            }
            return json_encode(['status' => "0", "data" => $res]);
        }catch (Exception $e){
            return json_encode(['status' => "1", 'message' => $e->getMessage()]);
        }
    }
    public function actionRemove(){
        $movie_id = Yii::$app->request->get('movie_id');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            MovieComment::deleteAll(['movie_id' => $movie_id]);
            MovieTag::deleteAll(['movie_id' => $movie_id]);
            MovieArtist::deleteAll(['movie_id' => $movie_id]);
            MovieRecord::deleteAll(['movie_id' => $movie_id]);
            $transaction->commit();
            return json_encode(['status' => "0", 'data' => "Success"]);
        }catch (Exception $e){
            $transaction->rollBack();
            return json_encode(['status' => "1", 'message' => $e->getMessage()]);
        }
    }
}