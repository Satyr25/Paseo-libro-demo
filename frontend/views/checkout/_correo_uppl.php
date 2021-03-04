<?php
use yii\helpers\Html;
use yii\helpers\Url;
date_default_timezone_set('America/Mexico_City');

?>
<?php
$logo = Url::base(true).'/images/logo-uppl-correo.png';
$logofedex = Url::base(true).'/images/Fedex.png';
$portada = Url::base(true).'/images/PortadaNoDisponible.png';
$totalp = 0;
$totales = 0;
$cantes =0;
$cantidades = 0;
$total_libros = 0;
$palabra_marca =0;
?>
<link href="https://fonts.googleapis.com/css?family=Raleway:400,700&display=swap" rel="stylesheet">
<style type="text/css">
    .vent-table{
        margin: 5% auto;width: 80%;
    }
    .body-container{
        font-family: 'Raleway', sans-serif;font-weight: 600;line-height: 1;font-size: 14px;color: #2b323b;
    }
    .tit-princ{
        font-size: 20px;font-weight: 600 !important;
    }
    .det-lib {
        vertical-align: text-top;text-align: left;font-weight: 600 !important;
    }
    .lib-hr {
        margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;
    }
    .sp-marg {
        width: 20%;display: inline-block;min-width: 80px;color: #666;
    }
    .cat-tit {
        color: #666;
    }
    .promo-span-tac {
        font-weight: 600 !important;text-decoration-line: line-through;
    }
    .tit-venta {
        font-size: 25px;color: #0b4066 !important;
    }
    td, th {
        padding-top: 15px;padding: 0;color: #2b323b;
    }
    th{
        text-align: left;
    }
    .dat-compr{
      width: 100%;background-color: #f4f6f6;
    }
    .left-data{
      text-align: left;padding-left: 10%;width: 50%;
    }

    .right-data{
      text-align: left;padding-left: 2%;
    }

    .right-data p{
      margin:12px 0;
    }
    .left-data p{
      margin: 10px 0;color: #2b323b!important;text-transform: capitalize;
    }
    .blvk{
      color: #2b323b;font-weight: normal!important;
    }
    .btnVerguia{
        border-radius: 2px;position: relative;background-color: #0b4066;padding: 8px 0px;width: 30%;margin: 0;font-weight: bold;margin-bottom: 15px;text-align: center;
    }
    .btnVerguia a{
        color: #fff;font-size: 12px;text-decoration: none;
    }
    .btnVerguia:hover{
        background-color: #0b4066;
    }
    .datos-hr{
      margin-top: 7px;margin-bottom: 7px;width: 80%;margin-right: 20%;
    }
</style>
<div style="background-color: #0b4066">
    <div style="background-color: #fff;padding: 50px 0px; text-align:center; margin-left: auto; margin-right: auto;width:600px; ">
        <a href="https://www.uppl.blackrobot.mx">
            <img src="<?= $logo ?>" style = "width: 130px;">
        </a>
    </div>
</div>
<div style="background-color: #0b4066">
    <div style="background-color: #fff; margin-left: auto; margin-right: auto; width:600px; ">
        <div style="font-weight: bold;font-size:19px;padding-top:20px;padding-bottom:20px; text-align: center;">
            <?php if(!$pedido_oxxo){ ?>
                &#161;Se realizó una Nueva Compra en línea!
            <?php }else{  ?>
                &#161;Se realizó un Nuevo Pedido por Oxxo pendiente de pagar!
            <?php } ?>
        </div>
    </div>
</div>
<div style="font-family: 'Raleway', sans-serif;font-weight: 600;line-height: 1;font-size: 14px;color: #2b323b;background-color: #0b4066;color:#000;" class="body-container">
    <div style="background-color: #fff; margin-left: auto; padding-bottom: 40px;margin-right: auto; width:600px; ">
        <div style="text-align: center; padding-top: 20px; font-size: 12px;">
            <?php if(!$pedido_oxxo){ ?>
                <p class="tit-venta" style="font-size: 25px;color: #0b4066 !important;">Venta: <?= $dia_now ?> de <?= $mes_now ?> de <?= $anio_now ?> a las <?= $hora_now ?> hrs.</p>
            <?php }else{ ?>
                <p class="tit-venta" style="font-size: 25px;color: #0b4066 !important;">Pedido Pendiente Oxxo: <?= $dia_now ?> de <?= $mes_now ?> de <?= $anio_now ?> a las <?= $hora_now ?> hrs.</p>
            <?php } ?>
        </div>
        <div style="text-align:center;">
            <p style="font-size:25px;">Detalle de Pedido</p>
        </div>
        <table class="vent-table" style="margin: 5% auto 20px;;width: 600px;">
            <thead>
            </thead>
                <?php
                    if($tarjeta){
                        $cant=count($libros);
                        for ($i = 0; $i < $cant; $i++){
                            $foto=$libro->fotoCorreo($libros[$i][0]);
                ?>
            <tr style="height: 300px;">
                <td style="padding:0 0 0 15px;color: #2b323b;width: 30%;">
                    <?php if($libros[$i][0] == $foto['id'] && $foto['portada']){ ?>
                        <img src="<?=Url::base(true)?>/images/<?=$foto['portada'] ?>" style="width:90%;float: right; max-width:220px; padding-right:20px;">
                    <?php } else {?>
                        <img src="<?php print_r($portada) ?>" style="width:90%;float: left;">
                    <?php }?>
                </td>
                <td class="det-lib" style="padding-top: 15px;padding: 0;color: #2b323b;vertical-align: middle;text-align: left; width:51%;">
                    <table style="width: 95%">
                        <tr>
                            <td style="padding-top: 15px;padding: 0;color: #2b323b;text-transform:capitalize;padding-top: 0;"><hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                <span class="cat-tit sp-marg" style="color: #666;width: 20%;display: inline-block;min-width: 80px;color: #666;">Título:</span> <?= mb_strtolower($libros[$i][2]) ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 15px;padding: 0;color: #2b323b;text-transform:capitalize;padding-top: 5px;">
                                <hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                <span class="cat-tit sp-marg" style="color: #666;width: 20%;display: inline-block;min-width: 80px;color: #666;">Autor:</span> <?= mb_strtolower($libros[$i][1]) ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 15px;padding: 0;color: #2b323b;height:20px;padding-top: 5px;">
                                <hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                <span class="cat-tit sp-marg" style="color: #666;width: 20%;display: inline-block;min-width: 80px;color: #666;">Cantidad:</span> <?= $libros[$i][5] ?>
                            </td>
                        </tr>
                        <?php if($libros[$i][3] && $libros[$i][3] >0){
                            $totalp = $totalp + ($libros[$i][5] * $libros[$i][3]);
                            $cantidades = $cantidades + $libros[$i][5];
                        }elseif ($cupon_global && !$libros[$i][3] >=0) {
                            $precioTotal = $libros[$i][4] - (($cupon_global->porcentaje / 100) * $libros[$i][4]);
                            $totalp = $totalp + ($libros[$i][5] * $precioTotal);
                            $cantidades = $cantidades + $libros[$i][5];
                        }else{
                            $totalp = $totalp + ($libros[$i][5] * $libros[$i][4]);
                            $cantidades = $cantidades + $libros[$i][5];
                        }
                        $total_libros = $total_libros + $libros[$i][5];
                        ?>
                        <tr>
                            <td style="padding-top: 15px;padding: 0;color: #2b323b;height:20px;padding-top: 5px;">
                                <hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                <span class="cat-tit sp-marg" style="color: #666;width: 20%;display: inline-block;min-width: 80px;color: #666;">Precio: </span>
                                <?php if($libros[$i][3] && $libros[$i][3] >0){
                                    echo '<span class="promo-span-tac" style="text-decoration-line: line-through;">$'.$libros[$i][4].'</span><span style="margin-left: 5px;">$'.$libros[$i][3].'</span>';
                                }elseif ($cupon_global && !$libros[$i][3] >=0) { $precioTotal = $libros[$i][4] - (($cupon_global->porcentaje / 100) * $libros[$i][4]);
                                    echo '<span class="promo-span-tac" style="text-decoration-line: line-through;">$'.$libros[$i][4].'</span><span style="margin-left: 5px;">$'.$precioTotal.'</span>';
                                }
                                else{
                                    echo '$'.$libros[$i][4];
                                } ?>
                                <hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 15px;padding: 0;color: #2b323b;height:20px;padding-top: 5px;">
                                <span class="cat-tit sp-marg" style="color: #666;width: 20%;display: inline-block;min-width: 80px;color: #666;">Subtotal: </span>
                                <?php if($libros[$i][3] && $libros[$i][3] >0){
                                    echo number_format($libros[$i][3]*$libros[$i][5], 2);
                                }elseif ($cupon_global && !$libros[$i][3] >=0) {
                                    $precioTotal = $libros[$i][4] - (($cupon_global->porcentaje / 100) * $libros[$i][4]);
                                    echo number_format($libros[$i][5]*$precioTotal, 2);
                                }
                                else{
                                    echo number_format($libros[$i][4]*$libros[$i][5], 2);
                                } ?>
                                <hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
                <?php } }else{
                    for ($i = 0; $i < count($libros); $i++){
                        $autor=$libro->autor($libros[$i]->id);
                        $foto=$libro->fotoCorreo($libros[$i]->id);
                ?>
                <tr>
                    <td style="padding:0 0 0 15px;color: #2b323b;width: 120px;">
                        <?php if($libros[$i]->id == $foto['id'] && $foto['portada']){ ?>
                            <img src="<?=Url::base(true)?>/images/<?php print_r($foto['portada']) ?>" style="width:100px;float: left;">
                        <?php } else {?>
                              <img src="<?php print_r($portada) ?>" style="width:100px;float: left;">
                        <?php }?>
                    </td>
                    <td class="det-lib" style="padding-top: 15px;padding: 0;color: #2b323b;vertical-align: text-top;text-align: left;">
                        <table style="width: 95%">
                            <tr>
                                <td style="padding-top: 15px;padding: 0;color: #2b323b;text-transform:capitalize;padding-top: 0;"><hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                    <span class="cat-tit sp-marg" style="color: #666;width: 20%;display: inline-block;min-width: 80px;color: #666;">Título:</span> <?= mb_strtolower($libros[$i]->titulo) ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 15px;padding: 0;color: #2b323b;text-transform:capitalize;padding-top: 5px;">
                                    <hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                    <span class="cat-tit sp-marg" style="color: #666;width: 20%;display: inline-block;min-width: 80px;color: #666;">Autor:</span> <?= mb_strtolower($autor['autor']) ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 15px;padding: 0;color: #2b323b;font-size:13px;height:20px;padding-top: 5px;">
                                    <hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                    <span class="cat-tit sp-marg" style="color: #666;width: 20%;display: inline-block;min-width: 80px;color: #666;">Cantidad:</span>
                                    <?php foreach ($libros_pedido as $cantidad){
                                        if($cantidad->libro_id==$libros[$i]->id){
                                            $cantidades = $cantidad->cantidad;
                                        }
                                    }
                                    echo $cantidades;
                                    ?>
                                </td>
                            </tr>
                            <?php $totalp = $subtotal;
                                if($libros[$i]->promo && $libros[$i]->promo>0) {
                                    $totales = $totales + ($libros[$i]->promo * $cantidades);
                                }elseif ($cupon_global && !$libros[$i]->promo >=0) {
                                    $precioTotal = $libros[$i]->pvp - (($cupon_global->porcentaje / 100) * $libros[$i]->pvp);
                                    $totales = $totales + ($precioTotal * $cantidades);
                                }else{
                                    $totales = $totales + ($libros[$i]->pvp * $cantidades);
                                }
                                $total_libros = $total_libros + $cantidades;
                            ?>
                            <tr>
                                <td style="padding-top: 15px;padding: 0;color: #2b323b;font-size:13px;height:20px;padding-top: 5px;"><hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                    <span class="cat-tit sp-marg" style="color: #666;width: 20%;display: inline-block;min-width: 80px;color: #666;">Precio: </span>
                                    <?php if($libros[$i]->promo && $libros[$i]->promo>0){
                                        echo '<span class="promo-span-tac" style="text-decoration-line: line-through;">$'.$libros[$i]->pvp.'</span><span style=";margin-left: 5px;">$'.$libros[$i]->promo.'</span>';
                                    }elseif ($cupon_global && !$libros[$i]->promo>=0) { $precioTotal = $libros[$i]->pvp - (($cupon_global->porcentaje / 100) * $libros[$i]->pvp);
                                       echo '<span class="promo-span-tac" style="text-decoration-line: line-through;">$'.$libros[$i]->pvp.'</span><span style="margin-left: 5px;">$'.$precioTotal.'</span>';
                                    }
                                    else{
                                        echo '$'.$libros[$i]->pvp;
                                    } ?>
                                    <hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            <?php }} ?>
        </table>
        <div style="width:350px; margin:0 auto 20px;">
            <table style="table-layout:fixed; width:350px; border-collapse:collapse;">
                <tbody>
                    <tr style="border-bottom: 2px solid #f4f6f6; height: 30px;">
                        <td style="width:50%; color:#0b4066; font-weight: bold;">Total de Libros:</td>
                        <td style="float: right; padding:10px 0; width:50%;"><?= $total_libros ?></td>
                    </tr>
                    <tr style="border-bottom: 2px solid #f4f6f6; height: 30px;">
                        <td style="width:50%; color:#0b4066; font-weight: bold;"><?php ($is_editorial) ? 'Total:' : 'Subtotal:';?></td>
                        <td style="float: right; padding:10px 0; width:50%;">$<?= number_format($totalp, 2)  ?></td>
                    </tr>
                    <?php if(!$is_editorial){ ?> 
                        <tr style="border-bottom: 2px solid #f4f6f6; height: 30px;">
                            <td style="width:50%; color:#0b4066; font-weight: bold;">Envío:</td>
                            <td style="float: right; padding:10px 0; width:50%;"><?=number_format($precio_envio, 2)?></td>
                        </tr>
                        <tr style="height: 30px;">
                            <td style="width:50%; color:#0b4066; font-weight: bold;">Total:</td>
                            <td style="float: right; padding:10px 0; width:50%; font-weight:bold;"><?= number_format($totalp+$precio_envio, 2) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div style="background-color:f4f6f6;">
            <div style="color: #2b323b;text-align: center;padding:30px 0;width: 100%;">
                <span class="tit-princ" style="font-size: 20px;">Envío</span>
                <p style="margin: 10px 0;color: #2b323b!important;text-transform: capitalize;"><?= mb_strtolower($cadena) ?></p>
                <?php if($num_guia){  ?>
                    <p style="margin: 10px 0;color: #2b323b!important;text-transform: capitalize;">No Seguimiento: <?= $num_guia ?></p>
                    <?php if(!$is_editorial){ ?> 
                        <a href="http://www.uppl.com.mx/label/<?= $num_guia ?>.pdf" target="_blank" style="text-decoration: none;color: #fff;font-size: 12px;border-radius: 2px;position: relative;background-color: #0068b3;padding: 8px 40px;width: 30%;margin: 0 auto;font-weight: bold;margin-bottom: 15px;text-align: center;">Imprimir Guía</a>
                    <?php } ?>
                <?php }else{ ?>
                    <p style="margin: 10px 0;color: #2b323b!important;text-transform: capitalize;">No Seguimiento: N/A</p>
                <?php } ?>
            </div>
            <?php if(!$is_editorial){ ?> 
            <div style="color: #2b323b;text-align: center;padding:30px 0;width: 100%;">
                <div class="tit-princ" style="font-size: 20px;"><p>Datos de Entrega</p></div>
                <div style="width:350px; margin:0 auto 20px;">
                    <table style="table-layout:fixed; width:350px;; border-collapse:collapse;">
                        <tbody>
                            <tr style="border-bottom: 1px solid #b9b9b9; height: 30px;">
                                <td style="width:30%; color:#666;">Calle: </td>
                                <td style="float: left; padding:10px 0; width:100%;"><?= $clientes->calle.' '.$clientes->num_ext  ?> <?php if($clientes->num_int){ echo('int '.$clientes->num_int); } ?></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #b9b9b9; height: 30px;">
                                <td style="width:30%; color:#666;">Colonia: </td>
                                <td style="float: left; padding:10px 0; width:100%;"><?= $clientes->colonia ?></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #b9b9b9; height: 30px;">
                                <td style="width:30%; color:#666;">Alcaldia: </td>
                                <td style="float: left; padding:10px 0; width:100%;"><?=$clientes->delegacion ?></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #b9b9b9; height: 30px;">
                                <td style="width:30%; color:#666;">C.P.: </td>
                                <td style="float: left; padding:10px 0; width:100%;"><?=$clientes->cp ?></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #b9b9b9; height: 30px;">
                                <td style="width:30%; color:#666;">Estado: </td>
                                <td style="float: left; padding:10px 0; width:100%;"><?= $estado_mundo->estadonombre ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php } ?>
            <div style="color: #2b323b;text-align: center;padding:30px 0;width: 100%;">
                <div class="tit-princ" style="font-size: 20px;"><p>Cliente</p></div>
                <div style="width:350px; margin:0 auto 20px;">
                    <table style="table-layout:fixed; width:350px; border-collapse:collapse;">
                        <tbody>
                            <tr style="border-bottom: 1px solid #b9b9b9; height: 30px;">
                                <td style="width:30%; color:#666;">Nombre: </td>
                                <td style="float: left; padding:10px 0; width:100%;"><?= $clientes->nombre.' '.$clientes->apellidos  ?></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #b9b9b9; height: 30px;">
                                <td style="width:30%; color:#666;">Teléfono: </td>
                                <td style="float: left; padding:10px 0; width:100%;"><?= $clientes->telefono  ?></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #b9b9b9; height: 30px;">
                                <td style="width:30%; color:#666;">Correo: </td>
                                <td style="float: left; padding:10px 0; width:100%;"><?= $clientes->email ?></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #b9b9b9; height: 30px;">
                                <td style="width:30%; color:#666;">Domicilio: </td>
                                <td style="float: left; padding:10px 0; width:100%;"><?= $clientes->calle.' '.$clientes->num_ext  ?> <?php if($clientes->num_int){ echo('int '.$clientes->num_int); } ?></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #b9b9b9; height: 30px;">
                                <td style="width:30%; color:#666;">Colonia: </td>
                                <td style="float: left; padding:10px 0; width:100%;"><?= $clientes->colonia ?></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #b9b9b9; height: 30px;">
                                <td style="width:30%; color:#666;">Alcaldía: </td>
                                <td style="float: left; padding:10px 0; width:100%;"><?= $clientes->delegacion ?></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #b9b9b9; height: 30px;">
                                <td style="width:30%; color:#666;">C.P.: </td>
                                <td style="float: left; padding:10px 0; width:100%;"><?= $clientes->cp ?></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #b9b9b9; height: 30px;">
                                <td style="width:30%; color:#666;">Estado: </td>
                                <td style="float: left; padding:10px 0; width:100%;"><?= $estado_mundo->estadonombre ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="color: #2b323b;text-align: center;padding:30px 0;width: 100%;">
                <span class="tit-princ" style="font-size: 20px;">Pago</span>
                <?php if($marca){ ?>
                    <p style="margin:12px 0;">Tarjeta</p>
                    <p style="margin:12px 0;text-transform: capitalize;"><?= $marca ?> terminada en: <span class="blvk" style="color: #2b323b;font-weight: normal!important;"><?= $numeros ?></span></p>
                <?php }elseif ($pedido_oxxo) { ?>
                    <p style="margin:12px 0;text-transform: capitalize;">OxxoPay</p>
                <?php }else{ ?>
                    <p style="margin:12px 0;text-transform: capitalize;">Paypal</p>
                <?php } ?>
                <p style="margin:12px 0;">Estatus: <span class="blvk" style="color: #2b323b;font-weight: normal!important;"><?= $estatus->nombre ?></span></p>
            </div>
        </div>
    </div>
</div>
