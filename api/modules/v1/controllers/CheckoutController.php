<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\base\Exception;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
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
use api\modules\v1\models\PasswordResetRequestForm;
use api\modules\v1\models\ResetPasswordForm;
use api\modules\v1\models\SignupForm;
use api\modules\v1\models\ContactoForm;
use api\modules\v1\models\ClientesForm;
use api\modules\v1\models\Libro;
use api\modules\v1\models\PedidoLibro;
use api\modules\v1\models\LibroPedido;
use api\modules\v1\models\PagoTienda;
use api\modules\v1\models\Tema;
use api\modules\v1\models\Sello;
use api\modules\v1\models\Coleccion;
use api\modules\v1\models\Imagenes;
use api\modules\v1\models\LibrosSearch;
use api\modules\v1\models\Carrito;
use api\modules\v1\models\Usuario;
use api\modules\v1\models\Clientes;
use api\modules\v1\models\Descuentos;
use api\modules\v1\models\Paises;
use api\modules\v1\models\EstadosMundo;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use app\components\AuthHandler;
use yii\helpers\Json;
use FedEx\RateService\Request;
use FedEx\RateService\ComplexType;
use FedEx\RateService\SimpleType;

class CheckoutController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\PagoTienda';


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'except' => ['conekta'],
            'authMethods' => [
                // HttpBasicAuth::className(),
                // HttpBearerAuth::className(),
                // QueryParamAuth::className(),
            ],
        ];
        return $behaviors;
    }

    public function actionConekta(){
        $body = @file_get_contents('php://input');
        // $body = '{"id":"5cad1b0e76e4c239721d45c6","created_at":1554848526,"livemode":true,"type":"order.paid","data":null}';
        $data = json_decode($body);
        file_put_contents('test.txt',$body);
        http_response_code(200); // Return 200 OK
        // $precio_envio = ($data->object->shipping_lines->data[0]->amount / 100);
        // $data_resp = $data->object->charges->data[0];

        if ($data->type == 'charge.paid'){
            $oxxo_id = $data->data->object->id;
            $pedidolibro_id = PagoTienda::find()->where('pago_tienda.oxxo_id="'.$oxxo_id.'"')->one();

            if(!$pedidolibro_id){
                return Array(
                    'exito' => false,
                    'mensaje' => 'ID de pago no encontrado'
                );
            }


            $pedido = PedidoLibro::find()->where('pedido_libro.id="'.$pedidolibro_id->pedido_libro_id.'"')->one();
            $pedido->estado_pedido_id = 1;

            if(!$pedido->save()) 
            {
                return Array(
                    'exito' => false,
                    'mensaje' => 'Error al actualizar el estado del pedido.'
                );
            }
            $datos_cliente = Clientes::find()->where('clientes.id="'.$pedido->clientes_id.'"')->one();
            $pide = PedidoLibro::find()->where('pedido_libro.id="'.$pedidolibro_id->pedido_libro_id.'"')->one();

            $check_cupon = $pide->descuento_id;
            $check_descuento_puntos = $pide->costo_descuento;

            if(!$check_cupon && $check_descuento_puntos && $datos_cliente->usuario_id){
                $user = Usuario::findOne(['id'=> $datos_cliente->usuario_id ]); 
                $rest = intval($check_descuento_puntos);
                $user->puntos = $user->puntos - $rest;
                $user->save();
            }

            if($datos_cliente->usuario_id){
                $us_puntos = Usuario::findOne(['id' => $datos_cliente->usuario_id]);
                $us_puntos->puntos = intval($pide->costo_total * 0.05);
                $us_puntos->total_puntos = intval($pide->costo_total * 0.05);
                $us_puntos->save();
            }
            $edo= EstadosMundo::find()->where(['id' => $datos_cliente->estados_mundo_id])->one();
            $paises = Paises::find()->where(['id' => $datos_cliente->paises_id])->one();
            $nombre = $datos_cliente->nombre. ' '.$datos_cliente->apellidos;
            $codigo = $paises->codigo;
            $cadena = str_replace('_', ' ', $pedido->nombre_envio);
            $dia_now = date("d");
            $mes_now = date("F");
            $anio_now = date("Y");
            $hora_now = date("H:i");
            $total = $pide->costo_total;
            $cantidades = LibroPedido::find()->where('pedido_libro_id="'.$pedidolibro_id->pedido_libro_id.'"')->one();
            $calle_concat = $datos_cliente->ciudad.' '.$datos_cliente->num_ext.' '.$datos_cliente->num_int.' '.$datos_cliente->colonia.' '.$datos_cliente->delegacion;
            $ini = parse_ini_file(Yii::getAlias('@frontend').'/fedex.ini');
            $num_guia = $this->renderPartial('ship', [
                'ini' => $ini,
                'ciudad' => $datos_cliente->ciudad,
                'codigo' => $codigo,
                'cp' => $datos_cliente->cp,
                'nombre' => $nombre,
                'telefono' => $datos_cliente->telefono,
                'calle' => $calle_concat,
                'radio' => $pedido->nombre_envio,
            ]);

            $track = PedidoLibro::find()->where('pedido_libro.id="'.$pedidolibro_id->pedido_libro_id.'"')->one();
            $track->tracking = $num_guia;
            $track->save();
            $libro = new Libro();
            $ciudad = $datos_cliente->ciudad;

            $libros_pedido = LibroPedido::find()->where('pedido_libro_id ="'.$track->id.'"')->all();
            $libros = [];
            foreach ($libros_pedido as $libro_pedido) {
                array_push($libros, $libro_pedido->libro);
            }

            $radio = $track->nombre_envio;
            $cadena = str_replace('_', ' ', $radio);
            $subtotal = $pide->costo_total;
            $cupon = $track->descuento_id;
            $desc = $track->costo_descuento;
            $cupon_global = Descuentos::findOne(['id'=>$pide->cupon_global_id]);
            Yii::$app->mailer->compose()
            ->setTo([Yii::$app->params['adminEmail'], Yii::$app->params['digitalEmail']])
            ->setFrom(["ventas@uppl.com.mx"=>"Un Paseo por los Libros"])
            ->setSubject("Se realizó una nueva compra en Línea")
            ->setHtmlBody(
                $this->renderPartial('_correo_lectorum',[
                    'nom' => $nombre,
                    'calle'=>$datos_cliente->calle,
                    'numero'=>$datos_cliente->num_ext,
                    'colonia'=>$datos_cliente->colonia,
                    'cp'=>$datos_cliente->cp,
                    'edo'=>$edo,
                    'tel'=>$datos_cliente->telefono,
                    'correo'=>$datos_cliente->email,
                    'del' => $datos_cliente->delegacion,
                    'dia_now' => $dia_now,
                    'mes_now' => $mes_now,
                    'anio_now' => $anio_now,
                    'hora_now' => $hora_now,
                    'num_guia' => $num_guia,
                    'total' => $total,
                    'cadena' => $cadena,
                    'transaccion' => $pedido->numero_pedido,
                    'orden' => $pedidolibro_id->orden_id,
                    'cantidades' => $cantidades->cantidad,

                    'ciudad'=>$ciudad,
                    'subtotal' => $subtotal,
                    'libro' => $libro,
                    'libros' => $libros,
                    'libros_pedido' => $libros_pedido,
                    'cadena' => $cadena,
                    'cupon' => $cupon,
                    'desc' => $desc,
                    'cupon_global' => $cupon_global,
                    'clientes' => $datos_cliente,
                    'estado_mundo' => $edo,
                ])
            )
            ->send();
            //para el cliente
            Yii::$app->mailer->compose()
            ->setTo($datos_cliente->email)
            ->setFrom(["ventas@uppl.com.mx"=>"Un Paseo por los Libros"])
            ->setSubject("Detalles de la compra")
            ->setHtmlBody(
                $this->renderPartial('_correo_cliente',[
                    'nom' => $nombre,
                    'calle'=>$datos_cliente->calle,
                    'numero'=>$datos_cliente->num_ext,
                    'colonia'=>$datos_cliente->colonia,
                    'cp'=>$datos_cliente->cp,
                    'edo'=>$edo,
                    'tel'=>$datos_cliente->telefono,
                    'correo'=>$datos_cliente->email,
                    'del' => $datos_cliente->delegacion,
                    'dia_now' => $dia_now,
                    'mes_now' => $mes_now,
                    'anio_now' => $anio_now,
                    'hora_now' => $hora_now,
                    'num_guia' => $num_guia,
                    'total' => $total,
                    'cadena' => $cadena,
                    'transaccion' => $pedido->numero_pedido,
                    'orden' => $pedidolibro_id->orden_id,
                    'cantidades' => $cantidades->cantidad,
                    'ciudad'=>$ciudad,
                    'subtotal' => $subtotal,
                    'libro' => $libro,
                    'libros' => $libros,
                    'precio_envio' => $precio_envio,
                    'libros_pedido' => $libros_pedido,
                    'cadena' => $cadena,
                    'cupon' => $cupon,
                    'desc' => $desc,
                    'cupon_global' => $cupon_global,
                    'clientes' => $datos_cliente,
                    'estado_mundo' => $edo,
                ])
            )
            ->send();
            return Array(
                'exito' => true,
                'mensaje' => 'Los datos del pedido se actualizaron correctamente.'
            );
        }

        if ($data->type == 'charge.expired') {
            $oxxo_id = $data->data->object->id;
            $pedidolibro_id = PagoTienda::find()->where('pago_tienda.oxxo_id="'.$oxxo_id.'"')->one();

            if(!$pedidolibro_id){
                return Array(
                    'exito' => false,
                    'mensaje' => 'ID de pago no encontrado'
                );
            }


            $pedido = PedidoLibro::find()->where('pedido_libro.id="'.$pedidolibro_id->pedido_libro_id.'"')->one();
            $pedido->estado_pedido_id = 4;
            $datos_cliente = Clientes::find()->where('clientes.id="'.$pedido->clientes_id.'"')->one();
            if($pedido->costo_descuento && !$pedido->descuento_id && $datos_cliente->usuario_id){
                $pun_back = intval($pedido->costo_descuento);
                $us_puntos = Usuario::findOne(['id' => $datos_cliente->usuario_id]);
                $us_puntos->puntos = $us_puntos->puntos + $pun_back;
                $us_puntos->save();
            }

            if(!$pedido->save()) 
            {
                return Array(
                    'exito' => false,
                    'mensaje' => 'Error al actualizar el estado del pedido.'
                );
            }
        }

    }
    
//    public function actionReenviar(){
//        $body = @file_get_contents('php://input');
//        // $body = '{"id":"5cad1b0e76e4c239721d45c6","created_at":1554848526,"livemode":true,"type":"order.paid","data":null}';
//        $data = json_decode($body);
//        file_put_contents('test.txt',$body);
//        http_response_code(200); // Return 200 OK
//        // $precio_envio = ($data->object->shipping_lines->data[0]->amount / 100);
//        // $data_resp = $data->object->charges->data[0];
//        if ($data->type == 'charge.expired'){
//            $oxxo_id = $data->data->object->id;
//            $pedidolibro_id = PagoTienda::find()->where(['orden_id' => $oxxo_id])->one();
//            if(!$pedidolibro_id){
//                return Array(
//                    'exito' => false,
//                    'mensaje' => 'ID de pago no encontrado'
//                );
//            }
//            $pedido = PedidoLibro::find()->where('pedido_libro.id="'.$pedidolibro_id->pedido_libro_id.'"')->one();
//            $datos_cliente = Clientes::find()->where('clientes.id="'.$pedido->clientes_id.'"')->one();
//            $pide = PedidoLibro::find()->where('pedido_libro.id="'.$pedidolibro_id->pedido_libro_id.'"')->one();
//
//            $check_cupon = $pide->descuento_id;
//            $check_descuento_puntos = $pide->costo_descuento;
//
//            if(!$check_cupon && $check_descuento_puntos && $datos_cliente->usuario_id){
//                $user = Usuario::findOne(['id'=> $datos_cliente->usuario_id ]); 
//                $rest = intval($check_descuento_puntos);
//                $user->puntos = $user->puntos - $rest;
//                $user->save();
//            }
//
//            if($datos_cliente->usuario_id){
//                $us_puntos = Usuario::findOne(['id' => $datos_cliente->usuario_id]);
//                $us_puntos->puntos = intval($pide->costo_total * 0.05);
//                $us_puntos->total_puntos = intval($pide->costo_total * 0.05);
//                $us_puntos->save();
//            }
//            $edo= EstadosMundo::find()->where(['id' => $datos_cliente->estados_mundo_id])->one();
//            $paises = Paises::find()->where(['id' => $datos_cliente->paises_id])->one();
//            $nombre = $datos_cliente->nombre. ' '.$datos_cliente->apellidos;
//            $codigo = $paises->codigo;
//            $cadena = str_replace('_', ' ', $pedido->nombre_envio);
//            $dia_now = date("d");
//            $mes_now = date("F");
//            $anio_now = date("Y");
//            $hora_now = date("H:i");
//            $total = $pide->costo_total;
//            $cantidades = LibroPedido::find()->where('pedido_libro_id="'.$pedidolibro_id->pedido_libro_id.'"')->one();
//            $calle_concat = $datos_cliente->ciudad.' '.$datos_cliente->num_ext.' '.$datos_cliente->num_int.' '.$datos_cliente->colonia.' '.$datos_cliente->delegacion;
//            $ini = parse_ini_file(Yii::getAlias('@frontend').'/fedex.ini');
//            $num_guia =$pedido->tracking;
//
//            $libro = new Libro();
//            $ciudad = $datos_cliente->ciudad;
//
//            $libros_pedido = LibroPedido::find()->where('pedido_libro_id ="'.$pedido->id.'"')->all();
//            $libros = [];
//            foreach ($libros_pedido as $libro_pedido) {
//                array_push($libros, $libro_pedido->libro);
//            }
//
//            $radio = $pedido->nombre_envio;
//            $cadena = str_replace('_', ' ', $radio);
//            $subtotal = $pide->costo_total;
//            $cupon = $pedido->descuento_id;
//            $desc = $pedido->costo_descuento;
//            $cupon_global = Descuentos::findOne(['id'=>$pide->cupon_global_id]);
//            $test = true;
//            Yii::$app->mailer->compose()
//            ->setTo([Yii::$app->params['adminEmail'], Yii::$app->params['digitalEmail']])
//            ->setFrom(["ventas@lectorum.com.mx"=>"Lectorum"])
//            ->setSubject("Se realizó una nueva compra en Línea")
//            ->setHtmlBody(
//                $this->renderPartial('_correo_lectorum',[
//                    'nom' => $nombre,
//                    'calle'=>$datos_cliente->calle,
//                    'numero'=>$datos_cliente->num_ext,
//                    'colonia'=>$datos_cliente->colonia,
//                    'cp'=>$datos_cliente->cp,
//                    'edo'=>$edo,
//                    'tel'=>$datos_cliente->telefono,
//                    'correo'=>$datos_cliente->email,
//                    'del' => $datos_cliente->delegacion,
//                    'dia_now' => $dia_now,
//                    'mes_now' => $mes_now,
//                    'anio_now' => $anio_now,
//                    'hora_now' => $hora_now,
//                    'num_guia' => $num_guia,
//                    'total' => $total,
//                    'cadena' => $cadena,
//                    'transaccion' => $pedido->numero_pedido,
//                    'orden' => $pedidolibro_id->orden_id,
//                    'cantidades' => $cantidades->cantidad,
//                    'test' => $test,
//                    'ciudad'=>$ciudad,
//                    'subtotal' => $subtotal,
//                    'libro' => $libro,
//                    'libros' => $libros,
//                    'libros_pedido' => $libros_pedido,
//                    'cadena' => $cadena,
//                    'cupon' => $cupon,
//                    'desc' => $desc,
//                    'cupon_global' => $cupon_global,
//                    'clientes' => $datos_cliente,
//                    'estado_mundo' => $edo,
//                ])
//            )
//            ->send();
//            //para el cliente
//            Yii::$app->mailer->compose()
//            ->setTo($datos_cliente->email)
//            ->setFrom(["ventas@lectorum.com.mx"=>"Lectorum"])
//            ->setSubject("Detalles de la compra")
//            ->setHtmlBody(
//                $this->renderPartial('_correo_cliente',[
//                    'nom' => $nombre,
//                    'calle'=>$datos_cliente->calle,
//                    'numero'=>$datos_cliente->num_ext,
//                    'colonia'=>$datos_cliente->colonia,
//                    'cp'=>$datos_cliente->cp,
//                    'edo'=>$edo,
//                    'tel'=>$datos_cliente->telefono,
//                    'correo'=>$datos_cliente->email,
//                    'del' => $datos_cliente->delegacion,
//                    'dia_now' => $dia_now,
//                    'mes_now' => $mes_now,
//                    'anio_now' => $anio_now,
//                    'hora_now' => $hora_now,
//                    'num_guia' => $num_guia,
//                    'total' => $total,
//                    'test' => $test,
//                    'cadena' => $cadena,
//                    'transaccion' => $pedido->numero_pedido,
//                    'orden' => $pedidolibro_id->orden_id,
//                    'cantidades' => $cantidades->cantidad,
//                    'ciudad'=>$ciudad,
//                    'subtotal' => $subtotal,
//                    'libro' => $libro,
//                    'libros' => $libros,
//                    'precio_envio' => $precio_envio,
//                    'libros_pedido' => $libros_pedido,
//                    'cadena' => $cadena,
//                    'cupon' => $cupon,
//                    'desc' => $desc,
//                    'cupon_global' => $cupon_global,
//                    'clientes' => $datos_cliente,
//                    'estado_mundo' => $edo,
//                ])
//            )
//            ->send();
//            return Array(
//                'exito' => true,
//                'mensaje' => 'Los datos del pedido se actualizaron correctamente.'
//            );
//        }
//    }
}
