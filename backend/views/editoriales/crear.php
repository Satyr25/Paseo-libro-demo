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
                    <?= $form->field($editorial_form, 'clave')->label('Clave de Editorial') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'nombre_editorial')->label('Nombre de Editorial') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'contacto') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'telefono')->label('Teléfono') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'correo')->textInput(['type' => 'email']) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'activo')->checkbox(['class' => 'editorial-checkbox']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'img_file')->fileInput(['accept' => '.jpg,.jpeg,.png', 'id' => 'evento-imagen-select' ])->label('Logo') ?>
                    <span id="evento-imagen-click">Da click aqui para agregar un logo
                        <span><?= Html::img('@web/images/portada.png') ?></span>
                    </span>
                </div>
                <div class="col-md-2 campo-libro-form" id="evento-imagen-mostrar"></div>
            </div>
            <div class="row row-libros-form" >
                <div class="col-xs-10">
                    <h3 class="libros-form-titulo">Datos de Usuario</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'usuario') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'nombre') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'apellido_paterno') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($editorial_form, 'apellido_materno') ?>
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