<!--
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$texto = "Con el presente libro, Sergio Pitol nos aproxima a la vida de diez novelistas ingleses. La narración de momentos cumbres de algunos de sus autores preferidos y el estudio minucioso e imaginativo de una o dos de sus mejores obras, nos hace partícipes de la adicción autoral por la literatura anglosajona.
Los textos que integran este análisis, en una primera mirada, parecen perfilarse como relatos autobiográficos, pero en realidad encubren una prosa híbrida, caracterizada por la heterogeneidad de las voces, los materiales, los géneros literarios que convergen en su interior y que contribuyen a que cobre forma su peculiar identidad estética y estilística.";
$titulo = 'Adicción a los ingleses';
$img = Yii::$app->request->BaseUrl.'/images/PortadaNoDisponible.png';

?>
  <div id="descr-lib" class="descr-lib">
            <div id="portada-modal">
                <?php if($documen[0]->portada){ ?>
                    <img src="<?php print_r(Yii::$app->request->BaseUrl.'/images/'.$documen[0]->portada);?>">
                <?php } else {?>
                        <img src="<?php print_r($img);?>">
                <?php }?>
            </div>
            <div class="carrito-add">
                <div class="titulo-mod" data-titulos="<?= $accion->titulo ?>">
                    <?= mb_strtolower($accion->titulo); ?>
                    <input type="text" value="<?= $tema_nombre->nombre ?>" style="" name="nombre_tema" class="oculto">   
                    <input type="text" value="<?= $accion->isbn ?>" style="" name="isbn" class="oculto"> 
                    <input type="text" value="<?= $sello_nombre->nombre ?>" style="" name="nombre_sello" class="oculto">                    
                </div>
                <?php if($accion->promo && $accion->promo>0){ ?>
                    <div class="precio-mod" value="$<?= $accion->promo ?>">
                        <span>$<?= $accion->promo ?></span>
                    </div>
                <?php }else{ ?>
                    <div class="precio-mod" value="$<?= $accion->pvp ?>">
                        <span>$<?= $accion->pvp ?></span>
                    </div>
                <?php } ?>
                <div class="texto-mod">
                    <?php if($documen[0]->descripcion){ ?>
                        <?php print_r(mb_convert_encoding($documen[0]->descripcion, "UTF-8"));?>
                    <?php } else {?>
                        <?php print_r($texto); ?>
                    <?php }?>
                    
                </div>
                <div class="mensaje-carrito">El libro se agregó a la <a href="<?= Url::to(['bolsa/']); ?>" class="redbolsa" >bolsa</a>.</div>
                <a class="btn-comprar" id="<?= $accion->id?>" href="javascript:;">
                    <div class="comprarbolsa" id="<?= $accion->id?>" >
                        Comprar
                    </div>
                </a>
                <a class="btn-accion" id="agregar-bolsa" href="javascript:;" data-id="<?= $accion->id?>">
                    <div class="addbolsa" id="<?= $accion->id?>" >
                         Agregar a bolsa
                    </div>
                </a>
            </div>
    </div>
            <div class="cont-tit-rel">
                <div class="tit-rel">
                    Títulos relacionados
                </div>
            <?php foreach ($titrel as $key => $val) { ?>
                <div class="librel" id="librel">
                        <div class="colrel" id="<?= $val->id?>">
                            <a href="#modal-ver-libros" rel="modal:open">
                            <div class="portada-modal-rel">
                                <?php if($val->portada){ ?>
                                    <img src="<?php print_r(Yii::$app->request->BaseUrl.'/images/'.$val->portada);?>">
                                <?php } else {?>
                                        <img src="<?php print_r($img);?>">
                                <?php }?>
                            </div>
                                <div id="titulo-cat" class="titulo-novrel">
                                    <?php print_r(mb_strtolower($val->titulo)) ?>
                                </div>
                                <div id="precio-cat" class="precio-novrel">
                                    <span>$<?php if($val->promo && $val->promo>0){print_r($val->promo);}else{ print_r($val->pvp);}  ?></span>
                                </div>
                            </a>
                        </div>
                </div>
            <?php } ?>
            </div>-->
