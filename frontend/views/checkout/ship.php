<?php
/**
 * This test will send the same test data as in FedEx's documentation:
 * /php/RateAvailableServices/RateAvailableServices.php5
 */

//echo 'brinco fedex'; exit;  // esta linea solo sirve apara brincarse todo el documento y regresar un                                    string

use FedEx\ShipService;
use FedEx\ShipService\ComplexType;
use FedEx\ShipService\SimpleType;

$ini = parse_ini_file('../fedex.ini');
$userCredential = new ComplexType\WebAuthenticationCredential();
$userCredential
    ->setKey($ini['FEDEX_KEY'])
    ->setPassword($ini['FEDEX_PASSWORD']);

$webAuthenticationDetail = new ComplexType\WebAuthenticationDetail();
$webAuthenticationDetail->setUserCredential($userCredential);

$clientDetail = new ComplexType\ClientDetail();
$clientDetail
    ->setAccountNumber($ini['FEDEX_ACCOUNT_NUMBER'])
    ->setMeterNumber($ini['FEDEX_METER_NUMBER']);

$version = new ComplexType\VersionId();
$version
    ->setMajor(23)
    ->setIntermediate(0)
    ->setMinor(0)
    ->setServiceId('ship');

$shipperAddress = new ComplexType\Address();
$shipperAddress
    ->setStreetLines(['Batalla de Casa Blanca 1621 Col. Leyes de Reforma 3 sección, Iztapalapa'])
    ->setCity('Ciudad de Mexico')
    ->setStateOrProvinceCode('DF')
    ->setPostalCode('09310')
    ->setCountryCode('MX');

$shipperContact = new ComplexType\Contact();
$shipperContact
    ->setCompanyName('Paseo por los libros')
    ->setEMailAddress('ventas@uppl.com.mx')
    ->setPersonName('UPPL')
    ->setPhoneNumber(('5581-3202'));

$shipper = new ComplexType\Party();
$shipper
    ->setAccountNumber($ini['FEDEX_ACCOUNT_NUMBER'])
    ->setAddress($shipperAddress)
    ->setContact($shipperContact);

$recipientAddress = new ComplexType\Address();
$recipientAddress
    ->setStreetLines([
        $calle_div1,
        'calle2'
    ])
    ->setCity($ciudad)
    ->setPostalCode($cp)
    ->setCountryCode('MX');

$recipientContact = new ComplexType\Contact();
$recipientContact
    ->setPersonName($nombre)
    ->setPhoneNumber($telefono);

$recipient = new ComplexType\Party();
$recipient
    ->setAddress($recipientAddress)
    ->setContact($recipientContact);

$labelSpecification = new ComplexType\LabelSpecification();
$labelSpecification
    ->setLabelStockType(new SimpleType\LabelStockType(SimpleType\LabelStockType::_PAPER_7X4POINT75))
    ->setImageType(new SimpleType\ShippingDocumentImageType(SimpleType\ShippingDocumentImageType::_PDF))
    ->setLabelFormatType(new SimpleType\LabelFormatType(SimpleType\LabelFormatType::_COMMON2D));

$packageLineItem1 = new ComplexType\RequestedPackageLineItem();
$packageLineItem1
    ->setSequenceNumber(1)
    ->setItemDescription('Product description')
    ->setDimensions(new ComplexType\Dimensions(array(
        'Width' => 10,
        'Height' => 10,
        'Length' => 25,
        'Units' => SimpleType\LinearUnits::_IN
    )))
    ->setWeight(new ComplexType\Weight(array(
        'Value' => 2,
        'Units' => SimpleType\WeightUnits::_LB
    )));

$shippingChargesPayor = new ComplexType\Payor();
$shippingChargesPayor->setResponsibleParty($shipper);

$shippingChargesPayment = new ComplexType\Payment();
$shippingChargesPayment
    ->setPaymentType(SimpleType\PaymentType::_SENDER)
    ->setPayor($shippingChargesPayor);

$requestedShipment = new ComplexType\RequestedShipment();
$requestedShipment->setShipTimestamp(date('c'));
$requestedShipment->setDropoffType(new SimpleType\DropoffType(SimpleType\DropoffType::_REGULAR_PICKUP));
$requestedShipment->setServiceType(new SimpleType\ServiceType($radio));
$requestedShipment->setPackagingType(new SimpleType\PackagingType(SimpleType\PackagingType::_YOUR_PACKAGING));
$requestedShipment->setShipper($shipper);
$requestedShipment->setRecipient($recipient);
$requestedShipment->setLabelSpecification($labelSpecification);
$requestedShipment->setRateRequestTypes(array(new SimpleType\RateRequestType(SimpleType\RateRequestType::_PREFERRED)));
$requestedShipment->setPackageCount(1);
$requestedShipment->setRequestedPackageLineItems([
    $packageLineItem1
]);
$requestedShipment->setShippingChargesPayment($shippingChargesPayment);

$processShipmentRequest = new ComplexType\ProcessShipmentRequest();
$processShipmentRequest->setWebAuthenticationDetail($webAuthenticationDetail);
$processShipmentRequest->setClientDetail($clientDetail);
$processShipmentRequest->setVersion($version);
$processShipmentRequest->setRequestedShipment($requestedShipment);


$shipService = new ShipService\Request();
$shipService->getSoapClient()->__setLocation($ini['FEDEX_SHIP_URL']);

//var_dump($processShipmentRequest);exit;

$result = $shipService->getProcessShipmentReply($processShipmentRequest);
echo $result->CompletedShipmentDetail->MasterTrackingId->TrackingNumber;

// Save .pdf label
/*
file_put_contents('@web/label/'.'/<?php echo $result->CompletedShipmentDetail->MasterTrackingId->TrackingNumber; ?>.pdf', $result->CompletedShipmentDetail->CompletedPackageDetails[0]->Label->Parts[0]->Image);
*/
file_put_contents(__DIR__.'/../../../backend/web/label/'. $result->CompletedShipmentDetail->MasterTrackingId->TrackingNumber.'.pdf', $result->CompletedShipmentDetail->CompletedPackageDetails[0]->Label->Parts[0]->Image);
//file_put_contents('@web/label/'. $result->CompletedShipmentDetail->MasterTrackingId->TrackingNumber.'.pdf', $result->CompletedShipmentDetail->CompletedPackageDetails[0]->Label->Parts[0]->Image);
//var_dump($result->CompletedShipmentDetail->CompletedPackageDetails[0]->Label->Parts[0]->Image);
