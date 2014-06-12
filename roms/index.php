<!--
  File Name: index.php
  Created by: Aidan McCarthy
  Project: Rostering Online Management System
  the login page for website
-->
<?php
session_start();//start session

$_SESSION['loggedIn'] = "NO";//sets loggedIN to NO. Ensures that user cannot go back. They must log in again
if(isset($_POST) and $_POST['submitForm'] == "Login" )
{

	$usr_email =($_POST['usr_email']);//sets variable to email
	$usr_password =($_POST['usr_password']);//set variable to password
	
	$error = array();//list of errors
	if(empty($usr_email) or !filter_var($usr_email,FILTER_SANITIZE_EMAIL))//check length and structure of email address
	{
		$error[] = "Empty or invalid email address";
	//message to be displayed
	}
	if(empty($usr_password))//if password is empty
	{
		$error[] = "Enter your password";	//message to be displayed
	}
	if(count($error) == 0)//if no errors
	{
		$connection = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');//establish connection to Mongolab.com
		if($connection)//if connection if valid
		{
			$database = $connection->selectDB('staff');// Select Database
			$collection = $database->selectCollection('users');// Select Collection
			$qry = array("email" => $usr_email,"password" => sha1($usr_password));//query contain email and password
			$result = $collection->findOne($qry);//finds document that satifys the query
			if($result)//if there is only one entry 
			{
				$_SESSION['type'] = $result['staffType'];//sets session variable type
				$_SESSION['username'] = $result['name'];//sets session variable username
				$_SESSION['loggedIn'] = "YES";//sets session variable loggedIn
				header("Location: http://aidanmac91.eu01.aws.af.cm/home.php");//redirect
			}		
			else
			{
				$error[] = "Password/Email combo was incorrect";//message to be displayed
			}	
		} 
		else 
		{
			die("Mongo DB not installed");
		}	
	}
}	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Rostering Online Management System Login</title>
	<meta charset="utf-8">
	<link href="css/bootstrap.css" rel="stylesheet"></link><!--include bootstrap.css -->
	<link href="css/signin.css" rel="stylesheet"></link><!--include signin.css -->
	<style type="text/css">

	</style>
	<script src="js/jquery.min.js"></script>
</head>
<body>
	<?php
	//displays the errors
	if(isset($error) and count($error) > 0){
		?>
		<div class="errorContainer"> <ul>
			<?php foreach($error as $err) echo '<li>'.$err.'</li>'; ?> 		
		</ul></div>
		<?php	
	}

	?>
	<div class="container">
		<form id="loginForm" class="form-signin" method="POST"><!--creates form-->
			<h2 class="form-signin-heading">ROMS Login</h2>
			<div class="control-group">
				<div class="controls"><!--email field-->
					<input type="text" id="usr_email" name="usr_email" class="input-block-level" placeholder="Email address" />
				</div>
			</div>
			<div class="control-group">
				<div class="controls"><!--password field-->
					<input type="password" id="usr_password" name="usr_password" class="input-block-level" placeholder="Password" />	    
				</div>
			</div>
			<input class="btn btn-large btn-primary" name="submitForm" id="submitForm" type="submit" value="Login" />
			<br/>
		</form>
	</div>
</body>
</html>
