<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
       
<div class="container-ajax" data-incremento="12">
    <div class="row catalogo-row-libros6">

    <?php   foreach($libros as $libro){ ?>
        <div class="col-xs-6 col-sm-2 tarjeta-libro contador-libros" data-libroid="<?= $libro->id ?>">
            <a href="<?= Url::to(['catalogo/ver', 'id' => $libro->id]) ?>" class="trigger-scale">
            <div class="img-contenedor-libro relacionados-altura">
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
    <?php   foreach($libros as $libro){ ?>
        <div class="col-xs-6 col-sm-2 tarjeta-libro">
            <div class="img-contenedor-libro">
                <?php if($libro->portada != ''){ ?>
                    <?php $portada = Html::img('@web/images/'.$libro->portada ,['class' => 'img-responsive']) ?>
                <?php }else{ ?>
                    <?php $portada = Html::img('@web/images/portada_default.jpg' ,['class' => 'img-responsive']) ?>
                <?php } ?>
                <?= Html::a($portada, ['/catalogo/ver', 'id' => $libro->id]) ?>
            </div>
            <p class="vendidos-carrusel-titulo"><?= ucfirst(mb_strtolower($libro->titulo)) ?></p>
            <p class="vendidos-carrusel-autor"><?= ucfirst(mb_strtolower($libro->autor)) ?></p>
            <p class="vendidos-carrusel-precio"><?= ucfirst(mb_strtolower($libro->pvp)) ?></p>
        </div>
        <?php $i += 1 ?>
        <?php if( ($i % 2) == 0 ){ ?>
            </div>
            <div class="row catalogo-row-libros2">
        <?php } ?>
    <?php } ?>
    </div>
<?php if (count($libros) < 12){ ?> 
    <div class="row">
        <div class="col-xs-12">
            <h2 class="todo-mostrado">Se han mostrado todos los libros disponibles con estos parámetros</h2>
        </div>
    </div>

<?php } ?>
</div>