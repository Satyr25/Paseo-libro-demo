<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<?php
$logo = 'https://www.uppl.com.mx/images/logo-uppl.png';
$portada = 'https://www.lectorum.com.mx/images/PortadaNoDisponible.png';
$totalp = 0;
$cantidades = 0;
$palabra_marca =0;
?>
<div class="container">

    <div id="cuerpoconfir" class="row checkout-block check-card-block">
        <div class="col-xs-12">
            <p class="confirmacion-titulo">¡Compra Exitosa!</p>
        </div>
        <div class="col-xs-12 confirmacion-texto">
          Hemos enviado un correo a <?= $correo ?>. En caso de que no aparezca revisa en tu carpeta de spam.
        </div>
        <div class="col-xs-offset-3 col-xs-6">
            <table class="table confirmacion-tabla">
                <tbody>
                    <tr>
                        <td>Registro de compra</td>
                        <td><?= $dia_now ?> de <?= $mes_now ?> de <?= $anio_now ?> a las <?= $hora_now ?> hrs.</td>
                    </tr>
                    <tr>
                        <td>No. orden</td>
                        <td><?= $orden ?></td>
                    </tr>
                    <tr>
                        <td>No. transacción</td>
                        <td> <?= $transaccion ?></td>
                    </tr>
                    <tr>
                        <td>Envío</td>
                        <td> <?= $cadena ?></td>
                    </tr>
                    <tr>
                        <td>Datos de envío</td>
                        <td><?= $calle." ".$numero.", No. Int "?><?php if($calle2){echo $calle2;}else{echo 'S/n';}?> <br><?= "Colonia ".$colonia?><?= " CP. ".$cp?><br>
                        <?= "Delegación ".$del?><?= " Ciudad ".$ciudad ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <p class="confirmacion-titulo">Pédido</p>
        </div>
        <div class="col-xs-offset-3 col-xs-6">

        <?php foreach ($libros as $libros){ ?>
        <?php
            $foto=$libro->fotoCorreo($libros->id);
            $autor=$libro->autor($libros->id);
            $sello=$libro->sello_get($libros->id);
            $tema=$libro->temas_get($libros->id);
        ?>
            <div class="row container-row-producto">
                <div class="col-xs-5">
                <?= Html::img('@web/images/'.$foto['portada'], ['class' => 'img-responsive']) ?>
                </div>
                <div class="col-xs-7">
                    <table class="table confirmacion-tabla2">
                        <tbody>
                            <tr>
                                <td>Título</td>
                                <td><?= $libros->titulo ?></td>
                            </tr>
                            <tr>
                                <td>Autor</td>
                                <td><?= $autor['autor'] ?></td>
                            </tr>
                            <tr>
                                <td>Cantidad</td>
                                <td> <?php foreach ($libros_pedido as $cantidad){if($cantidad->libro_id==$libros->id){
                                    $cantidades = $cantidad->cantidad;
                                    $totalp = $totalp + ($libros->pvp * $cantidades);
                                    echo $cantidades;
                                }}?>
                               </td>
                            </tr>
                            <tr>
                                <td>Precio</td>
                                <td> <?= '$ '.$libros->pvp ?></td>
                            </tr>
                            <tr>
                                <td>Subtotal</td>
                                <td> <?= '$ '.number_format($libros->pvp*$cantidades, 2) ?> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>

        </div>
    </div>
    <div class="row confirmacion-total">
        <div class="col-xs-offset-4 col-xs-4">
        <?php if($cupon){ ?>
            <table class="table confirmacion-tabla">
                <tbody>
                    <tr>
                        <td>Subtotal</td>
                        <td><?= '$ '.number_format($totalp, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Descuento</td>
                        <td><?= '-$ '.number_format($desc, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Envío</td>
                        <td><?= '$ '.(number_format($precio_envio, 2)) ?></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td> <?= '$ '.number_format($precio_envio - $desc + $totalp, 2) ?></td>
                    </tr>
                </tbody>
            </table>
        <?php } else { ?>
            <table class="table confirmacion-tabla">
                <tbody>
                    <tr>
                        <td>Subtotal</td>
                        <td><?= '$ '.number_format($totalp, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Envío</td>
                        <td><?= '$ '.(number_format($precio_envio, 2)) ?></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td> <?= '$ '.number_format($precio_envio + $totalp, 2) ?></td>
                    </tr>
                </tbody>
            </table>
        <?php } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-offset-5 col-xs-2">
            <a href="<?= !$home ? Url::to(['/site/index']) : '' ?>" class="backHome">Regresar a Inicio</a>
        </div>
    </div>
</div>
