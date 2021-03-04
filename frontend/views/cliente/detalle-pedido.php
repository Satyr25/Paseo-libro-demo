<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$portada = Yii::$app->request->BaseUrl.'/images/portada_default.jpg';
$totalcan = 0;
$total_libros = 0;
$total_ofertas = 0;
$cadena = str_replace('_', ' ', $accion->nombre_envio);
$totaldes= 0;
?>

<div class="muestra-libro">
    <div class="center">
        <div class="white-block">
            <div class="title-sell-table">
                <div class="row">
                    <div class="col-md-12">
                        <a href="<?= Url::toRoute('index')?>" class="link-boton-regresar">
                            <?= Html::img('@web/images/regresar.png', ['class' => 'img-responsive boton-regresar']) ?>
                            Regresar
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="venta-ver-titulo text-left">Detalle de venta</h3>
                    </div>
                </div>
            </div>
            <table class="vent-table">
                <thead>
                    <th colspan="2" class="tit-princ">
                        Título
                    </th>
                    <th class="tit-princ" style="text-align: right;">
                        Subtotal
                    </th>
                </thead>
                <?php foreach ($libros as $libro): ?>
                <tr>
                    <td style="width: 120px;" class="td-libro-ver">
                        <?php if ($libro->portada):?>
                            <img src="<?=  Yii::$app->request->BaseUrl.'/images/'.$libro->portada ?>" style="width:100px;float: left;">
                        <?php else: ?>
                          <img src="<?php print_r($portada) ?>" style="width:100px;float: left;">
                        <?php endif; ?>
                    </td>
                    <td class="det-lib">
                        <table style="width: 80%">
                            <tr>
                                <td style=" text-transform:capitalize;padding-top: 0;">
                                    <hr class="lib-hr">
                                    <span class="cat-tit sp-marg">Título:</span>
                                    <span class="info-table gray"><?= mb_strtolower($libro->nombre) ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-transform:capitalize;padding-top: 5px;">
                                    <hr class="lib-hr">
                                    <span class="cat-tit sp-marg">Autor:</span>
                                    <span class="info-table gray"><?= mb_strtolower($libro->autor) ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:13px;height:20px;padding-top: 5px;">
                                    <hr class="lib-hr">
                                    <span class="cat-tit sp-marg">Cantidad:</span>
                                    <span class="info-table gray"><?= $libro->cantidad ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:13px;height:20px;padding-top: 5px;">
                                    <hr class="lib-hr">
                                    <span class="cat-tit sp-marg">Precio: </span>
                                    <?php $precio_u = number_format($libro->cantidad, 2);?>
                                    <span class="promo-span-tac info-table gray">$<?= $libro->pvp ?></span>
                                    <hr class="lib-hr">
                                </td>
                            </tr>
                            <?php $totaldes = $totaldes + ($libro->pvp * $libro->cantidad); $totalcan = $totalcan + $libro->total; $total_libros += $libro->cantidad; ?>
                        </table>
                    </td>
                    <td style="text-align: right;" class="info-table gray">
                        $<?= number_format($libro->total, 2) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="2" style="vertical-align: bottom;text-align: right;padding-right: 13%;">
                        <span class="cat-tit sp-marg">Total:</span><span class="info-table"> <?= $total_libros ?> Libros</span>
                    </td>
                    <td style="border-top: 1px solid #eeeeee; text-align: right;">
                        <?php if ($cupon && $desc): ?>
                            <p style="margin-bottom: 5px;"><span class="total-titles">Subtotal:</span> <span style="color: #333;font-weight: normal;position: relative;float: right;">$<?= number_format($totalcan, 2);  ?></span></p>
                            <p style="margin-bottom: 5px;"><span class="total-titles">Descuento cupón:</span> <span style="color: #333;font-weight: normal;position: relative;float: right;">-$<?= number_format($desc, 2)  ?></span></p>
                            <p style="margin-bottom: 5px;"><span class="total-titles">Envío:</span> <span style="color: #333;font-weight: normal;position: relative;float: right;">$<?= number_format($accion->costo_total +$desc - $totalcan, 2)  ?></span></p>
                            <p><span style="position: relative;right: 25px;">Total:</span> <b style="color: #333;position: relative;float: right;">$<?= number_format($accion->costo_total, 2);  ?></b></p>
                        <?php elseif ($desc && !$cupon): ?>
                            <p style="margin-bottom: 5px;"><span class="total-titles">Subtotal:</span> <span style="color: #333;font-weight: normal;position: relative;float: right;">$<?= number_format($totalcan, 2);  ?></span></p>
                            <p style="margin-bottom: 5px;"><span class="total-titles">Descuento puntos:</span> <span style="color: #333;font-weight: normal;position: relative;float: right;">-$<?= number_format($desc, 2)  ?></span></p>
                            <p style="margin-bottom: 5px;"><span class="total-titles">Envío:</span> <span style="color: #333;font-weight: normal;position: relative;float: right;">$<?= number_format($accion->costo_total + $desc - $totalcan, 2)  ?></span></p>
                            <p><span style="position: relative;right: 25px;">Total:</span> <b style="color: #333;position: relative;float: right;">$<?= number_format($accion->costo_total, 2);  ?></b></p>
                        <?php else: ?>
                            <p style="margin-bottom: 5px;"><span class="total-titles">Subtotal:</span> <span style="color: #333;font-weight: normal;position: relative;float: right;">$<?= number_format($totalcan, 2);  ?></span></p>
                            <p style="margin-bottom: 5px;"><span class="total-titles">Envío:</span> <span style="color: #333;font-weight: normal;position: relative;float: right;">$<?= number_format($accion->costo_envio, 2) /*number_format($accion->costo_total - $totalcan, 2)*/  ?></span></p>
                            <p><span class="total-titles">Total:</span> <b style="color: #333;position: relative;float: right;">$<?= number_format($totalcan+$accion->costo_envio, 2);  ?></b></p>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="blue-block">
            <table class="dat-compr">
                <tr>
                    <td class="left-data">
                        <span class="tit-princ">Envío</span>
                        <p class="info-table"><?= mb_strtolower($cadena) ?></p>
                        <?php if ($accion->tracking):  ?>
                            <p class="info-table">No Seguimiento: <a href="https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber=<?= $accion->tracking ?>" target="_blank" class="link-tracking"><?= $accion->tracking ?></a></p>
                            <div class="btnVerguia">
                                    <a href="<?=Yii::$app->request->BaseUrl?>/label/<?= $accion->tracking ?>.pdf" target="_blank">Imprimir Guía</a>
                            </div>
                        <?php else: ?>
                            <p class="info-table">No Seguimiento: N/A</p>
                        <?php endif; ?>
                    </td>
                    <td rowspan="2" class="right-data">
                        <span class="tit-princ">Cliente</span>
                        <p class="info-table"><span class="sp-marg">Nombre: </span><span class="blvk"><?= $clientes->nombre.' '.$clientes->apellidos  ?></span></p>
                        <hr class="datos-hr2">
                        <p class="info-table"><span class="sp-marg">Teléfono: </span><span class="blvk"><?= $clientes->telefono  ?></span></p>
                        <hr class="datos-hr2">
                        <p class="info-table"><span class="sp-marg">Correo: </span><span class="blvk"><?= $clientes->email  ?></span></p>
                        <hr class="datos-hr2">
                        <p class="info-table"><span class="sp-marg">Domicilio: </span><span class="blvk" style="text-transform: capitalize;"><?= $clientes->calle.' '.$clientes->num_ext  ?> <?= isset ($clientes->num_int) ? "int {$clientes->num_int}" : null ?></span></p>
                        <hr class="datos-hr2">
                        <p class="info-table"><span class="sp-marg">Colonia: </span><span class="blvk"><?= $clientes->colonia ?></span></p>
                        <hr class="datos-hr2">
                        <p class="info-table"><span class="sp-marg">Alcaldía: </span><span class="blvk"><?=$clientes->delegacion ?></span></p>
                        <hr class="datos-hr2">
                        <p class="info-table"><span class="sp-marg">C.P.: </span><span class="blvk"><?= $clientes->cp ?></span></p>
                        <hr class="datos-hr2">
                        <p class="info-table"><span class="sp-marg">Estado: </span><span class="blvk"><?= $estado_mundo->estadonombre ?></span></p>
                        <hr class="datos-hr2">
                    </td>
                </tr>
                <tr>
                    <td class="left-data">
                        <span class="tit-princ">Datos de Entrega</span>
                        <p style="margin-top: 15px;" class="info-table"><span class="sp-marg">Calle: </span><span class="blvk"><?= $clientes->calle.' '.$clientes->num_ext  ?> <?= isset ($clientes->num_int) ? "int {$clientes->num_int}" : null ?></span></p>
                        <hr class="datos-hr2">
                        <p class="info-table"><span class="sp-marg">Colonia: </span><span class="blvk"><?= $clientes->colonia ?></span></p>
                        <hr class="datos-hr2">
                        <p class="info-table"><span class="sp-marg">Alcaldía: </span><span class="blvk"><?=$clientes->delegacion ?></span></p>
                        <hr class="datos-hr2">
                        <p class="info-table"><span class="sp-marg">C.P.: </span><span class="blvk"><?= $clientes->cp ?></span></p>
                        <hr class="datos-hr2">
                        <p class="info-table"><span class="sp-marg">Estado: </span><span class="blvk"><?= $estado_mundo->estadonombre ?></span></p>
                        <hr class="datos-hr2">
                    </td>
                </tr>
                <tr>
                    <td class="left-data"></td>
                    <td class="right-data" style="padding-bottom: 4%;">
                        <span class="tit-princ">Pago</span>
                        <?php if($accion->datos_pago_id): ?>
                            <p class="info-table">Tarjeta</p>
                            <p class="info-table" style="text-transform: capitalize;"><?= $datosPago->marca ?> terminada en: <span class="blvk"><?= $datosPago->numeros_tarjeta ?></span></p>
                        <?php elseif ($accion->pago_tienda_id): ?>
                            <p class="info-table" style="text-transform: capitalize;"><?= mb_strtolower($pagoTienda->payment_method) ?></p>
                        <?php endif; ?>
                        <p class="info-table">Estatus: <span class="blvk"><?= $estatus->nombre ?></span></p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
