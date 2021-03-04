<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
		<link href="http://www.2019.lectorum.com.mx/css/styles.css" media="all" rel="stylesheet" type="text/css" />
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
	<body>
<div style="background-color: #0b4066;padding: 20px 0px; text-align:center;">
    <a href="http://www.uppl.blackrobot.mx">
        <img src="http://www.uppl.blackrobot.mx/images/logo-uppl.png" style = "width: 130px;">
        <!-- <img src="http://tr.blackrobot.mx/images/logo.png" style = "display:inline-block; margin-left:10px; margin-right:10px; height:40px; margin-top:40px" /> -->
    </a>
</div> 
		<div class="opps" style="width: 496px; border-radius: 4px;box-sizing: border-box;padding: 0 45px;margin: 40px auto;overflow: hidden;border: 1px solid #b0afb5;font-family: 'Open Sans', sans-serif;color: #4f5365;">
			<div class="opps-header">
				<div class="opps-reminder" style="position: relative;top: -1px;padding: 9px 0 10px;font-size: 11px;text-transform: uppercase;text-align: center;color: #ffffff;background: #000000;">Ficha digital. No es necesario imprimir.</div>
				<div class="opps-info" style="margin-top: 26px;position: relative;">
					<div class="opps-brand" style="width: 45%;float: left;"><img style="max-width: 150px;margin-top: 2px;" src="http://www.2019.lectorum.com.mx/images/oxxopay_brand.png" alt="OXXOPay"></div>
					<div class="opps-ammount" style="width: 55%;float: right;">
						<h3 style="	margin-bottom: 10px;font-size: 15px;font-weight: 600;text-transform: uppercase;">Monto a pagar</h3>
						<h2 style="font-size: 36px;color: #000000;line-height: 24px;margin-bottom: 15px;">$ <?= $monto ?> <sup style="font-size: 16px;position: relative;top: -2px">MXN</sup></h2>
						<p style="font-size: 10px;line-height: 14px;">OXXO cobrará una comisión adicional al momento de realizar el pago.</p>
					</div>
				</div>
				<div class="opps-reference" style="margin-top: 14px;">
					<h3 style="	margin-bottom: 10px;font-size: 15px;font-weight: 600;text-transform: uppercase;">Referencia</h3>
					<!-- <h1><?= $referencia ?></h1> -->
					<?php 
					$val0 =substr($referencia, 0,-10);
					$val1 =substr($referencia, 4, -6);
					$val2 = substr($referencia,8, -2);
					$val3 = substr($referencia, 12); ?>
					<h1 style="font-size: 27px;color: #000000;text-align: center;margin-top: -1px;padding: 6px 0 7px;border: 1px solid #b0afb5;border-radius: 4px;background: #f8f9fa;"><?= $val0.'-'.$val1.'-'.$val2.'-'.$val3 ?></h1>
				</div>
			</div>
			<div class="opps-instructions" style="margin: 32px -45px 0;padding: 32px 45px 45px;border-top: 1px solid #b0afb5;background: #f8f9fa;">
				<h3 style="	margin-bottom: 10px;font-size: 15px;font-weight: 600;text-transform: uppercase;">Instrucciones</h3>
				<ol style="margin: 17px 0 0 16px;">
					<li style="margin-top: 10px;color: #000000;">Acude a la tienda OXXO más cercana. <a style="color: #1155cc;" href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala aquí</a>.</li>
					<li style="margin-top: 10px;color: #000000;">Indica en caja que quieres realizar un pago de <strong>OXXOPay</strong>.</li>
					<li style="margin-top: 10px;color: #000000;">Dicta al cajero el número de referencia en esta ficha para que tecleé directamete en la pantalla de venta.</li>
					<li style="margin-top: 10px;color: #000000;">Realiza el pago correspondiente con dinero en efectivo.</li>
					<li style="margin-top: 10px;color: #000000;">Al confirmar tu pago, el cajero te entregará un comprobante impreso. <strong>En el podrás verificar que se haya realizado correctamente.</strong> Conserva este comprobante de pago.</li>
				</ol>
				<div class="opps-footnote" style="margin-top: 22px;padding: 22px 20 24px;color: #108f30;text-align: center;border: 1px solid #108f30;border-radius: 4px;background: #ffffff;">Al completar estos pasos recibirás un correo de <strong>un Paseo por los Libros</strong> confirmando tu pago.</div>
			</div>
		</div>	
</body>