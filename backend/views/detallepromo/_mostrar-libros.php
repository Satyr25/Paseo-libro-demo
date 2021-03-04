<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class="edita-libro">
    <?php $form = ActiveForm::begin(['id'=>'libros-update-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="libros-fields">
        <div class="col-md-12">
            <button class="btnback" data-dismiss="modal"> << Regresar</button>
        </div>
        <div class="col-md-12">
            <h2>Detalle del Libro</h2>
        </div>
        <div class="col-md-12">
            <?= $form-> field($accion,'id')->textInput(['required'=>'true', 'type' => 'hidden'])->label(false)?>
        </div>


        <div class="col-md-6 grid-border">
            <label class="col-md-4">Precio por venta</label>
            <p class="col-md-8"><?= $accion->pvp ?>
                <?php if($accion->pvp == null){
                    echo 'No hay Precio por venta';
                } ?>
            </p>
        </div>

        <div class="col-md-6 grid-border">
            <?php if($accion->promo){  ?>

            <?= $form->field($accion,'promo', ['labelOptions' => ['class' => 'col-md-5' ]])->input(['class' => 'col-md-8', 
                'value' => ''.$accion->promo.''])?>
            <?php }else{ ?>
                <?= $form->field($accion,'promo', ['labelOptions' => ['class' => 'col-md-5' ]])->input(['class' => 'col-md-8', 
                'value' => ''])?>
            <?php } ?>

        </div> 
        
        <div class="col-md-12">
        <button class="boton" id="boton-actualizar-precio">Guardar</button>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
