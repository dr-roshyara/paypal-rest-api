## Paypal implementation  with PHP PAYPAL REST API 
	This repo is to implement paypal REST API form scratch. 
	#Follow the following steps
	composer init 
	// Prerequisites: PHP 5.3 or above
	// cURL, json & openssl extensions must be enabled
	##Install Paypal Rest Api 
	composer require "paypal/rest-api-sdk-php:*"
# Define Payment 
	 #create start.php file 
	 mkdir src 
	 touch src/start.php 
	 In the start.php file, we now start php session 
	 #start the session with : 
	  session_start () 
	#Go to the Developer.paypal.com 
	#create  an app and 
	#get the client_id and secret id. define the app in start.php file as following 
	$api = new APIContext(
		   new OAuthTokenCredential(
			'AdpI6XPg3mGD4SVjU46itbYxLtdpdIQ33GgBsJohPfmpLZq4bJim_rcohcvvX4Rkat_Hijgg64foDm51', // client id  
			'EKClsXrCq8H7E7sVY-7uP9Gq961dqPYhCnx8Boh11pT2-Vdyjg3snlRcWbCAA_8ZUZvBLzZyDtGdHteN'  //secret 
		)
	);
	$api->setConfig([
		'mode'=>'Sandbox',
		'http.connectionTimeOut'=>30,
		'log.LogEnabled'=>false,
		'log.FileName'=>'',
		'log.LogLevel'=>'FINE',
		'validation.level'=>'log'
	]);

# Create database 
	#Create a database e.g. paypal 
	#We use here mysql: 
		username:root 
		password: root 
	 #Now create some sql statements e.g. create table as follwoing 
			$sql ="CREATE TABLE user (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				firstname VARCHAR(30) NOT NULL,
				lastname VARCHAR(30) NOT NULL,
				email VARCHAR(50),
				reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			)";
		//sql statements creating a user 
			$sql1 = "INSERT INTO user (firstname, lastname, email)
			 VALUES ('John', 'Doe', 'john@example.com')";
			 $sql2 ="ALTER TABLE user ADD member TINYINT "; 
 		$sql3 ="UPDATE user SET member=0 WHERE id=1";
		#After creating database and  creating a user on it . we would like to  make a payment for the user.  #To make the payment for the first user , we would like to  add the following code: 
			$stmt1=$conn->prepare("SELECT * FROM `user` WHERE id= :user_id");
			$stmt1->execute(['user_id'=>$_SESSION['user_id']]);
			$user =$stmt1->fetchObject();

# Create a Payment Method now. 
		To create the payment, send the payment object to PayPal. This action provides a redirect URL to which to redirect the customer. After the customer accepts the payment, PayPal redirects the customer to the return 	or cancel URL that you specified in the payment object.
		# create a payment.php file to pay the membership fee. 
	 	mkdir member 
	 	touch member/payment.php 
		 #In this file, the payment will be defined and created.	
		 #Look at the codes here. If everything goes ok , it will be forwarded to further page 
		 payments/pay.php.
		 #If cancelled , it will be forwarded to page: cancelled.php 
# Execute Payment 
	#payment/pay.php 
	#Here will the be payment executed. 
	Finally, it will be directed to complete.php page


