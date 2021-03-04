<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;

use app\models\UploadForm;

use backend\models\Libro;
use backend\models\LibroForm;
use backend\models\Tema;
use backend\models\Sello;
use backend\models\Coleccion;
use backend\models\Imagenes;
use backend\models\LibrosSearch;
use backend\models\ImportLibroForm;

class LibrosController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','ver','actualizar', 'desactivar', 'activar', 'crear', 'editar', 'guardar', 'import', 'activar-novedad', 'desactivar-novedad', 'activar-recomendacion', 'desactivar-recomendacion'],
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
    	$searchModel = new LibrosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(isset(Yii::$app->request->queryParams) && isset(Yii::$app->request->queryParams['LibrosSearch']['titulo'])){
            $searchModel->titulo = Yii::$app->request->queryParams['LibrosSearch']['titulo'];
        }
        $tema = ArrayHelper::map(Tema::find()->all(),'id','nombre');
        $sello = ArrayHelper::map(Sello::find()->all(),'id','nombre');
        $coleccion = ArrayHelper::map(Tema::find()->all(),'id','nombre');

        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'tema' => $tema,
            'sello' => $sello,
            'filtro' => $searchModel,
            'coleccion' => $coleccion,
        ]);
    }

    public function actionVer(){

        
        if(!Yii::$app->request->get('id')){
            Yii::$app->session->setFlash('success', "No se especifico el id de libro que desea ver.");
        }
        $filtro = new LibrosSearch();
        $libroModel = new Libro();
        $libro = $libroModel->verLibros(Yii::$app->request->get('id'));
        
        if (Yii::$app->request->post()){
            if($libro->load(Yii::$app->request->post())){
                if (!$libro->save()){
                    Yii::$app->session->setFlash('error', 'Error al guardar precio de promoción');
                } else {
                    Yii::$app->session->setFlash('success', 'Se guardó el precio de promoción');
                }
            }
        }
        
        if (Yii::$app->request->get('id')){
            if (!$libro){
                Yii::$app->session->setFlash('success', "El libro que desea ver no pertenece a su editorial");
                return $this->redirect(['libros/index']);
            }
        }
        return $this->render('ver', [
            'filtro' => $filtro,
            'libro' => $libro,
        ]);
    }

    public function actionCrear(){
        
        if(Yii::$app->user->identity->rol_id !== 2){
            Yii::$app->session->setFlash('error', 'Solo el administrador de uan editorial puede dar de alta un libro');
            return $this->redirect(['libros/index']);
        }
        
        $libroForm = new LibroForm();
        $importLibrosForm = new ImportLibroForm();
        $temas = ArrayHelper::map(Tema::find()->all(), 'id', 'nombre');
        $sellos = ArrayHelper::map(Sello::find()->where('editorial_id = 1')->all(), 'id', 'nombre');
        $colecciones = ArrayHelper::map(Coleccion::find()->where('editorial_id = 1')->all(), 'id', 'nombre');
        return $this->render('crear',[
            'libroForm' => $libroForm,
            'importLibrosModel' => $importLibrosForm,
            'temas' => $temas,
            'sellos' => $sellos,
            'colecciones' => $colecciones,
        ]);
    }

    public function actionGuardar(){
        $libro = new LibroForm();
        if ($libro->load(Yii::$app->request->post())){
            if($libro->guardar()){
                Yii::$app->session->setFlash('success', 'El contenido se guardó extosamente');
                return $this->redirect(['libros/index']);
            } else {
                Yii::$app->session->setFlash('error', 'Ocurrio un error al guardar el libro');
                return $this->redirect(['libros/index']);
            }
        }
    }

    public function actionDesactivar()
    {
        $data = json_decode(stripslashes($_POST['data']));
        $deletedids=array();
        foreach($data as $d){
            $model = Libro::findOne($d);
            $model->mostrar = '0';
            if(!$model->save()){
                return false;
            }

        }
        return true;
    }
    public function actionActivar()
    {
        $data = json_decode(stripslashes($_POST['data']));
        $deletedids=array();
        foreach($data as $d){
            $model = Libro::findOne($d);
            $model->mostrar = '1';
            if($model->save())
            {
                $deletedids[]=$d;
            }

        }
        print json_encode($deletedids);
    }

    public function actionDesactivarNovedad()
    {
        $data = json_decode(stripslashes($_POST['data']));
        $deletedids=array();
        foreach($data as $d){
            $model = Libro::findOne($d);
            $model->novedad = '0';
            if(!$model->save()){
                return false;
            }

        }
        return true;
    }
    public function actionActivarNovedad()
    {
        $data = json_decode(stripslashes($_POST['data']));
        $deletedids=array();
        foreach($data as $d){
            $model = Libro::findOne($d);
            $model->novedad = '1';
            if($model->save())
            {
                $deletedids[]=$d;
            }

        }
        print json_encode($deletedids);
    }
    
    public function actionDesactivarRecomendacion()
    {
        $data = json_decode(stripslashes($_POST['data']));
        $deletedids=array();
        foreach($data as $d){
            $model = Libro::findOne($d);
            $model->recomendacion = '0';
            if(!$model->save()){
                return false;
            }

        }
        return true;
    }
    public function actionActivarRecomendacion()
    {
        $data = json_decode(stripslashes($_POST['data']));
        $deletedids=array();
        foreach($data as $d){
            $model = Libro::findOne($d);
            $model->recomendacion = '1';
            if($model->save())
            {
                $deletedids[]=$d;
            }
        }
        print json_encode($deletedids);
    }

    public function actionEditar(){
        if(Yii::$app->user->identity->rol_id !== 2){
            Yii::$app->session->setFlash('error', 'Solo el administrador de una editorial puede editar un libro');
            return $this->redirect(['libros/index']);
        }        
        
        if(!Yii::$app->request->get('id')){
            Yii::$app->session->setFlash('error', 'No se encontro el libro que desea editar');
            return $this->goHome();
        }
        
        $libroModel = new Libro();
        $libro = $libroModel->verLibros(Yii::$app->request->get('id'));
        
        if ($libro->editorial_id !== Yii::$app->user->identity->editorial_id){
            Yii::$app->session->setFlash('error', 'Debes de ser el administrador de la editorial de este libro');
            return $this->redirect(['libros/index']);
        }
        
        $libroForm = new LibroForm();
        $temas = ArrayHelper::map(Tema::find()->all(), 'id', 'nombre');
        $sellos = ArrayHelper::map(Sello::find()->where(['editorial_id' => $libro->editorial_id])->all(), 'id', 'nombre');
        $colecciones = ArrayHelper::map(Coleccion::find()->where(['editorial_id' => $libro->editorial_id])->all(), 'id', 'nombre');

        return $this->render('editar',[
            'libro' => $libro,
            'libroForm' => $libroForm,
            'temas' => $temas,
            'sellos' => $sellos,
            'colecciones' => $colecciones,
        ]);
    }

    public function actionActualizar(){

        $libro = new LibroForm();
        if ($libro->load(Yii::$app->request->post())){
            if($libro->actualizar($libro->id)){
                Yii::$app->session->setFlash('success', 'El contenido se guardó extosamente');
                return $this->redirect(['libros/index']);
            } else {
                Yii::$app->session->setFlash('error', 'Ocurrio un error al guardar el libro');
                return $this->redirect(['libros/index']);
            }
        }
    }

    public function actionImport() {
        ini_set('memory_limit', '-1');
        if (Yii::$app->request->isPost) {
            $model = new ImportLibroForm();
            if ($model->load(Yii::$app->request->post()) && $model->import()) {
                Yii::$app->session->setFlash('success', 'Exito al importar libros.');
            } else {
                switch ($model->errorType) {
                    case 0:
                        Yii::$app->session->setFlash('error', "Error al guardar archivo.");
                        break;
                    case 1:
                        Yii::$app->session->setFlash('error', "Error al guardar libro en el renglon {$model->errorRow}, {$model->errorMensaje}");
                        break;
                    default:
                        Yii::$app->session->setFlash('error', "Error al guardar libro en el renglon {$model->errorRow}.");
                        break;
                }
            }
        }
        return $this->redirect(['libros/crear']);
    }

}
