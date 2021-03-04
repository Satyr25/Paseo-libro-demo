<?php
use frontend\assets\SlickAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;


SlickAsset::register($this);
$ruta = Yii::$app->request->referrer;
$img = Yii::$app->request->BaseUrl.'/images/PortadaNoDisponible.png';
?>

<div class="container" id='catalogo-container'>
    <div class="row row-regresar">
        <div class="col-md-4">
            <a href="<?=$ruta?>" class="link-boton-regresar"> <?= Html::img('@web/images/regresar.png', ['class' => 'img-responsive boton-regresar']) ?> Regresar</a>
        </div>
        <div class="col-md-8 mensaje-carrito">
            <a href="<?= Url::to(['/checkout/index']); ?>" class="redbolsa" >El libro se agregó al carrito correctamente.</a>
        </div>
    </div>
    <div class="row row-ficha-ver hide-mobile">
        <div class="col-md-4">
            <?php if($documen->portada != ''){ ?>
                <?= Html::img('@web/images/'.$documen->portada , ['class' => 'img-responsive ver-portada center-block']) ?>
            <?php }else{ ?>
                <?= Html::img('@web/images/portada_default.jpg' , ['class' => 'img-responsive ver-portada center-block']) ?>
            <?php } ?>
            <p class="azul">Compartir</p>
            <p class="row-ver-social">
                <?= \ymaker\social\share\widgets\SocialShare::widget([
                    'configurator'  => 'socialShare',
                    'url'           => \yii\helpers\Url::to(['/catalogo/ver', 'id' => $libro->id], true),
                    'title'         => ucfirst(mb_strtolower($libro->titulo)),
                    'description'   => ucfirst(mb_strtolower($libro->autor)),
                    'imageUrl'      => \yii\helpers\Url::to('@web/images/'.$documen->portada.'', true),
                ]); ?>
            </p>
            <p class="azul">Entrega</p>
            <p class="negro">Día Siguiente</p>
        </div>
        <div class="col-md-8">
            <p class="ver-titulo"><?= ucfirst(mb_strtolower($libro->titulo, 'UTF-8')) ?></p>
            <p class="ver-autor"><?= ucwords(mb_strtolower($libro->autor, 'UTF-8')) ?></p>
            <div class="row row-ver-botones">
                <div class="col-md-2">
            <p class="ver-precio">$<?= $libro->pvp ?></p>
               </div>
                <div class="col-md-offset-1 col-md-4 col-sm-6">
                    <a class="btn-comprar agregar-bolsa" id="agregar-bolsa" href="javascript:;" data-id="<?= $libro->id?>">
                        <div class="addbolsa" id="<?= $libro->id?>" >
                             Agregar a carrito
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-sm-6">
            <a class="btn-libro" id="<?= $libro->id?>" href="javascript:;">
                <div class="comprarbolsa" id="<?= $libro->id?>" >
                    Comprar
                </div>
            </a>
                </div>
            </div>
            <p class="ver-descripcion"><?= $documen->descripcion ?></p>
            <div class="row">
                <div class="col-md-8">
                    <table class="table ver-tabla">
                        <tbody>
                            <tr>
                                <td>Editorial</td>
                                <td><?= $libro->editorial ?></td>
                            </tr>
                            <tr>
                                <td>Edición</td>
                                <td><?= $libro->anio ?></td>
                            </tr>
                            <tr>
                                <td>ISBN</td>
                                <td> <?= $libro->isbn ?></td>
                            </tr>
                            <tr>
                                <td>Número de páginas</td>
                                <td> <?= $libro->paginas ?></td>
                            </tr>
                            <tr>
                                <td>Encuadernación</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Género</td>
                                <td><?= $libro->tema ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-ficha-ver hide-desk">
        <div class="col-xs-12">
            <p class="ver-titulo"><?= ucfirst(mb_strtolower($libro->titulo, 'UTF-8')) ?></p>
            <p class="ver-autor"><?= ucwords(mb_strtolower($libro->autor, 'UTF-8')) ?></p>
            <?= Html::img('@web/images/'.$documen->portada , ['class' => 'img-responsive ver-portada center-block']) ?>
            <span class="azul ver-texto-compartir">Compartir</span>
            <?= \ymaker\social\share\widgets\SocialShare::widget([
                'configurator'  => 'socialShare',
                'url'           => \yii\helpers\Url::to(['/catalogo/ver', 'id' => $libro->id], true),
                'title'         => ucfirst(mb_strtolower($libro->titulo)),
                'description'   => ucfirst(mb_strtolower($libro->autor)),
                'imageUrl'      => \yii\helpers\Url::to('@web/images/'.$documen->portada.'', true),
            ]); ?>
            <p class="ver-precio">$<?= $libro->pvp ?></p>
        </div>
        <div class="col-xs-12">
            <p class="azul">Entrega</p>
            <p class="negro">Día Siguiente</p>
        </div>
        <div class="col-xs-12">
            
            <div class="col-xs-12 mensaje-carrito2">
                <a href="<?= Url::to(['/checkout/index']); ?>" class="redbolsa" >El libro se agregó al carrito correctamente.</a>
            </div>
            <a class="btn-comprar agregar-bolsa" id="agregar-bolsa" href="javascript:;" data-id="<?= $libro->id?>">
                <div class="addbolsa" id="<?= $libro->id?>" >
                     Agregar a carrito
                </div>
            </a>
            <a class="btn-libro" id="<?= $libro->id?>" href="javascript:;">
                <div class="comprarbolsa" id="<?= $libro->id?>" >
                    Comprar
                </div>
            </a>
            <p class="ver-descripcion"><?= $documen->descripcion ?></p>
            <table class="table ver-tabla">
                <tbody>
                    <tr>
                        <td>Editorial</td>
                        <td><?= $libro->editorial ?></td>
                    </tr>
                    <tr>
                        <td>Edición</td>
                        <td><?= $libro->anio ?></td>
                    </tr>
                    <tr>
                        <td>ISBN</td>
                        <td> <?= $libro->isbn ?></td>
                    </tr>
                    <tr>
                        <td>Número de páginas</td>
                        <td> <?= $libro->paginas ?></td>
                    </tr>
                    <tr>
                        <td>Encuadernación</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Género</td>
                        <td><?= $libro->tema ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <h3>Libros relacionados</h3>
        </div>
    </div>
    <div class="row ver-relacionados-img">
    <?php foreach($relacionados as $relacionado): ?>
        <div class="col-md-2 tarjeta-libro">
            <a href="<?= Url::to(['catalogo/ver', 'id' => $relacionado->id]) ?>">
                <div class="row row-carrusel-img">
                    <?php if($relacionado->portada != ''){ ?>
                        <?= Html::img("@web/images/{$relacionado->portada}" ,['class' => 'img-responsive']) ?>
                    <?php }else{ ?>
                        <?= Html::img('@web/images/portada_default.jpg' , ['class' => 'img-responsive center-block']) ?>
                    <?php } ?>
                </div>
            </a>
            <p class="vendidos-carrusel-titulo relacionados-altura" ><?= ucfirst(mb_strtolower($relacionado->titulo)) ?></p>
            <p class="vendidos-carrusel-autor relacionados-altura2" ><?= ucfirst(mb_strtolower($relacionado->autor)) ?></p>
            <p class="vendidos-carrusel-precio relacionados-altura3" >$<?= ucfirst(mb_strtolower($relacionado->pvp)) ?></p>
            <a class="btn-comprar agregar-relacionado" href="javascript:;" data-id="<?= $relacionado->id?>">
                <div class="addbolsa relacionados" id="<?= $relacionado->id?>" >
                     Agregar a carrito
                </div>
            </a>
            <p class="relacionado-agregado <?= $relacionado->id ?>">Se agregó al carrito</p>
        </div>
    <?php endforeach; ?>
    </div>
</div>

<input type="hidden" id="baseUrl" value="<?= Url::base(true) ?>">
