<?php
 session_start();

 //$_SESSION['loggedIn'] = "NO";
$succss = "";
if(isset($_POST) and $_POST['submitForm'] == "Login" ){

	$usr_email = mysql_escape_string($_POST['usr_email']);
	$usr_password = mysql_escape_string($_POST['usr_password']);
	
	$error = array();
	if(empty($usr_email) or !filter_var($usr_email,FILTER_SANITIZE_EMAIL)){
		$error[] = "Empty or invalid email address";
	}
	if(empty($usr_password)){
		$error[] = "Enter your password";	
	}
	if(count($error) == 0){
		$con = new Mongo('mongodb://root:root@ds057538.mongolab.com:57538/staff');
		if($con){
			// Select Database
			$db = $con->selectDB('staff');
			// Select Collection
			$people = $db->selectCollection('users');
			$qry = array("email" => $usr_email,"recordPassword" => sha1($usr_password));
			$result = $people->findOne($qry);
			if($result){
			    $success = "You are successully loggedIn";
			     session_start();
			     //$_SESSION['type'] = $result['staffType'];
				//$_SESSION['username'] = $result['name'];
				$_SESSION['authorised'] = "YES";
			    header("Location: http://aidanmac91.eu01.aws.af.cm/home.php");
			}		
			else{
				$error[] = "Password/Email combo was incorrect";
			}	
		} else {
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
<link href="css/bootstrap.css" rel="stylesheet"></link>
<style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }
      .errorContainer{ max-width:370px; margin:0 auto; }
	.errorContainer ul{ list-style:none; padding:5px; margin:0px;}
	.errorContainer li { color:red; padding:5px; }
    </style>
<script src="js/jquery.min.js"></script>
</head>
<body>
	<?php include 'common.php';?>
<?php
	if(isset($error) and count($error) > 0){
?>
		<div class="errorContainer"> <ul>
		<?php foreach($error as $err) echo '<li>'.$err.'</li>'; ?> 		
		</ul></div>
<?php	
	}

	if(!empty($uccess)){
		echo '<p class="text-success">'.$success.'</p>';
	}
?>
<div class="container">
      <form id="loginForm" class="form-signin" method="POST">
        <h2 class="form-signin-heading">ROMS Login</h2>
	<div class="control-group">
	  <div class="controls">
	    <input type="text" id="usr_email" name="usr_email" class="input-block-level" placeholder="Email address" />
	  </div>
	</div>
        <div class="control-group">
	  <div class="controls">
	    <input type="password" id="usr_password" name="usr_password" class="input-block-level" placeholder="Password" />	    
	  </div>
	</div>
        <input class="btn btn-large btn-primary" name="submitForm" id="submitForm" type="submit" value="Login" />
<br/>
      </form>
</div>
</body>
</html>
