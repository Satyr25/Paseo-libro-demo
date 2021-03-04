<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>

<section>
    <div class="container contenedor" id="libros-ver">
        <div class="row">
            <div class="col-md-12">
                <div class="row row-regresar">
                    <div class="col-md-2">
                        <a href="<?=$ruta?>" class="link-boton-regresar"> <?= Html::img('@web/images/regresar.png', ['class' => 'img-responsive boton-regresar']) ?> Regresar</a>
                    </div>
                </div>
                <?php if (Yii::$app->user->identity->rol_id == 2){ ?> 
                    <div class="row ver-titulo-row">
                        <div class="col-md-6">
                            <h3 class="libro-ver-titulo">Detalle de Editorial</h3>
                        </div>
                    </div>
                    <div class="row ver-libro-tabla">
                        <div class="col-md-6">
                            <p>Clave</p>
                            <p><?= $usuario->editorial->clave ?></p>
                        </div>
                        <div class="col-md-6">
                            <p>Nombre de Editorial</p>
                            <p><?= ucwords(mb_strtolower($usuario->editorial->nombre)) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p>Contacto</p>
                            <p><?= ucwords(mb_strtolower($usuario->editorial->contacto)) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p>Teléfono</p>
                            <p><?= $usuario->editorial->telefono ?></p>
                        </div>
                        <div class="col-md-6">
                            <p>Correo</p>
                            <p> <?= $usuario->editorial->correo ?></p>
                        </div>
                        <div class="col-md-6">
                            <p>Activo</p>
                            <?php if ($usuario->editorial->activo == '1'){ ?> 
                                <p>Si</p>
                            <?php } else { ?> 
                                <p>No</p>
                            <?php } ?>
                        </div>
                        <div class="col-md-6">
                            <p>Logo</p>
                            <?= Html::img('@web/images/'.$usuario->editorial->logo, ['class' => 'editorial-logo']) ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="row ver-titulo-row">
                    <div class="col-md-6">
                        <h3 class="libro-ver-titulo">Detalle de Usuario</h3>
                    </div>
                    <?php if (Yii::$app->user->identity->rol_id == 1){ ?> 
                        <div class="col-md-offset-4 col-md-2">
                            <a href=" <?=Url::toRoute(['actualizar', 'id' => $usuario->editorial->id]) ?>" class="btn btn-azul">Editar</a>
                        </div>
                    <?php } ?>
                </div>
                <div class="row ver-libro-tabla">
                    <div class="col-md-6">
                        <p>Usuario</p>
                        <p><?= $usuario->usuario ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Nombre de Usuario</p>
                        <p><?= ucwords(mb_strtolower($usuario->nombre)) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Apellido Paterno</p>
                        <p><?= ucwords(mb_strtolower($usuario->ap_paterno)) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Apellido Materno</p>
                        <p><?= ucwords(mb_strtolower($usuario->ap_materno)) ?></p>
                    </div>
                    <?php if(Yii::$app->user->identity->rol_id == 1){ ?> 
                        <div class="col-md-6">
                            <p>Correo</p>
                            <p> <?= $usuario->correo ?></p>
                        </div>
                    <?php }  ?> 
                </div>
            </div>
        </div>
    </div>
</section>
