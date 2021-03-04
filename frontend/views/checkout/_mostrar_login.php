<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div id="mostrar-login">
	<div class="titulo-login">Iniciar Sesión</div>
	<?php $form = ActiveForm::begin(['id' => 'login-form', 'action' => ['checkout/']]); ?>

                <?= $form->field($login, 'correo')->textInput(['autofocus' => true, 'class'=>'etmod','placeholder'=>"Correo electrónico"])->label(false) ?>

                <?= $form->field($login, 'password')->passwordInput(['class'=>'etmod','id'=>'password','placeholder'=>"Contraseña"])->label(false) ?>

        
	<!--<input type="text" placeholder="Correo electrónico">
	<input type="text" placeholder="Contraseña">-->
	<a href="javascript:;" class="olvidaste">¿Olvidaste tu contraseña?</a>
	<div class="recuerdame"><input type="checkbox"><span>Recuerdame</span></div>
	<div class="botones-login">
		<?= Html::submitButton('Inicia Sesión', ['class'=>'btniniciar', 'name' => 'login-button']) ?>


        <?php ActiveForm::end(); ?>
		
		<div class="btnfb">
			<a href="javascript:;">Iniciar Sesión con Facebook</a>
		</div>
	</div>
</div>