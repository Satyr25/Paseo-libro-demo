<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\moldels\Tema;
use yii\bootstrap\ActiveForm;

$ruta = Yii::$app->request->referrer
?>
<div class="container">
    <div class="row row-regresar">
        <div class="col-md-2">
            <a href="<?=$ruta?>" class="link-boton-regresar"> <?= Html::img('@web/images/regresar.png', ['class' => 'img-responsive boton-regresar']) ?> Regresar</a>
        </div>
    </div>
    <div class="row">
        <div class=" col-md-4">
            <h3 class="titulo-categoria">Novedades</h3>
        </div>
    </div>
    <div class="row">
    <div class="row catalogo-row-libros6">
    <?php   foreach($novedades as $libro){ ?>
        <div class="col-xs-6 col-sm-2 tarjeta-libro">
            <a href="<?= Url::to(['catalogo/ver', 'id' => $libro->id]) ?>" class="trigger-scale">
                <div class="img-contenedor-libro">
                    <?php if($libro->portada != ''){ ?>
                        <?= Html::img('@web/images/'.$libro->portada ,['class' => 'img-responsive scalable']) ?>
                    <?php }else{ ?>
                        <?= Html::img('@web/images/portada_default.jpg' ,['class' => 'img-responsive scalable']) ?>
                    <?php } ?>
                </div>
                <p class="vendidos-carrusel-titulo"><?= ucfirst(mb_strtolower($libro->titulo)) ?></p>
                <p class="vendidos-carrusel-autor"><?= ucfirst(mb_strtolower($libro->autor)) ?></p>
                <p class="vendidos-carrusel-precio"><?= ucfirst(mb_strtolower($libro->pvp)) ?></p>
            </a>
        </div>
        <?php $i += 1 ?>
        <?php if( ($i % 6) == 0 ){ ?>
            </div>
            <div class="row catalogo-row-libros6">
        <?php } ?>
    <?php } ?>
    </div>
    <div class="row catalogo-row-libros2">
    <?php   foreach($novedades as $libro){ ?>
        <div class="col-xs-6 col-sm-2 tarjeta-libro">
            <a href="<?= Url::to(['catalogo/ver', 'id' => $libro->id]) ?>" class="trigger-scale">
                <div class="img-contenedor-libro">
                    <?php if($libro->portada != ''){ ?>
                        <?php $portada = Html::img('@web/images/'.$libro->portada ,['class' => 'img-responsive scalable']) ?>
                    <?php }else{ ?>
                        <?php $portada = Html::img('@web/images/portada_default.jpg' ,['class' => 'img-responsive scalable']) ?>
                    <?php } ?>
                </div>
                <p class="vendidos-carrusel-titulo"><?= ucfirst(mb_strtolower($libro->titulo)) ?></p>
                <p class="vendidos-carrusel-autor"><?= ucfirst(mb_strtolower($libro->autor)) ?></p>
                <p class="vendidos-carrusel-precio"><?= ucfirst(mb_strtolower($libro->pvp)) ?></p>
            </a>
        </div>
        <?php $i += 1 ?>
        <?php if( ($i % 2) == 0 ){ ?>
            </div>
            <div class="row catalogo-row-libros2">
        <?php } ?>
    <?php } ?>
    </div>
    <?php if (count($novedades) > 11){ ?> 
        <div class="row">
            <div class="col-md-offset-5 col-md-2 col-boton">
                <?= Html::button('Ver más',['class' => 'btn btn-primary', 'id' => 'catalogo-mas' ]) ?>
            </div>
        </div>
    <?php } ?>
</div>
