<?php
/**
 * This test will send the same test data as in FedEx's documentation:
 * /php/RateAvailableServices/RateAvailableServices.php5
 **/

//remember to copy example.credentials.php as credentials.php replace 'FEDEX_KEY', 'FEDEX_PASSWORD', 'FEDEX_ACCOUNT_NUMBER', and 'FEDEX_METER_NUMBER'
use FedEx\RateService\Request;
use FedEx\RateService\ComplexType;
use FedEx\RateService\SimpleType;

$ini = parse_ini_file('../fedex.ini');

$rateRequest = new ComplexType\RateRequest();
//authentication & client details
$rateRequest->WebAuthenticationDetail->UserCredential->Key = $ini['FEDEX_KEY'];
$rateRequest->WebAuthenticationDetail->UserCredential->Password = $ini['FEDEX_PASSWORD'];
$rateRequest->ClientDetail->AccountNumber = $ini['FEDEX_ACCOUNT_NUMBER'];
$rateRequest->ClientDetail->MeterNumber = $ini['FEDEX_METER_NUMBER'];
//var_dump($rateRequest->ClientDetail);exit;

$rateRequest->TransactionDetail->CustomerTransactionId = '*** Rate Request using PHP ***';

//version
$rateRequest->Version->ServiceId = 'crs';
$rateRequest->Version->Major = 24;
$rateRequest->Version->Minor = 0;
$rateRequest->Version->Intermediate = 0;

$rateRequest->ReturnTransitAndCommit = true;

//shipper
$rateRequest->RequestedShipment->PreferredCurrency = 'MXN';
$rateRequest->RequestedShipment->Shipper->Address->StreetLines = ['Batalla de Casa Blanca 1621 Col. Leyes de Reforma 3 sección, Iztapalapa'];
$rateRequest->RequestedShipment->Shipper->Address->City = 'Ciudad de Mexico';
$rateRequest->RequestedShipment->Shipper->Address->StateOrProvinceCode = 'DF';
$rateRequest->RequestedShipment->Shipper->Address->PostalCode = '09310';
$rateRequest->RequestedShipment->Shipper->Address->CountryCode = 'MX';

//recipient
$rateRequest->RequestedShipment->Recipient->Address->StreetLines = $calle;
$rateRequest->RequestedShipment->Recipient->Address->City = $ciudad;
$rateRequest->RequestedShipment->Recipient->Address->StateOrProvinceCode = $estadocodigo;
$rateRequest->RequestedShipment->Recipient->Address->PostalCode = $cp;
$rateRequest->RequestedShipment->Recipient->Address->CountryCode = $codigo;

//shipping charges payment
$rateRequest->RequestedShipment->ShippingChargesPayment->PaymentType = SimpleType\PaymentType::_SENDER;

//rate request types
$rateRequest->RequestedShipment->RateRequestTypes = [SimpleType\RateRequestType::_PREFERRED];

$rateRequest->RequestedShipment->PackageCount = '1';

//create package line items
$rateRequest->RequestedShipment->RequestedPackageLineItems = [new ComplexType\RequestedPackageLineItem()];

//package 1
$rateRequest->RequestedShipment->RequestedPackageLineItems[0]->GroupPackageCount = 1;
$rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Weight->Value = 15.0;
$rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Weight->Units = SimpleType\WeightUnits::_KG;
$rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Length = 13.0;
$rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Width = 30.0;
$rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Height = 21.0;
$rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Units = SimpleType\LinearUnits::_CM;

$rateServiceRequest = new Request();
$rateServiceRequest->getSoapClient()->__setLocation($ini['FEDEX_RATE_URL']); //use production URL

$rateReply = $rateServiceRequest->getGetRatesReply($rateRequest); // send true as the 2nd argument to return the SoapClient's stdClass response.


if (!empty($rateReply->RateReplyDetails)) {
    foreach ($rateReply->RateReplyDetails as $rateReplyDetail) {
    	$tipo_envio = $rateReplyDetail->ServiceType;
        if($tipo_envio == 'STANDARD_OVERNIGHT' || $tipo_envio == 'FEDEX_EXPRESS_SAVER'){
            $precio = $rateReplyDetail->RatedShipmentDetails[1]->ShipmentRateDetail->TotalNetCharge->Amount;
            $cadena = str_replace('_', ' ', $tipo_envio);
            $fecha_normal = $rateReplyDetail->DeliveryTimestamp;
            $fecha = strtotime($rateReplyDetail->DeliveryTimestamp);
            $numeroDia = date('d', strtotime($fecha_normal."+ 3 days"));
            $dia = date('l', strtotime($fecha_normal."+ 3 days"));
            $mes = date('F', strtotime($fecha_normal."+ 3 days"));
            $anio = date('Y', strtotime($fecha_normal."+ 3 days"));

            if($mes == 'January'){
                $mes = 'Enero';
            }
            else  if($mes == 'February'){
                $mes = 'Febrero';
            }
            else  if($mes == 'March'){
                $mes = 'Marzo';
            }
            else  if($mes == 'April'){
                $mes = 'Abril';
            }
            else  if($mes == 'May'){
                $mes = 'Mayo';
            }
            else  if($mes == 'June'){
                $mes = 'Junio';
            }
            else  if($mes == 'July'){
                $mes = 'Julio';
            }
            else  if($mes == 'August'){
                $mes = 'Agosto';
            }
            else  if($mes == 'September'){
                $mes = 'Septiembre';
            }
            else  if($mes == 'October'){
                $mes = 'Octubre';
            }
            else  if($mes == 'November'){
                $mes = 'Noviembre';
            }
            else  if($mes == 'December'){
                $mes = 'Diciembre';
            }
            //var_dump($dia.", ".$numeroDia." de ".$mes." de ".$anio);
            //var_dump($tipo_envio. ": " . $precio);
            echo '<div class="etuno">';
            if($tipo_envio == 'STANDARD_OVERNIGHT'){
                echo '<span  class="tipo-pago-bl marg">FedEx Express</span><br>
                <input type="hidden" name="tenvio" value="'.$tipo_envio.'/'.$precio.'/'.$fecha.'/'.$fecha_normal.'" onclick="mostrar('.$precio.');">
                <span  class="tipo-pago-bl">Fecha estimada de entrega:</span><span class="precio-envio" id="'.$tipo_envio.'" value="'.$precio.'">$'.$precio.'</span><br>
                <span  class="tipo-pago marg">'.$numeroDia.' de '.$mes.' del '.$anio.'</span>';
            }else{
                echo '<span  class="tipo-pago-bl marg">FedEx Estándar</span><br>
                <input type="radio" name="tenvio" value="'.$tipo_envio.'/'.$precio.'/'.$fecha.'/'.$fecha_normal.'" onclick="mostrar('.$precio.');">
                <span  class="tipo-pago-bl">Fecha estimada de entrega:</span><span class="precio-envio" id="'.$tipo_envio.'" value="'.$precio.'">$'.$precio.'</span><br>
                <span  class="tipo-pago marg">'.$numeroDia.' de '.$mes.' del '.$anio.'</span>';
            }
            echo '</div>';
        } else {
            
        }


    }
}else{
//     echo '<div class="etuno">';
//     echo'<span class="tipo-pago-bl marg">FedEx Express</span><br>
//                 <input type="radio" name="tenvio" value="FEDEX_EXPRESS_SAVER/450.35/1562032800/2019-07-01T21:00:00" onclick="mostrar(450.35);">
//                 <span class="tipo-pago-bl">Fecha estimada de entrega:</span><span class="precio-envio" id="FEDEX_EXPRESS_SAVER" value="450.35">$450.35</span><br>
//                 <span class="tipo-pago marg">04 de Julio del 2019</span></div>';
//     echo '</div>';
}


