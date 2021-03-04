<?php

namespace backend\controllers;

use yii\helpers\Url;

use Yii;
use backend\models\Eventos;
use backend\models\Editorial;
use backend\models\EventosForm;
use backend\models\eventosSearch;
use backend\models\LibroCarrito;
use backend\models\Libro;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EventosController implements the CRUD actions for Eventos model.
 */
class PagosController extends Controller
{

    /**
     * Lists all Eventos models.
     * @return mixed
     */
    public function actionPagar()
    {
        $subtotal = Yii::$app->request->get('subtotal');
        return $this->render('pagar', [
            'subtotal' => $subtotal,
        ]);
    }
    
    public function actionDatos()
    {
        /*Agregar al carrito*/
        if(!Yii::$app->request->isAjax){
            return Yii::$app->getResponse()->redirect(Yii::$app->homeUrl);
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $params = Yii::$app->request->getBodyParams();
        
        $descuento = $params['cupon']['descuento'];
        $cupon_id = $params['cupon']['id'];
        $porcent = floatval($params['cupon']['porcentaje']);
        
        if(empty($params)){
            return [
                'exito' => 0,
                'mensaje' => 'Error finalizando compra, parametros'
            ];
        }
        $productosCarrito = LibroCarrito::find()
            ->select(['libro_carrito.*', 'libro.titulo AS titulo' , 'libro.pvp AS precio', 'libro.promo AS promo'])
            ->from('libro_carrito')
            ->join('INNER JOIN', 'libro', 'libro.id = libro_carrito.libro_id')
            ->where(['libro_carrito.carrito_id' => $params['pago']['carrito'], 'libro.editorial_id' => $params['pago']['editorial']])
            ->all();

        $items = array();
        
        foreach ($productosCarrito as $productoCarrito) {
            
            $libro = Libro::find()->where(['id' => $productoCarrito->libro_id])->one();
            

            $item = array();
            $item['name'] = $productoCarrito->titulo;
            if($productoCarrito->promo && $productoCarrito->promo>0){
                if($cupon_id){
                    $pro_sum = $productoCarrito->promo*$porcent;
                    $totales_des = $productoCarrito->promo - $pro_sum;
                    $item['unit_price'] = $totales_des*100;
                    $item['unit_price'] = intval($item['unit_price']);
                    $total_libros += intval($item['unit_price']);
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
                } else{
                    $item['unit_price'] = intval($preciobruto*100);
                    $total_libros += intval($preciobruto*100);
                }       
            } else{
                if($cupon_id != 0){
                    $pro_sum = $productoCarrito->precio*$porcent;
                    $totales_des = $productoCarrito->precio - $pro_sum;
                    $item['unit_price'] = $totales_des*100;
                    $item['unit_price'] = intval($item['unit_price']);
                    $total_libros += intval($item['unit_price']);
                } else{
                    
                    $item['unit_price'] = $productoCarrito->precio*100;
                    $total_libros += $productoCarrito->precio*100;
                }
            }
            $item['quantity'] = $productoCarrito->cantidad;
            $items[]=$item;
        }
        
        $ini = parse_ini_file("../private.ini");
        $baseurl = Url::base(true);
        $editorial = Editorial::find()->where(['id' => $params['pago']['editorial']])->one();
        if ($baseurl == 'http://localhost/uppl/backend/web') {
            $key = $ini['privateKey'];
        } else {
            $key = $ini[$editorial->clave.'private'];
        }
        if (!$key){
            return [
                'exito' => 0,
                'mensaje' => 'No se encontró la llave.',
            ];
        }
        \Conekta\Conekta::setApiKey($key);
        \Conekta\Conekta::setApiVersion("2.0.0");
        try{
            $order = \Conekta\Order::create(
                array(
                    'currency' => 'MXN',
                    'customer_info' => array(
                        'name'=> $params['pago']['nombre'],
                        'email'=> $params['pago']['email'],
                        'phone' => '+52'.$params['pago']['telefono']
                    ),
                    
                    "shipping_lines" => array(
                        array(
                            "amount" => '0',
                            "carrier" => "FEDEX"
                        )
                    ),
                    "shipping_contact" => array(
                        "address" => array(
                            "street1" => $params['pago']['calle'],
                            "postal_code" => $params['pago']['cp'],
                            "country" => "MX"
                        )
                    ),
                    'line_items' => $items,
                    'charges' => array(
                        array(
                            'payment_method' => array(
                                'type' => 'card',
                                "token_id" => $params['token']['id']
                            )
                        )
                    )
                )
            );
            
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
        
        return [
            'exito' => 1,
            'mensaje' => 'La compra ha finalizado de forma exitosa',
            'order_id' => $order->id,
            'order_monto' => $order->amount/100,
            'order_codigo' => $order->charges[0]->payment_method->auth_code,
            'order_numeros' => $order->charges[0]->payment_method->last4,
            'order_marca' => $order->charges[0]->payment_method->brand,
            'order_tipo' => $order->charges[0]->payment_method->type,
        ];
    }
    
}
