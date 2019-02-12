<?php

namespace app\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\News;
use app\models\NewsForm;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;
use yii\filters\HttpCache;
use  app\app\HelperUtils;
//1、想要添加自定义的函数，先新建文件夹然后新建一个类例如 app/HelperUtils.php
//2、在类中添加静态方法，例如HelperUtils::HtmlFilter()
//3、在composer中添加autoload:"app/HelperUtils"指明你要自动加载哪些内容
//4、执行composer dump-autoload命令把类添加到自动加载内容中
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return ArrayHelper::merge([
            'corsController'=>[
                'class' => Cors::className(),
                'cors' => [
                    'Origin'=>['*'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Request-Method' => ['POST','GET','OPTIONS']
                ]
            ],
            'cache'=>[
                'class' => HttpCache::className(),
                'only' => ['index'],
                'lastModified' => function () {
                    return filemtime("./index.php");
                }
            ]
        ], parent::behaviors());

    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // $news = new News();
        // $data = Yii::$app->request->post();
        // $news["name"] = $data['username'];
        // $news["password"] = $data['password'];
        // $news->save();
        $model = new NewsForm;
      // $data = UtilsHelper(Yii::$app->request->post());
          $data = HelperUtils::filterParams(Yii::$app->request->post());
       // $model->load($data,''); //注意，如果不是通过表单的方式提交的请求，一定要在load的第二个参数叫一个字符串/否则无法加载数据成功
                                //原因：查看源码你就了解了，yii中默认post是表单提交，所以load的时候尝试获取表单的名称，而我们开发的restful接口
                                //      提交的是json数据，所以无法获取formName，所以天一个空值可以跳过表单的名称验证！
                                //      源码路径vendor/yiisoft/yii2/base/Model.php
         $mydata = $model->validateData($data);                       
        if(!$model->hasErrors()){
            $msg = ["msg"=>'success',"data"=>$mydata];
            return  $msg;
        }else{
            $msg = ["msg"=>$model->getErrors(),'status'=>"fail"];
            return  $msg;
        }
    }
}
