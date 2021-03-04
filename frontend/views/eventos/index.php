<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>


<section class="eventos-proximos">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <p class="texto-filtro">Filtrar por:</p>
            </div>
            <div class="col-xs-2">
                <span class="texto-filtro2">Tipo de evento: </span>
            </div>
            <div class="col-xs-3">
                <?= Html::dropDownList('categorias', Yii::$app->request->get('categoria'), $categorias, ['class' => 'select-catgorias', 'id' => 'select-categorias']) ?>
            </div>
            <div class="col-xs-1">
                <span class="texto-filtro2">Mes: </span>
            </div>
            <div class="col-xs-3">
                <?= Html::dropDownList('meses', Yii::$app->request->get('mes'), $meses, ['class' => 'select-catgorias', 'id' => 'select-meses']) ?>
            </div>
            <div class="col-xs-2">
                <button class="btn btn-primary" id="filtrar-eventos">Filtrar</button>
            </div>
        </div>
        
        <div class="row ">
            <div class="col-xs-12">
                <p class="titulo-eventos">Próximos eventos</p>
            </div>
        </div>
        <div class="row">
            <?php foreach($proximos as $proximo){ ?> 
            <div class="col-md-6">
                <a href="<?= Url::to(['eventos/ver', 'id' => $proximo->id]) ?>" class="wrap-evento trigger-scale">
                    <?php if($proximo->imagen){ ?> 
                        <div class="col-xs-7 evento-img scalable" style="background-image: url(<?=Url::to('@web/images/'.$proximo->imagen)?>);"></div>
                    <?php } else { ?> 
                        <div class="col-xs-7 evento-img scalable" style="background-image: url(<?=Url::to('@web/images/evento_default.jpg')?>);"></div>
                    <?php }?>
                    <div class="col-xs-5">
                        <div class="wrap-evento-fecha" >
                            <p><?= date("d", $proximo->fecha) ?></p>
                            <p><?= ucfirst(substr(Yii::$app->formatter->asDate($proximo->fecha, 'dd-MMM-Y'), 3,3)) ?></p>
                        </div>
                        <p class="evento-subtitulo" ><?= $proximo->categoria->nombre ?></p>
                        <p calss="evento-texto" ><?= $proximo->nombre ?></p>
                        <p class="evento-subtitulo" >Presentador:</p>
                        <p calss="evento-texto" ><?= $proximo->presentador ?></p>
                        <p class="evento-subtitulo" >Hora:</p>
                        <p calss="evento-texto" ><?= $proximo->hora ?></p>
                    </div>
                </a>
            </div>
            
            <?php } ?> 
        </div>
    </div>
</section>

<section class="eventos-pasados">
    <div class="container">
        <div class="row ">
            <div class="col-xs-12">
                <p class="titulo-eventos2">Eventos pasados</p>
            </div>
        </div>
        <div class="row">
            <?php foreach($pasados as $pasado){ ?> 
            <div class="col-md-6">
                <a href="<?= Url::to(['eventos/ver', 'id' => $pasado->id]) ?>" class="wrap-evento trigger-scale">
                    <?php if($proximo->imagen){ ?> 
                        <div class="col-xs-7 evento-img scalable" style="background-image: url(<?=Url::to('@web/images/'.$proximo->imagen)?>);"></div>
                    <?php } else { ?> 
                        <div class="col-xs-7 evento-img scalable" style="background-image: url(<?=Url::to('@web/images/evento_default.jpg')?>);"></div>
                    <?php }?>
                    <div class="col-xs-5">
                        <div class="wrap-evento-fecha" >
                            <p><?= date("d", $pasado->fecha) ?></p>
                            <p><?= ucfirst(substr(Yii::$app->formatter->asDate($pasado->fecha, 'dd-MMM-Y'), 3,3)) ?></p>
                        </div>
                        <p class="evento-subtitulo" ><?= $pasado->categoria->nombre ?></p>
                        <p calss="evento-texto" ><?= $pasado->nombre ?></p>
                        <p class="evento-subtitulo" >Presentador:</p>
                        <p calss="evento-texto" ><?= $pasado->presentador ?></p>
                        <p class="evento-subtitulo" >Hora:</p>
                        <p calss="evento-texto" ><?= $pasado->hora ?></p>
                    </div>
                </a>
            </div>
            
            <?php } ?> 
        </div>
    </div>
</section>