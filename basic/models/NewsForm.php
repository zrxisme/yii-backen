<?php
namespace app\models;
use yii\db\ActiveRecord;

class NewsForm extends ActiveRecord{
    public $username;
    public $password;
   public function rules(){
       return [
           [["username","password"],"required","on"=>["validateData"]]
       ];
   }
   public static function tableName(){
       return "news";
   }

   public function validateData($data){
       $this->scenario = "validateData";
       $this->load($data,"");
       if($this->validate()){
        $data = self::find()->select(["name","password"])->where(
            "name=:name and password=:password",
            [":name"=>$this->username,
            ":password"=>$this->password]
            )->one();
        if(is_null($data)){
           $this->addError("error","用户不存在");
        }else{
            return $data;
        }
       }else{
        $this->addError("error","参数错误");
       }
   }
}