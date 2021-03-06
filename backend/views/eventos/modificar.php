<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\time\TimePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Eventos */

?>

<div class="container">
    <div class="row row-agenda">
        <div class="col-md-12 ">
            <p class="eventos-titulo">Agenda Cultural </p>
        </div>
    </div>
    
    <div class="row row-form-nuevo">
        <?php $form = ActiveForm::begin(); ?>
        <div class="col-md-3 campo-libro-form">
           <label for="">Fecha</label>
           <?= DatePicker::widget([
                'name' => 'EventosForm[fecha]',
                'type' => DatePicker::TYPE_INPUT,
                'value' => $evento->fecha,
                'language' => 'es',
                'options' => [
                    'readonly' => true,
                ],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-mm-yyyy'
                ]
            ]); ?>
        </div>
        <div class="col-md-3 campo-libro-form">
            <label for="">Hora</label>
            <?= TimePicker::widget([
                'name' => 'EventosForm[hora]',
                'value' => $evento->hora,
                'options' => [
                    'readonly' => true,
                ],
            ]); ?>
        </div>
        <div class="col-md-6 campo-libro-form">
            <?= $form->field($model, 'tema')->textInput(['maxlength' => true, 'value' => $evento->tema]) ?>
        </div>
        <div class="col-md-6 campo-libro-form">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true, 'value' => $evento->nombre]) ?>
        </div>
        <div class="col-md-6 campo-libro-form">
            <?= $form->field($model, 'presentador')->textInput(['maxlength' => true, 'value' => $evento->presentador]) ?>
        </div>
        <div class="col-md-6 campo-libro-form">
            <?= $form->field($model, 'img_file')->fileInput(['accept' => '.jpg,.jpeg,.png', 'id' => 'evento-imagen-select' ]) ?>
            <span id="evento-imagen-click">Da click aqui para agregar una imagen
                <span><?= Html::img('@web/images/portada.png') ?></span>
            </span>
        </div>
        <div class="col-md-2 campo-libro-form" id="evento-imagen-mostrar" style="background-image: url('../images/<?=$evento->imagen?>');"></div>
    </div>
    <div class="row">
        <div class="col-md-offset-5 col-md-2">
            <?= $form->field($model, 'categoria_id')->hiddenInput(['value' => 1])->label(false) ?>
            <?= Html::submitButton('Actualizar', ['class' => 'btn btn-primary']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

