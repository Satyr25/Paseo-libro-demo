<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class="edita-libro">
    <?php $promos_form = ActiveForm::begin(['id'=>'crear-promo-form']); ?>
    <div class="libros-fields">
        <div class="col-md-12">
            <button class="btnback" data-dismiss="modal"> << Regresar</button>
        </div>
        <div class="col-md-12">
            <h2>Promoción</h2>
        </div>

        <div class="col-md-12 grid-border">
           <?= $promos_form->field($model,'codigo', ['labelOptions' => ['class' => 'col-md-6']])->textinput(['class' => 'col-md-6', 
                'value' => ''])->label('Nombre de promoción')?>
        </div> 
        
        <div class="col-md-12">
        <button class="boton" id="boton-crear" type="submit">Guardar</button>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>