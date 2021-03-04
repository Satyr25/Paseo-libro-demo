<?php

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use backend\models\Eventos;
use backend\models\EventosForm;
use backend\models\eventosSearch;

/**
 * EventosController implements the CRUD actions for Eventos model.
 */
class EventosController extends Controller
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','ver','nuevo', 'modificar', 'delete', 'update', 'guardar', 'activar', 'desactivar'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'update' => ['post'],
                    'guardar' => ['post'],
                    'activar' => ['post'],
                    'desactivar' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        if (Yii::$app->user->identity->rol_id !== 1){
            Yii::$app->session->setFlash('error', 'No tiene los permisos para ver esta página');
            return $this->redirect(['libros/index']);
        }
        $searchModel = new eventosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(isset(Yii::$app->request->queryParams) && Yii::$app->request->queryParams['eventosSearch']['nombre']){
            $searchModel->nombre = Yii::$app->request->queryParams['eventosSearch']['nombre'];
        }
        return $this->render('index', [
            'filtro' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Eventos model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionVer($id)
    {
        if (Yii::$app->user->identity->rol_id !== 1){
            Yii::$app->session->setFlash('error', 'No tiene los permisos para ver esta página');
            return $this->redirect(['libros/index']);
        }
        return $this->render('ver', [
            'evento' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Eventos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionNuevo()
    {
        $model = new EventosForm();

        return $this->render('nuevo', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Eventos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionModificar($id)
    {
        if (Yii::$app->user->identity->rol_id !== 1){
            Yii::$app->session->setFlash('error', 'No tiene los permisos para ver esta página');
            return $this->redirect(['libros/index']);
        }
        $model = new EventosForm();
        $evento = Eventos::find()->where(['id' => $id])->one(); 

        if (Yii::$app->request->post()){
            if ($model->load(Yii::$app->request->post())){
                if($model->update($id)){
                    Yii::$app->session->setFlash('success', 'El evento se guardó extosamente');
                    return $this->redirect(['eventos/index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Ocurrio un error al guardar el evento');               
                    return $this->redirect(['eventos/index']);
                }
            }
        }
        
        return $this->render('modificar', [
            'model' => $model,
            'evento' => $evento,
        ]);
    }

    /**
     * Deletes an existing Eventos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Eventos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Eventos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Eventos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionGuardar(){
        $evento = new EventosForm();
        if ($evento->load(Yii::$app->request->post())){
            if($evento->guardar()){
                Yii::$app->session->setFlash('success', 'El evento se guardó extosamente');
                return $this->redirect(['eventos/index']);
            } else {
                Yii::$app->session->setFlash('error', 'Ocurrio un error al guardar el evento');               
                return $this->redirect(['eventos/index']);
            }
        }
    }
    public function actionUpdate(){
        $evento = new EventosForm();
        if ($evento->load(Yii::$app->request->post())){
            if($evento->update()){
                Yii::$app->session->setFlash('success', 'El evento se guardó extosamente');
                return $this->redirect(['eventos/index']);
            } else {
                Yii::$app->session->setFlash('error', 'Ocurrio un error al guardar el evento');               
                return $this->redirect(['eventos/index']);
            }
        }
    }
    public function actionDesactivar()
    {
        $data = json_decode(stripslashes($_POST['data']));
        foreach($data as $d){
            $model = Eventos::findOne($d);
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
            $model = Eventos::findOne($d);
            $model->activo = '1';
            if(!$model->save()){
                return false;
            }
        }
        return true;
    }
    
}
