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
                    <?= $form->field($editorial_form, 'correo')->textInput(['type' => 'email', 'value' => $usuario->correo ]) ?>
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
