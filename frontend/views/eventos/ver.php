<?php

use yii\helpers\Html;

Yii::$app->formatter->locale = 'es-ES';

?>


<section class="seccion-blog-ver">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <a href="<?= Yii::$app->request->referrer ?>" class="link-boton-regresar"> <?= Html::img('@web/images/regresar.png', ['class' => 'img-responsive boton-regresar']) ?> Regresar</a>
            </div>
        </div>
        <div class="row evento-titulo">
            <div class="col-xs-12">
                <p>
                    <?= $evento->categoria->nombre ?>: <?= $evento->nombre ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <?php if ($evento->imagen){ ?> 
                    <?= Html::img('@web/images/'.$evento->imagen ,['class' => 'img-responsive']) ?>
                <?php } else { ?> 
                    <?= Html::img('@web/images/evento_default.jpg' ,['class' => 'img-responsive']) ?>
                <?php } ?>
            </div>
            <div class="col-md-5">
                <p class="evento-subtitulo">Fecha y Hora</p>
                <p class="evento-texto"><?= Yii::$app->formatter->asDate($evento->fecha, 'd').' de '.Yii::$app->formatter->asDate($evento->fecha, 'MMMM').' del '.Yii::$app->formatter->asDate($evento->fecha, 'Y').', '.$evento->hora ?></p>
                <p class="evento-subtitulo">Lugar</p>
                <p class="evento-texto"> <?= $evento->lugar ?> </p>
                <p class="evento-subtitulo">Presentador</p>
                <p class="evento-texto"> <?= $evento->presentador ?> </p>
                <p class="evento-descripcion"> <?= $evento->descripcion ?> </p>
            </div>
        </div>
    </div>
</section>