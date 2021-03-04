<?php
use yii\helpers\Html;
use yii\helpers\Url;
date_default_timezone_set('America/Mexico_City');

?>
<?php 
$logo = 'https://www.lectorum.com.mx/images/LectorumLogo.png';
$logofedex = 'https://www.lectorum.com.mx/images/Fedex.png';
$portada = 'https://www.lectorum.com.mx/images/PortadaNoDisponible.png';
$totalp = 0;
$totales = 0;
$cantes =0;
$cantidades = 0;
$total_libros = 0;
$palabra_marca =0;
?>
<link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet"> 
<style type="text/css">
    .vent-table{
        margin: 5% auto;width: 80%;
    }
    .body-container{
        font-family: 'Raleway', sans-serif;font-weight: 600;line-height: 1;font-size: 14px;color: #333333;
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
        width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;
    }
    .cat-tit {
        color: #999;font-weight: 600 !important;
    }
    .promo-span-tac {
        font-weight: 600 !important;text-decoration-line: line-through;
    }
    .tit-venta {
        font-size: 25px;color: #1f5b5f !important;
    }
    td, th {
        padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;
    }
    th{
        text-align: left;
    }
    .dat-compr{
      width: 100%;background-color: #f4f6f6;
    }
    .left-data{
      text-align: left;padding-left: 10%;width: 50%;border-right: 1px solid #d1d1d1;
    }

    .right-data{
      text-align: left;padding-left: 2%;
    }

    .right-data p{
      margin:12px 0;
    }
    .left-data p{
      margin: 10px 0;color: #333!important;text-transform: capitalize;
    }
    .blvk{
      color: #333;font-weight: normal!important;
    }
    .btnVerguia{
        border-radius: 2px;position: relative;background-color: #379fa2;padding: 8px 0px;width: 30%;margin: 0;font-weight: bold;margin-bottom: 15px;text-align: center;
    }
    .btnVerguia a{
        color: #fff;font-size: 12px;text-decoration: none;
    }
    .btnVerguia:hover{
        background-color: #1f5b5f;
    }
    .datos-hr{
      margin-top: 7px;margin-bottom: 7px;width: 80%;margin-right: 20%; 
    }
</style> 
<div style="background-color: #04685c;padding: 30px 0px; text-align:center;">
    <a href="https://www.lectorum.com.mx">
        <img src="https://www.lectorum.com.mx/images/LectorumLogo.png" style = "width: 130px;">

    </a>
</div>
<div style="background-color: #f9f9f9;">
    <div style="font-weight: bold;font-size:19px;padding-top:20px;padding-bottom:20px; text-align: center;">
        <?php if(!$pedido_oxxo){ ?>
            &#161;Se realizó una Nueva Compra en línea!
        <?php }else{  ?>
            &#161;Se realizó un Nuevo Pedido por Oxxo pendiente de pagar! 
        <?php } ?>
    </div>
</div>
<div style="font-family: 'Raleway', sans-serif;font-weight: 600;line-height: 1;font-size: 14px;color: #333333;background-color: #e2e2e3;color:#000;" class="body-container">
    <br>
    <div style="background-color: #fff; margin-left: 50px; padding-bottom: 40px;margin-right: 50px; ">
        <div style="text-align: center; padding-top: 20px; font-size: 12px;">
            <?php if(!$pedido_oxxo){ ?>
                <p class="tit-venta" style="font-size: 25px;color: #1f5b5f !important;">Venta: <?= $dia_now ?> de <?= $mes_now ?> de <?= $anio_now ?> a las <?= $hora_now ?> hrs.</p>
            <?php }else{ ?>
                <p class="tit-venta" style="font-size: 25px;color: #1f5b5f !important;">Pedido Pendiente Oxxo: <?= $dia_now ?> de <?= $mes_now ?> de <?= $anio_now ?> a las <?= $hora_now ?> hrs.</p>
            <?php } ?>
        </div>
        <table class="vent-table" style="margin: 5% auto;width: 80%;">
            <thead>
                <th colspan="2" class="tit-princ" style="text-align: left;padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;font-size: 20px;font-weight: 600 !important;">
                    Título
                </th>
                <th class="tit-princ" style="text-align: left;padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;font-size: 20px;font-weight: 600 !important;text-align: right;">
                    Subtotal
                </th>
            </thead>
                <?php  
                    if($tarjeta){ 
                        $cant=count($libros);
                        for ($i = 0; $i < $cant; $i++){
                            $foto=$libro->fotoCorreo($libros[$i][0]);
                ?>
            <tr>
                <td style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;width: 120px;"> 
                    <?php if($libros[$i][0] == $foto['id'] && $foto['portada']){ ?>
                        <img src="https://www.lectorum.com.mx/images/<?php print_r($foto['portada']) ?>" style="width:100px;float: left;">
                    <?php } else {?>
                        <img src="<?php print_r($portada) ?>" style="width:100px;float: left;">
                    <?php }?> 
                </td>
                <td class="det-lib" style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;vertical-align: text-top;text-align: left;font-weight: 600 !important;">
                    <table style="width: 80%">
                        <tr>
                            <td style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;text-transform:capitalize;padding-top: 0;"><hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                <span class="cat-tit sp-marg" style="color: #999;font-weight: 600 !important;width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Título:</span> <?= mb_strtolower($libros[$i][2]) ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;text-transform:capitalize;padding-top: 5px;">
                                <hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                <span class="cat-tit sp-marg" style="color: #999;font-weight: 600 !important;width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Autor:</span> <?= mb_strtolower($libros[$i][1]) ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;font-size:13px;height:20px;padding-top: 5px;">
                                <hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                <span class="cat-tit sp-marg" style="color: #999;font-weight: 600 !important;width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Cantidad:</span> <?= $libros[$i][5] ?>
                            </td>
                        </tr>
                        <?php if($libros[$i][3] && $libros[$i][3] >0){
                            $totalp = $totalp + ($libros[$i][5] * $libros[$i][3]);
                            $cantidades = $cantidades + $libros[$i][5]; 
                        }elseif ($cupon_global && !$libros[$i][3]>=0) {
                            $precioTotal= $libros[$i][4] - (($cupon_global->porcentaje / 100) * $libros[$i][4]);
                            $totalp = $totalp + ($libros[$i][5] * $precioTotal);
                            $cantidades = $cantidades + $libros[$i][5]; 
                        }else{ 
                            $totalp = $totalp + ($libros[$i][5] * $libros[$i][4]);
                            $cantidades = $cantidades + $libros[$i][5]; 
                        }
                        $total_libros = $total_libros + $libros[$i][5];
                        ?>
                        <tr>
                            <td style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;font-size:13px;height:20px;padding-top: 5px;">
                                <hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                <span class="cat-tit sp-marg" style="color: #999;font-weight: 600 !important;width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Precio: </span>
                                <?php if($libros[$i][3] && $libros[$i][3] >0){
                                    echo '<span class="promo-span-tac" style="font-weight: 600 !important;text-decoration-line: line-through;">$'.$libros[$i][4].'</span><span style="font-weight:600;margin-left: 5px;">$'.$libros[$i][3].'</span>'; 
                                }elseif ($cupon_global && !$libros[$i][3]>=0) {
                                    echo '<span class="promo-span-tac" style="font-weight: 600 !important;text-decoration-line: line-through;">$'.$libros[$i][4].'</span><span style="font-weight:600;margin-left: 5px;">$'.$precioTotal.'</span>'; 
                                } else{ 
                                    echo '$'.$libros[$i][4]; 
                                } ?>
                                <hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;text-align: right;">
                    <?php if($libros[$i][3] && $libros[$i][3] >0){
                        echo number_format($libros[$i][3]*$libros[$i][5], 2); 
                    }elseif ($cupon_global && !$libros[$i][3]>=0) {
                        echo number_format($precioTotal*$libros[$i][5], 2); 
                    } else{ 
                        echo number_format($libros[$i][4]*$libros[$i][5], 2); 
                    } ?>
                </td>
            </tr>
                <?php } }else{ 
                    for ($i = 0; $i < count($libros); $i++){
                        $autor=$libro->autor($libros[$i]->id);
                        $foto=$libro->fotoCorreo($libros[$i]->id);
                ?>
                <tr>
                    <td style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;width: 120px;"> 
                        <?php if($libros[$i]->id == $foto['id'] && $foto['portada']){ ?>
                            <img src="https://www.lectorum.com.mx/images/<?php print_r($foto['portada']) ?>" style="width:100px;float: left;">
                        <?php } else {?>
                              <img src="<?php print_r($portada) ?>" style="width:100px;float: left;">
                        <?php }?> 
                    </td>
                    <td class="det-lib" style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;vertical-align: text-top;text-align: left;font-weight: 600 !important;">
                        <table style="width: 80%">
                            <tr>
                                <td style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;text-transform:capitalize;padding-top: 0;"><hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                    <span class="cat-tit sp-marg" style="color: #999;font-weight: 600 !important;width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Título:</span> <?= mb_strtolower($libros[$i]->titulo) ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;text-transform:capitalize;padding-top: 5px;">
                                    <hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                    <span class="cat-tit sp-marg" style="color: #999;font-weight: 600 !important;width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Autor:</span> <?= mb_strtolower($autor['autor']) ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;font-size:13px;height:20px;padding-top: 5px;">
                                    <hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                    <span class="cat-tit sp-marg" style="color: #999;font-weight: 600 !important;width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Cantidad:</span>
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
                                    $precioTotal= $libros[$i]->pvp - (($cupon_global->porcentaje / 100) * $libros[$i]->pvp);
                                    $totales = $totales + ($precioTotal * $cantidades);
                                }else{ 
                                    $totales = $totales + ($libros[$i]->pvp * $cantidades);
                                }
                                $total_libros = $total_libros + $cantidades;
                            ?>
                            <tr>
                                <td style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;font-size:13px;height:20px;padding-top: 5px;"><hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                    <span class="cat-tit sp-marg" style="color: #999;font-weight: 600 !important;width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Precio: </span>
                                    <?php if($libros[$i]->promo && $libros[$i]->promo>0){ 
                                        echo '<span class="promo-span-tac" style="font-weight: 600 !important;text-decoration-line: line-through;">$'.$libros[$i]->pvp.'</span><span style="font-weight:600;margin-left: 5px;">$'.$libros[$i]->promo.'</span>'; 
                                    }elseif ($cupon_global && !$libros[$i]->promo >=0) {
                                        echo '<span class="promo-span-tac" style="font-weight: 600 !important;text-decoration-line: line-through;">$'.$libros[$i]->pvp.'</span><span style="font-weight:600;margin-left: 5px;">$'.$precioTotal.'</span>'; 
                                    }else{ 
                                        echo '$'.$libros[$i]->pvp; 
                                    } ?>
                                    <hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;text-align: right;">
                        <?php if($libros[$i]->promo && $libros[$i]->promo>0){ 
                            echo number_format($libros[$i]->promo*$cantidades, 2); 
                        }elseif ($cupon_global && !$libros[$i]->promo>=0) {
                            echo number_format($precioTotal*$cantidades,2); 
                        }else{ 
                            echo number_format($libros[$i]->pvp*$cantidades,2); 
                        } ?>
                    </td>
                </tr>
            <?php }} ?>
                <tr>
                    <td colspan="2" style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;vertical-align: bottom;text-align: right;padding-right: 13%;">
                        <span class="cat-tit sp-marg" style="color: #999;font-weight: 600 !important;width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Total:</span><b> <?= $total_libros ?> Libros</b>
                    </td>
                    <td style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;border-top: 1px solid #eeeeee; text-align: right;">
                        <?php if($cupon && $desc){ 
                                $totalp = $totales; 
                            ?>
                            <p style="margin-bottom: 5px;"><span style="position: relative;right: 25px;">Subtotal:</span> <span style="color: #333;font-weight: normal;position: relative;float: right;">$<?= number_format($totalp, 2);  ?></span>
                            </p>
                            <p style="margin-bottom: 5px;"><span style="position: relative;right: 25px;">Descuento cupón:</span> <span style="color: #333;font-weight: normal;position: relative;float: right;">-$<?= number_format($desc, 2)  ?></span></p>
                            <p style="margin-bottom: 5px;"><span style="position: relative;right: 25px;">Envío:</span> <span style="color: #333;font-weight: normal;position: relative;float: right;">$<?php 
                                    echo number_format($subtotal - $totalp + $desc,2); 
                                ?></span></p>
                            <p><span style="position: relative;right: 25px;">Total:</span> <b style="color: #333;position: relative;float: right;">$<?php 
            
                                    echo number_format($subtotal, 2); 
                                ?>
                                </b></p>
                        <?php }elseif (!$cupon && $desc) { $totalp = $totales; ?>
                            <p style="margin-bottom: 5px;"><span style="position: relative;right: 25px;">Subtotal:</span> <span style="color: #333;font-weight: normal;position: relative;float: right;">$<?= number_format($totalp, 2);  ?></span>
                            </p>
                            <p style="margin-bottom: 5px;"><span style="position: relative;right: 25px;">Descuento puntos:</span> <span style="color: #333;font-weight: normal;position: relative;float: right;">-$<?= number_format($desc, 2)  ?></span></p>
                            <p style="margin-bottom: 5px;"><span style="position: relative;right: 25px;">Envío:</span> <span style="color: #333;font-weight: normal;position: relative;float: right;">$<?php 
                                    echo number_format($subtotal - $totalp + $desc,2); 
                                ?></span></p>
                            <p><span style="position: relative;right: 25px;">Total:</span> <b style="color: #333;position: relative;float: right;">$<?php 
            
                                    echo number_format($subtotal, 2); 
                                ?>
                                </b></p>
                        <?php } else{ $totalp = $totales;  ?>
                            <p style="margin-bottom: 5px;"><span style="position: relative;right: 25px;">Subtotal:</span> <span style="color: #333;font-weight: normal;position: relative;float: right;">$<?= number_format($totalp, 2);  ?></span>
                            </p>
                            <p style="margin-bottom: 5px;"><span style="position: relative;right: 25px;">Envío:</span> <span style="color: #333;font-weight: normal;position: relative;float: right;">$<?php 
                                    echo number_format($subtotal - $totalp,2); 
                                ?></span></p>
                            <p><span style="position: relative;right: 25px;">Total:</span> <b style="color: #333;position: relative;float: right;">$<?php 
            
                                    echo number_format($subtotal, 2); 
                                ?>
                                </b></p>
                        <?php } ?>
                    </td>
                </tr>
        </table>
        <table class="dat-compr" style="width: 100%;background-color: #f4f6f6;">
            <tr>
                <td class="left-data" style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;text-align: left;padding-left: 10%;width: 50%;border-right: 1px solid #d1d1d1;padding-top: 5%;">
                    <span class="tit-princ" style="font-size: 20px;font-weight: 600 !important;">Envío</span>
                    <p style="margin: 10px 0;color: #333!important;text-transform: capitalize;"><?= mb_strtolower($cadena) ?></p>
                    <?php if($num_guia){  ?>
                        <p style="margin: 10px 0;color: #333!important;text-transform: capitalize;">No Seguimiento: <?= $num_guia ?></p>
                        <div class="btnVerguia" style="border-radius: 2px;position: relative;background-color: #379fa2;padding: 8px 0px;width: 30%;margin: 0;font-weight: bold;margin-bottom: 15px;text-align: center;">
                                <a href="http://www.lectorum.com.mx/label/<?= $num_guia ?>.pdf" target="_blank" style="color: #fff;font-size: 12px;text-decoration: none;">Imprimir Guía</a>
                        </div>
                    <?php }else{ ?>
                        <p style="margin: 10px 0;color: #333!important;text-transform: capitalize;">No Seguimiento: N/A</p>
                    <?php } ?>
                </td>
                <td rowspan="2" class="right-data" style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;text-align: left;padding-left: 2%;padding-top: 5%;">
                    <span class="tit-princ" style="font-size: 20px;font-weight: 600 !important;">Cliente</span>

                    <p style="margin:12px 0;"><span class="sp-marg" style="width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Nombre: </span><span class="blvk" style="color: #333;font-weight: normal!important;"><?= $clientes->nombre.' '.$clientes->apellidos  ?></span></p>
                    <hr class="datos-hr" style="margin-top: 7px;margin-bottom: 7px;width: 80%;margin-right: 20%;">

                    <p style="margin:12px 0;"><span class="sp-marg" style="width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Teléfono: </span><span class="blvk" style="color: #333;font-weight: normal!important;"><?= $clientes->telefono  ?></span></p>
                    <hr class="datos-hr" style="margin-top: 7px;margin-bottom: 7px;width: 80%;margin-right: 20%;">

                    <p style="margin:12px 0;"><span class="sp-marg" style="width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Correo: </span><span class="blvk" style="color: #333;font-weight: normal!important;"><?= $clientes->email  ?></span></p>
                    <hr class="datos-hr" style="margin-top: 7px;margin-bottom: 7px;width: 80%;margin-right: 20%;">

                    <p style="margin:12px 0;"><span class="sp-marg" style="width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Domicilio: </span><span class="blvk" style="text-transform: capitalize;"><?= $clientes->calle.' '.$clientes->num_ext  ?> <?php if($clientes->num_int){ echo('int '.$clientes->num_int); } ?></span></p>
                    <hr class="datos-hr" style="margin-top: 7px;margin-bottom: 7px;width: 80%;margin-right: 20%;">

                    <p style="margin:12px 0;"><span class="sp-marg" style="width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Colonia: </span><span class="blvk" style="color: #333;font-weight: normal!important;"><?= $clientes->colonia ?></span></p>
                    <hr class="datos-hr" style="margin-top: 7px;margin-bottom: 7px;width: 80%;margin-right: 20%;">

                    <p style="margin:12px 0;"><span class="sp-marg" style="width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Alcaldía: </span><span class="blvk" style="color: #333;font-weight: normal!important;"><?=$clientes->delegacion ?></span></p>
                    <hr class="datos-hr" style="margin-top: 7px;margin-bottom: 7px;width: 80%;margin-right: 20%;">

                    <p style="margin:12px 0;"><span class="sp-marg" style="width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">C.P.: </span><span class="blvk" style="color: #333;font-weight: normal!important;"><?= $clientes->cp ?></span></p>
                    <hr class="datos-hr" style="margin-top: 7px;margin-bottom: 7px;width: 80%;margin-right: 20%;">

                    <p style="margin:12px 0;"><span class="sp-marg" style="width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Estado: </span><span class="blvk" style="color: #333;font-weight: normal!important;"><?= $estado_mundo->estadonombre ?></span></p>
                    <hr class="datos-hr" style="margin-top: 7px;margin-bottom: 7px;width: 80%;margin-right: 20%;">

                </td>
            </tr>
            <tr>
                <td class="left-data" style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;text-align: left;padding-left: 10%;width: 50%;border-right: 1px solid #d1d1d1;">
                    <span class="tit-princ" style="font-size: 20px;font-weight: 600 !important;">Datos de Entrega</span>
                    <p style="margin: 10px 0;color: #333!important;text-transform: capitalize;margin-top: 15px;"><span class="sp-marg" style="width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Calle: </span><span class="blvk" style="color: #333;font-weight: normal!important;"><?= $clientes->calle.' '.$clientes->num_ext  ?> <?php if($clientes->num_int){ echo('int '.$clientes->num_int); } ?></span></p>
                    <hr class="datos-hr" style="margin-top: 7px;margin-bottom: 7px;width: 80%;margin-right: 20%;">
                    <p style="margin: 10px 0;color: #333!important;text-transform: capitalize;"><span class="sp-marg" style="width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Colonia: </span><span class="blvk" style="color: #333;font-weight: normal!important;"><?= $clientes->colonia ?></span></p>
                    <hr class="datos-hr" style="margin-top: 7px;margin-bottom: 7px;width: 80%;margin-right: 20%;">
                    <p style="margin: 10px 0;color: #333!important;text-transform: capitalize;"><span class="sp-marg" style="width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Alcaldía: </span><span class="blvk" style="color: #333;font-weight: normal!important;"><?=$clientes->delegacion ?></span></p>
                    <hr class="datos-hr" style="margin-top: 7px;margin-bottom: 7px;width: 80%;margin-right: 20%;">
                    <p style="margin: 10px 0;color: #333!important;text-transform: capitalize;"><span class="sp-marg" style="width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">C.P.: </span><span class="blvk" style="color: #333;font-weight: normal!important;"><?= $clientes->cp ?></span></p>
                    <hr class="datos-hr" style="margin-top: 7px;margin-bottom: 7px;width: 80%;margin-right: 20%;">
                    <p style="margin: 10px 0;color: #333!important;text-transform: capitalize;"><span class="sp-marg" style="width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Estado: </span><span class="blvk" style="color: #333;font-weight: normal!important;"><?= $estado_mundo->estadonombre ?></span></p>
                    <hr class="datos-hr" style="margin-top: 7px;margin-bottom: 7px;width: 80%;margin-right: 20%;">
                </td>
            </tr>
            <tr>
                <td class="left-data" style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;text-align: left;padding-left: 10%;width: 50%;border-right: 1px solid #d1d1d1;"></td>
                <td class="right-data" style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;text-align: left;padding-left: 2%;padding-bottom: 4%;">
                    <span class="tit-princ" style="font-size: 20px;font-weight: 600 !important;">Pago</span>

                        <?php if($test){ ?>
                            <p style="margin:12px 0;text-transform: capitalize;">Paypal</p>
                        <?php }else{ ?>
                            <p style="margin:12px 0;text-transform: capitalize;">OxxoPay</p>
                        <?php } ?>

                    <p style="margin:12px 0;">Estatus: <span class="blvk" style="color: #333;font-weight: normal!important;">Pagado</span></p>
                </td>
            </tr>
        </table>
    </div>
    <div style="text-align: center; font-size: 12px; padding-top: 20px; padding-bottom: 20px;">
        ¿Necesitas Ayuda?
        <br><br>
        <span style="font-size: 12px;">Contáctanos a <a href="mailto:digital@lectorum.com.mx" style="color: #1f5b5f;">digital@lectorum.com.mx</a> o llámanos <span style="color: #3ed1b8">(55) 5581 3202</span></span>
    </div>
</div>