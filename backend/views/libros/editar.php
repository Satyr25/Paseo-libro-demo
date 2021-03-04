<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

?>


<div class="container ">
    <div class="row">
        <div class="col-md-12">
            <div class="row row-libros-form" >
                <div class="col-xs-10">
                    <h3 class="libro-ver-titulo">Editar Libro</h3>
                </div>
            </div>
            <?php $form = ActiveForm::begin([
//                'method' => 'post',
                'action' => Url::to(['libros/actualizar']),
            ]); ?>
            <div class="row">
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'codigo_barras')->textInput(['value' => $libro->codigo_barras ]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'cantidad')->textInput(['value' => $libro->cantidad]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'titulo')->textInput(['value' => $libro->titulo]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'pvp')->textInput(['value' => $libro->pvp]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'autor')->textInput(['value' => $libro->autor]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'subtitulo')->textInput(['value' => $libro->subtitulo]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'isbn')->textInput(['value' => $libro->isbn]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'interno')->textInput(['value' => $libro->interno]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'anio')->textInput(['value' => $libro->anio]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'sello_id')->dropDownList($sellos, ['value' => $libro->sello_id]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'coleccion_id')->dropDownList($colecciones, ['value' => $libro->coleccion_id]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'tema_id')->dropDownList($temas, ['value' => $libro->tema_id]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'paginas')->textInput(['value' => $libro->paginas]) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'peso')->textInput(['value' => $libro->peso]) ?>
                </div>
                <div class="col-md-4 campo-libro-form">
                    <?= $form->field($libroForm, 'profundo')->textInput(['value' => $libro->profundo]) ?>
                </div>
                <div class="col-md-4 campo-libro-form">
                    <?= $form->field($libroForm, 'alto')->textInput(['value' => $libro->alto]) ?>
                </div>
                <div class="col-md-4 campo-libro-form">
                    <?= $form->field($libroForm, 'largo')->textInput(['value' => $libro->largo]) ?>
                </div>
                <div class="col-md-3 campo-libro-form">
                    <?= $form->field($libroForm, 'portada')->fileInput(['accept' => '.jpg,.jpeg,.png,.gif', 'id' => 'libro-portada-select']) ?>
                    <span id="libro-portada-click">Da click aqui para cambiar la imagen</span>
                </div>
                <div class="col-md-3 campo-libro-form" id="muestra-libro-fondo" >
<!--                    aqui se ve la imagen seleccionada         -->
                    <?= Html::img('@web/images/'.$libro->portada, ['class' => 'editar-img', 'id' => 'editar-portada']) ?>
                </div>
                <div class="col-md-3 campo-libro-form"  id="campo-fondo">
                    <?= $form->field($libroForm, 'fondo')->fileInput(['accept' => '.jpg,.jpeg,.png,.gif', 'id' => 'libro-fondo-select']) ?>
                    <span id="libro-fondo-click">Da click aqui para cambiar la imagen</span>
                </div>
                <div class="col-md-3 campo-libro-form" id="muestra-libro-fondo-fondo">
<!--                    aqui se ve la imagen seleccionada         -->
                    <?= Html::img('@web/images/'.$libro->fondo, ['class' => 'editar-img', 'id' => 'editar-fondo']) ?>
                </div>
                <div class="col-md-12 campo-libro-form">
                    <?= $form->field($libroForm, 'descripcion')->textArea(['value' => $libro->descripcion]) ?>
                </div>
                <div class="col-md-offset-10 col-md-2">
                    <div class="form-group">
                        <?= $form->field($libroForm, 'editorial_id')->hiddenInput(['value' => Yii::$app->user->identity->editorial_id])->label(false) ?>
                        <?= $form->field($libroForm, 'id')->hiddenInput(['value' => Yii::$app->request->get('id')])->label(false) ?>
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
