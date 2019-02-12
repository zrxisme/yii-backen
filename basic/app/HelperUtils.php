<?php
namespace app\app;
use yii\helpers\Html;
class  HelperUtils{
     public static function filterParams($data){
         foreach($data as $key=>$value){
            $data[$key] = Html::encode($value);
         }
         return $data;
     }
}