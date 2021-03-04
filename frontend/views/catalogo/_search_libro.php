<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="libros-search col-md-6" id="search-block">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($filtro, 'titulo')->textInput(['placeholder'=>'Búsqueda'])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary btn-buscar']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>