<!--
	File Name: clientInfo.php
	Created by: Aidan McCarthy
	Project: Rostering Online Management System
	Login page for viewing client information
-->
<?php
if(isset($_POST) and $_POST['submitForm'] == "Login" )//if the submit button was pressed
{

	$usr_email = mysql_escape_string($_POST['usr_email']);//set local variable for email
	$usr_password = mysql_escape_string($_POST['usr_password']);//set password variable
	
	$error = array();//list of problems
	if(empty($usr_email) or !filter_var($usr_email,FILTER_SANITIZE_EMAIL))//check length and structure of email address
	{
		$error[] = "Empty or invalid email address";//message to be displayed
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
			$qry = array("email" => $usr_email,"recordPassword" => sha1($usr_password));//query contain email and recordPassword
			$result = $collection->findOne($qry);//finds document that satifys the query
			if($result)//if there is only one entry 
			{
			     session_start();//starts sessions
				$_SESSION['authorised'] = "YES";//sets session variable
			    header("Location: http://aidanmac91.eu01.aws.af.cm/viewMedical.php");//redirects to viewMedical page
			}		
			else//no entry
			{
				$error[] = "Password/Email combo was incorrect";//message to be displayed
			}	
		} 
		else //no connection
		{
			die("Mongo DB not installed");
		}	
	}
}	
?>

<!DOCTYPE html>
<html>
<head>
	<title>ROMS Medical Login</title>
	<meta charset="utf-8">
	<link href="css/bootstrap.css" rel="stylesheet"></link><!--include bootstrap.css-->
	<link href="css/signin.css" rel="stylesheet"></link><!--include signin.css-->
	<style type="text/css">
	</style>
</head>
<body>
	<?php include 'common.php';?><!--include common.php-->
	
	<?php
	//displays the error
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
			<h2 class="form-signin-heading">ROMS Medical Login</h2>
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
