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
            <input type="hidden" value="<?= $descuento->id ?>" id="descuento-id">
           <?= $desc_form->field($descuento,'codigo', ['labelOptions' => ['class' => 'col-md-6']])->textinput(['class' => 'col-md-6', 
                'value' => $descuento->codigo])->label('Código de Descuento')?>
        </div> 

        <div class="col-md-12 grid-border">
           <?= $desc_form->field($descuento,'porcentaje', ['labelOptions' => ['class' => 'col-md-6']])->textinput(['class' => 'col-md-6', 
                'value' => $descuento->porcentaje, 'type' => 'number', 'min' => '0.1', 'max' => '100', 'step' => '0.1'])->label('Porcentaje (0-100)')?>
        </div>

        <div class="col-md-4">
                <?= $desc_form->field($descuento, 'activo')->checkbox() ?>
        </div>

        <div class="col-md-4">
                <?= $desc_form->field($descuento, 'global')->checkbox() ?>
        </div>
        
        <div class="col-md-12">
        <button class="boton" id="boton-desc-editar" type="submit">Guardar</button>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>