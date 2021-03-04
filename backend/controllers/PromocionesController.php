<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use backend\models\PromocionSearch;
use backend\models\Promocion;
use backend\models\PromocionForm;
use backend\models\LibroPromocion;
use yii\data\ArrayDataProvider;
use backend\models\Libro;
use backend\models\Tema;
use backend\models\Sello;
use backend\models\Coleccion;
use backend\models\Imagenes;
use backend\models\LibrosSearch;
use app\models\UploadForm;


class PromocionesController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'ver', 'mostrar', 'actualizar', 'editpromo', 'promo', 'crearpromo', 'editarpromo', 'agregar', 'agregarpromo', 'eliminar'],
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
    	$searchModel = new PromocionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(isset(Yii::$app->request->queryParams) && Yii::$app->request->queryParams['PromocionSearch']['codigo']){
            $searchModel->titulo = Yii::$app->request->queryParams['PromocionSearch']['codigo'];
        }

        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'filtro' => $searchModel,
        ]);
    }

    public function actionMostrar(){
        $libros = new Libro();
        $model = new UploadForm();
        $accion = $libros -> verLibros(Yii::$app->request->get('id'));

        return $this->renderAjax('_mostrar-libros', [
            'accion' => $accion,
            'model' => $model,
        ]);
    }
    public function actionActualizar()
    {
        $libros = new Libro();
        $accion = $libros -> actualizarPrecio(Yii::$app->request->post('Libro'));
        return $accion;
    }

    public function actionCrearpromo()
    {
        $promo = new PromocionForm();
        $accion = $promo->savePromocion(Yii::$app->request->post());
        return $accion;
    }

    public function actionEditarpromo()
    {
        $promo = new PromocionForm();
        $accion = $promo->editarPromocion(Yii::$app->request->post());
        return $accion;
    }

    public function actionEditpromo()
    {
        $model = new PromocionForm();
        $promo_code = Yii::$app->request->post('promo_code');
        return $this->renderAjax('_renombrar-promo', [
            'model' => $model,
            'promo_code' => $promo_code,
        ]);
    }

    public function actionPromo()
    {
        $model = new PromocionForm();
        return $this->renderAjax('_crear-promo', [
            'model' => $model,
        ]);
    }

    public function actionAgregar()
    {
        $searchModel = new LibrosSearch();
        $dataProvider = $searchModel->search2(Yii::$app->request->get('buscar'));
        $id_promo = Yii::$app->request->get('id');
        return $this->renderAjax('_agregar-libros', [
            'dataProvider'=> $dataProvider,
            'id_promo'=>$id_promo,
            'filtro'=>$searchModel,
        ]);
    }

    public function actionAgregarpromo()
    {
        $data = json_decode(stripslashes($_POST['data']));
        $promo_id = Yii::$app->request->post('codigo');
        $newlibpromo=array();
        foreach($data as $d){
            $model = new LibroPromocion();
            $model->libro_id = $d;
            $model->promocion_id = $promo_id;
            if($model->save())
            {
                $newlibpromo[]=$d;
            }
 
        }
        return true;
    }

    public function actionEliminar()
    {
        $data = json_decode(stripslashes($_POST['data']));
        $deletedids=array();
        $promosid=array();
        foreach($data as $d){
            $libros = LibroPromocion::find()->where('libro_promocion.promocion_id='.$d)->all();
            foreach ($libros as $libro) {
                $encuentra = Libro::findOne($libro->libro_id);
                $encuentra->promo = 0;
                if($encuentra->save())
                {
                    $deletedids[]=$libro;
                }
            }
            $model = Promocion::findOne($d);
            if($model->delete())
            {
                $deletedids[]=$d;
            }
 
        }
        print json_encode($deletedids);
    }
}
?>
