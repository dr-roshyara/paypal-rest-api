<?php 

 use PayPal\Rest\ApiContext;
 use PayPal\Auth\OAuthTokenCredential;


session_start();
/****
	*Define the Server paramter 
	*servername 
	*username 
	*password etc 
*/
$servername 		= "127.0.0.1";
$username 			= "root";
$password 			= "root";
$dbname 			='paypal';

 require __DIR__."/../vendor/autoload.php";
//session starts here 
 $_SESSION['user_id']=1;
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

$sql ="CREATE TABLE user (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		firstname VARCHAR(30) NOT NULL,
		lastname VARCHAR(30) NOT NULL,
		email VARCHAR(50),
		reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
		)";
$sql_1 ="CREATE TABLE transactions_paypal(
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		user_id	INT(11) UNSIGNED,
		payment_id INT(11) UNSIGNED,
		hash VARCHAR(255),
		complete TINYINT,
		reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
		)";		
//sql statements creating a user 
  	$sql1 = "INSERT INTO user (firstname, lastname, email)
	  VALUES ('John', 'Doe', 'john@example.com')";
	 $sql2 ="ALTER TABLE user ADD member TINYINT "; 
	 $sql3 ="UPDATE user SET member=0 WHERE id=1";
	
try{
	// Make sql connection with pdo 
	$conn=new PDO("mysql:host =127.0.0.1; port=3307; dbname=$dbname; charset=utf8", 
		$username, $password );  
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	 //create table  and use 
	/** *Command to execute only once. 
		*uncomment the follwoing commands and
		*excecute them only once.
	**/	 
	/** if ($conn->query($sql) === TRUE) {
	  		 $conn->query($sql1);
	  		 $conn->query($sql2);
	  	 	$conn->query($sql3);
	  		echo "Table users created successfully";
		} else {
		  echo "Error creating table: " . $conn->error;
		}
	*/
	//just a test 
	// $conn->query($sql_1);

	$stmt = $conn->prepare("show tables");
	$stmt->execute();
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$result =$stmt->fetchAll();
	var_dump($result);
	//preparing a statement get the user.  
		$stmt1=$conn->prepare("SELECT * FROM `user` WHERE id= :user_id");
		$stmt1->execute(['user_id'=>$_SESSION['user_id']]);
		$user =$stmt1->fetchObject();
		//var_dump($stmt1->fetchAll());
	//this command is just to display the $user properites.You can remove it .  
	var_dump($user);
	
	$stmt_1=$conn->prepare("UPDATE `transactions_paypal`  
			 		SET complete=0 where user_id=1 ");
	$stmt_1->execute();
	//
	$setMember =$conn->prepare(" UPDATE user SET member=0 WHERE id= :user_id");
			$setMember->execute([
				'user_id'=>$_SESSION['user_id'] 

	]);

	
	$stmt_1=$conn->prepare("SELECT * FROM `transactions_paypal` ");
	$stmt_1->execute();
	$mypay =$stmt_1->fetchObject();
	echo "<br/> Transaction: <br/>"; 
	var_dump($mypay);	


}catch(PDOException $e){
	// $conn->close();

	echo "Connection Failed:". $e->getMessage();
}
//session ends here
?>  
<!DOCTYPE html>
<html>
<head>
	<title> Paypal </title>
</head>
<body>
	<p>
	<b><?php echo "Hi ".$user->firstname ."," ?> </b>
	<?php if($user->member): ?>
		Great to know that you are a member.
		<?php else: ?>
		 You are not a member.  So please become a  <a href="member/payment.php"> Member</a>.  
		<?php endif; ?>	
	</p>

</body>
</html>