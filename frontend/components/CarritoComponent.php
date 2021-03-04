<?php

namespace app\components;

use Yii;
use DateTime;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Cookie;
use yii\db\Query;

use frontend\models\Carrito;
use frontend\models\DatosPago;
use frontend\models\PagoTienda;
use frontend\models\LibroCarrito;
use frontend\models\PedidoLibro;
use frontend\models\LibroPedido;
use frontend\models\PagoEditorial;
use frontend\models\Clientes;
use frontend\models\Descuentos;
use frontend\models\Usuario;
use frontend\models\Libro;

use \Conekta\Conekta;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class CarritoComponent extends Component
{

    private $transaction;
    private $cookie_id;
    private $usuario_id;
    private $where_carrito;

    private function identificador(){
        if(Yii::$app->user->isGuest){
            $cookie_id = Yii::$app->getRequest()->getCookies()->getValue('lectorum_cart');
            if(!$cookie_id){
                $cookie_id = uniqid(rand(10,99));
                $cookie = new Cookie([
                    'name' => 'lectorum_cart',
                    'value' => $cookie_id,
                    'expire' => time() + 86400 * 365,
                ]);
                Yii::$app->getResponse()->getCookies()->add($cookie);
            }
            $this->cookie_id = $cookie_id;
            $this->where_carrito = 'carrito.cookie_id = "'.$cookie_id.'"';
        }else{
            $this->usuario_id = Yii::$app->user->identity->id;
            $this->where_carrito = 'carrito.usuario_id = "'.$this->usuario_id.'"';
        }
    }

    public function switchUsuario(){
        $cookie_id = Yii::$app->getRequest()->getCookies()->getValue('lectorum_cart');
        if(!$cookie_id){
            return true;
        }
        $carrito_cookie = Carrito::find()->where(['cookie_id' => $cookie_id])->one();
        
//        var_dump($carrito_cookie->usuario_id);exit;
        if(!$carrito_cookie){
            return true;
        }
        $carrito_user = Carrito::find()->where(['usuario_id'=> Yii::$app->user->identity->id])->one();
//        if($carrito_user)
        if($carrito_user){
            $libros_cookie = LibroCarrito::find()->where(['carrito_id'=>$carrito_cookie->id])->all();
            foreach ($libros_cookie as $libro_cookie) {
                
                
                
                $mismo_libro = LibroCarrito::find()->where(['carrito_id' => $carrito_user->id, 'libro_id' => $libro_cookie->libro_id])->one();
                if ($mismo_libro){
                    $libro_cookie->delete();
                } else {
                    $libro_cookie->carrito_id = $carrito_user->id;
                    $libro_cookie->save();
                }
            }
            
            $carrito_user->cookie_id = $cookie_id;
            $carrito_user->save();
        }
        if (!$carrito_cookie->usuario_id){
            $carrito_cookie->delete();
        }
        return true;
//        $carrito_cookie->usuario_id = Yii::$app->user->identity->id;
//        return $carrito_cookie->save();
    }

    public function botonCarrito($imagen = false){
        $this->identificador();
        $cantidad = Carrito::find()
        ->select(['COUNT(libro_carrito.cantidad) AS cantidad'])
        ->join('INNER JOIN', 'libro_carrito', 'libro_carrito.carrito_id = carrito.id')
        ->where($this->where_carrito)
        ->one();
        $cantidad_carrito = $cantidad->cantidad > 0 ? $cantidad->cantidad : '';
        //var_dump($cantidad_carrito);exit;
        if($cantidad_carrito == 0){
            return 
            Html::img('@web/images/carrito_blanco.png',['class' => 'img-responsive social-nav']).'<span id="carrito-span" class="numbolsa">'.$cantidad_carrito.'</span>';
        }elseif ($cantidad_carrito >= 10) {
            return 
            Html::img('@web/images/carrito_blanco.png',['class' => 'img-responsive social-nav']).'<span id="carrito-span" class="numbolsa pad_M10" style="background-color:#fa3e3e;">'.$cantidad_carrito.'</span>';
        }elseif ($cantidad_carrito == 1) {
            return 
            Html::img('@web/images/carrito_blanco.png',['class' => 'img-responsive social-nav']).'<span id="carrito-span" class="numbolsa pad_M1" style="background-color:#fa3e3e;">'.$cantidad_carrito.'</span>';
        }else{
            return 
            Html::img('@web/images/carrito_blanco.png',['class' => 'img-responsive social-nav']).'<span id="carrito-span" class="numbolsa" style="background-color:#fa3e3e;">'.$cantidad_carrito.'</span>';
        }
    }

    public function agregar($libro_id, $cantidad = 1){
        $this->identificador();
        $carrito = new Carrito();
        if(Yii::$app->user->isGuest){
            $carrito = $carrito->idCarrito(true, $this->cookie_id);
        }else{
            $carrito = $carrito->idCarrito(false, $this->usuario_id);
        }
        if(!$carrito){
            return [
                'exito' => 0,
                'mensaje' => 'Error al agregar producto a carrito.'
            ];
        }
        $carrito_up = Carrito::findOne(['id'=>$carrito]);
        $carrito_up->touch('updated_at');
        $producto = LibroCarrito::find()
        ->where(
            'carrito_id='.$carrito.
            ' AND libro_id='.$libro_id)->one();
        if($producto){
            $producto->cantidad += $cantidad;
            if(!$producto->save()){
                return [
                    'exito' => 0,
                    'mensaje' => 'Error al actualizar producto en carrito.'
                ];
            }
        }else{
            $producto = new LibroCarrito();
            $producto->carrito_id = $carrito;
            $producto->libro_id = $libro_id;
            $producto->cantidad = $cantidad;

            if(!$producto->save()){
                return [
                    'exito' => 0,
                    'mensaje' => 'Error al agregar producto a carrito.'
                ];
            }
        }
        return [
            'exito' => 1,
            'mensaje' => 'El producto se agregó al carrito.'
        ];
    }

    public function obtieneProductos(){
        $this->identificador();
        //var_dump($this->where_carrito);exit;
        $productos = LibroCarrito::find()
        ->select([
            'libro_carrito.id AS libro_carrito',
            'libro_carrito.carrito_id AS carrito_id',
            'libro.id AS libro', 
            'libro.titulo AS titulo',
            'libro.pvp AS precio',
            'libro.promo AS promo',
            'libro.editorial_id AS editorial_id',
            'libro_carrito.cantidad AS cantidad',
            'libro.isbn AS isbn',
            'autor.nombre AS autor',
            'sello.nombre AS sello',
            'tema.nombre AS tema',
            'imagenes.portada AS portada',
            'editorial.clave as editorial_clave',
            'editorial.nombre as editorial_nombre',
        ])
        ->from('carrito')
        ->orderBy(['libro_carrito' => SORT_DESC,])
        ->join('INNER JOIN', 'libro_carrito', 'libro_carrito.carrito_id = carrito.id')
        ->join('INNER JOIN', 'libro', 'libro.id = libro_carrito.libro_id')
        ->join('INNER JOIN', 'libro_autor', 'libro_autor.libro_id = libro.id')
        ->join('INNER JOIN', 'autor', 'autor.id = libro_autor.autor_id')
        ->join('INNER JOIN', 'sello', 'sello.id = libro.sello_id')
        ->join('INNER JOIN', 'tema', 'tema.id = libro.tema_id')
        ->join('LEFT JOIN', 'imagenes', 'imagenes.libro_id = libro.id')
        ->join('LEFT JOIN', 'editorial', 'libro.editorial_id = editorial.id')
        ->where($this->where_carrito)
        ->all();
        return $productos;
    }
//    public function editorialesProdcutos(){
//        
//        $this->identificador();
//        $productos = LibroCarrito::find()->where($this->where_carrito)->all();
//    }

    public function cantidadProductos(){
        $this->identificador();
        $productos = LibroCarrito::find()
            ->select(['SUM(libro_carrito.cantidad) AS cantidad'])
            ->from('carrito')
            ->join('INNER JOIN', 'libro_carrito', 'libro_carrito.carrito_id = carrito.id')
            ->where($this->where_carrito)
            ->one();
        if($productos)
            return $productos->cantidad;
        return 0;
    }

    public function borrarCarrito(){
        $this->identificador();
        $carrito = new Carrito();
        if(Yii::$app->user->isGuest){
            $carrito = $carrito->idCarrito(true, $this->cookie_id);
        }else{
            $carrito = $carrito->idCarrito(false, $this->usuario_id);
        }
        if(!$carrito){
            return [
                'exito' => 0,
                'mensaje' => 'Error al agregar producto a carrito.'
            ];
        }
        $carrito = Carrito::findOne($carrito);
        if(!is_numeric($carrito->delete())){
            return false;
        }
        return true;
    }

    public function borrarLibro($id){
        $this->identificador();
        $carrito = new Carrito();
        if (Yii::$app->user->isGuest) {
            $carrito = $carrito->idCarrito(true, $this->cookie_id);
        } else {
            $carrito = $carrito->idCarrito(false, $this->usuario_id);
        }
        if (!$carrito) {
            return json_encode([
                'exito' => 0,
                'mensaje' => 'Error al encontrar carrito.'
            ]);
        }

        $id = LibroCarrito::find()
            ->where([
                'carrito_id'=>$carrito,
                'libro_id'=>$id,
            ])
            ->one();
        if($id){
            if(!$id->delete()){
                return json_encode([
                    'exito' => 0,
                    'mensaje'  => 'Error al borrar producto.'
                ]);
            }
        }
        $carrito_up = Carrito::findOne(['id'=>$carrito]);
        $carrito_up->touch('updated_at');
        return json_encode([
            'exito' => 1,
            'mensaje' => 'El carrito ha sido actualizado.',
        ]);
    }

    public function actualizar($libro, $cantidad){
        if(empty($libro)){
            return [
                'exito' => 0,
                'mensaje' => 'Error actualizando el carrito'
            ];
        }

        $connection = \Yii::$app->db;
        $this->transaction = $connection->beginTransaction();

        $this->identificador();

        $carrito = Carrito::find()
        ->where($this->where_carrito)
        ->one();

        $productos_carro = LibroCarrito::find()->where('libro_id='.$libro)->all();
        foreach ($productos_carro as $producto_carro) {
            if(! $producto_carro->delete()){
                $this->transaction->rollback();
                return [
                    'exito' => 0,
                    'mensaje' => 'Error actualizando el carrito'
                ];
            }
        }

            $producto = new LibroCarrito();
            $producto->carrito_id = $carrito->id;
            $producto->libro_id = $libro;
            $producto->cantidad = $cantidad;
            if(!$producto->save()){
                $this->transaction->rollback();
                return [
                    'exito' => 0,
                    'mensaje' => 'Error actualizando el carrito'
                ];
            }
        $carrito->touch('updated_at');
        $this->transaction->commit();
        return [
            'exito' => 1,
            'mensaje' => 'El carrito se ha actualizado.'
        ];
    }

    public function actualizarCarrito($correo){
        if(empty($correo)){
            return [
                'exito' => 0,
                'mensaje' => 'Error actualizando el carrito'
            ];
        }

        $connection = \Yii::$app->db;
        $this->transaction = $connection->beginTransaction();

        $this->identificador();

        $carrito = Carrito::find()
        ->where($this->where_carrito)
        ->one();

            $producto = Carrito::find()
            ->where(['id'=> $carrito->id])
            ->one();
            //$producto->carrito_id = $carrito->id;
            //$producto->libro_id = $libro;
            //$producto->cantidad = $cantidad;
            $producto->correo = $correo;
            if(!$producto->save()){
                $this->transaction->rollback();
                return [
                    'exito' => 0,
                    'mensaje' => 'Error actualizando el carrito'
                ];
            }
        $this->transaction->commit();
        return [
            'exito' => 1,
            'mensaje' => 'El carrito se ha actualizado.'
        ];
    }

    public function finalizar($params){
        $descuento = $params['cupon']['descuento'];
        $cupon_id = $params['cupon']['id'];
        $porcent = floatval($params['cupon']['porcentaje']);
//        $puntos_descuento = intval($params['puntos_des']);
        $costo_envio = floatval($params['envio']['costo']);
//        $paquete = $params['precio_paquete']*100;
//        $paquete = intval($paquete);
//        $cupon_global = Descuentos::findOne(['global'=>1]);
        if(empty($params)){
            return [
                'exito' => 0,
                'mensaje' => 'Error finalizando compra, parametros'
            ];
        }
        //$this->identificador();
        $connection = \Yii::$app->db;
        $this->transaction = $connection->beginTransaction();

        if(Yii::$app->user->isGuest){
            $cliente = new Clientes();
            $cliente->nombre = $params['envio']['nombre'];
            $cliente->apellidos = $params['envio']['apellidos'];
            $cliente->telefono = $params['envio']['telefono'];
            $cliente->calle = $params['envio']['calle'];
            
//            $cliente->num_ext = 'numext';
//            $cliente->num_int = 'numint';
            $cliente->colonia = $params['envio']['colonia'];
            $cliente->ciudad = $params['envio']['ciudad'];
            $cliente->delegacion = $params['envio']['ciudad'];
            
            $cliente->paises_id = $params['envio']['pais'];
            $cliente->estados_mundo_id = $params['envio']['estado'];
            $cliente->cp = $params['envio']['cp'];
            $cliente->colonia = $params['envio']['colonia'];
            $cliente->email = $params['envio']['emailtext'];

            $validaGuardaCliente = $cliente->save();

            if(!$validaGuardaCliente){
                $this->transaction->rollback();
                // var_dump($cliente->getErrors());exit;
                return [
                    'exito' => 0,
                    'mensaje' => 'Error finalizando compra, cliente'
                ];
            }
        }else{
            $cliente = Clientes::findOne(['usuario_id' => Yii::$app->user->identity->id]);
        }

        $pedido = new PedidoLibro();
        $pedido->clientes_id = $cliente->id;
        $caracteres = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for($x = 0; $x < 10; $x++)
        {
            $aleatoria = substr(str_shuffle($caracteres), 0, 10);
         //   echo $aleatoria;
        }
        $pedido->numero_pedido = $aleatoria;
        $pedido->estado_pedido_id = 1;
        $pedido->nombre_envio = $params['envio']['tipo'];
        $pedido->costo_envio = $costo_envio;
        $costo_envio_conekta = intval($costo_envio*100);
        if($cupon_id){
            $pedido->descuento_id = $cupon_id;
            $pedido->costo_descuento = $descuento;
//        }elseif ($puntos_descuento) {
//            $pedido->costo_descuento = $puntos_descuento;
        }
        if($cupon_global){
            $pedido->cupon_global_id = $cupon_global->id;
        }
        $validaGuardaPedido = $pedido->save();

        if(!$validaGuardaPedido){
            $this->transaction->rollback();
             //var_dump($pedido->getErrors());exit;
            return [
                'exito' => 0,
                'mensaje' => 'Error finalizando compra, pedido'
            ];
        }
        
        $pago_total = 0;
        
        $editorial_array = json_decode($params['editorial'], true);
        
        foreach ($editorial_array as $editorial){
            
            $pago_editorial = new PagoEditorial();
            
            $pago_editorial->orden_id = $editorial['order'];
            $pago_editorial->monto = floatval($editorial['monto']);
            $pago_editorial->codigo_auth = intval($editorial['codigo']);
            $pago_editorial->numeros_tarjeta = intval($editorial['numeros']);
            $pago_editorial->marca = $editorial['marca'];
            $pago_editorial->tipo = $editorial['tipo'];
            $pago_editorial->pedido_libro_id = intval($pedido->id);
            $pago_editorial->editorial_id = intval($editorial['id']);
            
            
            $pago_total += floatval($editorial['monto']); 
            
            if(!$pago_editorial->save()){
                $this->transaction->rollback();
                $mensaje_error = 'Error guardando pago de editorial '.$pago_editorial->editorial_id.': ';
                foreach ($pago_editorial->getErrors() as $errors){
                    foreach ($errors as $error){
                        $mensaje_error .= $error.' ';
                    }
                }
                return [
                    'exito' => 0,
                    'mensaje' => $mensaje_error,
                ];
            }
        }
        
        if(Yii::$app->user->isGuest){
            $cookie_id = $this->identificador();
            //var_dump($this->cookie_id);exit;
            $carrito = Carrito::find()
            ->where('cookie_id="'.$this->cookie_id.'"')
            ->one();
        }else{
            $usuario_id = Yii::$app->user->identity->id;
            $carrito = Carrito::find()
            ->where('usuario_id="'.$usuario_id.'"')
            ->one();
        }

        $productosCarrito = LibroCarrito::find()
            ->select(['libro_carrito.*', 'libro.titulo AS titulo' , 'libro.pvp AS precio', 'libro.promo AS promo'])
            ->from('libro_carrito')
            ->join('INNER JOIN', 'libro', 'libro.id = libro_carrito.libro_id')
            ->where('libro_carrito.carrito_id='.$carrito->id)
            ->all();

        $items = array();
        $total_libros = 0;
        $total_cont = 0;
        foreach ($productosCarrito as $conteo) {
            $total_cont += $conteo->cantidad;
        }
//        if($puntos_descuento){
//            $div = $puntos_descuento/$total_cont;
//            $desc_punt_div = number_format($div, 2);
//        }
        
        foreach ($productosCarrito as $productoCarrito) {
            
            $libro = Libro::find()->where(['id' => $productoCarrito->libro_id])->one();
            
//            var_dump($libro);
//            var_dump($libro->id);
//            var_dump($productoCarrito->cantidad);exit;
            
            $libro->cantidad = $libro->cantidad - $productoCarrito->cantidad;
            if (!$libro->save()){
                $this->transaction->rollback();
                var_dump($libro->getErrors());exit;
                return [
                    'exito' => 0,
                    'mensaje' => 'Error finalizando compra, restando cantidad',
                ];                
            }
            
            
            $productop = new LibroPedido();
            $productop->pedido_libro_id = $pedido->id;
            $productop->libro_id = $productoCarrito->libro_id;
            $productop->cantidad = $productoCarrito->cantidad;
            
            if($productoCarrito->promo && $productoCarrito->promo>0){
                $productop->total = $productoCarrito->cantidad * $productoCarrito->promo;
            }elseif ($cupon_global && !$productoCarrito->promo>=0) {
                $preciobruto= $productoCarrito->precio - (($cupon_global->porcentaje / 100) * $productoCarrito->precio);
                $productop->total = $productoCarrito->cantidad * $preciobruto;
            }else{
                $productop->total = $productoCarrito->cantidad * $productoCarrito->precio;
            }
            
            $validaGuardaProductoP = $productop->save();

            $item = array();
            $item['name'] = $productoCarrito->titulo;
            //$item['unit_price'] = intval($productoCarrito->precio)*100;
            if($productoCarrito->promo && $productoCarrito->promo>0){
                if($cupon_id){
                    $pro_sum = $productoCarrito->promo*$porcent;
                    $totales_des = $productoCarrito->promo - $pro_sum;
                    $item['unit_price'] = $totales_des*100;
                    $item['unit_price'] = intval($item['unit_price']);
                    $total_libros += intval($item['unit_price']);
//                }elseif ($puntos_descuento) {
//                    $item['unit_price'] = ($productoCarrito->promo - $desc_punt_div)*100;
//                    $total_libros += ($productoCarrito->promo - $desc_punt_div)*100;
                }else{
                    $item['unit_price'] = $productoCarrito->promo*100;
                    $total_libros += $productoCarrito->promo*100;
                }
            }elseif ($cupon_global && !$productoCarrito->promo>=0) {
                $preciobruto= $productoCarrito->precio - (($cupon_global->porcentaje / 100) * $productoCarrito->precio);
                if($cupon_id){
                    $pro_sum = $preciobruto*$porcent;
                    $totales_des = $preciobruto - $pro_sum;
                    $item['unit_price'] = $totales_des*100;
                    $item['unit_price'] = intval($item['unit_price']);
                    $total_libros += intval($item['unit_price']);
//                }elseif ($puntos_descuento) {
//                    $item['unit_price'] = intval(($preciobruto - $desc_punt_div)*100);
//                    $total_libros += intval(($preciobruto - $desc_punt_div)*100);
                } else{
                    $item['unit_price'] = intval($preciobruto*100);
                    $total_libros += intval($preciobruto*100);
                }       
            } else{
                if($cupon_id){
                    $pro_sum = $productoCarrito->precio*$porcent;
                    $totales_des = $productoCarrito->precio - $pro_sum;
                    $item['unit_price'] = $totales_des*100;
                    $item['unit_price'] = intval($item['unit_price']);
                    $total_libros += intval($item['unit_price']);
//                }elseif ($puntos_descuento) {
//                    $item['unit_price'] = ($productoCarrito->precio - $desc_punt_div)*100;
//                    $total_libros += ($productoCarrito->precio - $desc_punt_div)*100;
                } else{
                    $item['unit_price'] = $productoCarrito->precio*100;
                    $total_libros += $productoCarrito->precio*100;
                }
            }
            $item['quantity'] = $productoCarrito->cantidad;
            $items[]=$item;
            //var_dump($item);exit;
            if(!$validaGuardaProductoP){
                $this->transaction->rollback();
                return [
                    'exito' => 0,
                    'mensaje' => 'Error finalizando compra, productos pedido',
                ];            }
        }
        
//        var_dump('despues de foreach'); exit;
        //$this->transaction->commit();
        //var_dump($params['precio_paquete']*100);exit;
        $ini = parse_ini_file("../private.ini");
        
        \Conekta\Conekta::setApiKey($ini['privateKey']);
        \Conekta\Conekta::setApiVersion("2.0.0");
        try{
            $order = \Conekta\Order::create(
                array(
                'currency' => 'MXN',
                'customer_info' => array(
                    'name'=> $cliente->nombre.' '.$cliente->apellidos,
                    'email'=> $cliente->email,
                    'phone' => '+52'.$cliente->telefono
                ),
                "shipping_lines" => array(
                    array(
                        "amount" => 0,
                        "carrier" => "FEDEX"
                    )
                ),
                'line_items' => array(
                    array(
                        'name' => 'envío',
                        "unit_price" => $costo_envio_conekta,
                        "quantity" => 1
                    )
                ),
                "shipping_contact" => array(
                    "address" => array(
                        "street1" => $params['envio']['calle'],
                        "postal_code" => $params['envio']['cp'],
                        "country" => "MX"
                    )
                ),//address
                'charges' => array(
                    array(
                        'payment_method' => array(
                            'type' => 'card',
                            "token_id" => $params['token']['id']
                        )
                    )
                )
            ));
        } catch (\Conekta\ProcessingError $error){
            return [
                'exito' => 0,
                'mensaje' => '1: '.$error->getMessage(),
            ];
        } catch (\Conekta\ParameterValidationError $error){
            
            return [
                'exito' => 0,
                'mensaje' => '2: '.$error->getMessage(),
            ];
        } catch (\Conekta\Handler $error){
            return [
                'exito' => 0,
                'mensaje' => '3: '.$error->getMessage(),
            ];
        } catch (\Conekta\ApiError $error){
            return [
                'exito' => 0,
                'mensaje' => '4: '.$error->getMessage(),
            ];
        } catch (\Conekta\AuthenticationError $error){
            return [
                'exito' => 0,
                'mensaje' => '5: '.$error->getMessage(),
            ];
        } catch (\Conekta\MalFormedRequestError $error){
            return [
                'exito' => 0,
                'mensaje' => '6: '.$error->getMessage(),
            ];
        } catch (\Conekta\ResourceNotFoundError $error){
            return [
                'exito' => 0,
                'mensaje' => '7: '.$error->getMessage(),
            ];
        }

        $datosPago = new DatosPago();
        $datosPago->orden_id = $order->id;
        $datosPago->monto = $pago_total+($order->amount/100);
        $datosPago->codigo_auth = $order->charges[0]->payment_method->auth_code;
        $datosPago->numeros_tarjeta = $order->charges[0]->payment_method->last4;
        $datosPago->marca = $order->charges[0]->payment_method->brand;
        $datosPago->tipo = $order->charges[0]->payment_method->type;
        $validaDatosPago = $datosPago->save();

        if(!$validaDatosPago){
            $this->transaction->rollback();
            return [
                'exito' => 0,
                'mensaje' => 'Error finalizando compra, datos del pago'
            ];
        }

        $pedido->datos_pago_id = $datosPago->id;
        $pedido->costo_total = $datosPago->monto;
        $validaGuardaPedido = $pedido->save();

        if(!$validaGuardaPedido){
            $this->transaction->rollback();
            return [
                'exito' => 0,
                'mensaje' => 'Error actualizando el carrito, pedido'
            ];
        }

        $productos_carro_borrar = LibroCarrito::find()->where('carrito_id='.$carrito->id)->all();
        foreach ($productos_carro_borrar as $producto_carro) {
            if(! $producto_carro->delete()){
                $this->transaction->rollback();
                return [
                    'exito' => 0,
                    'mensaje' => 'Error actualizando el carrito'
                ];
            }
        }

        $this->transaction->commit();

        return [
            'exito' => 1,
            'mensaje' => 'La compra ha finalizado de foma exitosa',
            'orden' => $aleatoria,
            'transaccion' => $datosPago->orden_id, 
            'numeros' => $datosPago->numeros_tarjeta,
            'marca' => $datosPago->marca,
            'numped' => $aleatoria,
        ];
    }

    public function pagarCarrito($params){
        $descuento = $params['descuento_precio'];
        $cupon_id = $params['cupon_id'];
        $porcent = floatval($params['descuento_porcentaje']);
//        $puntos_descuento = intval($params['puntos_des']);
        $paquete = $params['precio_paquete'];
        $costo_envio = $params['precio_paquete'];
        $paquete = floatval($paquete);
//        $cupon_global = Descuentos::findOne(['global'=>1]);
        if(empty($params)){
            return [
                'exito' => 0,
                'mensaje' => 'Error finalizando compra, parametros'
            ];
        }
        //$this->identificador();
        $connection = \Yii::$app->db;
        $this->transaction = $connection->beginTransaction();
        if(Yii::$app->user->isGuest){
            $cliente = new Clientes();
            $cliente->nombre = $params['envio']['nombre'];
            $cliente->apellidos = $params['envio']['apellidos'];
            $cliente->telefono = $params['envio']['telefono'];
            $cliente->calle = $params['envio']['calle'];
            $cliente->num_ext = 'jhbsdf';
//            $cliente->num_int = $params['envio']['num_int'];
            $cliente->ciudad = $params['envio']['ciudad'];
            $cliente->paises_id = $params['envio']['pais'];
            $cliente->estados_mundo_id = $params['envio']['estado'];
            $cliente->cp = $params['envio']['cp'];
//            $cliente->delegacion = $params['envio']['delegacion'];
//            $cliente->colonia = $params['envio']['colonia'];
            $cliente->email = $params['envio']['emailtext'];
//            var_dump($cliente); exit;
            $validaGuardaCliente = $cliente->save();
            
            if(!$validaGuardaCliente){
                $this->transaction->rollback();
                // var_dump($cliente->getErrors());exit;
                return [
                    'exito' => 0,
                    'mensaje' => 'Error finalizando compra, cliente'
                ];
            }
        }else{
            $cliente = Clientes::findOne(['usuario_id' => Yii::$app->user->identity->id]);
        }

        $pedido = new PedidoLibro();
        $pedido->clientes_id = $cliente->id;
        $caracteres = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for($x = 0; $x < 10; $x++)
        {
            $aleatoria = substr(str_shuffle($caracteres), 0, 10);
         //   echo $aleatoria;
        }
        if($cupon_id){
            $pedido->descuento_id = $cupon_id;
            $pedido->costo_descuento = $descuento;
//        }elseif ($puntos_descuento) {
//            $pedido->costo_descuento = $puntos_descuento;
        }
        $pedido->numero_pedido = $aleatoria;
        $pedido->estado_pedido_id = 2;
        $pedido->nombre_envio = $params['radio_envio'];
        $pedido->cupon_global_id = $cupon_global->id;
        $pedido->costo_envio = $costo_envio;
        $validaGuardaPedido = $pedido->save();

        if(!$validaGuardaPedido){
            $this->transaction->rollback();
             //var_dump($pedido->getErrors());exit;
            return [
                'exito' => 0,
                'mensaje' => 'Error finalizando compra, pedido'
            ];
        }

        if(Yii::$app->user->isGuest){
            $cookie_id = $this->identificador();
            //var_dump($this->cookie_id);exit;
            $carrito = Carrito::find()
            ->where('cookie_id="'.$this->cookie_id.'"')
            ->one();
        }else{
            $usuario_id = Yii::$app->user->identity->id;
            $carrito = Carrito::find()
            ->where('usuario_id="'.$usuario_id.'"')
            ->one();
        }

        $productosCarrito = LibroCarrito::find()
        ->select(['libro_carrito.*', 'libro.titulo AS titulo' , 'libro.pvp AS precio', 'libro.promo AS promo'])
        ->from('libro_carrito')
        ->join('INNER JOIN', 'libro', 'libro.id = libro_carrito.libro_id')
        ->where('libro_carrito.carrito_id='.$carrito->id)
        ->all();

        $items = array();
        $total_libros = 0;
        $total_cont = 0;
        foreach ($productosCarrito as $conteo) {
            $total_cont += $conteo->cantidad;
        }
//        if($puntos_descuento){
//            $div = $puntos_descuento/$total_cont;
//            $desc_punt_div = number_format($div, 2);
//        }

        foreach ($productosCarrito as $productoCarrito) {
            $productop = new LibroPedido();
            $productop->pedido_libro_id = $pedido->id;
            $productop->libro_id = $productoCarrito->libro_id;
            $productop->cantidad = $productoCarrito->cantidad;
            // $producto = $productoCarrito->producto;
            // var_dump($productoCarrito->precio);exit;
            if($productoCarrito->promo && $productoCarrito->promo>0){
                $productop->total = $productoCarrito->cantidad * $productoCarrito->promo;
            }elseif ($cupon_global && !$productoCarrito->promo>=0) {
                $preciobruto= $productoCarrito->precio - (($cupon_global->porcentaje / 100) * $productoCarrito->precio);
                $productop->total = $productoCarrito->cantidad * $preciobruto;
            }else{
                $productop->total = $productoCarrito->cantidad * $productoCarrito->precio;
            }
            //var_dump($productop->total);exit;
            $validaGuardaProductoP = $productop->save();

            $item = array();
            $item['name'] = $productoCarrito->titulo;

            if($productoCarrito->promo && $productoCarrito->promo>0){
                if($cupon_id){
                    $pro_sum = $productoCarrito->promo*$porcent;
                    $totales_des = $productoCarrito->promo - $pro_sum;
                    $item['unit_price'] = $totales_des*100;
                    $item['unit_price'] = intval($item['unit_price']);
//                }elseif ($puntos_descuento) {
//                    $item['unit_price'] = intval(($productoCarrito->promo - $desc_punt_div)*100);
                } else{
                    $item['unit_price'] = intval($productoCarrito->promo*100);
                }
            }elseif ($cupon_global && !$productoCarrito->promo>=0) {
                $preciobruto= $productoCarrito->precio - (($cupon_global->porcentaje / 100) * $productoCarrito->precio);
                if($cupon_id){
                    $pro_sum = $preciobruto*$porcent;
                    $totales_des = $preciobruto - $pro_sum;
                    $item['unit_price'] = $totales_des*100;
                    $item['unit_price'] = intval($item['unit_price']);
                    $total_libros += intval($item['unit_price']);
//                }elseif ($puntos_descuento) {
//                    $item['unit_price'] = intval(($preciobruto - $desc_punt_div)*100);
//                    $total_libros += intval(($preciobruto - $desc_punt_div)*100);
                } else{
                    $item['unit_price'] = intval($preciobruto*100);
                    $total_libros += intval($preciobruto*100);
                }       
            }else{
                if($cupon_id){
                    $pro_sum = $productoCarrito->precio*$porcent;
                    $totales_des = $productoCarrito->precio - $pro_sum;
                    $item['unit_price'] = $totales_des*100;
                    $item['unit_price'] = intval($item['unit_price']);
//                }elseif ($puntos_descuento) {
//                    $item['unit_price'] = intval(($productoCarrito->precio - $desc_punt_div)*100);
                } else{
                    $item['unit_price'] = intval($productoCarrito->precio*100);
                }
            }
            $item['quantity'] = $productoCarrito->cantidad;
            $items[]=$item;
            //var_dump($item);exit;
            if(!$validaGuardaProductoP){
                $this->transaction->rollback();
                return [
                    'exito' => 0,
                    'mensaje' => 'Error finalizando compra, productos pedido',
                ];
            }
        }
        $this->transaction->commit();
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");
        $productos = array();
        $subtotal = 0;
        foreach($this->obtieneProductos() as $producto){
            if($producto->promo && $producto->promo>0)
            {
                $precio = $producto->promo;
            }elseif ($cupon_global && !$productoCarrito->promo>=0) 
            {
                $preciobruto= $producto->precio - (($cupon_global->porcentaje / 100) * $producto->precio);
                $precio = $preciobruto;
            }else{
                $precio = $producto->precio;
            }
            if($cupon_id){
                $des = $precio * $porcent; $precio = $precio - $des; 
            }
//            if($puntos_descuento){ 
//                $precio = $precio - $desc_punt_div; 
//            }
            $producto_pago = new Item();
            $producto_pago->setName($producto->titulo)
                ->setCurrency('MXN')
                ->setQuantity($producto->cantidad)
                ->setSku($producto->libro)
                ->setPrice($precio);
            $subtotal += $precio*$producto->cantidad;
            array_push($productos,$producto_pago);
        }
        $itemList = new ItemList();
        $itemList->setItems($productos);

        $details = new Details();
        $details->setShipping($paquete)->setSubtotal($subtotal);

        $amount = new Amount();
        $amount->setCurrency('MXN')
            ->setTotal($subtotal + $paquete)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription('Compra de Libro(s)')
            ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(Yii::$app->urlManager->createAbsoluteUrl(['checkout/confirmacionpaypal', 'success' => 'true']))
            ->setCancelUrl(Yii::$app->urlManager->createAbsoluteUrl(['checkout/ejecutarfalse', 'success' => 'false']));

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        $request = clone $payment;
        //var_dump($request);exit;

        try {
            $apiContext = $this->getApiContext();
            $payment->create($apiContext);
        } catch (Exception $ex) {
            return false;
        }
        $approvalUrl = $payment->getApprovalLink();
        //var_dump($approvalUrl);exit;

        $connection = \Yii::$app->db;
        $this->transaction = $connection->beginTransaction();
        $token = $payment->getToken($approvalUrl);
        $datosPagot = new PagoTienda();
        $datosPagot->pedido_libro_id = $pedido->id;
        $datosPagot->orden_id = $token;
        $datosPagot->payment_method = 'paypal';
        $datosPagot->monto = $payment->transactions[0]->amount->total;
        $datosPagot->referencia = 'Paypal';
        $datosPagot->barcode_url = 'No Barcode';
        //return $datosPagot ;exit;
        $validaDatosPagot = $datosPagot->save();
        //var_dump($datosPago);exit;
        if(!$validaDatosPagot){
            $this->transaction->rollback();
            return [
                'exito' => 0,
                'mensaje' => 'Error finalizando compra, datos del pago'
            ];
        }
        $pedido->pago_tienda_id = $datosPagot->id;
        $pedido->costo_total = $datosPagot->monto;
        $pedido->estado_pedido_id = 2;
        $validaGuardaPedido = $pedido->save();

        if(!$validaGuardaPedido){
            $this->transaction->rollback();
            return [
                'exito' => 0,
                'mensaje' => 'Error actualizando el carrito, pedido'
            ];
        }

        $productos_carro_borrar = LibroCarrito::find()->where('carrito_id='.$carrito->id)->all();
        foreach ($productos_carro_borrar as $producto_carro) {
            if(! $producto_carro->delete()){
                $this->transaction->rollback();
                return [
                    'exito' => 0,
                    'mensaje' => 'Error actualizando el carrito'
                ];
            }
        }
        $this->transaction->commit();
        return $approvalUrl;
    }

    private function getApiContext(){
        $datos = parse_ini_file('../paypal.ini');
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $datos['client_id'],     // ClientID
                $datos['secret']      // ClientSecret
            )
        );
        $apiContext->setConfig(
            array(
                'mode' => $datos['mode'],
            )
        );
        return $apiContext;
    }
    
    public function modificarProducto($producto, $cantidad = false){
        
        $cookie_id = Yii::$app->getRequest()->getCookies()->getValue('lectorum_cart');
        if($cookie_id){
            $carrito = Carrito::findOne(['cookie_id' => $cookie_id]);
            $producto_carro = LibroCarrito::find()->where(['libro_id' => $producto, 'carrito_id' => $carrito->id])->one();
            if ($producto_carro) {
                if ($cantidad) {
                    $producto_carro->cantidad = $cantidad;
                    if ($producto_carro->save()) {
                        return [
                            'exito' => 1,
                            'mensaje' => 'Exito al actualizar el carro',
                            'cantidad' => $cantidad,
                        ];
                    } else {
                        return [
                            'exito' => 0,
                            'mensaje' => 'Error al guardar su petición',
                        ];
                    }
                } else {
                    return [
                        'exito' => 0,
                        'mensaje' => 'No se encontro alguna cantidad del producto en el carrito'
                    ];
                }
            } else {
                return [
                    'exito' => 0,
                    'mensaje' => 'No se encontro el producto en el carrito'
                ];
            }
        } else {
            return [
                'exito' => 0,
                'mensaje' => 'No se encontro el registro del carrito'
            ];
        }
    }
}
