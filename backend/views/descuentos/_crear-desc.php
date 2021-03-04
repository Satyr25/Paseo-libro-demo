<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class="edita-libro">
    <?php $desc_form = ActiveForm::begin(['id'=>'crear-descuento-form']); ?>
    <div class="libros-fields">
        <div class="col-md-12">
            <button class="btnback" data-dismiss="modal"> << Regresar</button>
        </div>
        <div class="col-md-12">
            <h2>Descuentos</h2>
        </div>

        <div class="col-md-12 grid-border">
           <?= $desc_form->field($model,'codigo', ['labelOptions' => ['class' => 'col-md-6']])->textinput(['class' => 'col-md-6', 
                'value' => ''])->label('Código de Descuento')?>
        </div> 

        <div class="col-md-12 grid-border">
           <?= $desc_form->field($model,'porcentaje', ['labelOptions' => ['class' => 'col-md-6']])->textinput(['class' => 'col-md-6', 
                'value' => '', 'type' => 'number', 'min' => '0.1', 'max' => '100', 'step' => '0.1'])->label('Porcentaje (0-100)')?>
        </div>

        <div class="col-md-4">
                <?= $desc_form->field($model, 'activo')->checkbox() ?>
        </div>

        <div class="col-md-4">
                <?= $desc_form->field($model, 'global')->checkbox() ?>
        </div>
        
        <div class="col-md-12">
        <button class="boton" id="boton-desc-crear" type="submit">Guardar</button>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>