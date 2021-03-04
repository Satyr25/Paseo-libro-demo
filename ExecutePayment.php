<?php 
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

$clientId = 'AYSq3RDGsmBLJE-otTkBtM-jBRd1TCQwFf9RGfwddNXWz0uFU9ztymylOhRS';
$clientSecret = 'EGnHDxD_qRPdaLdZz8iCr8N7_MzF-YHPTkjs6NKYQvQSBngp4PTTVWkPZRbL';
if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $paymentId = $_GET['paymentId'];

    $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $clientId,
                $clientSecret
                )
            );
        $apiContext->setConfig(
            array(
                'mode' => 'sandbox',
                'log.LogEnabled' => true,
                'log.FileName' => '../PayPal.log',
                'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
                'cache.enabled' => true,
            )
        );

    //var_dump($clientId);exit;
    $payment = Payment::get($paymentId, $apiContext);

    $execution = new PaymentExecution();
    $execution->setPayerId($_GET['PayerID']);

    $transaction = new Transaction();
    $amount = new Amount();
    $details = new Details();

    // $details->setShipping(278.52)
    //     ->setSubtotal(139);

    // $amount->setCurrency('MXN');
    // $amount->setTotal(417.42);
    // $amount->setDetails($details);
    // $transaction->setAmount($amount);

    $execution->addTransaction($transaction);

       try {
        $result = $payment->execute($execution, $apiContext);

       var_dump($result);exit;

        //ResultPrinter::printResult("Executed Payment", "Payment", $payment->getId(), $execution, $result);

        try {
            $payment = Payment::get($paymentId, $apiContext);
        } catch (Exception $ex) {
            var_dump($ex);exit;
            //ResultPrinter::printError("Get Payment", "Payment", null, null, $ex);
            exit(1);
        }
    } catch (Exception $ex) {
        var_dump($ex);exit;

        //ResultPrinter::printError("Executed Payment", "Payment", null, null, $ex);
        exit(1);
    }
    var_dump($payment->getId());exit;
    //ResultPrinter::printResult("Get Payment", "Payment", $payment->getId(), null, $payment);

    return $payment;
} else {
    var_dump('cancel');exit;
    //ResultPrinter::printResult("User Cancelled the Approval", null);
    exit;
}
