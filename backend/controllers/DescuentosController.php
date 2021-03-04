<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Descuentos;
use backend\models\DescuentosSearch;
use backend\models\DescuentosForm;
use yii\helpers\ArrayHelper;
use app\models\UploadForm;

class DescuentosController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'desc', 'eliminar', 'creardesc', 'ver', 'editardesc', 'globaldes'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionIndex()
    {
    	$searchModel = new DescuentosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(isset(Yii::$app->request->queryParams) && Yii::$app->request->queryParams['DescuentosSearch']['codigo']){
            $searchModel->codigo = Yii::$app->request->queryParams['DescuentosSearch']['codigo'];
        }

        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'filtro' => $searchModel,
        ]);
    }

    public function actionDesc()
    {
        $model = new DescuentosForm();
        return $this->renderAjax('_crear-desc', [
            'model' => $model,
        ]);
    }

    public function actionEliminar()
    {
        $data = json_decode(stripslashes($_POST['data']));
        $deletedids=array();
        foreach($data as $d){
            $model = Descuentos::findOne($d);
            $model->activo = 0;
            if($model->save())
            {
                $deletedids[]=$d;
            }
        }
        print json_encode($deletedids);
    }
    public function actionCreardesc()
    {
        $promo = new DescuentosForm();
        $accion = $promo->saveDescuento(Yii::$app->request->post());
        return $accion;
    }

    public function actionEditardesc()
    {
        $promo = new DescuentosForm();
        $accion = $promo->editarDescuentos(Yii::$app->request->post());
        return $accion;
    }

    public function actionGlobaldes()
    {
        $des_glob = Descuentos::findOne(['global'=> 1]);
        $des_nuevo_glob = Yii::$app->request->post('des_nuevo_glob');
        if(Yii::$app->request->post('nuevo') == 1){
            if($des_glob){
                if($des_nuevo_glob == 1){
                    $des_glob->global = 0;
                    $des_glob->activo = 0;
                    $des_glob->save();
                    return true;
                }else{
                    return true;
                }
            }
            return true;
        }elseif($des_glob && Yii::$app->request->post('desc_id') != $des_glob->id){
            return false;
        }
        return true;
    }

    public function actionVer(){
        $model = new DescuentosForm();
        $descuento = Descuentos::findOne(['id', Yii::$app->request->post('id')]);
        return $this->renderAjax('_editar-desc', [
            'model' => $model,
            'descuento' => $descuento,

        ]);
    }
}
?>
