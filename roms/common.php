<?php

session_start();
$messageID = $_SESSION['loggedIn'];
$pass=" YES";
// if ($messageID!=$pass) {
//   header("Location: http://aidanmac91.eu01.aws.af.cm/index.php");
// }
if(($messageID)!="YES")
{
header("Location: http://aidanmac91.eu01.aws.af.cm/index.php");
}

// if($_GET['logout']){
//   fun1();
// }

// if( isset( $_REQUEST['modify'] )) 
// { 
// // insert code here... 
//   header("Location: http://aidanmac91.eu01.aws.af.cm/home.php");

// } 
?>

<head>

     <link href="http://aidanmac91.eu01.aws.af.cm/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="jumbotron.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Staff Viewer</title>
 
    <link type="text/css" rel="stylesheet" href="" />
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
 
</head>
<body>
 <div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/home.php">Rostering Online Management System</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="/home.php">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
          <p class="navbar-text pull-right">
              <a href="http://aidanmac91.eu01.aws.af.cm/index.php">Logout</a>
      <?php
 
 ?>


<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script type="text/javascript">
$(function(){
$('#example_tree').find('SPAN').click(function(e){
    $(this).parent().find('UL').toggle();
});
});
</script>
             <!--  //<button type="button">Log out</button> -->
            </p>
        </div><!-- /.nav-collapse -->
      </div><!-- /.container -->
    </div><!-- /.navbar -->
<div class="jumbotron">
      <div class="col-sm-3 col-md-2 sidebar">
        <ul >

        </ul></div></div>

<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
  				
          <ul class="nav">
            <?php if ($_SESSION['type'] != "Administrator"): ?>
           <li><a href="/create/create_swapMessage.php">Create Swap Message</a></li>
            <li><a href="/create/create_timeoffMessage.php">Create Time off Message</a></li>
            <li><a href="/view/viewLocation.php">View Accommodation</a></li>
            <li><a href="/view/viewSwapMessage.php">View swap message</a></li>
            <li><a href="/view/viewTimeOffMessage.php">View time off message</a></li>
            <li><a href="/view/viewStaffMember.php">View Staff Member</a></li>
            <li><a href="/view/viewAppointment.php">View Appointment</a></li>
            <li><a href="/view/viewClient.php">View Client</a></li>
            <li><a href="/view/viewRoster.php">View Roster</a></li> 
            <li><a href="/view/viewPersonalRoster.php">View Personal Roster</a></li>  
            <li><a href="/view/viewRosters.php">View Roster by Staff</a></li>   

            <li><a href="/view/viewRosters.php">View Roster </a></li>   
            <?php endif; ?>
            <?php if ($_SESSION['type'] == "Administrator"): ?>
            <li><a href="/create/create_appointment.php">Create Appointment</a></li>
            <li><a href="/create/create_client.php">Create Client</a></li>
            <li><a href="/create/create_location.php">Create Location</a></li>
            <li><a href="/create/create_roster.php">Create Roster</a></li>
            <li><a href="/create/create_staff.php">Create Staff member</a></li>
            <li><a href="/view/viewLocation.php">View Accommodation</a></li>
            <li><a href="/view/viewSwapMessage.php">View swap message</a></li>
            <li><a href="/view/viewTimeOffMessage.php">View time off message</a></li>
            <li><a href="/view/viewStaffMember.php">View Staff Member</a></li>
            <li><a href="/view/viewAppointment.php">View Appointment</a></li>
            <li><a href="/view/viewClient.php">View Client</a></li>
            <li><a href="/view/viewRoster.php">View Roster</a></li>   
            <?php endif; ?>
  			        
  				</ul>
  			</div>
          <hr>
  <footer>
    <p>&copy; ROMS 2014</p>
  </footer>
        </body>