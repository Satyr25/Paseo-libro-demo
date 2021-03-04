<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<?php
$list = Yii::$app->request->BaseUrl.'/images/igual.png';
?>

<?php
//datos de ejemplo para probar que pueden admitir desde una BD
$portada = Yii::$app->request->BaseUrl.'/images/PortadaNoDisponible.png';

$iconoc = Yii::$app->request->BaseUrl.'/images/close_icon.png';
$totalp = 0;

?>
<?php if (count($libros) >=1){ ?>
    <div class="bolsaindex" id="bolsaindex">
        <div class="left-bol" id="left-bol">
            <div id="btnSeguir-mob">
                <a href="<?= Url::to(['catalogo/']); ?>" id="myBtn">Continuar comprando ></a>
            </div>
            <div class="titbol">
                Bolsa de compra
            </div>
            <div class="titprod">
                Productos
            </div>
            <?php foreach ($libros as $carritoInfo) { ?>
                <table class="detalle-lista-bolsa">
                    <tr>
                        <td class="producto">
                            <div class="producto">
                                <?php if($carritoInfo->promo && $carritoInfo->promo>0 || $cupon_global){ ?>
                                    <span class="pleca-promo-libro-bolsa">Oferta</span>
                                <?php } ?>
                                <?php if($carritoInfo->portada){ ?>
                                    <img class="imgprod" src="<?php print_r(Yii::$app->request->BaseUrl.'/images/'.$carritoInfo->portada);?>">
                                <?php } else {?>
                                    <img class="imgprod" src="<?php print_r($portada);?>">
                                <?php } ?>
                            </div>
                        </td>
                        <td class="max-width-bolsa">
                            <div class="info-prod">
                                <div class="tit-prod">
                                    <?php print_r($carritoInfo->titulo);?>
                                </div>
                                <br>
                                <div class="pre-prod">
                                    <?php if($carritoInfo->promo && $carritoInfo->promo>0){ ?>
                                        <span class="promo">$<?= number_format($carritoInfo->promo,2); ?></span><span class="orig">$<?= number_format($carritoInfo->precio,2); ?></span>
                                    <?php $totalp = $totalp + ($carritoInfo->promo * $carritoInfo->cantidad);
                                    }elseif ($cupon_global && !$carritoInfo->promo>=0) { $precioTotal= $carritoInfo->precio - (($cupon_global->porcentaje / 100) * $carritoInfo->precio); $totalp = $totalp + ($precioTotal * $carritoInfo->cantidad); ?>
                                        <span class="promo">$<?= number_format($precioTotal,2) ?></span><span class="orig">$<?= number_format($carritoInfo->precio,2); ?></span>
                                    <?php }else{ 
                                        print_r(number_format($carritoInfo->precio,2)); $totalp = $totalp + ($carritoInfo->precio * $carritoInfo->cantidad); 
                                    } ?>
                                </div>
                                <br>
                                Cantidad
                                <div class="cantidadProducto campoProducto">
                                    <div class="campoCantidadProducto">
                                        <div class="menos botonLateral">
                                            <a href="javascript:;" id="<?= $carritoInfo->libro ?>" value="<?= $carritoInfo->cantidad ?>"class="productoMenos">-</a>
                                        </div>
                                        <input class="cantidadLibros" value="<?= $carritoInfo->cantidad ?>" type="text">
                                        <div class="mas botonLateral">
                                            <a href="javascript:;" id="<?= $carritoInfo->libro ?>" value="<?= $carritoInfo->cantidad ?>" class="productoMas">+</a>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="quitar" id="<?= $carritoInfo->libro?>" data-titulo="<?= $carritoInfo->titulo ?>"
                                    data-cantidad="<?= $carritoInfo->cantidad ?>" data-isbn="<?= $carritoInfo->isbn ?>"
                                    data-precio="<?= $carritoInfo->precio ?>" data-sello="<?= $carritoInfo->sello ?>" data-tema="<?= $carritoInfo->tema ?>">
                                    <a href="javascript:;" id="btnQuit">Quitar</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <br>
                </table>
            <?php } ?>
        </div>
        <div class="right-bol" id="right-bol">
            <div id="btnSeguir">
                <a href="<?= Url::to(['catalogo/']); ?>" id="myBtn">Continuar comprando ></a>
            </div>
            <div class="titsub">
                Subtotal
            </div>
            <br>
            <div class="pre-sub"> 
                $<span id="spTotal">
                        <?= number_format($totalp, 2);?>
                </span>
            </div>
            <br>
            <div class="men-sub">
                Costo de envío no incluido
            </div>
            <div class="btnpago">
                <a href="<?= Url::to(['checkout/']); ?>">Proceder con el pago</a>
            </div>
        </div>
    </div>
<?php } else {?>
    <div class="todovacio">
        <div class="bvacia" id="bvacia">
            <div class="titbol">
                Bolsa de compra
            </div>
            <div class="titprod">
                No hay productos en la bolsa
            </div>
        </div>
    <div class="mvacio" id="mvacio">
        <div id="btnSeguir">
            <a href="<?= Url::to(['catalogo/']); ?>" id="myBtn">Continuar comprando ></a>
        </div>
        <div class="titsub">
            Subtotal
        </div>
        <br>
        <div class="pre-sub">
            $<span id="spTotal"></span> 
        </div>
        <br>
        <div class="men-sub">
            Costo de envío no incluido
        </div>
        <div class="btnpago">
            <a class="bloqueado" href="javascript:;">Proceder con el pago</a>
        </div>
    </div>
</div>
<?php } ?>

