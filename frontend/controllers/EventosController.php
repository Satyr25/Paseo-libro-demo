<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use frontend\models\Eventos;
use frontend\models\Categoria;


class EventosController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
//                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $time = new \DateTime('now');
        $today = $time->format('U');  
        
        $proximos_query = Eventos::find()->orderBy('fecha DESC');
        $pasados_query = Eventos::find()->orderBy('fecha DESC');
        
        if(Yii::$app->request->get('categoria')){
            $proximos_query->andWhere(['categoria_id' => Yii::$app->request->get('categoria')]);
            $pasados_query->andWhere(['categoria_id' => Yii::$app->request->get('categoria')]);
        }
        if(Yii::$app->request->get('mes') > 0 && Yii::$app->request->get('mes') < 13){     
            $mes_inicio = '1-'.Yii::$app->request->get('mes').'-'.date("Y");
            $mes_valida = Yii::$app->request->get('mes')+1;
            if ($mes_valida == 13 ){
                $mes_final = '1-1-'.(date("Y")+1);                
            } else {
                $mes_final = '1-'.(Yii::$app->request->get('mes')+1).'-'.date("Y");
            }
            $proximos_query->andWhere(['between', 'fecha', strtotime($mes_inicio), strtotime($mes_final) ]);
            $pasados_query->andWhere(['between', 'fecha', strtotime($mes_inicio), strtotime($mes_final) ]);
        } else {
            $proximos_query->where(['>=', 'fecha', $today]);
            $pasados_query->where(['<', 'fecha', $today]);
        }
        
        $proximos = $proximos_query->all();
        $pasados = $pasados_query->all();
        
        $categorias = ArrayHelper::map(Categoria::find()->all(), 'id', 'nombre');
        $meses = array(
            1 => "Enero",
            2 => "Febrero",
            3 => "Marzo",
            4 => "Abril",
            5 => "Mayo",
            6 => "Junio",
            7 => "Julio",
            8 => "Agosto",
            9 => "Septiembre",
            10 => "Octubre",
            11 => "Noviembre",
            12 => "Diceimbre",
        );
        
        return $this->render('index', [
            'proximos' => $proximos,
            'pasados' => $pasados,
            'categorias' => $categorias,
            'meses' => $meses,
        ]);
    }
    
    public function actionVer(){
        if (Yii::$app->request->queryParams['id']){
            $evento = Eventos::find()
                ->where(['id' => Yii::$app->request->queryParams['id']])
                ->one();
        } else {
            Yii::$app->session->setFlash('error', 'No se encontró el evento solicitado');
            return $this->redirect('index');
        }
        return $this->render('ver', [
            'evento' => $evento
        ]);
    }
}
