<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

?>


<div class="container main-contenedor">
    <div class="row">
        <div class="col-md-12">
            <div class="row row-libros-form" >
                <div class="col-xs-10">
                    <h3 class="libros-form-titulo">Datos de Editorial</h3>
                </div>
            </div>
            <?php $form = ActiveForm::begin() ?>
            <div class="row">
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'clave')->textInput(['value' => $editorial->clave])->label('Clave de Editorial') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'nombre_editorial')->textInput(['value' => $editorial->nombre])->label('Nombre de Editorial') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'contacto')->textInput(['value' => $editorial->contacto]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'telefono')->textInput(['value' => $editorial->telefono])->label('Teléfono') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'correo')->textInput(['type' => 'email', 'value' => $editorial->correo ]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?php $checked = ($editorial->activo == 0 ? false : true)  ?>
                    <?= $form->field($editorial_form, 'activo')->checkbox(['class' => 'editorial-checkbox', 'checked' => $checked]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'img_file')->fileInput(['accept' => '.jpg,.jpeg,.png', 'id' => 'evento-imagen-select' ])->label('Logo') ?>
                    <span id="evento-imagen-click">Da click aqui para cambiar el logo
                        <span><?= Html::img('@web/images/portada.png') ?></span>
                    </span>
                </div>
                <?php if ($editorial->logo){ ?>
                    <div class="col-md-2 campo-libro-form" id="evento-imagen-mostrar" style="background-image: url( <?=Url::to('@web/images/'.$editorial->logo)?>) "></div>
                <?php } else { ?>
                    <div class="col-md-2 campo-libro-form" id="evento-imagen-mostrar"></div>
                <?php } ?>
            </div>
            <div class="row row-libros-form" >
                <div class="col-xs-10">
                    <h3 class="libros-form-titulo">Datos de Usuario</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'usuario')->textInput(['value' => $usuario->usuario]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'nombre')->textInput(['value' => $usuario->nombre]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'apellido_paterno')->textInput(['value' => $usuario->ap_paterno]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'apellido_materno')->textInput(['value' => $usuario->ap_materno]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'password')->textInput(['type' => 'password']) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'confirma_password')->textInput(['type' => 'password']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-offset-5 col-xs-2">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
