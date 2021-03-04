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
use frontend\models\SignupForm;
use frontend\models\ClientesForm;
use frontend\models\Clientes;
use frontend\models\Paises;
use frontend\models\PedidoSearch;
use frontend\models\PedidoLibro;
use backend\models\EstadosMundo;
use backend\models\EstadoPedido;
use backend\models\Descuentos;

/**
 * Site controller
 */
class ClienteController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['resignup', 'actualizar'],
                'rules' => [
                    [
                        'actions' => ['resignup', 'actualizar', 'detalle-pedido'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'resignup' => ['post'],
                    'actualizar' => ['post'],
                ],
            ],
        ];
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $cliente = Clientes::find()
            ->where(['usuario_id' => Yii::$app->user->identity->id])
            ->one();
        $resignup = new SignupForm();
        $clienteForm = new ClientesForm();
        $paises = ArrayHelper::map(Paises::find()->all(),'id', 'nombre');
    	$searchModel = new PedidoSearch();
        $dataProvider = $searchModel->pedidoCliente(Yii::$app->request->queryParams);
        return $this->render('index',[
            'resignup' => $resignup,
            'clienteForm' => $clienteForm,
            'paises' => $paises,
            'dataProvider' => $dataProvider,
            'cliente' => $cliente,
        ]);
    }
    
    public function actionDetallePedido()
    {
        $pedido_usuario = PedidoLibro::find()->where(['id' => Yii::$app->request->get('id')])->one();
        
        if (Yii::$app->user->identity->id !== $pedido_usuario->clientes->usuario->id){
            Yii::$app->session->setFlash('error', 'Este pedido no corresponde al usuario');
            return $this->redirect(["cliente/index"]);            
        }
        
        $pedidos = new PedidoLibro();
        $searchModel = new PedidoSearch();
        $accion = $pedidos->verPedidos(Yii::$app->request->get('id'));
        $mes = date('F', $accion->created_at);
        $dia = date('d', $accion->created_at);
        $anio = date('Y', $accion->created_at);
        $hora = date('H:i', $accion->created_at);
        $PedidoId = $accion->id;
        $libros = $searchModel->buscar($PedidoId);
        $clientes = $searchModel->clientes($accion->clientes_id);
        $datosPago = $searchModel->datosPago($accion->datos_pago_id);
        $pagoTienda = $searchModel->pagoTienda($accion->pago_tienda_id);
        $estado_mundo = EstadosMundo::findOne(['id'=> $clientes->estados_mundo_id]);
        $estatus = EstadoPedido::findOne(['id' => $accion->estado_pedido_id]);
        $cupon = $accion->descuento_id;
        $desc = $accion->costo_descuento;
        $cupon_global = Descuentos::findOne(['id'=>$accion->cupon_global_id]);

        if($mes == 'January'){
            $mes = 'Enero';
            $mes_now = 'Enero';
        }
        else  if($mes == 'February'){
            $mes = 'Febrero';
            $mes_now = 'Febrero';
        }
        else  if($mes == 'March'){
            $mes = 'Marzo';
            $mes_now = 'Marzo';
        }
        else  if($mes == 'April'){
            $mes = 'Abril';
            $mes_now = 'Abril';
        }
        else  if($mes == 'May'){
            $mes = 'Mayo';
            $mes_now = 'Mayo';
        }
        else  if($mes == 'June'){
            $mes = 'Junio';
            $mes_now = 'Junio';
        }
        else  if($mes == 'July'){
            $mes = 'Julio';
            $mes_now = 'Julio';
        }
        else  if($mes == 'August'){
            $mes = 'Agosto';
            $mes_now = 'Agosto';
        }
        else  if($mes == 'September'){
            $mes = 'Septiembre';
            $mes_now = 'Septiembre';
        }
        else  if($mes == 'October'){
            $mes = 'Octubre';
            $mes_now = 'Octubre';
        }
        else  if($mes == 'November'){
            $mes = 'Noviembre';
            $mes_now = 'Noviembre';
        }
        else  if($mes == 'December'){
            $mes = 'Diciembre';
            $mes_now = 'Diciembre';
        }

        return $this->render('detalle-pedido', [
            'accion' => $accion,
            'dia' => $dia,
            'mes' => $mes,
            'anio' => $anio,
            'hora' => $hora,
            'libros' => $libros,
            'clientes' => $clientes,
            'datosPago' => $datosPago,
            'pagoTienda' => $pagoTienda,
            'estado_mundo' => $estado_mundo,
            'estatus' => $estatus,
            'cupon' => $cupon,
            'desc' => $desc,
            'cupon_global' => $cupon_global,
            'filtro' => $searchModel,
        ]);
    }
    
    public function actionResignup(){
        $usuarioForm = new SignupForm();
        if ($usuarioForm->load(Yii::$app->request->post())){
            if ($usuarioForm->resignup()){
                Yii::$app->session->setFlash('success', 'Sus datos se han actualizado correctamente');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'No se han podido guardar sus nuevos datos');
                return $this->goHome();
            }
        } else {
            Yii::$app->session->setFlash('error', 'No se han podido cargar sus nuevo datos');
            return $this->goHome();
        }
    }
    public function actionActualizar(){
        $clienteForm = new ClientesForm();
        if ($clienteForm->load(Yii::$app->request->post())){
            if($clienteForm->actualizarCliente()){
                Yii::$app->session->setFlash('success', 'Sus datos se han actualizado correctamente');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'No se han podido guardar sus nuevos datos');
                return $this->goHome();
            }
        } else {
            Yii::$app->session->setFlash('error', 'No se han podido cargar sus nuevo datos');
            return $this->goHome();
        }
    }
}
