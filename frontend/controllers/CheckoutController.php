<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\Cookie;
use app\components\CarritoComponent;
use frontend\fedex\Solicitudes;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactoForm;
use frontend\models\ClientesForm;
use frontend\models\CuponForm;
use frontend\models\Usuario;
use frontend\models\Libro;
use frontend\models\PedidoLibro;
use frontend\models\Descuentos;
use frontend\models\LibroPedido;
use frontend\models\DatosPago;
use frontend\models\PagoTienda;
use frontend\models\EstadoPedido;
use frontend\models\Tema;
use frontend\models\Sello;
use frontend\models\Clientes;
use frontend\models\Coleccion;
use frontend\models\Imagenes;
use frontend\models\LibrosSearch;
use frontend\models\Carrito;
use frontend\models\Paises;
use frontend\models\EstadosMundo;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use app\components\AuthHandler;
use yii\helpers\Json;
use FedEx\RateService\Request;
use FedEx\RateService\ComplexType;
use FedEx\RateService\SimpleType;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use kartik\mpdf\Pdf;


date_default_timezone_set('America/Mexico_City');
//require_once '../fedex/credenciales.php';
//require_once('../fedex/RateService/RateAvailableServices/RateAvailableServicesWebServiceClient.php5');


/**
 * Site controller
 */

class CheckoutController extends Controller
{
    public function beforeAction($action)
{
    $this->enableCsrfValidation = false;
    return parent::beforeAction($action);
}

    public function actions(){
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'oAuthSuccess'],
                'successUrl' => Url::to(['checkout/index'],true)
            ],
        ];
    }

    public function oAuthSuccess($client){
        (new AuthHandler($client))->handle();
    }

    public function actionIndex($direccion_id=false)
    {
        Url::remember();
        if ($this->verificaCarritoVacio()) {
            return $this->goHome();
        }
        if(!Yii::$app->user->isGuest){
            $clientes = Clientes::find()->where(['usuario_id' => Yii::$app->user->id])->one();
        }
        $queryimg = Imagenes::find();
        $images = $queryimg->all();

        $modelCon = new ContactoForm();
        $modelLogin = new LoginForm();
        $modelSign = new SignupForm();
        $cliente = new ClientesForm();
        $searchModel = new LibrosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $paises = ArrayHelper::map(Paises::find()->all(),'id', 'nombre');
        $estados = ArrayHelper::map(EstadosMundo::find()->all(),'id', 'estadonombre');
        $colonia = [];
        $queryt = Tema::find();
        $temas = $queryt->all();
        $libros = \Yii::$app->Carrito->obtieneProductos();
        $carrito = \Yii::$app->Carrito->botonCarrito();
        $cantidad = \Yii::$app->Carrito->cantidadProductos();
//        $editoriales = \Yii::$app->Carrito->editorialesProductos();
        $descuento = New Descuentos();        

        return $this->render('index', [
            'descuento' => $descuento,
            'contactar' => $modelCon,
            'login' => $modelLogin,
            'signup' => $modelSign,
            'dataProvider' => $dataProvider,
            'filtro' => $searchModel,
            'carrito' => $carrito,
            'libros' => $libros,
            'cantidad'=> $cantidad,
            'paises' => $paises,
            'estados' => $estados,
            'cliente' => $cliente,
            'images' => $images,
            'temas' => $temas,
            'colonia' => $colonia,
            'clientes' => $clientes,
        ]);
    }
    public function actionVer(){
        $modelCon = new ContactoForm();
        $modelLogin = new LoginForm();
        $modelSign = new SignupForm();
        $searchModel = new LibrosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    return $this->renderAjax('_mostrar_login', [
        'contactar' => $modelCon,
        'login' => $modelLogin,
        'signup' => $modelSign,
        'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTrack(){
        $guia = Yii::$app->request->post('guia');
        $numero_pedido = Yii::$app->request->post('num_ped');
        $pedido = PedidoLibro::find()->where(['numero_pedido' => $numero_pedido])->one();
        $pedido->tracking = $guia;
        $pedido->save();
    }
    
    public function actionEliminar(){
        return \Yii::$app->Carrito->borrarLibro(Yii::$app->request->post('id'));
    }

    public function actionActualizar(){
        return json_encode(\Yii::$app->Carrito->actualizarCarrito(Yii::$app->request->post('correo')));
    }
    
    public function actionEstado(){
        $datos = Yii::$app->request->post("depdrop_all_params");
        if(!$datos){
            return Json::encode(['output'=>'', 'selected'=>'']);
        }
        if ($datos['pais-id']){
            $estados = EstadosMundo::find()->where('paises_id='.$datos['pais-id'])->all();
        } else {
            $estados = EstadosMundo::find()->where('paises_id='.$datos['cliente-pais-id'])->all();
        }
        $respuesta = [];
        foreach($estados as $estado){
            $respuesta[] = [
                'id' => $estado->id,
                'name' => $estado->estadonombre
            ];
        }
        return Json::encode(['output'=>$respuesta, 'selected'=>'']);
    }

    public function actionDatos(){
    if(!Yii::$app->request->isAjax){
        return Yii::$app->getResponse()->redirect(Yii::$app->homeUrl);
    }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Yii::$app->Carrito->finalizar(
            Yii::$app->request->getBodyParams()
        );
    }

    public function actionPaypal()
    {
        if(!Yii::$app->request->isAjax){
            return Yii::$app->getResponse()->redirect(Yii::$app->homeUrl);
        }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return Yii::$app->Carrito->pagarCarrito(
                Yii::$app->request->getBodyParams()
            );
    }
    public function actionEnviarCorreo(){

        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
        $calle=Yii::$app->request->post('calle');
        $numero=Yii::$app->request->post('numero');
        $colonia=Yii::$app->request->post('colonia');
        $cp=Yii::$app->request->post('cp');
        $edo= EstadosMundo::find()->where(['id' => Yii::$app->request->post('edo')])->one();
        $del=Yii::$app->request->post('del');
        $tel=Yii::$app->request->post('tel');
        $nom=Yii::$app->request->post('nom');
        $correo=Yii::$app->request->post('correo');
        $fecha=Yii::$app->request->post('fecha');
        $time=Yii::$app->request->post('time');
        $num_guia=Yii::$app->request->post('num_guia');
        $radio_envio=Yii::$app->request->post('radio_envio');
        $precio_envio=Yii::$app->request->post('precio_paquete');
        $num_ped = Yii::$app->request->post('num_ped');
        $pedido_libro = PedidoLibro::findOne(['numero_pedido' => Yii::$app->request->post('num_ped')]);
        $clientes = Clientes::findOne(['id' => $pedido_libro->clientes_id]);
        if($clientes->usuario_id){
            $user_pay = Usuario::findOne(['id'=>$clientes->usuario_id]);
        }
        $estatus = EstadoPedido::findOne(['id' => $pedido_libro->estado_pedido_id]);
        $estado_mundo = EstadosMundo::findOne(['id' => $clientes->estados_mundo_id]);
        $cupon = $pedido_libro->descuento_id; 
        $desc = $pedido_libro->costo_descuento;
        $puntos = false;
        if(!$cupon && $desc){
            $puntos = true;
        }
        setlocale(LC_ALL,"es_ES");
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));
        $cupon_global = Descuentos::findOne(['global'=>1]);
        $libros=Yii::$app->request->post('products');   
        $libro = new Libro();
        $tarjeta = true;
        $respuesta=Yii::$app->request->post('datos_respuesta');
        $datos = json_decode($respuesta);
        $orden = $datos->orden;
        $transaccion = $datos->transaccion;
        $numeros = $datos->numeros;
        $marca = $datos->marca;
        $ciudad = Yii::$app->request->post('ciudad');
        $calle2 = Yii::$app->request->post('calle2');

        $is_editorial = false;
        
        $dia_now = date("d");
        $mes_now = date("F");
        $anio_now = date("Y");
        $hora_now = date("H:i");
        if($dia == 'Monday'){
            $dia = 'Lunes';
        }
        else  if($dia == 'Tuesday'){
            $dia = 'Martes';
        }
        else  if($dia == 'Wednesday'){
            $dia = 'Miercoles';
        }
        else  if($dia == 'Thursday'){
            $dia = 'Jueves';
        }
        else  if($dia == 'Friday'){
            $dia = 'Viernes';
        }
        else  if($dia == 'Saturday'){
            $dia = 'Sabado';
        }
        else  if($dia == 'Sunday'){
            $dia = 'Domingo';
        }


        if($mes == 'January' || $mes_now == 'January'){
            $mes = 'Enero';
            $mes_now = 'Enero';
        }
        else  if($mes == 'February' || $mes_now == 'February'){
            $mes = 'Febrero';
            $mes_now = 'Febrero';
        }
        else  if($mes == 'March' || $mes_now == 'March'){
            $mes = 'Marzo';
            $mes_now = 'Marzo';
        }
        else  if($mes == 'April' || $mes_now == 'April'){
            $mes = 'Abril';
            $mes_now = 'Abril';
        }
        else  if($mes == 'May' || $mes_now == 'May'){
            $mes = 'Mayo';
            $mes_now = 'Mayo';
        }
        else  if($mes == 'June' || $mes_now == 'June'){
            $mes = 'Junio';
            $mes_now = 'Junio';
        }
        else  if($mes == 'July' || $mes_now == 'July'){
            $mes = 'Julio';
            $mes_now = 'Julio';
        }
        else  if($mes == 'August' || $mes_now == 'August'){
            $mes = 'Agosto';
            $mes_now = 'Agosto';
        }
        else  if($mes == 'September' || $mes_now == 'September'){
            $mes = 'Septiembre';
            $mes_now = 'Septiembre';
        }
        else  if($mes == 'October' || $mes_now == 'October'){
            $mes = 'Octubre';
            $mes_now = 'Octubre';
        }
        else  if($mes == 'November' || $mes_now == 'November'){
            $mes = 'Noviembre';
            $mes_now = 'Noviembre';
        }
        else  if($mes == 'December' || $mes_now == 'December'){
            $mes = 'Diciembre';
            $mes_now = 'Diciembre';
        }
        $cadena = str_replace('_', ' ', $radio_envio);
        
        if($pedido_libro->confirmacion == 0){
            Yii::$app->mailer->compose()
                ->setTo([Yii::$app->params['adminEmail'], Yii::$app->params['digitalEmail']])
                ->setFrom([Yii::$app->params['adminEmail']=>"UPPL"])
                ->setSubject("Se realizó una nueva compra en Línea")
                ->setHtmlBody(
                    $this->renderPartial('_correo_uppl',[
                        'nom' => $nom,
                        'calle'=>$calle,
                        'calle2'=>$calle2,
                        'numero'=>$numero,
                        'colonia'=>$colonia,
                        'ciudad'=>$ciudad,
                        'cp'=>$cp,
                        'edo'=>$edo,
                        'tel'=>$tel,
                        'correo'=>$correo,
                        'del' => $del,
                        'numeroDia' => $numeroDia,
                        'dia' => $dia,
                        'mes' => $mes,
                        'anio' => $anio,
                        'dia_now' => $dia_now,
                        'mes_now' => $mes_now,
                        'anio_now' => $anio_now,
                        'hora_now' => $hora_now,
                        'num_guia' => $num_guia,
                        'libro' => $libro,
                        'libros' => $libros,
                        'precio_envio' => $precio_envio,
                        'orden' => $orden,
                        'transaccion' => $transaccion,
                        'numeros' => $numeros,
                        'marca' => $marca,
                        'cadena' => $cadena,
                        'tarjeta' => $tarjeta,
                        'cupon' => $cupon,
                        'desc' => $desc,
                        'puntos' => $puntos,
                        'clientes' => $clientes,
                        'estado_mundo' => $estado_mundo,
                        'estatus' => $estatus,
                        'cupon_global' => $cupon_global,
                        'user_pay' => $user_pay,
                        'is_editorial' => $is_editorial,
                    ])
                )
                ->send();
            
            $editoriales=Yii::$app->request->post('editoriales');
            foreach($editoriales as $clave => $total){
                $libros = Yii::$app->request->post('products');
                $libros_editorial = array();
                foreach($libros as $index => $libro_arr){
                    if ($libro_arr[6] == $clave){
                        $libros_editorial[] = $libro_arr;
                    }
                }
                $is_editorial = true;
                
                Yii::$app->mailer->compose()
                    ->setTo([Yii::$app->params[$clave]])
                    ->setFrom([Yii::$app->params['adminEmail']=>"UPPL"])
                    ->setSubject("Se realizó una nueva compra en Línea")
                    ->setHtmlBody(
                        $this->renderPartial('_correo_uppl',[
                            'nom' => $nom,
                            'calle'=>$calle,
                            'calle2'=>$calle2,
                            'numero'=>$numero,
                            'colonia'=>$colonia,
                            'ciudad'=>$ciudad,
                            'cp'=>$cp,
                            'edo'=>$edo,
                            'tel'=>$tel,
                            'correo'=>$correo,
                            'del' => $del,
                            'numeroDia' => $numeroDia,
                            'dia' => $dia,
                            'mes' => $mes,
                            'anio' => $anio,
                            'dia_now' => $dia_now,
                            'mes_now' => $mes_now,
                            'anio_now' => $anio_now,
                            'hora_now' => $hora_now,
                            'num_guia' => $num_guia,
                            'libro' => $libro,
                            'libros' => $libros_editorial,
                            'precio_envio' => $precio_envio,
                            'orden' => $orden,
                            'transaccion' => $transaccion,
                            'numeros' => $numeros,
                            'marca' => $marca,
                            'cadena' => $cadena,
                            'tarjeta' => $tarjeta,
                            'cupon' => $cupon,
                            'desc' => $desc,
                            'puntos' => $puntos,
                            'clientes' => $clientes,
                            'estado_mundo' => $estado_mundo,
                            'estatus' => $estatus,
                            'cupon_global' => $cupon_global,
                            'user_pay' => $user_pay,
//                            'clave' => $clave,
//                            'total' => $total,
                            'is_editorial' => $is_editorial,
                        ])
                    )
                    ->send();
            } 
            
                //para el cliente
            Yii::$app->mailer->compose()
                ->setTo($correo)
                ->setFrom([Yii::$app->params['adminEmail']=>"Un Paseo por los Libros"])
                ->setSubject("Detalles de la compra")
                ->setHtmlBody(
                    $this->renderPartial('_correo_cliente',[
                        'nom' => $nom,
                        'calle'=>$calle,
                        'calle2'=>$calle2,
                        'numero'=>$numero,
                        'colonia'=>$colonia,
                        'cp'=>$cp,
                        'edo'=>$edo,
                        'tel'=>$tel,
                        'ciudad'=>$ciudad,
                        'correo'=>$correo,
                        'del' => $del,
                        'numeroDia' => $numeroDia,
                        'dia' => $dia,
                        'mes' => $mes,
                        'anio' => $anio,
                        'dia_now' => $dia_now,
                        'mes_now' => $mes_now,
                        'anio_now' => $anio_now,
                        'hora_now' => $hora_now,
                        'num_guia' => $num_guia,
                        'libro' => $libro,
                        'libros' => $libros,
                        'precio_envio' => $precio_envio,
                        'orden' => $orden,
                        'transaccion' => $transaccion,
                        'numeros' => $numeros,
                        'marca' => $marca,
                        'cadena' => $cadena,
                        'tarjeta' => $tarjeta,
                        'cupon' => $cupon,
                        'desc' => $desc,
                        'puntos' => $puntos,
                        'clientes' => $clientes,
                        'estado_mundo' => $estado_mundo,
                        'estatus' => $estatus,
                        'cupon_global' => $cupon_global,
                        'user_pay' => $user_pay,
                    ])
                )
                ->send();
            $pedido_libro->confirmacion = 1;
            $pedido_libro->save();
        }
            return $this->redirect(["checkout/confirmacion", 'num_pedido' => $num_ped]);
        }
    public function actionFicha(){
        $respuesta=Yii::$app->request->post('monto');
        $datos = json_decode($respuesta);
        $monto = Yii::$app->request->post('monto');
        $referencia = Yii::$app->request->post('referencia');

        return Yii::$app->mailer->compose()
            ->setTo($correo)
            ->setFrom([Yii::$app->params['adminEmail']=>"Un Paseo por los Libros"])
            ->setSubject("Ficha de Pago OXXO Pay")
            ->setHtmlBody(
            $this->renderPartial('_ficha-oxxo',[
            'monto' => $monto,
            'referencia' => $referencia,
                ])
            )
            ->send();
    }

    public function actionReenvio(){
        $num_ped = Yii::$app->request->post('num_ped');
        return $this->redirect(["checkout/confirmacionoxxo", 'num_pedido' => $num_ped]);
    }

    public function actionTarifas(){
        
        $ini = parse_ini_file('../fedex.ini');
        
        $paises = Paises::find()->where(['id' => Yii::$app->request->post('pais')])->one();
        $ciudad = Yii::$app->request->post('ciudad');
        $cp = Yii::$app->request->post('cp');
        $calle = Yii::$app->request->post('calle');
        $codigo = $paises->codigo;
        $estadocodigo = EstadosMundo::find()->where(['id' => Yii::$app->request->post('estado')])->one();
        $estadocodigo = $estadocodigo->codigo;
        
        $rateRequest = new ComplexType\RateRequest();
        //authentication & client details
        $rateRequest->WebAuthenticationDetail->UserCredential->Key = $ini['FEDEX_KEY'];
        $rateRequest->WebAuthenticationDetail->UserCredential->Password = $ini['FEDEX_PASSWORD'];
        $rateRequest->ClientDetail->AccountNumber = $ini['FEDEX_ACCOUNT_NUMBER'];
        $rateRequest->ClientDetail->MeterNumber = $ini['FEDEX_METER_NUMBER'];

        $rateRequest->TransactionDetail->CustomerTransactionId = '*** Rate Request using PHP ***';

        //version
        $rateRequest->Version->ServiceId = 'crs';
        $rateRequest->Version->Major = 24;
        $rateRequest->Version->Minor = 0;
        $rateRequest->Version->Intermediate = 0;

        $rateRequest->ReturnTransitAndCommit = true;

        //shipper
        $rateRequest->RequestedShipment->PreferredCurrency = 'MXN';
        $rateRequest->RequestedShipment->Shipper->Address->StreetLines = ['Pasaje Zócalo / Pino Suárez del Metro de la Ciudad de México. Col. Centro'];
        $rateRequest->RequestedShipment->Shipper->Address->City = 'Ciudad de Mexico';
        $rateRequest->RequestedShipment->Shipper->Address->StateOrProvinceCode = 'DF';
        $rateRequest->RequestedShipment->Shipper->Address->PostalCode = '06000';
        $rateRequest->RequestedShipment->Shipper->Address->CountryCode = 'MX';

        //recipient
        $rateRequest->RequestedShipment->Recipient->Address->StreetLines = $calle;
        $rateRequest->RequestedShipment->Recipient->Address->City = $ciudad;
        $rateRequest->RequestedShipment->Recipient->Address->StateOrProvinceCode = $estadocodigo;
        $rateRequest->RequestedShipment->Recipient->Address->PostalCode = $cp;
        $rateRequest->RequestedShipment->Recipient->Address->CountryCode = $codigo;
        
        //shipping charges payment
        $rateRequest->RequestedShipment->ShippingChargesPayment->PaymentType = SimpleType\PaymentType::_SENDER;

        //rate request types
        $rateRequest->RequestedShipment->RateRequestTypes = [SimpleType\RateRequestType::_PREFERRED];

        $rateRequest->RequestedShipment->PackageCount = '1';

        //create package line items
        $rateRequest->RequestedShipment->RequestedPackageLineItems = [new ComplexType\RequestedPackageLineItem()];

        //package 1
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->GroupPackageCount = 1;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Weight->Value = 15.0;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Weight->Units = SimpleType\WeightUnits::_KG;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Length = 13.0;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Width = 30.0;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Height = 21.0;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Units = SimpleType\LinearUnits::_CM;

        $rateServiceRequest = new Request();
        $rateServiceRequest->getSoapClient()->__setLocation($ini['FEDEX_RATE_URL']); //use production URL
        
        $rateReply = $rateServiceRequest->getGetRatesReply($rateRequest); // send true as the 2nd argument to return the SoapClient's stdClass response.

        if (!empty($rateReply->RateReplyDetails)) {
            
            foreach ($rateReply->RateReplyDetails as $rateReplyDetail) {
                $tipo_envio = $rateReplyDetail->ServiceType;
                if($tipo_envio == 'STANDARD_OVERNIGHT' || $tipo_envio == 'FEDEX_EXPRESS_SAVER'){
                    $precio = $rateReplyDetail->RatedShipmentDetails[1]->ShipmentRateDetail->TotalNetCharge->Amount;
                    $cadena = str_replace('_', ' ', $tipo_envio);
                    $fecha_normal = $rateReplyDetail->DeliveryTimestamp;
                    $fecha = strtotime($rateReplyDetail->DeliveryTimestamp);
                    $numeroDia = date('d', strtotime($fecha_normal."+ 3 days"));
                    $dia = date('l', strtotime($fecha_normal."+ 3 days"));
                    $mes = date('F', strtotime($fecha_normal."+ 3 days"));
                    $anio = date('Y', strtotime($fecha_normal."+ 3 days"));

                    if($mes == 'January'){
                        $mes = 'Enero';
                    }
                    else  if($mes == 'February'){
                        $mes = 'Febrero';
                    }
                    else  if($mes == 'March'){
                        $mes = 'Marzo';
                    }
                    else  if($mes == 'April'){
                        $mes = 'Abril';
                    }
                    else  if($mes == 'May'){
                        $mes = 'Mayo';
                    }
                    else  if($mes == 'June'){
                        $mes = 'Junio';
                    }
                    else  if($mes == 'July'){
                        $mes = 'Julio';
                    }
                    else  if($mes == 'August'){
                        $mes = 'Agosto';
                    }
                    else  if($mes == 'September'){
                        $mes = 'Septiembre';
                    }
                    else  if($mes == 'October'){
                        $mes = 'Octubre';
                    }
                    else  if($mes == 'November'){
                        $mes = 'Noviembre';
                    }
                    else  if($mes == 'December'){
                        $mes = 'Diciembre';
                    }
                    
                    if($tipo_envio == 'FEDEX_EXPRESS_SAVER'){
                        $fedex_std = [
                            "tipo" => $tipo_envio, 
                            "costo" => $precio, 
                            "fecha" => $fecha, 
                            "fecha_normal" => $fecha_normal, 
                            "dia" => $dia, 
                            "mes" => $mes, 
                            "anio" => $anio
                        ];
                    }else if ($tipo_envio == 'STANDARD_OVERNIGHT'){
                        $fedex_exp = [
                            "tipo" => $tipo_envio, 
                            "costo" => $precio, 
                            "fecha" => $fecha, 
                            "fecha_normal" => $fecha_normal, 
                            "dia" => $dia, 
                            "mes" => $mes, 
                            "anio" => $anio
                        ];
                    }
                } else {
                    return [
                        "exito" => 0,
                        "mensaje" => "Actualmente el servicio de envios tiene inconvenientes"
                    ];
                }
            }
            
            $fedex_array = [
                "exito" => 1,
                "express" => $fedex_exp, 
                "standard" => $fedex_std
            ];                    
            return json_encode($fedex_array);
        }else{
//            $fedex_exp = [
//                "tipo" => 'STANDARD_OVERNIGHT', 
//                "costo" => $precio, 
//                "fecha" => $fecha, 
//                "fecha_normal" => $fecha_normal, 
//                "dia" => $dia, 
//                "mes" => $mes, 
//                "anio" => $anio
//            ];
//            $fedex_std = [
//                "tipo" => $tipo_envio, 
//                "costo" => $precio, 
//                "fecha" => $fecha, 
//                "fecha_normal" => $fecha_normal, 
//                "dia" => $dia, 
//                "mes" => $mes, 
//                "anio" => $anio
//            ];
//            $fedex_array = [
//                "exito" => 1,
//                "express" => $fedex_exp, 
//                "standard" => $fedex_std
//            ];  
//            return $fedex_array;
            
            return [
                "exito" => 0,
                "mensaje" => "Actualmente el servicio de envios tiene inconvenientes"
            ];
        }
    }

    public function actionPostal(){
        $cp = '13040';

        return $this->renderPartial('country',[
            'cp' => $cp,
        ]);
    }

    public function actionShip(){

        $calles = Yii::$app->request->post('calle');
//        $num_ext = Yii::$app->request->post('num_ext');
//        $num_inter = Yii::$app->request->post('num_inter');
//        $deleg = Yii::$app->request->post('deleg');
//        $colonia = Yii::$app->request->post('colonia');

        $concat = $calles;
//        $concat = $calles.' '.$num_ext.' '.$num_inter;
//        $concat2 = $colonia.' '.$deleg;
        $paises = Paises::find()->where(['id' => Yii::$app->request->post('pais')])->one();
        $ciudad = Yii::$app->request->post('ciudad');
        $cp = Yii::$app->request->post('cp');
        $nombre = Yii::$app->request->post('nombre'). ' '.Yii::$app->request->post('apellido');
        //var_dump($nombre);exit;
        $telefono = Yii::$app->request->post('telefono');
        $cp = Yii::$app->request->post('cp');
        $calle_div1 = $concat;
//        $calle_div2 = $concat2;
        $radio = Yii::$app->request->post('radio_envio');
        $codigo = $paises->codigo;
//        var_dump('antes de render partial');exit;
        return $this->renderPartial('ship', [
            'ciudad' => $ciudad,
            'codigo' => $codigo,
            'cp' => $cp,
            'nombre' => $nombre,
            'telefono' => $telefono,
            'calle_div1' => $calle_div1,
//            'calle_div2' => $calle_div2,
            'radio' => $radio,
        ]);


    }

    public function verificaCarritoVacio(){
        if (count(\Yii::$app->Carrito->obtieneProductos()) == 0) {
            return true;
        }
        return false;
    }

    public function actionConfirmacion(){
        $numero_get = Yii::$app->request->get('num_pedido');
        $pedidos_traer = PedidoLibro::find()->where('numero_pedido ="'.$numero_get.'"')->one();
        $clientes = Clientes::find()->where('id ="'.$pedidos_traer->clientes_id.'"')->one();
        if($clientes->usuario_id){
            $user_pay = Usuario::findOne(['id'=>$clientes->usuario_id]);
//            $total_puntos = intval(($pedidos_traer->costo_total - $pedidos_traer->costo_envio) * 0.05);
        }
        $datos_pago = DatosPago::find()->where('id ="'.$pedidos_traer->datos_pago_id.'"')->one();
        $libros_pedido = LibroPedido::find()->where('pedido_libro_id ="'.$pedidos_traer->id.'"')->all();
        $libros = [];
        foreach ($libros_pedido as $libro_pedido) {
            array_push($libros, $libro_pedido->libro);
        }
        $calle= $clientes->calle;
        $ciudad=$clientes->ciudad;
        $calle2=$clientes->num_int;
        $numero=$clientes->num_ext;
        $colonia=$clientes->colonia;
        $cp=$clientes->cp;
        $edo= EstadosMundo::find()->where(['id' => $clientes->estados_mundo_id])->one();
        $del=$clientes->delegacion;
        $tel=$clientes->telefono;
        $nom=$clientes->nombre;
        $correo=$clientes->email;
        $num_guia=$pedidos_traer->tracking;
        //var_dump($num_guia);exit;
        setlocale(LC_ALL,"es_ES");
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));
        $costo_total = $pedidos_traer->costo_total;
        $costo_envio = $pedidos_traer->costo_envio;
//        var_dump($costo_total);
//        var_dump($costo_envio);exit();
        $libro = new Libro();
        $respuesta=Yii::$app->request->post('datos_respuesta');
        $datos = json_decode($respuesta);
        $orden = $numero_get;
        $transaccion = $datos_pago->orden_id;
        $numeros = $datos->numeros;
        $marca = $datos->marca;
        $cupon_global = Descuentos::findOne(['global'=>1]);
        $dia_now = date("d");
        $mes_now = date("F");
        $anio_now = date("Y");
        $hora_now = date("H:i");
        //var_dump(date("d").' de '.date("F").' del '.date('Y').' a las '.date("h:i"));exit;
        if($dia == 'Monday'){
            $dia = 'Lunes';
        }
        else  if($dia == 'Tuesday'){
            $dia = 'Martes';
        }
        else  if($dia == 'Wednesday'){
            $dia = 'Miercoles';
        }
        else  if($dia == 'Thursday'){
            $dia = 'Jueves';
        }
        else  if($dia == 'Friday'){
            $dia = 'Viernes';
        }
        else  if($dia == 'Saturday'){
            $dia = 'Sabado';
        }
        else  if($dia == 'Sunday'){
            $dia = 'Domingo';
        }


        if($mes == 'January' || $mes_now == 'January'){
            $mes = 'Enero';
            $mes_now = 'Enero';
        }
        else  if($mes == 'February' || $mes_now == 'February'){
            $mes = 'Febrero';
            $mes_now = 'Febrero';
        }
        else  if($mes == 'March' || $mes_now == 'March'){
            $mes = 'Marzo';
            $mes_now = 'Marzo';
        }
        else  if($mes == 'April' || $mes_now == 'April'){
            $mes = 'Abril';
            $mes_now = 'Abril';
        }
        else  if($mes == 'May' || $mes_now == 'May'){
            $mes = 'Mayo';
            $mes_now = 'Mayo';
        }
        else  if($mes == 'June' || $mes_now == 'June'){
            $mes = 'Junio';
            $mes_now = 'Junio';
        }
        else  if($mes == 'July' || $mes_now == 'July'){
            $mes = 'Julio';
            $mes_now = 'Julio';
        }
        else  if($mes == 'August' || $mes_now == 'August'){
            $mes = 'Agosto';
            $mes_now = 'Agosto';
        }
        else  if($mes == 'September' || $mes_now == 'September'){
            $mes = 'Septiembre';
            $mes_now = 'Septiembre';
        }
        else  if($mes == 'October' || $mes_now == 'October'){
            $mes = 'Octubre';
            $mes_now = 'Octubre';
        }
        else  if($mes == 'November' || $mes_now == 'November'){
            $mes = 'Noviembre';
            $mes_now = 'Noviembre';
        }
        else  if($mes == 'December' || $mes_now == 'December'){
            $mes = 'Diciembre';
            $mes_now = 'Diciembre';
        }
        $radio = $pedidos_traer->nombre_envio;
        $cadena = str_replace('_', ' ', $radio);
        $carrito = $pedidos_traer->id;
        $cupon = $pedidos_traer->descuento_id;
        $desc = $pedidos_traer->costo_descuento;
        $puntos = false;
        if(!$cupon && $desc){
            $puntos = true;
        }
        return $this->render('confirmacion', [
            'nom' => $nom,
            'calle'=>$calle,
            'ciudad'=>$ciudad,
            'calle2'=>$calle2,
            'numero'=>$numero,
            'colonia'=>$colonia,
            'cp'=>$cp,
            'edo'=>$edo,
            'tel'=>$tel,
            'correo'=>$correo,
            'del' => $del,
            'numeroDia' => $numeroDia,
            'dia' => $dia,
            'mes' => $mes,
            'anio' => $anio,
            'dia_now' => $dia_now,
            'mes_now' => $mes_now,
            'anio_now' => $anio_now,
            'hora_now' => $hora_now,
            'num_guia' => $num_guia,
            'libro' => $libro,
            'libros' => $libros,
            'orden' => $orden,
            'transaccion' => $transaccion,
            'numeros' => $numeros,
            'marca' => $marca,
            'cadena' => $cadena,
            'libros_pedido' =>$libros_pedido,
            'costo_total' => $costo_total,
            'costo_envio' => $costo_envio,
            'carrito' => $carrito,
            'cupon' => $cupon,
            'desc' => $desc,
            'puntos' => $puntos,
            'cupon_global' => $cupon_global,
            'user_pay' => $user_pay,
        ]);
    }

    public function actionConfirmacionoxxo(){
        $numero_get = Yii::$app->request->get('num_pedido');

        $pedidos_traer = PedidoLibro::find()->where('numero_pedido ="'.$numero_get.'"')->one();
        $estatus = EstadoPedido::findOne(['id' => $pedidos_traer->estado_pedido_id]);
        $clientes = Clientes::find()->where('id ="'.$pedidos_traer->clientes_id.'"')->one();
        if($clientes->usuario_id){
            $user_pay = Usuario::findOne(['id'=>$clientes->usuario_id]);
        }
        $pago_tienda = PagoTienda::find()->where('id ="'.$pedidos_traer->pago_tienda_id.'"')->one();
        $libros_pedido = LibroPedido::find()->where('pedido_libro_id ="'.$pedidos_traer->id.'"')->all();
        $libros = [];
        foreach ($libros_pedido as $libro_pedido) {
            array_push($libros, $libro_pedido->libro);
        }
        $costo_total = $pedidos_traer->costo_total;
        $costo_envio = $pedidos_traer->costo_envio;
        $calle= $clientes->calle;
        $ciudad=$clientes->ciudad;
        $calle2=$clientes->num_int;
        $numero=$clientes->num_ext;
        $colonia=$clientes->colonia;
        $cp=$clientes->cp;
        $edo= EstadosMundo::find()->where(['id' => $clientes->estados_mundo_id])->one();
        $del=$clientes->delegacion;
        $tel=$clientes->telefono;
        $nom=$clientes->nombre;
        $correo=$clientes->email;

        $num_guia=Yii::$app->request->post('num_guia');

        $precio_envio = Yii::$app->request->post('precio_paquete');
        setlocale(LC_ALL,"es_ES");
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));

        $libro = new Libro();

        $dia_now = date("d");
        $mes_now = date("F");
        $anio_now = date("Y");
        $hora_now = date("H:i");
        if($dia == 'Monday'){
            $dia = 'Lunes';
        }
        else  if($dia == 'Tuesday'){
            $dia = 'Martes';
        }
        else  if($dia == 'Wednesday'){
            $dia = 'Miercoles';
        }
        else  if($dia == 'Thursday'){
            $dia = 'Jueves';
        }
        else  if($dia == 'Friday'){
            $dia = 'Viernes';
        }
        else  if($dia == 'Saturday'){
            $dia = 'Sabado';
        }
        else  if($dia == 'Sunday'){
            $dia = 'Domingo';
        }


        if($mes == 'January' || $mes_now == 'January'){
            $mes = 'Enero';
            $mes_now = 'Enero';
        }
        else  if($mes == 'February' || $mes_now == 'February'){
            $mes = 'Febrero';
            $mes_now = 'Febrero';
        }
        else  if($mes == 'March' || $mes_now == 'March'){
            $mes = 'Marzo';
            $mes_now = 'Marzo';
        }
        else  if($mes == 'April' || $mes_now == 'April'){
            $mes = 'Abril';
            $mes_now = 'Abril';
        }
        else  if($mes == 'May' || $mes_now == 'May'){
            $mes = 'Mayo';
            $mes_now = 'Mayo';
        }
        else  if($mes == 'June' || $mes_now == 'June'){
            $mes = 'Junio';
            $mes_now = 'Junio';
        }
        else  if($mes == 'July' || $mes_now == 'July'){
            $mes = 'Julio';
            $mes_now = 'Julio';
        }
        else  if($mes == 'August' || $mes_now == 'August'){
            $mes = 'Agosto';
            $mes_now = 'Agosto';
        }
        else  if($mes == 'September' || $mes_now == 'September'){
            $mes = 'Septiembre';
            $mes_now = 'Septiembre';
        }
        else  if($mes == 'October' || $mes_now == 'October'){
            $mes = 'Octubre';
            $mes_now = 'Octubre';
        }
        else  if($mes == 'November' || $mes_now == 'November'){
            $mes = 'Noviembre';
            $mes_now = 'Noviembre';
        }
        else  if($mes == 'December' || $mes_now == 'December'){
            $mes = 'Diciembre';
            $mes_now = 'Diciembre';
        }
        $radio = $pedidos_traer->nombre_envio;
        $cadena = str_replace('_', ' ', $radio);
        $monto = $pago_tienda->monto;
        $referencia = $pago_tienda->referencia;
        $carrito = $pedidos_traer->id;
        $cupon = $pedidos_traer->descuento_id;
        $desc = $pedidos_traer->costo_descuento;
        $orden = $pago_tienda->orden_id;
        $pedido_oxxo = true;
        $cupon_global = Descuentos::findOne(['id'=>$pedidos_traer->cupon_global_id]);
        if($pedidos_traer->confirmacion == 0){ 
            Yii::$app->mailer->compose()
                ->setTo([Yii::$app->params['adminEmail'], Yii::$app->params['digitalEmail']])
                ->setFrom([Yii::$app->params['adminEmail']=>"Un Paseo por los Libros"])
                ->setSubject("Se realizó un nuevo pedido en Línea por Oxxo")
                ->setHtmlBody(
                $this->renderPartial('_correo_uppl',[
                    'nom' => $nom,
                    'calle'=>$calle,
                    'calle2'=>$calle2,
                    'numero'=>$numero,
                    'ciudad'=>$ciudad,
                    'colonia'=>$colonia,
                    'cp'=>$cp,
                    'edo'=>$edo,
                    'tel'=>$tel,
                    'correo'=>$correo,
                    'del' => $del,
                    'numeroDia' => $numeroDia,
                    'dia' => $dia,
                    'mes' => $mes,
                    'anio' => $anio,
                    'dia_now' => $dia_now,
                    'mes_now' => $mes_now,
                    'anio_now' => $anio_now,
                    'hora_now' => $hora_now,
                    'num_guia' => $num_guia,
                    'libro' => $libro,
                    'libros' => $libros,
                    'precio_envio' => $costo_envio,
                    'cadena' => $cadena,
                    'monto' => $monto,
                    'referencia' => $referencia,
                    'subtotal'=> $costo_total,
                    'costo_envio'=> $costo_envio,
                    'libros_pedido' => $libros_pedido,
                    'carrito' => $carrito,
                    'cupon' => $cupon,
                    'desc' => $desc,
                    'pedido_oxxo' => $pedido_oxxo,
                    'orden' => $orden,
                    'clientes' => $clientes,
                    'estado_mundo' => $edo,
                    'estatus' => $estatus,
                    'cupon_global' => $cupon_global,
                    'user_pay' => $user_pay,
//                    'total_puntos' => $total_puntos,
                ])
            )
            ->send();

            Yii::$app->mailer->compose()
                ->setTo($correo)
                ->setFrom([Yii::$app->params['adminEmail']=>"Un Paseo por los Libros"])
                ->setSubject("Detalles del Pedido")
                ->setHtmlBody(
                    $this->renderPartial('_correo_cliente',[
                        'nom' => $nom,
                        'calle'=>$calle,
                        'calle2'=>$calle2,
                        'numero'=>$numero,
                        'ciudad'=>$ciudad,
                        'colonia'=>$colonia,
                        'cp'=>$cp,
                        'edo'=>$edo,
                        'tel'=>$tel,
                        'correo'=>$correo,
                        'del' => $del,
                        'numeroDia' => $numeroDia,
                        'dia' => $dia,
                        'mes' => $mes,
                        'anio' => $anio,
                        'dia_now' => $dia_now,
                        'mes_now' => $mes_now,
                        'anio_now' => $anio_now,
                        'hora_now' => $hora_now,
                        'num_guia' => $num_guia,
                        'libro' => $libro,
                        'libros' => $libros,
                        'precio_envio' => $costo_envio,
                        'cadena' => $cadena,
                        'monto' => $monto,
                        'referencia' => $referencia,
                        'subtotal'=> $costo_total,
                        'libros_pedido' => $libros_pedido,
                        'carrito' => $carrito,
                        'cupon' => $cupon,
                        'desc' => $desc,
                        'pedido_oxxo' => $pedido_oxxo,
                        'orden' => $orden,
                        'imgportadas' => $imgportadas,
                        'clientes' => $clientes,
                        'estado_mundo' => $edo,
                        'estatus' => $estatus,
                        'cupon_global' => $cupon_global,
                        'user_pay' => $user_pay,
//                        'total_puntos' => $total_puntos,
                    ])
                )
            ->send();


            $pedidos_compro = PedidoLibro::findOne(['numero_pedido'=>$numero_get]);
            $pedidos_compro->confirmacion = 1;
            $pedidos_compro->save();
        }
        $puntos = false;
        if(!$cupon && $desc){
            $puntos = true;
        }
        return $this->render('confirmacion_oxxo', [
            'nom' => $nom,
            'ciudad'=>$ciudad,
            'calle'=>$calle,
            'calle2'=>$calle2,
            'numero'=>$numero,
            'colonia'=>$colonia,
            'cp'=>$cp,
            'edo'=>$edo,
            'tel'=>$tel,
            'correo'=>$correo,
            'del' => $del,
            'numeroDia' => $numeroDia,
            'dia' => $dia,
            'mes' => $mes,
            'anio' => $anio,
            'dia_now' => $dia_now,
            'mes_now' => $mes_now,
            'anio_now' => $anio_now,
            'hora_now' => $hora_now,
            'num_guia' => $num_guia,
            'libro' => $libro,
            'libros' => $libros,
            'precio_envio' => $precio_envio,
            'cadena' => $cadena,
            'monto' => $monto,
            'referencia' => $referencia,
            'costo_total'=> $costo_total,
            'costo_envio'=> $costo_envio,
            'libros_pedido' => $libros_pedido,
            'carrito' => $carrito,
            'cupon' => $cupon,
            'desc' => $desc,
            'puntos' => $puntos,
            'cupon_global' => $cupon_global,
            'user_pay' => $user_pay,
        ]);

    }
    public function actionPrueba(){
        $numero_get = Yii::$app->request->get('num_pedido');
        $pedidos_traer = PedidoLibro::find()->where('numero_pedido ="'.$numero_get.'"')->one();
        $estatus = EstadoPedido::findOne(['id' => $pedidos_traer->estado_pedido_id]);
        $clientes = Clientes::find()->where('id ="'.$pedidos_traer->clientes_id.'"')->one();
        if($clientes->usuario_id){
            $user_pay = Usuario::findOne(['id'=>$clientes->usuario_id]);
        }
        $pago_tienda = PagoTienda::find()->where('id ="'.$pedidos_traer->pago_tienda_id.'"')->one();
        $libros_pedido = LibroPedido::find()->where('pedido_libro_id ="'.$pedidos_traer->id.'"')->all();
        $libros = [];
        foreach ($libros_pedido as $libro_pedido) {
            array_push($libros, $libro_pedido->libro);
        }
        $costo_total = $pedidos_traer->costo_total;
        $costo_envio = $pedidos_traer->costo_envio;
        $calle= $clientes->calle;
        $ciudad=$clientes->ciudad;
        $calle2=$clientes->num_int;
        $numero=$clientes->num_ext;
        $colonia=$clientes->colonia;
        $cp=$clientes->cp;
        $edo= EstadosMundo::find()->where(['id' => $clientes->estados_mundo_id])->one();
        $del=$clientes->delegacion;
        $tel=$clientes->telefono;
        $nom=$clientes->nombre;
        $correo=$clientes->email;

        $num_guia=Yii::$app->request->post('num_guia');

        $precio_envio = Yii::$app->request->post('precio_paquete');
        setlocale(LC_ALL,"es_ES");
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));

        $libro = new Libro();

        $dia_now = date("d");
        $mes_now = date("F");
        $anio_now = date("Y");
        $hora_now = date("H:i");
        if($dia == 'Monday'){
            $dia = 'Lunes';
        }
        else  if($dia == 'Tuesday'){
            $dia = 'Martes';
        }
        else  if($dia == 'Wednesday'){
            $dia = 'Miercoles';
        }
        else  if($dia == 'Thursday'){
            $dia = 'Jueves';
        }
        else  if($dia == 'Friday'){
            $dia = 'Viernes';
        }
        else  if($dia == 'Saturday'){
            $dia = 'Sabado';
        }
        else  if($dia == 'Sunday'){
            $dia = 'Domingo';
        }


        if($mes == 'January' || $mes_now == 'January'){
            $mes = 'Enero';
            $mes_now = 'Enero';
        }
        else  if($mes == 'February' || $mes_now == 'February'){
            $mes = 'Febrero';
            $mes_now = 'Febrero';
        }
        else  if($mes == 'March' || $mes_now == 'March'){
            $mes = 'Marzo';
            $mes_now = 'Marzo';
        }
        else  if($mes == 'April' || $mes_now == 'April'){
            $mes = 'Abril';
            $mes_now = 'Abril';
        }
        else  if($mes == 'May' || $mes_now == 'May'){
            $mes = 'Mayo';
            $mes_now = 'Mayo';
        }
        else  if($mes == 'June' || $mes_now == 'June'){
            $mes = 'Junio';
            $mes_now = 'Junio';
        }
        else  if($mes == 'July' || $mes_now == 'July'){
            $mes = 'Julio';
            $mes_now = 'Julio';
        }
        else  if($mes == 'August' || $mes_now == 'August'){
            $mes = 'Agosto';
            $mes_now = 'Agosto';
        }
        else  if($mes == 'September' || $mes_now == 'September'){
            $mes = 'Septiembre';
            $mes_now = 'Septiembre';
        }
        else  if($mes == 'October' || $mes_now == 'October'){
            $mes = 'Octubre';
            $mes_now = 'Octubre';
        }
        else  if($mes == 'November' || $mes_now == 'November'){
            $mes = 'Noviembre';
            $mes_now = 'Noviembre';
        }
        else  if($mes == 'December' || $mes_now == 'December'){
            $mes = 'Diciembre';
            $mes_now = 'Diciembre';
        }
        $radio = $pedidos_traer->nombre_envio;
        $cadena = str_replace('_', ' ', $radio);
        $monto = $pago_tienda->monto;
        $referencia = $pago_tienda->referencia;
        $carrito = $pedidos_traer->id;
        $cupon = $pedidos_traer->descuento_id;
        $desc = $pedidos_traer->costo_descuento;
        $orden = $pago_tienda->orden_id;
        $pedido_oxxo = true;
        $cupon_global = Descuentos::findOne(['id'=>$pedidos_traer->cupon_global_id]);

                  return  $this->renderPartial('prueba',[
                        'nom' => $nom,
                        'calle'=>$calle,
                        'calle2'=>$calle2,
                        'numero'=>$numero,
                        'ciudad'=>$ciudad,
                        'colonia'=>$colonia,
                        'cp'=>$cp,
                        'edo'=>$edo,
                        'tel'=>$tel,
                        'correo'=>$correo,
                        'del' => $del,
                        'numeroDia' => $numeroDia,
                        'dia' => $dia,
                        'mes' => $mes,
                        'anio' => $anio,
                        'dia_now' => $dia_now,
                        'mes_now' => $mes_now,
                        'anio_now' => $anio_now,
                        'hora_now' => $hora_now,
                        'num_guia' => $num_guia,
                        'libro' => $libro,
                        'libros' => $libros,
                        'precio_envio' => $costo_envio,
                        'cadena' => $cadena,
                        'monto' => $monto,
                        'referencia' => $referencia,
                        'subtotal'=> $costo_total,
                        'libros_pedido' => $libros_pedido,
                        'carrito' => $carrito,
                        'cupon' => $cupon,
                        'desc' => $desc,
                        'pedido_oxxo' => $pedido_oxxo,
                        'orden' => $orden,
                        'imgportadas' => $imgportadas,
                        'clientes' => $clientes,
                        'estado_mundo' => $edo,
                        'estatus' => $estatus,
                        'cupon_global' => $cupon_global,
                        'user_pay' => $user_pay,
                    ]);
    }
    public function actionConfirmacionpaypal(){
        $datos = parse_ini_file('../paypal.ini');
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $datos['client_id'],     // ClientID
                $datos['secret']      // ClientSecret
            )
        );
        // remove on live sandbox is for testing only
        $apiContext->setConfig(
            array(
                'mode' => $datos['mode'],
            )
        );
        // Get payment object by passing paymentId
        $paymentId = $_GET['paymentId'];
        $payerId = $_GET['PayerID'];
        $tokenId = $_GET['token'];
        $payments = Payment::get($paymentId, $apiContext);
        $payments->getTransactions();
        $obj = $payments->toJSON();//I wanted to look into the object
        $paypal_obj = json_decode($obj, 1);//I wanted to look into the object

        $datos = PedidoLibro::find()
            ->select('clientes.*, pedido_libro.id')
            ->join('INNER JOIN', 'pago_tienda', 'pago_tienda.orden_id ="'.$tokenId.'"')
            ->join('INNER JOIN', 'clientes', 'clientes.id = pedido_libro.clientes_id')
            ->where('pedido_libro.id = pago_tienda.pedido_libro_id')->one();
        $libros_pedido = LibroPedido::find()->where('pedido_libro_id ="'.$datos->id.'"')->all();
        $libros = [];
        foreach ($libros_pedido as $libro_pedido) {
            array_push($libros, $libro_pedido->libro);
        }
        $correo = $datos->email;
        $calle = $datos->calle;
        $colonia = $datos->colonia;
        $cp = $datos->cp;
        $estado = EstadosMundo::find()->where('estados_mundo.id ="'.$datos->estados_mundo_id.'"')->one();
        $estado_mundo = EstadosMundo::find()->where('estados_mundo.id ="'.$datos->estados_mundo_id.'"')->one();
        $estado = $estado->codigo;
        $country_code = 'MX';
        $nombre = $datos->nombre;
        $apellidos = $datos->apellidos;
        $nombre = $nombre.' '.$apellidos;
        //$libros = $payments->transactions[0]->item_list->items;
        $phone = $datos->telefono;
        $numero = $datos->num_ext;
        $calle2 = $datos->num_int;
        $ciudad = $datos->ciudad;
        $del = $datos->delegacion;

        foreach ($libros as $key => $value) {
            $imgportadas[] = Imagenes::getImgPortada($value->titulo);
        }

        $libro = new Libro();
        $datos_de_pedido = PedidoLibro::find()
            ->select('pedido_libro.*')
            ->join('INNER JOIN', 'pago_tienda', 'pago_tienda.orden_id ="'.$tokenId.'"')
            ->where('pedido_libro.id = pago_tienda.pedido_libro_id')->one();
        $cupon = $datos_de_pedido->descuento_id;
        $desc = $datos_de_pedido->costo_descuento;

        $puntos = false;
        if(!$cupon && $desc){
            $puntos = true;
        }

        $transaccion = $tokenId;
        $dia_now = date("d");
        $mes_now = date("F");
        $anio_now = date("Y");
        $hora_now = date("H:i");
        if($dia == 'Monday'){
            $dia = 'Lunes';
        }
        else  if($dia == 'Tuesday'){
            $dia = 'Martes';
        }
        else  if($dia == 'Wednesday'){
            $dia = 'Miercoles';
        }
        else  if($dia == 'Thursday'){
            $dia = 'Jueves';
        }
        else  if($dia == 'Friday'){
            $dia = 'Viernes';
        }
        else  if($dia == 'Saturday'){
            $dia = 'Sabado';
        }
        else  if($dia == 'Sunday'){
            $dia = 'Domingo';
        }


          if($mes == 'January' || $mes_now == 'January'){
            $mes = 'Enero';
            $mes_now = 'Enero';
        }
        else  if($mes == 'February' || $mes_now == 'February'){
            $mes = 'Febrero';
            $mes_now = 'Febrero';
        }
        else  if($mes == 'March' || $mes_now == 'March'){
            $mes = 'Marzo';
            $mes_now = 'Marzo';
        }
        else  if($mes == 'April' || $mes_now == 'April'){
            $mes = 'Abril';
            $mes_now = 'Abril';
        }
        else  if($mes == 'May' || $mes_now == 'May'){
            $mes = 'Mayo';
            $mes_now = 'Mayo';
        }
        else  if($mes == 'June' || $mes_now == 'June'){
            $mes = 'Junio';
            $mes_now = 'Junio';
        }
        else  if($mes == 'July' || $mes_now == 'July'){
            $mes = 'Julio';
            $mes_now = 'Julio';
        }
        else  if($mes == 'August' || $mes_now == 'August'){
            $mes = 'Agosto';
            $mes_now = 'Agosto';
        }
        else  if($mes == 'September' || $mes_now == 'September'){
            $mes = 'Septiembre';
            $mes_now = 'Septiembre';
        }
        else  if($mes == 'October' || $mes_now == 'October'){
            $mes = 'Octubre';
            $mes_now = 'Octubre';
        }
        else  if($mes == 'November' || $mes_now == 'November'){
            $mes = 'Noviembre';
            $mes_now = 'Noviembre';
        }
        else  if($mes == 'December' || $mes_now == 'December'){
            $mes = 'Diciembre';
            $mes_now = 'Diciembre';
        }
        /********************Aqui van las variables*******************/

    // Execute payment with payer id
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
        try {
            // Execute payment
            $result = $payments->execute($execution, $apiContext);
            if($result->state == "approved"){
            $pedido = PedidoLibro::find()
            ->join('INNER JOIN', 'pago_tienda', 'pago_tienda.orden_id ="'.$tokenId.'"')
            ->where('pedido_libro.id = pago_tienda.pedido_libro_id')->one();
            $pedido->estado_pedido_id = 1;
            $pedido->save();
            $estatus = EstadoPedido::findOne(['id' => $pedido->estado_pedido_id]);
            $orden = $pedido->numero_pedido;
            $cadena = $pedido->nombre_envio;
            $precio_envio = $payments->transactions[0]->amount->details->shipping;
            $subtotal = $payments->transactions[0]->amount->details->subtotal;

            $usuario_id = Yii::$app->user->identity->id;
            $check_cupon = $pedido->descuento_id;
            $check_descuento_puntos = $pedido->costo_descuento;

            if(!$check_cupon && $check_descuento_puntos && $pedido->confirmacion == 0){
                $user = Usuario::findOne(['id'=> $usuario_id ]); 
                $rest = intval($check_descuento_puntos);
                $user->puntos = $user->puntos - $rest;
                $user->save();
            }

            if($usuario_id && $pedido->confirmacion == 0){
                $user = Usuario::findOne(['id'=> $usuario_id ]); 
                $sume = intval($subtotal * 0.05);
//                $user->puntos = $sume + $user->puntos;
//                $user->total_puntos = $sume + $user->total_puntos;
                $user->save();
            }

            $clientes = Clientes::findOne(['id' => $pedido->clientes_id]);
            if($clientes->usuario_id){
                $user_pay = Usuario::findOne(['id'=>$clientes->usuario_id]);
//                $total_puntos = intval(($pedido->costo_total - $pedido->costo_envio) * 0.05);
            }
            $fecha = $this->renderPartial('rate',[
                'calle' => $calle,
                'ciudad' => $ciudad,
                'cp' => $cp,
                'codigo' => $country_code,
                'estadocodigo' => $estado,
            ]);
            $calle_concat = $calle.' '.$numero.' '.$calle2;
            $calle_concat2 = $colonia.' '.$del;
            $num_guia = $this->renderPartial('ship', [
                'ciudad' => $ciudad,
                'codigo' => $country_code,
                'cp' => $cp,
                'nombre' => $nombre,
                'telefono' => $phone,
                'calle_div1' => $calle_concat,
                'calle_div2' => $calle_concat2,
                'radio' => $cadena,
                   ]);
            $track = PedidoLibro::find()->where(['numero_pedido' => $orden])->one();
            $track->tracking = $num_guia;
            $track->save();

            $posicion = strpos($fecha, $cadena);

            $dividir = substr($fecha, $posicion, 90);

            $explotar = explode('/', $dividir);

            $explotar2 = substr($explotar[3], 0,10);

            $fecha = explode('-', $explotar2);
            $numeroDia = $fecha[2];
            $mes = $fecha[1];
            $anio = $fecha[0];
            $carrito = $datos->id;
            $pay_pal = true;
            $cupon_global = Descuentos::findOne(['global'=>1]);

            $confirma = $this->render('confirmacion_paypal', [
                'nom' => $nombre,
                'calle'=>$calle,
                'calle2'=>$calle2,
                'numero'=>$numero,
                'colonia'=>$colonia,
                'ciudad' => $ciudad,
                'cp'=>$cp,
                'edo'=>$estado,
                'tel'=>$phone,
                'correo'=>$correo,
                'del' => $del,
                'numeroDia' => $numeroDia,
                'dia' => $dia,
                'mes' => $mes,
                'anio' => $anio,
                'dia_now' => $dia_now,
                'mes_now' => $mes_now,
                'anio_now' => $anio_now,
                'hora_now' => $hora_now,
                'num_guia' => $num_guia,
                'libros' => $libros,
                'precio_envio' => $precio_envio,
                'orden' => $orden,
                'transaccion' => $transaccion,
                'numeros' => $numeros,
                'marca' => $marca,
                'cadena' => $cadena,
                'subtotal' => $subtotal,
                'imgportadas' => $imgportadas,
                'libros_pedido' => $libros_pedido,
                'libro' => $libro,
                'carrito' => $carrito,
                'cupon' => $cupon,
                'desc' => $desc,
                'puntos' => $puntos,
                'cupon_global' => $cupon_global,
                'user_pay' => $user_pay,
//                'total_puntos' => $total_puntos,
            ]);
            if($pedido->confirmacion == 0){
                Yii::$app->mailer->compose()
                    ->setTo([Yii::$app->params['adminEmail'], Yii::$app->params['digitalEmail']])
                    ->setFrom([$correo=>"Un Paseo por los Libros"])
                    ->setSubject("Se realizó una nueva compra en Línea")
                    ->setHtmlBody(
                    $this->renderPartial('_correo_uppl',[
                        'nom' => $nombre,
                        'calle'=>$calle,
                        'calle2'=>$calle2,
                        'numero'=>$numero,
                        'colonia'=>$colonia,
                        'ciudad' => $ciudad,
                        'cp'=>$cp,
                        'edo'=>$estado,
                        'tel'=>$phone,
                        'correo'=>$correo,
                        'del' => $del,
                        'numeroDia' => $numeroDia,
                        'dia' => $dia,
                        'mes' => $mes,
                        'anio' => $anio,
                        'dia_now' => $dia_now,
                        'mes_now' => $mes_now,
                        'anio_now' => $anio_now,
                        'hora_now' => $hora_now,
                        'num_guia' => $num_guia,
                        'libros' => $libros,
                        'precio_envio' => $precio_envio,
                        'orden' => $orden,
                        'transaccion' => $transaccion,
                        'numeros' => $numeros,
                        'marca' => $marca,
                        'cadena' => $cadena,
                        'imgportadas' => $imgportadas,
                        'subtotal' => $subtotal,
                        'libros_pedido' => $libros_pedido,
                        'libro' => $libro,
                        'cupon' => $cupon,
                        'desc' => $desc,
                        'pay_pal' => $pay_pal,
                        'puntos' => $puntos,
                        'clientes' => $clientes,
                        'estado_mundo' => $estado_mundo,
                        'estatus' => $estatus,
                        'cupon_global' => $cupon_global,
                        'user_pay' => $user_pay,
//                        'total_puntos' => $total_puntos,
                    ])
                )
                ->send();

                Yii::$app->mailer->compose()
                    ->setTo($correo)
                    ->setFrom([Yii::$app->params['adminEmail']=>"Un Paseo por los Libros"])
                    ->setSubject("Detalles de la compra")
                    ->setHtmlBody(
                        $this->renderPartial('_correo_cliente',[
                            'nom' => $nombre,
                            'calle'=>$calle,
                            'numero'=>$numero,
                            'ciudad'=>$ciudad,
                            'colonia'=>$colonia,
                            'cp'=>$cp,
                            'edo'=>$estado,
                            'tel'=>$phone,
                            'correo'=>$correo,
                            'del' => $del,
                            'numeroDia' => $numeroDia,
                            'dia' => $dia,
                            'mes' => $mes,
                            'anio' => $anio,
                            'dia_now' => $dia_now,
                            'mes_now' => $mes_now,
                            'anio_now' => $anio_now,
                            'hora_now' => $hora_now,
                            'num_guia' => $num_guia,
                            'libros' => $libros,
                            'precio_envio' => $precio_envio,
                            'orden' => $orden,
                            'transaccion' => $transaccion,
                            'numeros' => $numeros,
                            'marca' => $marca,
                            'cadena' => $cadena,
                            'imgportadas' => $imgportadas,
                            'subtotal' => $subtotal,
                            'libros_pedido' => $libros_pedido,
                            'libro' => $libro,
                            'cupon' => $cupon,
                            'desc' => $desc,
                            'pay_pal' => $pay_pal,
                            'puntos' => $puntos,
                            'clientes' => $clientes,
                            'estado_mundo' => $estado_mundo,
                            'estatus' => $estatus,
                            'cupon_global' => $cupon_global,
                            'user_pay' => $user_pay,
//                            'total_puntos' => $total_puntos,
                        ])
                    )
                ->send();
                $pedido->confirmacion = 1;
                $pedido->save();
            }
            return $confirma;

            }

            //var_dump($result);
        } catch (PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            die($ex);
        }

    }

    public function actionEjecutarfalse()
    {
        return $this->redirect(['site/']);
    }

    public function actionReport() {
        // get your HTML raw content without any layouts or scripts
        $monto = Yii::$app->request->get('monto');
        $referencia = Yii::$app->request->get('referencia');
        
//        return $this->renderPartial('prueba',[
//            'monto' => $monto,
//            'referencia' => $referencia,
//            ]);
        $content = $this->renderPartial('_ficha-oxxo',[
            'monto' => $monto,
            'referencia' => $referencia,
            ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '@frontend/web/css/ficha-oxxo.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => 'Krajee Report Title'],
            'methods' => [
                'SetHeader'=>0,
                'SetFooter'=>0,
            ]
        ]);
        return $pdf->render();
    }

    public function actionCupon(){
        $descuentos = Descuentos::findOne(['codigo' => Yii::$app->request->post('cupon'), 'activo' => 1]);
        if($descuentos){
            return Json::encode($descuentos);
        }
        return false;
    }
    public function actionEditoriales(){
        $editoriales = Yii::$app->request->post();
        
        $respuesta = $this->renderPartial('pago_editorial',[
            'editoriales' => $editoriales,
//            'subtotal' => $subtotal,
        ]);
    }
}
