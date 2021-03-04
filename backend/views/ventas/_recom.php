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
$cantidades = 0;
$palabra_marca = 0;
$total_libros = 0;
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
<div style="background-color: #04685c;padding: 20px 0px; text-align:center;">
    <a href="https://www.lectorum.com.mx">
        <img src="https://www.lectorum.com.mx/images/LectorumLogo.png" style = "width: 130px;">
    </a>
</div>
<div style="background-color: #f9f9f9;color: #333;">
    <div style="font-weight: bold;font-size:19px;padding-top:20px;padding-bottom:10px; text-align: center; font-family: 'Raleway'">
        <?php if($nombre){echo '¡Hola '.$nombre.'!';}else{echo '¡Hola!';} ?>
    </div>
    <div style="font-size:19px;padding-top:0px;padding-bottom:20px; text-align: center; font-family: 'Raleway' ">
        <?php 
         ?>
    </div>
</div>
<div style="background-color: #e2e2e3;color:#333;" class="body-container" style="font-family: 'Raleway', sans-serif;font-weight: 600;line-height: 1;font-size: 14px;color: #333333;">
    <br>
    <div style="background-color: #fff; margin-left: 50px; margin-right: 50px; padding-bottom: 40px;">
        <div style="text-align: center; padding-top: 20px; font-size: 12px;">
            <p class="tit-venta" style="font-size: 25px;color: #1f5b5f !important;">!Lectorum tiene las siguientes recomendaciones para tí!</p>
        </div>
        <table class="vent-table" style="margin: 5% auto;width: 80%;border-collapse: separate;border-spacing: 0 25px;">
            <thead>
                <th colspan="2" class="tit-princ" style="text-align: left;padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;font-size: 20px;font-weight: 600 !important;">
                    Título
                </th>
            </thead>
                <?php
                        foreach ($libros as $librox) {

                ?>
            <tr>
                <td style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;width: 120px;"> 
                    <?php if($librox->portada){ ?>
                            <img src="https://www.lectorum.com.mx/images/<?= $librox->portada ?>" style="width:100px;float: left;">
                        <?php } else {?>
                              <img src="<?php print_r($portada) ?>" style="width:100px;float: left;">
                        <?php }?>       
                </td>
                <td class="det-lib" style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;vertical-align: text-top;text-align: left;font-weight: 600 !important;">
                    <table style="width: 80%">
                        <tr>
                            <td style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;text-transform:capitalize;padding-top: 0;"><hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                <span class="cat-tit sp-marg" style="color: #999;font-weight: 600 !important;width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Título:</span> <?= mb_strtolower($librox->titulo) ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 15px;padding: 0;font-weight: 600 !important;color: #333;text-transform:capitalize;padding-top: 5px;">
                                <hr class="lib-hr" style="margin-bottom: 7px;margin-top: 7px;border-top: 1px solid #eeeeee;box-sizing: content-box;height: 0;">
                                <span class="cat-tit sp-marg" style="color: #999;font-weight: 600 !important;width: 20%;display: inline-block;min-width: 80px;color: #999;font-weight: 600 !important;">Autor:</span> <?= mb_strtolower($librox->autor) ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
    <div style="text-align: center; font-size: 14px; padding-top: 20px; padding-bottom: 20px;">
        ¿Necesitas Ayuda?
        <br><br>
        <span style="font-size: 12px;">Contáctanos a <a href="mailto:digital@lectorum.com.mx" style="color: #1f5b5f;">digital@lectorum.com.mx</a> o llámanos <span style="color: #1f5b5f">(55) 5581 3202</span></span>
    </div>
</div>