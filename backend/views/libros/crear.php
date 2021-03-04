<?php
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<!--<div class="pad-nav-back"></div>-->
<div class="container ">
    <div class="row">
        <div class="col-md-12">
            <div class="row row-libros-form">
                <div class="col-md-4">
                    <h3 class="libros-form-titulo">Alta de Libro</h3>
                </div>
                <div class="col-md-4 contenedor-spinner">
                    <div class="lds-facebook">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="pull-right import-button"><div class="glyphicon glyphicon-share"></div>Importar</button>
                        </div>
                    </div>
                    <div class="row import-block">
                        <div class="col-md-12">
                            <div class="row link-row">
                                <div class="col-md-12">
                                    <a href="<?= Url::to("@web/plantilla.xlsx") ?>">Descargar Plantilla</a>
                                </div>
                            </div>
                            <div class="row">
                                <?php $importForm = ActiveForm::begin([
                                    'id' => 'import-books-form',
                                    'action' => ['libros/import'],
                                    'options' => [
                                        'enctype' => 'multipart/form-data'
                                    ]
                                ]) ?>
                                    <div class="col-md-8">
                                        <?= $importForm->field($importLibrosModel, 'file')->widget(FileInput::className(), [
                                            'pluginOptions' => [
                                                'showPreview' => false,
                                                'showCaption' => true,
                                                'showRemove' => false,
                                                'showUpload' => false,
                                                'browseLabel' => '',
                                                'initialCaption'=> "Adjuntar archivo",
                                            ]
                                        ])->label(false) ?>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= Html::submitButton('Importar', ['class' => 'btn btn-primary importar-xls']) ?>
                                        </div>
                                    </div>
                                <?php ActiveForm::end() ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $form = ActiveForm::begin([
                'method' => 'post',
                'action' => Url::to(['libros/guardar']),
            ]); ?>
            <div class="row">
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'codigo_barras') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'cantidad') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'titulo') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'pvp') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'autor') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'subtitulo') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'isbn') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'interno') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'anio') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'sello_id')->dropDownList($sellos) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'coleccion_id')->dropDownList($colecciones) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'tema_id')->dropDownList($temas) ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'paginas') ?>
                </div>
                <div class="col-md-6 campo-libro-form">
                    <?= $form->field($libroForm, 'peso') ?>
                </div>
                <div class="col-md-4 campo-libro-form">
                    <?= $form->field($libroForm, 'profundo') ?>
                </div>
                <div class="col-md-4 campo-libro-form">
                    <?= $form->field($libroForm, 'alto') ?>
                </div>
                <div class="col-md-4 campo-libro-form">
                    <?= $form->field($libroForm, 'largo') ?>
                </div>
                <div class="col-md-3 campo-libro-form">
                    <?= $form->field($libroForm, 'portada')->fileInput(['accept' => '.jpg,.jpeg,.png,.gif', 'id' => 'libro-portada-select']) ?>
                    <span id="libro-portada-click">Da click aqui para agregar una imagen</span>
                </div>
                <div class="col-md-3 campo-libro-form" id="muestra-libro-fondo">
<!--                    aqui se ve la imagen seleccionada         -->
                </div>
                <div class="col-md-3 campo-libro-form" id="campo-fondo">
                    <?= $form->field($libroForm, 'fondo')->fileInput(['accept' => '.jpg,.jpeg,.png,.gif', 'id' => 'libro-fondo-select']) ?>
                    <span id="libro-fondo-click">Da click aqui para agregar una imagen</span>
                </div>
                <div class="col-md-3 campo-libro-form" id="muestra-libro-fondo-fondo">
<!--                    aqui se ve la imagen seleccionada         -->
                </div>
                <div class="col-md-12 campo-libro-form">
                    <?= $form->field($libroForm, 'descripcion')->textArea() ?>
                </div>
                <div class="col-md-offset-10 col-md-2">
                    <div class="form-group">
                        <?= $form->field($libroForm, 'editorial_id')->hiddenInput(['value' => Yii::$app->user->identity->editorial_id])->label(false) ?>
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
