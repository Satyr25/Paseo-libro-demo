<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\moldels\Tema;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

$ruta = Yii::$app->request->referrer;
?>
<div class="container">
    <div class="row row-regresar">
        <div class="col-md-2">
            <a href="<?=$ruta?>" class="link-boton-regresar"> <?= Html::img('@web/images/regresar.png', ['class' => 'img-responsive boton-regresar']) ?> Regresar</a>
        </div>
        <?php if (isset($_GET['editorial'])){ ?>
            <div class="col-md-3">
                <?php if ($editorial->logo && $editorial->logo !== ''){ ?> 
                    <?= Html::img('@web/images/'.$editorial->logo, ['class' => 'img-responsive', 'alt' => $editorial->nombre]) ?>
                <?php } ?>
            </div>
            <div class="col-md-7">
                <h2><?= $editorial->nombre ?> </h2>
            </div>
        <?php } ?>
        <?php if (isset($_GET['buscar']) && $libros){ ?>
            <div class="col-md-10 muestra-busqueda">
                <h2>Resultados de tu búsqueda: "<?= Yii::$app->request->get('buscar'); ?>" </h2>
            </div>
        <?php } ?>
    </div>
    <div class="row">
        <div class=" col-md-4">
            <?php if(Yii::$app->request->get('vendidos') == 1){ ?> 
                <h3 class="titulo-categoria">Más vendidos</h3>
            <?php } else if (Yii::$app->request->get('promociones') == 1) { ?> 
                <h3 class="titulo-categoria">Promociones</h3>
            <?php } else if (Yii::$app->request->get('recomendaciones') == 1){ ?> 
                <h3 class="titulo-categoria">Recomendaciones del mes</h3>
            <?php } else { ?> 
                <h3 class="titulo-categoria">Catálogo</h3>
            <?php } ?>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4 categoria-selector">
            <div class="row" id='categoria-selector-row'>
                <div class="col-xs-6 categoria-altura">
                    <p>Categoría</p>
                </div>
                <div class="col-xs-6 categoria-altura" id="categorias-nav">
                <?php if($tema){ ?>
                    <p><?= $tema->nombre ?></p>
                <?php }else{ ?>
                    <p>Elegir Categoría </p>
                <?php } ?>
<!--
                    <?= Html::img('@web/images/iconos/desplegar.png', ['id' => 'categorias-desplegar']) ?>
                    <?= Html::img('@web/images/iconos/cerrar.png', ['id' => 'categorias-cerrar']) ?>
-->
                </div>
            </div>
            <div class="row" id='categorias-contenedor'>
                <div class="col-xs-12">
                    <?php foreach($categorias as $categoria){ ?>
                        <a href= '<?= Url::current(['tema' => $categoria->id]) ?>' class='enlace-categoria' ><p><?= $categoria->nombre ?></p></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <?php if ($tema){ ?>
                <h3 class="titulo-tema"><?= $tema->nombre ?></h3>
            <?php } ?>
        </div>
        <div class="col-md-4">
            <?php if(!$libros){ ?> 
                <h2 class="no-params">No se encontró ningun libro con estos parámetros</h2>
            <?php } ?>
        </div>
    </div>
</div>

<div class="container">
    <?= $this->renderAjax('_mas-libros', [
            'libros' => $libros,
        ]);
    ?>
</div>

<?php if (count($libros) > 11){ ?> 
<div class="container">
    <div class="row">
        <div class="col-md-offset-5 col-md-2 col-boton">
            <?= Html::submitButton('Ver más', ['class' => 'btn btn-primary', 'id' => 'catalogo-mas']) ?>
        </div>
    </div>
</div>
<?php } ?>
<div class="datos-ajax" data-incremento="12" data-tema="<?=$tema->id?>" data-editorial="<?=$editorial->id?>" data-buscar="<?=$buscar?>" data-vendidos="<?=$vendidos?>" data-promociones="<?=$promociones?>" data-recomendaciones="<?=$recomendaciones?>" ></div>
