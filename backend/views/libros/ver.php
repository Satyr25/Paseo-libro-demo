<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>

    <div class="container contenedor" id="libros-ver">
        <div class="row">
            <div class="col-md-12">
                <div class="row row-regresar">
                    <div class="col-md-2">
                        <a href="<?= Url::toRoute('index')?>" class="link-boton-regresar"> <?= Html::img('@web/images/regresar.png', ['class' => 'img-responsive boton-regresar']) ?> Regresar</a>
                    </div>
                </div>
                <div class="row ver-titulo-row">
                    <div class="col-md-6">
                        <h3 class="libro-ver-titulo">Detalle del Libro</h3>
                    </div>
                    
                    <?php if (Yii::$app->user->identity->rol_id == '2'){ ?> 
                        <div class="col-md-offset-4 col-md-2">  
                            <?= Html::a('Editar', ['libros/editar', 'id' => $libro->id], ['class' => 'editorial-enlaces']) ?>
                        </div>      
                    <?php } ?>     
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php if ($libro->novedad == 1) { ?> 
                            <p class="texto-novedad">
                                Este libro esta marcado como una novedad.
                            </p>
                        <?php } ?> 
                        <?php if ($libro->recomendacion == 1) { ?> 
                            <p class="texto-novedad">
                                Este libro esta marcado como una recomendación del mes.
                            </p>
                        <?php } ?> 
                    </div>
                </div>
                <div class="row ver-libro-tabla">
                    <div class="col-md-6">
                        <p>Código de barras</p>
                        <p><?= $libro->codigo_barras ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Inventario Disponible</p>
                        <p><?= $libro->cantidad ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Título</p>
                        <p><?= ucfirst(mb_strtolower($libro->titulo)) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Precio de Venta al Público</p>
                        <p><?= $libro->pvp ?></p>
                    </div>
                    <?php if (Yii::$app->user->identity->rol_id == '1'){ ?> 
                        <div class="col-md-offset-6 col-md-6">
                            <p>Precio de Promoción</p>
                            <?php $form = ActiveForm::begin([
                            ])?>
                                <?= $form->field($libro, 'promo')->textInput(['value' => $libro->promo])->label(false) ?>
                            <?php ActiveForm::end()?>
                        </div>
                    <?php } ?> 
                    <div class="col-md-6">
                        <p>Autor</p>
                        <p> <?= ucwords(mb_strtolower($libro->autor)) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Subtítulo</p>
                        <p> <?= ucfirst(mb_strtolower($libro->subtitulo)) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>ISBN</p>
                        <p><?= $libro->isbn ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Interno</p>
                        <p><?= $libro->interno ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Edición</p>
                        <p><?= $libro->anio ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Sello</p>
                        <p><?= $libro->sello ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Colección</p>
                        <p><?= $libro->coleccion ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Tema</p>
                        <p><?= $libro->tema ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Páginas</p>
                        <p> <?= $libro->paginas ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Peso</p>
                        <p> <?= $libro->peso ?></p>
                    </div>
                    <div class="col-md-4">
                        <p>Profundo</p>
                        <p> <?= $libro->profundo ?></p>
                    </div>
                    <div class="col-md-4">
                        <p>Alto</p>
                        <p> <?= $libro->alto ?></p>
                    </div>
                    <div class="col-md-4">
                        <p>Largo</p>
                        <p> <?= $libro->largo ?></p>
                    </div>
                    <div class="col-md-3">
                        <p>Portada</p>
                        <?php if($libro->portada){ ?> 
                            <?= Html::img('@web/images/'.$libro->portada, ['class' => 'img-responsive']) ?>
                        <?php } else { ?> 
                            <?= Html::img('@web/images/portada_default.jpg', ['class' => 'img-responsive']) ?>
                        <?php }  ?>
                    </div>
                    <div class="col-md-9">
                        <p>Descripción</p>
                        <p class="descripcion"> <?= $libro->descripcion ?> </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--</section>-->