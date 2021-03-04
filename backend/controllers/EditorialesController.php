<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use backend\models\EditorialSearch;
use backend\models\Editorial;
use backend\models\EditorialForm;
use app\models\Usuario;
use backend\models\SignupForm;

class EditorialesController extends Controller
{
    
    private $transaction;
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','ver','actualizar', 'desactivar', 'activar', 'crear', 'guardar'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'activar' => ['post'],
                    'desactivar' => ['post'],
                    'guardar' => ['post'],
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
        if (Yii::$app->user->identity->rol_id !== 1){
            Yii::$app->session->setFlash('error', 'No tiene los permisos para ver esta página');
            return $this->redirect(['libros/index']);
        }
        
    	$searchModel = new EditorialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(isset(Yii::$app->request->queryParams) && Yii::$app->request->queryParams['EditorialSearch']['nombre']){
            $searchModel->nombre = Yii::$app->request->queryParams['EditorialSearch']['nombre'];
        }
        return $this->render('index',[
            'filtro' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionVer(){  
        if (Yii::$app->user->identity->rol_id !== 1){
            Yii::$app->session->setFlash('error', 'No tiene los permisos para ver esta página');
            return $this->redirect(['libros/index']);
        }
        
        if(!Yii::$app->request->get('id')){
            Yii::$app->session->setFlash('success', "No se especifico el id de la Editorial que desea ver.");
        }
        $filtro = new EditorialSearch();
        $editorialModel = new Editorial();
        $editorial = $editorialModel->verEditorial(Yii::$app->request->get('id'));
        $usuario = Usuario::find()->where(['editorial_id' => Yii::$app->request->get('id')])->one();
        return $this->render('ver', [
            'filtro' => $filtro,
            'editorial' => $editorial,
            'usuario' => $usuario,
        ]);
    }
    
    public function actionCrear(){
        
        $editorial_form = new EditorialForm();
        $editorial_form->scenario = 'crear';
        
        if (Yii::$app->request->post()){
            if ($editorial_form->load(Yii::$app->request->post())){
                if($editorial_form->guardar()){
                    Yii::$app->session->setFlash('success', 'La editorial se guardó extosamente');
                    return $this->redirect(['editoriales/index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Ocurrio un error al guardar la editorial');               
                    return $this->redirect(['editoriales/index']);
                }
            }
        }
        return $this->render('crear',[
            'editorial_form' => $editorial_form,
        ]);
    }
    
    
    public function actionActualizar()
    {
        if (Yii::$app->user->identity->rol_id !== 1){
            Yii::$app->session->setFlash('error', 'No tiene los permisos para ver esta página');
            return $this->redirect(['libros/index']);
        }
        $editorial = Editorial::find()->where(['id' => Yii::$app->request->get('id')])->one();
        $usuario = Usuario::find()->where(['editorial_id' => Yii::$app->request->get('id')])->one();   
        
        $editorial_form = new EditorialForm();
        
        if (Yii::$app->request->post()){
            if ($editorial_form->load(Yii::$app->request->post())){
                if($editorial_form->actualizar(Yii::$app->request->get('id'))){
                    Yii::$app->session->setFlash('success', 'La editorial se actualizó extosamente');
                    return $this->redirect(['editoriales/index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Ocurrio un error al actualizar la editorial');               
                    return $this->redirect(['editoriales/index']);
                }
            }
        }
        
        return $this->render('actualizar', [
            'editorial' => $editorial,
            'usuario' => $usuario,
            'editorial_form' => $editorial_form,
        ]);
    }
    
    public function actionDesactivar()
    {
        $data = json_decode(stripslashes($_POST['data']));
        foreach($data as $d){
            $model = Editorial::findOne($d);
            $model->activo = '0';
            if(!$model->save()){
                return false;
            }
        }
        return true;
    }
    public function actionActivar()
    {
        $data = json_decode(stripslashes($_POST['data']));
        foreach($data as $d){
            $model = Editorial::findOne($d);
            $model->activo = '1';
            if(!$model->save()){
                return false;
            }
        }
        return true;
    }
}
?>
