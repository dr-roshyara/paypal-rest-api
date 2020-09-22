<?php 
require('../src/start.php');
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ExecutePayment;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;




if(isset($_GET['approved'])){
	$approved =$_GET['approved']==='true';

	if($approved){
		// Get payment object by passing paymentId
		echo "<br>approved<br>";
		// $paymentId =$conn->prepare("SELECT payment_id FROM transactions_paypal where hash=:hash");
		// $paymentId->execute([
		// 	'hash'=>$_SESSION['paypal_hash']
		// ]);
		// $paymentId =$paymentId->fetchObject()->payment_id;
		$paymentId = $_GET['paymentId'];
		$payment = Payment::get($paymentId, $api);



		// //
		// // Execute payment with payer ID
		$payerId = $_GET['PayerID'];
		$execution = new PaymentExecution();
		$execution->setPayerId($payerId);

		try {
			  // Execute payment
			 $payment = $payment->execute($execution, $api);
			 	 // var_dump($payment);
			 //update Transaction 
			 $updateTransaction =$conn->prepare("UPDATE transactions_paypal  
			 		SET complete=1 
			 		WHERE payment_id= :payment_id");
			//execute the command 
			$updateTransaction->execute([
				'payment_id'=>$paymentId
			]);
			// Update membership 
			$setMember =$conn->prepare(" UPDATE user SET member=1 WHERE id= :user_id");
			$setMember->execute([
				'user_id'=>$_SESSION['user_id']

			]);
			//unset paypal has 
			unset ($_SESSION['paypal_hash']);
			// redirect 
			header('Location: ../member/complete.php');
			} catch (PayPal\Exception\PayPalConnectionException $ex) {
			  echo "<br>failed<br>";
			  echo $ex->getCode();
			  echo $ex->getData();
			  die($ex);
			} catch (Exception $ex) {
			  die($ex);
			}


	}else{
	
		header('Location: ../paypal/cancelled.php');		
	}
}