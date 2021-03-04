<?php

use yii\helpers\Url;

$ini = parse_ini_file("../private.ini");
$baseurl = Url::base(true);

if ($baseurl == 'http://localhost/uppl/backend/web') {
    $key = $ini['publicKey'];
} else {
    $dominio = explode('.', $baseurl);
    if ($dominio[1] == 'unpaseoporloslibros'){
        $key = $ini[substr($dominio[0], -4)];
    } else {
        $key = $ini[$dominio[1]];
    }
    var_dump($baseurl);
    var_dump($dominio);
    var_dump($key);
}
?>

<input type="hidden" id="copy-tarjeta" value="">
<input type="hidden" id="copy-nombre" value="">
<input type="hidden" id="copy-mes" value=''>
<input type="hidden" id="copy-anio" value=''>
<input type="hidden" id="copy-seguridad" value=''>

<input type="hidden" id="copy-telefono" value=''>
<input type="hidden" id="copy-email" value=''>
<input type="hidden" id="copy-calle" value=''>
<input type="hidden" id="copy-cp" value=''>

<input type="hidden" id="copy-cupon" value=''>
<input type="hidden" id="copy-descuento" value=''>
<input type="hidden" id="copy-porcentaje" value=''>

<input type="hidden" id="copy-carrito" value=''>
<input type="hidden" id="copy-key" value="<?=$key?>">
<input type="hidden" id="copy-editorial-id" value="">

<input type="hidden" id="copy-subtotal" value="">

<input type="hidden" id="copy-editorial-clave" value="">

<input type="hidden" id="copy-exito" value="">
<input type="hidden" id="copy-order" value="">
<input type="hidden" id="copy-monto" value="">
<input type="hidden" id="copy-codigo" value="">
<input type="hidden" id="copy-numeros" value="">
<input type="hidden" id="copy-marca" value="">
<input type="hidden" id="copy-tipo" value="">

<button class="prueba-alerta"></button>

