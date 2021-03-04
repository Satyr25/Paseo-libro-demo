<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Carrusel';

?>


<div class="container main-contenedor">
    <div class="row">
        <div class="col-md-12">
            <div class="row row-libros-form" >
                <div class="col-xs-10">
                    <h3 class="libros-form-titulo">Carrusel</h3>
                </div>
            </div>
            <?php $form = ActiveForm::begin() ?>
            <?= $form->field($carrusel_form, 'id')->hiddenInput()->label(false) ?>
            <div class="row">
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($carrusel_form, 'titulo') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($carrusel_form, 'imagen')->fileInput(['accept' => '.jpg,.jpeg']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($carrusel_form, 'url') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($carrusel_form, 'status')->checkBox([]);  ?>
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
