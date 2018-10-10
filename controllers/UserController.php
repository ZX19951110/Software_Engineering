<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 13:48
 */
namespace app\controllers;
use app\models\UserRecord;
use yii\db\Exception;
use yii\web\Controller;
use Yii;

class UserController extends Controller{
    public function actionAdd(){
        if(!isset($_COOKIE['admin_type'])){
            return json_encode(['status' => "1", 'message' => "You Are Not Admin" ]);
        }
        if($_COOKIE['admin_type'] != "1"){
            return json_encode(['status' => "1", 'message' => "You Are Not Admin" ]);
        }
        $flag = 1;
        $name = Yii::$app->request->get("name")?Yii::$app->request->get("name"):$flag = 0;
        $password = md5(Yii::$app->request->get("password"))?md5(Yii::$app->request->get("password")):$flag = 0;
        $email = Yii::$app->request->get("email")?Yii::$app->request->get("email"):$flag = 0;
        if($flag == 0){
            return json_encode(['status' => "1", 'message' => "Inputs Missing" ]);
        }
        $user = new UserRecord();
        $user->name = $name;
        $user->password = $password;
        $user->email = $email;
        if(Yii::$app->request->get("admin_type") == 1){
            $user->admin_type = 1;
        }
        try{
            $user->save();
            return json_encode(["status" => "0", 'data' => "Success"]);
        }catch (Exception $e){
            return json_encode(['status' => "1", 'message' => $e->getMessage()]);
        }
    }
    public function actionList(){
        if(!isset($_COOKIE['admin_type'])){
            return json_encode(['status' => "1", 'message' => "You Are Not Admin" ]);
        }
        if($_COOKIE['admin_type'] != "1"){
            return json_encode(['status' => "1", 'message' => "You Are Not Admin" ]);
        }
        try{
            $res = UserRecord::find()->asArray()->orderBy(["date" => SORT_DESC])->all();
            return json_encode(["status" => "0", 'data' => $res]);
        }catch (Exception $e){
            return json_encode(['status' => "1", 'message' => $e->getMessage()]);
        }

    }
    public function actionGet(){
        $name = Yii::$app->request->get("name");
        try {
            $res = UserRecord::find()->where(['name' => $name])->asArray()->one();
        }catch (Exception $e){
            return json_encode(['status' => "1", 'message' => $e->getMessage()]);
        }
        return json_encode(['status' => "0", 'data' => $res]);
    }
    public function actionLogin(){
        $name = Yii::$app->request->get("name");
        $password = md5(Yii::$app->request->get("password"));
        $cond1 = [
            'name' => $name,
            'password' => $password
        ];
        $cond2 = [
            'email' => $name,
            'password' => $password
        ];
        try {
            $user = UserRecord::find()->where($cond1)->asArray()->one() ? UserRecord::find()->where($cond1)->asArray()->one() : UserRecord::find()->where($cond2)->asArray()->one();
        }catch (Exception $e){
            return json_encode(['status' => "1", 'message' => $e->getMessage()]);
        }
        if($user){
            setcookie('user_name', $user['name'],time()+86400, "/");
            setcookie('admin_type', $user['admin_type'],time()+86400, "/");
            setcookie('user_id', $user['user_id'],time()+86400, "/");
            return json_encode(["status" => "0", 'data' => "Login Success"]);
        }
        else {
            return json_encode(['status' => "1", 'message' => "Please Check Inputs" ]);
        }
    }
    public function actionLogout(){
        if (!isset($_COOKIE["user_name"]) && !isset($_COOKIE["user_name"]) && !isset($_COOKIE["user_id"])){
            return json_encode(['status' => "1", 'message' => "Please Login" ]);
        }
        setcookie('user_name','',time()-3600);
        setcookie('admin_type','',time()-3600);
        setcookie('user_id','',time()-3600);
        return json_encode(["status" => "0", 'data' => "Logout Success"]);
    }
}