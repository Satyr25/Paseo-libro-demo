<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Json;
$ini = parse_ini_file("../private.ini");
?>
<?php
$list = Yii::$app->request->BaseUrl.'/images/igual.png';
?>

<?php
//datos de ejemplo para probar que pueden admitir desde una BD
$portada = Yii::$app->request->BaseUrl.'/images/PortadaNoDisponible.png';

$iconoc = Yii::$app->request->BaseUrl.'/images/close_icon.png';
$totalp = 0;
$envio = 0;
$usuario_id = Yii::$app->user->identity->id;
$usuario = Yii::$app->user->identity;


?>
<div class="checkout-desk">
    <div class="container container-pasos">
        <div class="row row-proceso-checkout">
            <div class="col-md-12">
                <span class="paso-checkout-proceso paso-proceso-seleccion" id="checkout-proceso-1">1</span>
                <span class="separador-pasos"></span>
                <span class="paso-checkout-proceso" id="checkout-proceso-2">2</span>
                <span class="separador-pasos"></span>
                <span class="paso-checkout-proceso" id="checkout-proceso-3">3</span>
                <span class="separador-pasos"></span>
                <span class="paso-checkout-proceso" id="checkout-proceso-4">4</span>
            </div>
            <div class="col-md-12">
                <span class="texto-checkout-proceso">Carrito</span>
                <span class="separador-pasos-texto"></span>
                <span class="texto-checkout-proceso">Información</span>
                <span class="separador-pasos-texto"></span>
                <span class="texto-checkout-proceso">Envío</span>
                <span class="separador-pasos-texto"></span>
                <span class="texto-checkout-proceso">Pagos</span>
            </div>
        </div>
    </div>
    <div class="container container-checkout-1" id="container-checkout-1"> 
        <div class="row">
            <div class="col-md-8 col-checkout-carrito" id="checkout-1-carrito">
                <div class="row">
                    <div class="col-md-12">
                        <p class="checkout-titulo-carrito">Carrito de compra</p>
                    </div>
                </div>
                <div class="row row-subtitulos-carrito" id="checkout-1-subtotal">
                    <div class="col-sm-6">
                        <p>Producto</p>
                    </div>
                    <div class="col-sm-2">
                        <p>Precio</p>
                    </div>
                    <div class="col-sm-2">
                        <p>Cantidad</p>
                    </div>
                    <div class="col-sm-2">
                        <p>total</p>
                    </div>
                </div>

            <?php foreach($libros as $libro){ ?> 
                <div class="row row-datos-carrito" id="libro-checkout1-<?=$libro->libro?>">
                    <div class="col-sm-2">
                    <?php if ($libro->portada){ ?> 
                        <?= Html::img('@web/images/'.$libro->portada, ['class' => 'img-responsive']); ?>
                    <?php } else { ?> 
                        <?= Html::img('@web/images/portada_default.jpg', ['class' => 'img-responsive']); ?>
                    <?php }?>
                    </div>
                    <div class="col-sm-4">
                        <p><?= ucfirst(mb_strtolower($libro->titulo)) ?></p>
                    </div>
                    <div class="col-sm-2">
                        <p>$ <?= $libro->precio ?></p>
                    </div>
                    <div class="col-sm-2">
                       <span class="contenedor-cantidad">
                            <span class="btn-cantidad menos" data-producto="<?=$libro->libro?>">-</span>

                            <span class="span-cantidad" id="span-cantidad-<?=$libro->libro?>"><?= $libro->cantidad ?></span>

                            <span class="btn-cantidad mas" data-producto="<?=$libro->libro?>">+</span>
                        </span>
                    </div>
                    <div class="col-sm-2">
                        <p id="total-libro-<?=$libro->libro?>">$ <?= number_format($libro->precio*$libro->cantidad, 2) ?></p>
                    </div>
                    <?= Html::button("".Html::img('@web/images/iconos/Quitar.png', ['class' => 'img-responsive img-quitar'])."" , ['class' => 'btn-quitar-paso1 btn-quitar-checkout', 'value' => $libro->libro]) ?>
                </div>
            <?php } ?>


            </div>
            <div class="col-md-4 col-checkout-carrito">
                <div class="row">
                    <div class="col-md-12">
                        <p class="checkout-titulo-carrito">Subtotal</p>
                    </div>
                </div>
                <div class="row row-cupon-form">
                    <div class="col-xs-12">
                        <p class="sub-cupon-3">¿Tienes un cupón de descuento?</p>
                    </div>
                    <div class="col-md-7">
                        <?php $cupForm = ActiveForm::begin() ?>
                        <?= $cupForm->field($descuento, 'codigo')->textInput(['id' => 'ingresar-cupon'])->label(false) ?>
                    </div>
                    <div class="col-md-5 col-checkout2-cupon">
                        <button class="btn-accion canjear-cupon" id="canjear-cupon">Canjear</button>
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
                <div class="row row-subtotal-form">
                    <div class="col-md-7">
                        <p class="cupon-subtotal-subtitulo">Subtotal</p>
                    </div>
                    <div class="col-md-5">
                        <p class="cupon-subtotal" id="cupon-subtotal" >
                            <?php foreach($libros as $libro){
                                static $subtotal;
                                $subtotal += $libro->cantidad*$libro->precio;
                            } ?>
                            $ <?= number_format($subtotal, 2) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-offset-9 col-md-3 col-seguir-comprando">
                        <?= Html::a('Seguir Comprando', ['site/index'], ['class' => 'btn-seguir-comprando']) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-offset-6 col-md-6 col-seguir-comprando">
                        <?= Html::button('Ir a Pagar', ['class' => 'btn-ir-pagar', 'id' => 'btn-ir-pagar']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row checkout-beneficios">
            <div class="col-md-4">
                <?= Html::img('@web/images/compraSegura.png', ['alt' => 'compra-segura', 'class' => 'img-beneficios']) ?>
                <p class="texto-beneficios">Tu compra 100% segura</p>
            </div>
            <div class="col-md-4">
                <?= Html::img('@web/images/Envios.png', ['alt' => 'Envios', 'class' => 'img-beneficios']) ?>
                <p class="texto-beneficios">Envíos a toda la República</p>
            </div>
            <div class="col-md-4">
                <?= Html::img('@web/images/PAGO.png', ['alt' => 'formas de pago', 'class' => 'img-beneficios']) ?>
                <p class="texto-beneficios">Paga con tarjeta de crédito o débito</p>
            </div>
        </div>
    </div>


    <div class="container container-checkout-2" id="container-checkout-2">
        <div class="row">
            <div class="col-md-6" id="container-subcheckout-2">
                <div class="row col-checkout-contacto">
                    <div class="col-md-12">
                        <p class="checkout-titulo-carrito">Información de Contacto</p>
                    </div>
                    <div class="col-md-6">
                        <p>Información de Contacto</p>
                    </div>
                    <div class="col-md-6 text-center">
                    <?php if (Yii::$app->user->isGuest) { ?>
                        <p>¿Ya tienes una cuenta?</p>
                        <a href="javascript:;" class="parrafo-info iniciar">Iniciar Sesión</a>
                    </div>
                    <div class="col-md-12">
                    <?php $clienteForm = ActiveForm::begin() ?>
                        <?= $clienteForm->field($cliente, 'email')->textInput(['class' => 'checkout-correo checkout-form', 'id' => 'checkout-correo', 'placeholder' => 'Correo electrónico'])->label(false) ?>
                    </div>
                    <div class="col-md-12 col-check-ofertas">
                        <input type="checkbox" class="check-ofertas" id="check-ofertas">
                        <span class="check-ofertas-texto">Mantenerme informado sobre novedades y ofertas exclusivas</span>
                    </div>
                </div>
                <div class="row col-checkout-datos">
                    <div class="col-md-12">
                        <p class="checkout-titulo-carrito">Datos de Envío</p>
                        <p>Dirección de envío</p>
                        <p class="checkout-datos-nota">Recuerda que es necesario tener tu información completa, incluyendo calle, número y código postal</p>
                    </div>
                    <div class="col-md-6">
                        <?= $clienteForm->field($cliente, 'nombre')->textInput(['class' => 'checkout-nombre checkout-form', 'id' => 'checkout-nombre', 'placeholder' => 'Nombre'])->label(false) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $clienteForm->field($cliente, 'apellidos')->textInput(['class' => 'checkout-apellido checkout-form', 'id' => 'checkout-apellido', 'placeholder' => 'Apellido'])->label(false) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $clienteForm->field($cliente, 'calle')->textInput(['class' => 'checkout-calle checkout-form', 'id' => 'checkout-calle', 'placeholder' => 'Calle y Numero'])->label(false) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $clienteForm->field($cliente, 'colonia')->textInput(['class' => 'checkout-colonia checkout-form', 'id' => 'checkout-colonia', 'placeholder' => 'Colonia'])->label(false) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $clienteForm->field($cliente, 'ciudad')->textInput(['class' => 'checkout-ciudad checkout-form', 'id' => 'checkout-ciudad', 'placeholder' => 'Ciudad'])->label(false) ?>
                    </div>

                    <div class="col-md-4">
                        <div class="row">
                           <div class="col-md-12">
                                <span class="span-drop-checkout">País/Región</span>
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?= $clienteForm->field($cliente, 'paises_id',['template'=>"<div>\n{error}\n{input}\n</div>"])
                                    ->widget(Select2::classname(), [
                                     'data' => $paises,
                                     'language' => 'es',
                                     'options' => ['placeholder' => 'País*', 'id' => 'pais-id', 'value' => 42 , 'class' => 'checkout-form'],
                                     'pluginOptions' => [
                                         'allowClear' => false
                                    ],
                                    ])->label(false) 
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                         <div class="row">
                            <div class="col-md-12">
                                 <span class="span-drop-checkout">Estado</span>
                             </div>
                         </div>
                         <div class="row">
                            <div class="col-md-12">
                                 <?= $clienteForm->field($cliente, 'estados_mundo_id')->widget(DepDrop::classname(), [
                                    'type' => DepDrop::TYPE_SELECT2,
                                    'options' => ['id' => 'estado-id', 'class' => 'checkout-form'],
                                    'pluginOptions' => [
                                        'depends' => ['pais-id'],
                                        'initialize' => true,
                                        'placeholder' => 'Estado',
                                        'url' => Url::to(['/checkout/estado'])
                                    ]
                                ])->label(false);
                                ?>
                            </div>
                         </div>
                    </div>
                    <div class="col-md-4">
                        <?= $clienteForm->field($cliente, 'cp')->textInput(['class' => 'checkout-cp checkout-form', 'id' => 'checkout-cp', 'placeholder' => 'Código Postal', 'maxlength'=> 5])->label(false) ?>
                    </div>
                    <div class="col-md-12">
                       <?= $clienteForm->field($cliente, 'telefono')->textInput(['class' => 'checkout-telefono checkout-form', 'id' => 'checkout-telefono', 'placeholder' => 'Numero de Celular'])->label(false) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $clienteForm->field($cliente, 'publicidad')->checkbox(['class' => 'checkout-publicidad', 'id' => 'checkout-publicidad'])->label('Guardar información') ?>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>

                <div class="row row-datos-botones">
                    <div class="col-md-4">
                        <?= Html::button(Html::img('@web/images/regresar.png', ['class' => 'img-responsive img-checkout-regresar']).'Regresar a carrito', ['class' => 'btn-regresar btn-regresar-carrito', 'id' => 'btn-regresar-carrito']) ?>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-6">
                        <?=  Html::button('Continuar con envíos', ['class' => 'checkout-continuar-envio btn-continuar-envio btn-disabled', 'id' => 'btn-continuar-envio', 'disabled' => 'disabled'])?>
                    </div>
                </div>
        <?php } else { ?>
                        <a href="<?= Url::to(['/cliente/index', 'id' => Yii::$app->user->id]) ?>" class="parrafo-info iniciar">Modificar datos</a>
                    </div>
                    <div class="col-md-12">
                    <?php $clienteForm = ActiveForm::begin() ?>
                        <?= $clienteForm->field($cliente, 'email')->textInput(['class' => 'checkout-correo checkout-form', 'id' => 'checkout-correo', 'placeholder' => 'Correo electrónico', 'value' => $usuario->correo, 'disabled' => true])->label(false) ?>
                    </div>
                    <div class="col-md-12 col-check-ofertas">
                        <input type="checkbox" class="check-ofertas" id="check-ofertas">
                        <span class="check-ofertas-texto">Mantenerme informado sobre novedades y ofertas exclusivas</span>
                    </div>
                </div>
                <div class="row col-checkout-datos">
                    <div class="col-md-12">
                        <p class="checkout-titulo-carrito">Datos de Envío</p>
                        <p>Dirección de envío</p>
                        <p class="checkout-datos-nota">Recuerda que es necesario tener tu información completa, incluyendo calle, número y código postal</p>
                    </div>
                    <div class="col-md-6">
                        <?= $clienteForm->field($cliente, 'nombre')->textInput(['class' => 'checkout-nombre checkout-form', 'id' => 'checkout-nombre', 'placeholder' => 'Nombre', 'value' => $usuario->nombre, 'disabled' => true])->label(false) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $clienteForm->field($cliente, 'apellidos')->textInput(['class' => 'checkout-apellido checkout-form', 'id' => 'checkout-apellido', 'placeholder' => 'Apellido', 'value' => $usuario->ap_paterno.' '.$usuario->ap_materno, 'disabled' => true])->label(false) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $clienteForm->field($cliente, 'calle')->textInput(['class' => 'checkout-calle checkout-form', 'id' => 'checkout-calle', 'placeholder' => 'Calle y Numero', 'value' => $clientes->calle, 'disabled' => true])->label(false) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $clienteForm->field($cliente, 'colonia')->textInput(['class' => 'checkout-colonia checkout-form', 'id' => 'checkout-colonia', 'placeholder' => 'Colonia', 'value' => $clientes->colonia, 'disabled' => true])->label(false) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $clienteForm->field($cliente, 'ciudad')->textInput(['class' => 'checkout-ciudad checkout-form', 'id' => 'checkout-ciudad', 'placeholder' => 'Ciudad', 'value' => $clientes->ciudad, 'disabled' => true])->label(false) ?>
                    </div>

                    <div class="col-md-4">
                        <div class="row">
                           <div class="col-md-12">
                                <span class="span-drop-checkout">País/Región</span>
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?= $clienteForm->field($cliente, 'paises_id',['template'=>"<div>\n{error}\n{input}\n</div>"])
                                    ->widget(Select2::classname(), [
                                     'data' => $paises,
                                     'language' => 'es',
                                     'options' => ['placeholder' => 'País*', 'id' => 'pais-id', 'value' => $clientes->paises_id , 'class' => 'checkout-form', 'disabled' => true],
                                     'pluginOptions' => [
                                         'allowClear' => false
                                    ],
                                    ])->label(false) 
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                         <div class="row">
                            <div class="col-md-12">
                                 <span class="span-drop-checkout">Estado</span>
                             </div>
                         </div>
                         <div class="row">
                            <div class="col-md-12">
                                 <?= $clienteForm->field($cliente, 'estados_mundo_id')->widget(DepDrop::classname(), [
                                    'type' => DepDrop::TYPE_SELECT2,
                                    'options' => ['id' => 'estado-id', 'class' => 'checkout-form', 'value' => $clientes->estados_mundo_id, 'disabled' => true],
                                    'pluginOptions' => [
                                        'depends' => ['pais-id'],
                                        'initialize' => true,
                                        'placeholder' => 'Estado',
                                        'url' => Url::to(['/checkout/estado'])
                                    ]
                                ])->label(false);
                                ?>
                            </div>
                         </div>
                    </div>
                    <div class="col-md-4">
                        <?= $clienteForm->field($cliente, 'cp')->textInput(['class' => 'checkout-cp checkout-form', 'id' => 'checkout-cp', 'placeholder' => 'Código Postal', 'maxlength'=> 5, 'value' => $clientes->cp, 'disabled' => true])->label(false) ?>
                    </div>
                    <div class="col-md-12">
                       <?= $clienteForm->field($cliente, 'telefono')->textInput(['class' => 'checkout-telefono checkout-form', 'id' => 'checkout-telefono', 'placeholder' => 'Numero de Celular', 'value' => $clientes->telefono, 'disabled' => true])->label(false) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $clienteForm->field($cliente, 'publicidad')->checkbox(['class' => 'checkout-publicidad', 'id' => 'checkout-publicidad'])->label('Guardar información') ?>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>

                <div class="row row-datos-botones">
                    <div class="col-md-4">
                        <?= Html::button(Html::img('@web/images/regresar.png', ['class' => 'img-responsive img-checkout-regresar']).'Regresar a carrito', ['class' => 'btn-regresar btn-regresar-carrito', 'id' => 'btn-regresar-carrito']) ?>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-6">
                        <?=  Html::button('Continuar con envíos', ['class' => 'checkout-continuar-envio btn-continuar-envio', 'id' => 'btn-continuar-envio'])?>
                    </div>
                </div>
                <?php } ?>
            </div>


            <div class="col-md-6 misma-altura-3" id="container-subcheckout-3">
                <div class="row col-checkout-contacto">
                    <div class="col-md-12">
                        <p class="checkout-titulo-carrito">Resumen</p>
                        <div class="row row-resumen">
                            <div class="col-md-3">
                                <p class="datos-subtitulo">Contacto</p>
                            </div>
                            <div class="col-md-6" id="resumen-correo">
                                <p class="datos-resumen resumen-correo-p" id="resumen-correo-p"></p>
                            </div>
                            <div class="col-md-3">
                                <?= Html::button('Cambiar', ['class' => 'btn-regresar btn-regresar-info', 'id' => 'btn-regresar-info5']) ?>
                            </div>
                        </div>
                        <hr class="datos-hr">
                        <div class="row row-resumen">
                            <div class="col-md-3">
                                <p class="datos-subtitulo">Enviar a</p>
                            </div>
                            <div class="col-md-6">
                                <p class="datos-resumen resumen-enviar-p" id="resumen-envio-p" ></p>
                            </div>
                            <div class="col-md-3">
                                <?= Html::button('Cambiar', ['class' => 'btn-regresar btn-regresar-info', 'id' => 'btn-regresar-info2']) ?>
                            </div>
                        </div> 
                    </div>
                </div>
                <div class="row col-checkout-contacto">
                    <div class="col-md-12">
                        <p class="checkout-titulo-carrito">Envíos</p>
                        <div class="row row-resumen">
                            <div class="col-md-3 col-envios-radio">
                                <input type="radio" name="radio-envio" value="estandar" class="envios-radio envio-standard" id="envio-standard" data-envio="50.50" data-tipo="">
                                <span class="datos-subtitulo" id="tipo-envio-std">Estándar</span>
                            </div>
                            <div class="col-md-5" id="resumen-correo">
                                <p class="datos-resumen" id="dias-std">3 a 5 días hábiles</p>
                            </div>
                            <div class="col-md-4">
                                <p class="datos-subtitulo envio-std-costo" id="envio-std-costo" >$ 50.00</p>
                            </div>
                        </div>
                        <hr class="datos-hr">
                        <div class="row row-resumen">
                            <div class="col-md-3 col-envios-radio">
                                <input type="radio" name="radio-envio" value="express" class="envios-radio envio-express" id="envio-express" data-envio="65.00" data-tipo="">
                                <span class="datos-subtitulo" id="tipo-envio-exp">Express</span>
                            </div>
                            <div class="col-md-5">
                                <p class="datos-resumen" id="dias-exp">1 a 3 días hábiles</p>
                            </div>
                            <div class="col-md-4">
                                <p class="datos-subtitulo envio-exp-costo" id="envio-exp-costo">$ 65.00</p>
                            </div>
                        </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <?= Html::button(Html::img('@web/images/regresar.png', ['class' => 'img-responsive img-checkout-regresar']).'Regresar a información', ['class' => 'btn-regresar btn-regresar-info', 'id' => 'btn-regresar-info']) ?>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-6">
                        <?=  Html::button('Continuar con pago', ['class' => 'checkout-continuar-envio  btn-continuar-pago btn-disabled', 'id' => 'btn-continuar-pago'])?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 misma-altura-3" id="container-subcheckout-4">
                <div class="row col-checkout-contacto">
                    <div class="col-md-12">
                        <p class="checkout-titulo-carrito">Resumen</p>
                        <div class="row row-resumen">
                            <div class="col-md-3">
                                <p class="datos-subtitulo">Contacto</p>
                            </div>
                            <div class="col-md-6" id="resumen-correo">
                                <p class="datos-resumen resumen-correo-p2" id="resumen-correo-p2"></p>
                            </div>
                            <div class="col-md-3">
                                <?= Html::button('Cambiar', ['class' => 'btn-regresar btn-regresar-info', 'id' => 'btn-regresar-info3']) ?>
                            </div>
                        </div>
                        <hr class="datos-hr">
                        <div class="row row-resumen">
                            <div class="col-md-3">
                                <p class="datos-subtitulo">Enviar a</p>
                            </div>
                            <div class="col-md-6">
                                <p class="datos-resumen resumen-enviar-p" id="resumen-envio-p2" ></p>
                            </div>
                            <div class="col-md-3">
                                <?= Html::button('Cambiar', ['class' => 'btn-regresar btn-regresar-info', 'id' => 'btn-regresar-info4']) ?>
                            </div>
                        </div> 
                        <hr class="datos-hr">
                        <div class="row row-resumen">
                            <div class="col-md-3">
                                <p class="datos-subtitulo">Envío</p>
                            </div>
                            <div class="col-md-6">
                                <p class="datos-resumen resumen-envio-p3" id="resumen-envio-p3"></p>
                            </div>
                            <div class="col-md-3">
                                <?= Html::button('Cambiar', ['class' => 'btn-regresar btn-regresar-envio', 'id' => 'btn-regresar-envio']) ?>
                            </div>
                        </div> 
                    </div>
                </div>
                <div class="row col-checkout-contacto">
                    <div class="col-md-12">
                        <p class="checkout-titulo-carrito">Pago</p>
                        <p class="parrafo-info" >Todas las transacciones son seguras y estan cifradas</p>
                        <div class="row row-resumen-pago">
                            <div class="col-md-7 col-envios-radio">
                                <input type="radio" name="radio-pago" value="tarjeta" class="pago-radio radio-tarjeta" id="radio-tarjeta" checked>
                                <span class="datos-subtitulo">Tarjeta de crédito o débito</span>
                            </div>
                            <div class="col-md-5">
                                <?= Html::img('@web/images/iconos/visa.png', ['class' => 'img-responsive pago-imagen']) ?>
                                <?= Html::img('@web/images/iconos/cc-mastercard.png', ['class' => 'img-responsive pago-imagen']) ?>
                                <?= Html::img('@web/images/iconos/amex.png', ['class' => 'img-responsive pago-imagen']) ?>
                            </div>
                        </div>
                        <div class="row row-tarjeta-pago" id="row-tarjeta-pago">
                            <form action="" method="POST" id="card-form">
                            <div class="col-md-12">
                                <input type="text" class="checkout-form tarjeta-form" id="tarjeta-num" placeholder="Número de Tarjeta" data-conekta="card[number]">
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="checkout-form tarjeta-form" id="tarjeta-titular" placeholder="Nombre del Titular" data-conekta="card[name]">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="checkout-form tarjeta-form" id="tarjeta-vencimiento" placeholder="Fecha de Vencimiento MM/AA">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="checkout-form tarjeta-form" id="tarjeta-codigo" placeholder="Código de Seguridad" maxlength="4" data-conekta="card[cvc]">
                            </div>
                                <input type="hidden" id="tarjeta-mes" data-conekta="card[exp_month]">
                                <input type="hidden" id="tarjeta-anio" data-conekta="card[exp_year]">
                            </form>
                        </div>
                        <div class="row row-resumen-pago hidden">
                            <div class="col-md-7 col-envios-radio">
                                <input type="radio" name="radio-pago" value="efectivo" class="pago-radio radio-efectivo" id="radio-efectivo">
                                <span class="datos-subtitulo">Pagos en Efectivo en OXXO</span>
                            </div>
                            <div class="col-md-5">
                                <?= Html::img('@web/images/iconos/oxxo.png', ['class' => 'img-responsive pago-imagen']) ?>
                            </div>
                        </div>
                        <div class="row row-resumen-pago hidden">
                            <div class="col-md-7 col-envios-radio">
                                <input type="radio" name="radio-pago" value="paypal" class="pago-radio radio-paypal" id="radio-paypal">
                                <span class="datos-subtitulo">Pagos con PayPal</span>
                            </div>
                            <div class="col-md-5">
                                <?= Html::img('@web/images/iconos/paypal.png', ['class' => 'img-responsive pago-imagen']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <?= Html::button(Html::img('@web/images/regresar.png', ['class' => 'img-responsive img-checkout-regresar']).'Regresar a envío', ['class' => 'btn-regresar btn-regresar-envio', 'id' => 'btn-regresar-envio2']) ?>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-6">
                        <?=  Html::button('Realizar pago', ['class' => 'checkout-continuar-envio btn-disabled btn-realizar-pago', 'id' => 'btn-realizar-pago', 'disabled' => 'disabled'])?>
                    </div>
                </div>
            </div>
            

            <div class="col-md-6 col-checkout-pedido ">
                <div class="row">
                    <div class="col-md-12 col-titulo-pedido">
                        <p class="checkout-titulo-carrito">Tu Pedido</p>
                    </div>
                    <?php static $i2 = 0; ?>
                    <?php foreach ($libros as $libro){ ?>
                    <?php $i2 += 1; ?>
                    <div class="row row-pedido-libro" id="libro-checkout2-<?=$libro->libro?>">
                        <div class="col-md-5 misma-altura-pedido">
                            <?php  if($libro->portada){ ?>
                                <?= Html::img('@web/images/'.$libro->portada, ['class' => 'img-responsive portada-checkout']) ?>
                            <?php } else { ?> 
                                <?= Html::img('@web/images/portada_default.jpg', ['class' => 'img-responsive portada-checkout']) ?>
                            <?php } ?>
                        </div>
                        <div class="col-md-7 misma-altura-pedido">
                            <p class="checkout2-titulo"><?= $libro->titulo ?></p>
                            <p class="checkout2-autor"><?= $libro->autor ?></p>
                            <p class="checkout2-precio"><?= $libro->precio ?></p>
                            <p class="checkout2-ed-clave hidden"><?= $libro->editorial_clave ?></p>
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="checkout2-cantidad">Cantidad:</p>
                                </div>
                                <div class="col-md-4">
                                    <span class="contenedor-cantidad">
                                        <span class="btn-cantidad menos" data-producto="<?=$libro->libro?>">-</span>

                                        <span class="span-cantidad" id="span-cantidad2-<?=$libro->libro?>"><?= $libro->cantidad ?></span>

                                        <span class="btn-cantidad mas" data-producto="<?=$libro->libro?>">+</span>
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <?= Html::button('Quitar', ['class' => 'btn-quitar-checkout', 'value' => $libro->libro]) ?>
                                </div>
                            </div>
                            <?php if($i2 == (count($libros))){ ?> 
                            <div class="row row-cupon-form2">
                                <div class="col-xs-12">
                                    <p class="sub-cupon-3">¿Tienes un cupón de descuento?</p>
                                </div>
                                <div class="col-md-12">
                                    <?php $cupForm = ActiveForm::begin() ?>
                                    <?= $cupForm->field($descuento, 'codigo')->textInput(['id' => 'ingresar-cupon2', 'placeholder' => 'Cupón de descuento'])->label(false) ?>
                                </div>
                                <div class="col-md-12">
                                    <button class="btn-accion canjear-cupon2" id="canjear-cupon2">Aplicar</button>
                                    <?php ActiveForm::end() ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p class="checkout-texto-subtotal">Sub total</p>
                    </div>
                    <div class="col-md-6">
                        <p class="checkout-numero-subtotal cupon-subtotal">$ <?= number_format($subtotal, 2) ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p class="checkout-texto-envio">Envío</p>
                    </div>
                    <div class="col-md-6">
                        <p class="checkout-numero-envio" id="checkout-numero-envio">*Pendiente Seleccionar</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <p class="checkout-texto-total">Total</p>
                    </div>
                    <div class="col-md-6">
                        <p class="checkout-numero-total" id="checkout-numero-total">$ <?= number_format($subtotal+$envio, 2) ?></p>
                    </div>
                    <div class="col-md-12 col-check-terminos">
                        <input type="checkbox" class="check-terminos" id="check-terminos">
                        <span class="check-ofertas-texto">Acepto los Términos y Condiciones</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 img-procesando">
            <div class="lds-facebook"><div></div><div></div><div></div></div>
        </div>
    </div>
</div>


<div class="checkout-mob">
    <div class="checkout-mob-1">
        <div class="col-xs-12 col-checkout-carrito" id="checkout-1-carrito">
            <div class="row">
                <div class="col-xs-12">
                    <p class="checkout-titulo-carrito">Carrito de compra</p>
                </div>
            </div>

        <?php foreach($libros as $libro){ ?> 
            <div class="row row-datos-carrito" id="libro-checkout3-<?= $libro->libro ?>">
                <div class="col-xs-7 mob-img-container">
                    <?php  if($libro->portada){ ?>
                        <?= Html::img('@web/images/'.$libro->portada, ['class' => 'img-responsive portada-checkout']) ?>
                    <?php } else { ?> 
                        <?= Html::img('@web/images/portada_default.jpg', ['class' => 'img-responsive portada-checkout']) ?>
                    <?php } ?>
                </div>
                <div class="col-xs-5">
                <p class="checkout2-cantidad">Cantidad:</p>
                   <span class="contenedor-cantidad">
                        <span class="btn-cantidad menos" data-producto="<?=$libro->libro?>">-</span>

                        <span class="span-cantidad" id="span-cantidad3-<?=$libro->libro?>"><?= $libro->cantidad ?></span>

                        <span class="btn-cantidad mas" data-producto="<?=$libro->libro?>">+</span>
                    </span>
                    <br>
                    <br>
                    <br>
                    <br>
                <?= Html::button('Quitar', ['class' => 'btn-quitar-checkout', 'value' => $libro->libro]) ?>
                </div>
            </div>
            <div class="row row-datos-carrito2">
                <div class="col-xs-12">
                    <p class="vendidos-carrusel-titulo"><?= ucfirst(mb_strtolower($libro->titulo)) ?></p>
                    <p class="vendidos-carrusel-autor"><?= ucfirst(mb_strtolower($libro->autor)) ?></p>
                    <p class="vendidos-carrusel-precio">$ <?= $libro->precio ?></p>
                </div>
            </div>
            <br>
        <?php } ?>
            <div class="row">
                <div class="col-xs-12 col-seguir-comprando">
                    <?= Html::a('Seguir Comprando', ['site/index'], ['class' => 'btn-seguir-comprando']) ?>
                    <p class="checkout-titulo-carrito">Subtotal</p>
                </div>
                <div class="row row-cupon-form">
                    <div class="col-xs-12">
                        <p class="sub-cupon-3">¿Tienes un cupón de descuento?</p>
                    </div>
                    <div class="col-xs-6 campo-cupon-mob">
                        <?php $cupForm = ActiveForm::begin() ?>
                        <?= $cupForm->field($descuento, 'codigo')->textInput(['id' => 'ingresar-cupon'])->label(false) ?>
                    </div>
                    <div class="col-xs-6 boton-cupon-mob">
                        <button class="btn-accion canjear-cupon" id="canjear-cupon">Canjear</button>
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <p class="cupon-subtotal-subtitulo">Subtotal</p>
                    </div>
                    <div class="col-xs-6">
                        <p class="cupon-subtotal">
                            $ <?= number_format($subtotal, 2) ?>
                        </p>
                    </div>
                    <div class="col-xs-12 col-seguir-comprando">
                        <?= Html::button('Ir a Pagar', ['class' => 'btn-ir-pagar', 'id' => 'btn-ir-pagar']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row checkout-beneficios">
            <div class="col-xs-4">
                <?= Html::img('@web/images/compraSegura.png', ['alt' => 'compra-segura', 'class' => 'img-beneficios']) ?>
                <p class="texto-beneficios">Tu compra 100% segura</p>
            </div>
            <div class="col-xs-4">
                <?= Html::img('@web/images/Envios.png', ['alt' => 'Envios', 'class' => 'img-beneficios']) ?>
                <p class="texto-beneficios">Envíos a toda la República</p>
            </div>
            <div class="col-xs-4">
                <?= Html::img('@web/images/PAGO.png', ['alt' => 'formas de pago', 'class' => 'img-beneficios']) ?>
                <p class="texto-beneficios">Paga con tarjeta de crédito o débito</p>
            </div>
        </div>
    </div>
    <div class="checkout-mob-2">
         <div class="row">
            <div class="col-xs-12 col-checkout-pedido">
                <div class="row">
                    <div class="col-xs-12 col-titulo-pedido">
                        <p class="checkout-titulo-carrito">Tu Pedido</p>
                    </div>
                    <?php foreach ($libros as $libro){ ?>
                    <div class="row row-pedido-libro2" id="libro-checkout4-<?=$libro->libro?>">
                        <div class="col-xs-6 img-checkout2-mob">
                            <?php  if($libro->portada){ ?>
                                <?= Html::img('@web/images/'.$libro->portada, ['class' => 'img-responsive portada-checkout']) ?>
                            <?php } else { ?> 
                                <?= Html::img('@web/images/portada_default.jpg', ['class' => 'img-responsive portada-checkout']) ?>
                            <?php } ?>
                        </div>
                        <div class="col-xs-6 datos-checkout2-mob">
                            <p class="vendidos-carrusel-titulo"><?= $libro->titulo ?></p>
                            <p class="vendidos-carrusel-autor"><?= $libro->autor ?></p>
                            <p class="vendidos-carrusel-precio"><?= $libro->precio ?></p>
                            <br>
                            <div class="col-xs-12 datos-checkout2-cantidad">
                            <p class="checkout2-cantidad">Cantidad:</p>
                            <br>
                                <span class="contenedor-cantidad">
                                    <span class="btn-cantidad menos" data-producto="<?=$libro->libro?>">-</span>

                                    <span class="span-cantidad" id="span-cantidad4-<?=$libro->libro?>"><?= $libro->cantidad ?></span>

                                    <span class="btn-cantidad mas" data-producto="<?=$libro->libro?>">+</span>
                                </span>
                                <br>
                                <br>
                                <br>
                                <div class="col-xs-12">
                                    <?= Html::button('Quitar', ['class' => 'btn-quitar-checkout', 'value' => $libro->libro]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="row row-cupon-form2">
                    <div class="col-xs-12">
                        <p class="sub-cupon-3">¿Tienes un cupón de descuento?</p>
                    </div>
                    <div class="col-xs-6 col-cupon-3">
                        <?php $cupForm = ActiveForm::begin() ?>
                        <?= $cupForm->field($descuento, 'codigo')->textInput(['id' => 'ingresar-cupon2', 'placeholder' => 'Cupón de descuento'])->label(false) ?>
                    </div>
                    <div class="col-xs-6 col-btn-cupon3">
                        <button class="btn-accion canjear-cupon2" id="canjear-cupon2">Aplicar</button>
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <p class="checkout-texto-subtotal">Sub total</p>
                    </div>
                    <div class="col-xs-6">
                        <p class="checkout-numero-subtotal cupon-subtotal">$ <?= number_format($subtotal, 2) ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <p class="checkout-texto-envio">Envío</p>
                    </div>
                    <div class="col-xs-6">
                        <p class="checkout-numero-envio" id="checkout-numero-envio">*Pendiente Seleccionar</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-6">
                        <p class="checkout-texto-total">Total</p>
                    </div>
                    <div class="col-xs-6">
                        <p class="checkout-numero-total" id="checkout-numero-total">$ <?= number_format($subtotal+$envio, 2) ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        
        
        <div class="row col-checkout-contacto">
            <div class="col-xs-12">
                <p class="checkout-titulo-carrito">Información de Contacto</p>
            </div>
            <div class="col-xs-6 info-contacto-mob">
                <p>Información de Contacto</p>
            </div>
            <div class="col-xs-6 text-center">
            <?php if(Yii::$app->user->isGuest){ ?>
               <a href="<?= Url::to(['/cliente/index', 'id' => $clientes->id])?>" class="parrafo-info" >Modificar datos</a>
            </div>
            <div class="col-xs-12">
<?php $clienteForm = ActiveForm::begin() ?>
                <?= $clienteForm->field($cliente, 'email')->textInput(['class' => 'checkout-correo checkout-form', 'id' => 'checkout-correo2', 'placeholder' => 'Correo electrónico'])->label(false) ?>
            </div>
            <div class="col-md-12 col-check-ofertas">
                <input type="checkbox" class="check-ofertas" id="check-ofertas2">
                <span class="check-ofertas-texto">Mantenerme informado sobre novedades y ofertas exclusivas</span>
            </div>
        </div>
        <div class="row col-checkout-datos">
            <div class="col-md-12">
                <p class="checkout-titulo-carrito">Datos de Envío</p>
                <p>Dirección de envío</p>
                <p class="checkout-datos-nota">Recuerda que es necesario tener tu información completa, incluyendo calle, número y código postal</p>
            </div>
            <div class="col-md-6">
                <?= $clienteForm->field($cliente, 'nombre')->textInput(['class' => 'checkout-nombre checkout-form', 'id' => 'checkout-nombre2', 'placeholder' => 'Nombre'])->label(false) ?>
            </div>
            <div class="col-md-6">
                <?= $clienteForm->field($cliente, 'apellidos')->textInput(['class' => 'checkout-apellido checkout-form', 'id' => 'checkout-apellido2', 'placeholder' => 'Apellido'])->label(false) ?>
            </div>
            <div class="col-md-12">
                <?= $clienteForm->field($cliente, 'calle')->textInput(['class' => 'checkout-calle checkout-form', 'id' => 'checkout-calle2', 'placeholder' => 'Calle y Numero'])->label(false) ?>
            </div>
            <div class="col-md-12">
                <?= $clienteForm->field($cliente, 'colonia')->textInput(['class' => 'checkout-colonia checkout-form', 'id' => 'checkout-colonia2', 'placeholder' => 'Colonia'])->label(false) ?>
            </div>
            <div class="col-md-12">
                <?= $clienteForm->field($cliente, 'ciudad')->textInput(['class' => 'checkout-ciudad checkout-form', 'id' => 'checkout-ciudad2', 'placeholder' => 'Ciudad'])->label(false) ?>
            </div>

           <div class="col-md-12">
                <span class="span-drop-checkout">País/Región</span>
           </div>
            <div class="col-md-12">
                <?= $clienteForm->field($cliente, 'paises_id',['template'=>"<div>\n{error}\n{input}\n</div>"])
                    ->widget(Select2::classname(), [
                     'data' => $paises,
                     'language' => 'es',
                     'options' => ['placeholder' => 'País*', 'id' => 'pais-id-2', 'value' => 42 , 'class' => 'checkout-form'],
                     'pluginOptions' => [
                         'allowClear' => false
                    ],
                    ])->label(false) 
                ?>
            </div>
            <div class="col-md-12">
                 <span class="span-drop-checkout">Estado</span>
             </div>
            <div class="col-md-12">
                 <?= $clienteForm->field($cliente, 'estados_mundo_id')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'options' => ['id' => 'estado-id-2', 'class' => 'checkout-form'],
                    'pluginOptions' => [
                        'depends' => ['pais-id'],
                        'initialize' => true,
                        'placeholder' => 'Estado',
                        'url' => Url::to(['/checkout/estado'])
                    ]
                ])->label(false);
                ?>
            </div>
            <div class="col-md-4">
                <?= $clienteForm->field($cliente, 'cp')->textInput(['class' => 'checkout-cp checkout-form', 'id' => 'checkout-cp2', 'placeholder' => 'Código Postal', 'maxlength'=>5])->label(false) ?>
            </div>
            <div class="col-md-12">
               <?= $clienteForm->field($cliente, 'telefono')->textInput(['class' => 'checkout-telefono checkout-form', 'id' => 'checkout-telefono2', 'placeholder' => 'Numero de Celular'])->label(false) ?>
            </div>
            <div class="col-md-12">
                <?= $clienteForm->field($cliente, 'publicidad')->checkbox(['class' => 'checkout-publicidad', 'id' => 'checkout-publicidad2'])->label('Guardar información') ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>

        <div class="row row-datos-botones">
            <div class="col-xs-12">
                <?=  Html::button('Continuar con envíos', ['class' => 'checkout-continuar-envio btn-continuar-envio btn-disabled', 'id' => 'btn-continuar-envio', 'disabled' => 'disabled'])?>
            </div>
            <div class="col-xs-12">
                <?= Html::button(Html::img('@web/images/regresar.png', ['class' => 'img-responsive img-checkout-regresar']).'Regresar a carrito', ['class' => 'btn-regresar btn-regresar-carrito', 'id' => 'btn-regresar-carrito']) ?>
            </div>
        </div>
        <?php } else { ?>
               <a href="<?= Url::to(['/cliente/index', 'id' => $clientes->id])?>" class="parrafo-info" >Modificar datos</a>
            </div>
            <div class="col-xs-12">
<?php $clienteForm = ActiveForm::begin() ?>
                <?= $clienteForm->field($cliente, 'email')->textInput(['class' => 'checkout-correo checkout-form', 'id' => 'checkout-correo2', 'placeholder' => 'Correo electrónico', 'value' => $clientes->email, 'disabled' => true])->label(false) ?>
            </div>
            <div class="col-md-12 col-check-ofertas">
                <input type="checkbox" class="check-ofertas" id="check-ofertas2">
                <span class="check-ofertas-texto">Mantenerme informado sobre novedades y ofertas exclusivas</span>
            </div>
        </div>
        <div class="row col-checkout-datos">
            <div class="col-md-12">
                <p class="checkout-titulo-carrito">Datos de Envío</p>
                <p>Dirección de envío</p>
                <p class="checkout-datos-nota">Recuerda que es necesario tener tu información completa, incluyendo calle, número y código postal</p>
            </div>
            <div class="col-md-6">
                <?= $clienteForm->field($cliente, 'nombre')->textInput(['class' => 'checkout-nombre checkout-form', 'id' => 'checkout-nombre2', 'placeholder' => 'Nombre', 'value' => $clientes->nombre, 'disabled' => true])->label(false) ?>
            </div>
            <div class="col-md-6">
                <?= $clienteForm->field($cliente, 'apellidos')->textInput(['class' => 'checkout-apellido checkout-form', 'id' => 'checkout-apellido2', 'placeholder' => 'Apellido', 'value' => $clientes->apellidos, 'disabled' => true])->label(false) ?>
            </div>
            <div class="col-md-12">
                <?= $clienteForm->field($cliente, 'calle')->textInput(['class' => 'checkout-calle checkout-form', 'id' => 'checkout-calle2', 'placeholder' => 'Calle y Numero', 'value' => $clientes->calle, 'disabled' => true])->label(false) ?>
            </div>
            <div class="col-md-12">
                <?= $clienteForm->field($cliente, 'colonia')->textInput(['class' => 'checkout-colonia checkout-form', 'id' => 'checkout-colonia2', 'placeholder' => 'Colonia', 'value' => $clientes->colonia, 'disabled' => true])->label(false) ?>
            </div>
            <div class="col-md-12">
                <?= $clienteForm->field($cliente, 'ciudad')->textInput(['class' => 'checkout-ciudad checkout-form', 'id' => 'checkout-ciudad2', 'placeholder' => 'Ciudad', 'value' => $clientes->ciudad, 'disabled' => true])->label(false) ?>
            </div>

           <div class="col-md-12">
                <span class="span-drop-checkout">País/Región</span>
           </div>
            <div class="col-md-12">
                <?= $clienteForm->field($cliente, 'paises_id',['template'=>"<div>\n{error}\n{input}\n</div>"])
                    ->widget(Select2::classname(), [
                     'data' => $paises,
                     'language' => 'es',
                     'options' => ['placeholder' => 'País*', 'id' => 'pais-id-2', 'value' => $clientes->paises_id , 'class' => 'checkout-form', 'disabled' => true],
                     'pluginOptions' => [
                         'allowClear' => false
                    ],
                    ])->label(false) 
                ?>
            </div>
            <div class="col-md-12">
                 <span class="span-drop-checkout">Estado</span>
             </div>
            <div class="col-md-12">
                 <?= $clienteForm->field($cliente, 'estados_mundo_id')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'options' => ['id' => 'estado-id-2', 'class' => 'checkout-form', 'value' => $clientes->estados_mundo_id, 'disabled' => true],
                    'pluginOptions' => [
                        'depends' => ['pais-id'],
                        'initialize' => true,
                        'placeholder' => 'Estado',
                        'url' => Url::to(['/checkout/estado'])
                    ]
                ])->label(false);
                ?>
            </div>
            <div class="col-md-4">
                <?= $clienteForm->field($cliente, 'cp')->textInput(['class' => 'checkout-cp checkout-form', 'id' => 'checkout-cp2', 'placeholder' => 'Código Postal', 'maxlength'=>5, 'value' => $clientes->cp, 'disabled' => true])->label(false) ?>
            </div>
            <div class="col-md-12">
               <?= $clienteForm->field($cliente, 'telefono')->textInput(['class' => 'checkout-telefono checkout-form', 'id' => 'checkout-telefono2', 'placeholder' => 'Numero de Celular', 'value' => $clientes->telefono, 'disabled' => true])->label(false) ?>
            </div>
            <div class="col-md-12">
                <?= $clienteForm->field($cliente, 'publicidad')->checkbox(['class' => 'checkout-publicidad', 'id' => 'checkout-publicidad2'])->label('Guardar información') ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>

        <div class="row row-datos-botones">
            <div class="col-xs-12">
                <?=  Html::button('Continuar con envíos', ['class' => 'checkout-continuar-envio btn-continuar-envio', 'id' => 'btn-continuar-envio'])?>
            </div>
            <div class="col-xs-12">
                <?= Html::button(Html::img('@web/images/regresar.png', ['class' => 'img-responsive img-checkout-regresar']).'Regresar a carrito', ['class' => 'btn-regresar btn-regresar-carrito', 'id' => 'btn-regresar-carrito']) ?>
            </div>
        </div>
        <?php } ?>
        <div class="col-sm-12 img-procesando">
            <div class="lds-facebook"><div></div><div></div><div></div></div>
        </div>
    </div>
    <div class="checkout-mob-3">
        <div class="row row-resumen-mob">
            <div class="col-xs-12">
                <p class="checkout-titulo-carrito">Resumen</p>
                <div class="row row-resumen">
                    <div class="col-xs-3">
                        <p class="datos-subtitulo">Contacto</p>
                    </div>
                    <div class="col-xs-6" id="resumen-correo">
                        <p class="datos-resumen resumen-correo-p" id="resumen-correo-p4"></p>
                    </div>
                    <div class="col-xs-3">
                        <?= Html::button('Cambiar', ['class' => 'btn-regresar2 btn-regresar-info', 'id' => 'btn-regresar-info5']) ?>
                    </div>
                </div>
                <hr class="datos-hr">
                <div class="row row-resumen">
                    <div class="col-xs-3">
                        <p class="datos-subtitulo">Enviar a</p>
                    </div>
                    <div class="col-xs-6">
                        <p class="datos-resumen resumen-enviar-p" id="resumen-envio-p4" ></p>
                    </div>
                    <div class="col-xs-3">
                        <?= Html::button('Cambiar', ['class' => 'btn-regresar2 btn-regresar-info', 'id' => 'btn-regresar-info2']) ?>
                    </div>
                </div>  
                <hr class="datos-hr">
                <div class="row row-resumen row-resumen-envio">
                    <div class="col-xs-3">
                        <p class="datos-subtitulo">Envío</p>
                    </div>
                    <div class="col-xs-6">
                        <p class="datos-resumen resumen-envio-p" id="resumen-envio-p4"></p>
                    </div>
                    <div class="col-xs-3">
                        <?= Html::button('Cambiar', ['class' => 'btn-regresar2 btn-regresar-envio', 'id' => 'btn-regresar-envio']) ?>
                    </div>
                </div> 
            </div>
        </div>
        <div class="paso-3-mob">
            <div class="row row-envios-mob">
                <div class="col-xs-12">
                    <p class="checkout-titulo-carrito">Envíos</p>
                    <div class="row row-resumen">
                        <div class="col-xs-3 col-envios-radio">
                            <input type="radio" name="radio-envio2" value="estandar" class="envios-radio envio-standard" id="envio-standard2" data-envio="50.50" data-tipo="">
                            <span class="datos-subtitulo" id="tipo-envio-std">Estándar</span>
                        </div>
                        <div class="col-xs-6" id="resumen-correo">
                            <p class="datos-resumen" id="dias-std">3 a 5 días hábiles</p>
                        </div>
                        <div class="col-xs-3">
                            <p class="datos-subtitulo envio-std-costo" id="envio-std-costo" >$ 50.00</p>
                        </div>
                    </div>
                    <hr class="datos-hr">
                    <div class="row row-resumen">
                        <div class="col-xs-3 col-envios-radio">
                            <input type="radio" name="radio-envio2" value="express" class="envios-radio envio-express" id="envio-express2" data-envio="65.00" data-tipo="">
                            <span class="datos-subtitulo" id="tipo-envio-exp">Express</span>
                        </div>
                        <div class="col-xs-6">
                            <p class="datos-resumen" id="dias-exp">1 a 3 días hábiles</p>
                        </div>
                        <div class="col-xs-3">
                            <p class="datos-subtitulo envio-exp-costo" id="envio-exp-costo">$ 65.00</p>
                        </div>
                    </div> 
                </div>
            </div>
            <div class="row row-botones">
                <div class="col-xs-12">
                    <?=  Html::button('Continuar con pago', ['class' => 'checkout-continuar-envio btn-continuar-pago btn-disabled', 'id' => 'btn-continuar-pago'])?>
                </div>
                <div class="col-xs-12">
                    <?= Html::button(Html::img('@web/images/regresar.png', ['class' => 'img-responsive img-checkout-regresar']).'Regresar a información', ['class' => 'btn-regresar btn-regresar-info', 'id' => 'btn-regresar-info']) ?>
                </div>
            </div>
        </div>
        <div class="row-pago-mob">
            <div class="row col-checkout-contacto row-pago-mob">
                <div class="col-xs-12">
                    <p class="checkout-titulo-carrito">Pago</p>
                    <p class="parrafo-info" >Todas las transacciones son seguras y estan cifradas</p>
                    <div class="row row-resumen-pago">
                        <div class="col-xs-7 col-envios-radio">
                            <input type="radio" name="radio-pago2" value="tarjeta" class="pago-radio radio-tarjeta" id="radio-tarjeta2">
                            <span class="datos-subtitulo">Tarjeta de crédito o débito</span>
                        </div>
                        <div class="col-xs-5">
                            <?= Html::img('@web/images/iconos/visa.png', ['class' => 'img-responsive pago-imagen']) ?>
                            <?= Html::img('@web/images/iconos/cc-mastercard.png', ['class' => 'img-responsive pago-imagen']) ?>
                            <?= Html::img('@web/images/iconos/amex.png', ['class' => 'img-responsive pago-imagen']) ?>
                        </div>
                    </div>
                    <div class="row row-tarjeta-pago" id="">
                        <form action="" method="POST" id="card-form">
                        <div class="col-xs-12">
                            <input type="text" class="checkout-form tarjeta-form" id="tarjeta-num2" placeholder="Número de Tarjeta" data-conekta="card[number]">
                        </div>
                        <div class="col-xs-12">
                            <input type="text" class="checkout-form tarjeta-form" id="tarjeta-titular2" placeholder="Nombre del Titular" data-conekta="card[name]">
                        </div>
                        <div class="col-xs-6">
                            <input type="text" class="checkout-form tarjeta-form" id="tarjeta-vencimiento2" placeholder="Fecha de Vencimiento MM/AA">
                        </div>
                        <div class="col-xs-6">
                            <input type="text" class="checkout-form tarjeta-form" id="tarjeta-codigo2" placeholder="Código de Seguridad" maxlength="4" data-conekta="card[cvc]">
                        </div>
                        </form>
                    </div>
                    <div class="row row-resumen-pago">
                        <div class="col-xs-7 col-envios-radio">
                            <input type="radio" name="radio-pago2" value="efectivo" class="pago-radio radio-efectivo" id="radio-efectivo2">
                            <span class="datos-subtitulo">Pagos en Efectivo en OXXO</span>
                        </div>
                        <div class="col-xs-5">
                            <?= Html::img('@web/images/iconos/oxxo.png', ['class' => 'img-responsive pago-imagen']) ?>
                        </div>
                    </div>
                    <div class="row row-resumen-pago">
                        <div class="col-xs-7 col-envios-radio">
                            <input type="radio" name="radio-pago2" value="paypal" class="pago-radio radio-paypal" id="radio-paypal2">
                            <span class="datos-subtitulo">Pagos con PayPal</span>
                        </div>
                        <div class="col-xs-5">
                            <?= Html::img('@web/images/iconos/paypal.png', ['class' => 'img-responsive pago-imagen']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-check-terminos">
                    <input type="checkbox" class="check-terminos" id="check-terminos2">
                    <span class="check-ofertas-texto">Acepto los Términos y Condiciones</span>
                </div>
            </div>
            <div class="row row-botones">
                <div class="col-xs-12">
                    <?=  Html::button('Realizar pago', ['class' => 'checkout-continuar-envio btn-disabled btn-realizar-pago', 'id' => 'btn-realizar-pago', 'disabled' => 'disabled'])?>
                </div>
                <div class="col-xs-12">
                    <?= Html::button(Html::img('@web/images/regresar.png', ['class' => 'img-responsive img-checkout-regresar']).'Regresar a envío', ['class' => 'btn-regresar btn-regresar-envio', 'id' => 'btn-regresar-envio2']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    
    <input type="hidden" id="cupon-id" value="">
    <input type="hidden" id="cupon-codigo" value="">
    <input type="hidden" id="cupon-porcentaje" value="">
    <input type="hidden" id="cupon-descuento" value="">
    
    <input type="hidden" id="compra-subtotal" value="<?=$subtotal?>">
    <input type="hidden" id="compra-envio" value="">
    <input type="hidden" id="compra-total" value="">
    
    <input type="hidden" value="<?= $ini['publicKey'] ?>" id="conk_key" >
    
    <?php
    $editoriales = [];
    $baseurl = Url::base(true);
    ?>
<?php foreach ($libros as $libro){ ?> 
    <input type="hidden" class="compra-cantidad" 
        id="compra-cantidad-<?=$libro->libro?>" 
        data-id="<?=$libro->libro?>" 
        value="<?= $libro->cantidad ?>"
        data-precio="<?=$libro->precio?>" 
        data-subtotal="<?=$libro->cantidad*$libro->precio?>"
        data-titulo="<?= $libro->titulo ?>"
        data-isbn="<?= $libro->isbn ?>"
        data-sello="<?= $libro->sello ?>"
        data-tema="<?= $libro->tema ?>"
        data-editorial="<?= $libro->editorial_nombre ?>"
        data-editclave="<?= $libro->editorial_clave ?>"
        data-carrito="<?= $libro->carrito_id ?>"
    >
    <?php 
        
        if ($baseurl == 'http://localhost/uppl/frontend/web') {
            $frameurl = $ini['local_url'];
//        } else if ($baseurl == 'http://www.uppl.blackrobot.mx') {
//            $frameurl = $ini['pruebas_url'];
        } else {
            $frameurl = $ini[$libro->editorial_clave];
        }
    
        if($editoriales[$libro->editorial_clave]){ 
            $editoriales[$libro->editorial_clave]['subtotal'] += $libro->cantidad*$libro->precio;
        } else {
            $editoriales[$libro->editorial_clave] = array('subtotal' => $libro->cantidad*$libro->precio, 'id' => $libro->editorial_id, 'clave' => $libro->editorial_clave, 'ruta' => $frameurl );
        } 
    ?>
<?php } ?>

<?php foreach($editoriales as $editorial) { ?> 
    <input type="hidden" class="compra-editorial" 
        id="compra-editorial-<?=$editorial['clave']?>" 
        data-subtotal="<?=$editorial['subtotal']?>"
        data-editorial="<?=$editorial['id']?>"
        data-ruta="<?=$editorial['ruta']?>"
    >
<?php } ?>


</div>

