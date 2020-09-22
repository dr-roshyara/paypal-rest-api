<?php 
/**
	*paypal link : https://developer.paypal.com/docs/api/quickstart/payments/#execute-payment
	*
**/
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Exception\PayPalConnectionException;

require '../src/start.php';

$payer 			=new Payer();
$details 		=new Details();
$amount 		= new Amount();
$transaction	=new Transaction();
$payment		=new Payment();
$redirectUrls	=new RedirectUrls();

// Create new payer and method
$payer->setPaymentMethod("paypal");

//Details 
$details->setShipping('2.00')
	->setTax('0.00')
	->setSubtotal('20.00');

//Amount 
$amount->setCurrency('EUR')
	->setTotal('22.00')
	->setDetails($details);

// Set transaction object
$transaction->setAmount($amount)
  ->setDescription("Payment description");

$payment->setIntent('sale')
	->setPayer($payer)
	->setTransactions(array($transaction));




// $payment->setIntent('sale')
//   ->setPayer($payer)
//   ->setRedirectUrls($redirectUrls)
//   ->setTransactions(array($transaction));


//Redirecturls 
$redirectUrls->setReturnUrl('http://localhost/namastenepal/paypal/paypalpayments/pay.php?approved=true')
	->setCancelUrl('http://localhost/namastenepal/paypal/cancelled.php?approved=false');

//
 $payment->setRedirectUrls($redirectUrls);


// Create payment with valid API context
try {
  $payment->create($api); 

  //generate and store has 
  	$hash =md5($payment->getId());
  	$_SESSION['paypal_hash']= $hash;
  
  //prepare and execute trasaction storage 
  $store=$conn->prepare("INSERT INTO transactions_paypal(user_id,payment_id,hash, complete) 
  		VALUES(:user_id,:payment_id,:hash,0)");
  	$store->execute([
  		'user_id'=>$_SESSION['user_id'],
  		'payment_id'=>$payment->getId(),
  		'hash'=>$hash
  	]);

  // Get PayPal redirect URL and redirect the customer
  $approvalUrl = $payment->getApprovalLink();
  var_dump($approvalUrl);
  // Redirect the customer to $approvalUrl
} catch (PayPal\Exception\PayPalConnectionException $ex) {
 	 echo $ex->getCode();
	  echo $ex->getData();
  	die($ex);
} catch (Exception $ex) {
  	die($ex);
}
foreach($payment->getLinks() as $link ){
	if($link->getRel()=='approval_url'){
		$redirectUrl =$link->getHref();
	}
}

 header('Location: '.$redirectUrl);

