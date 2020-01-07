<?php
	include("config.php");
	session_start();
	if(isset($_SESSION['login_user']))
	{
		header("location:redirect.php");
	}
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		$username = $_POST['email'];
		$password = $_POST['password'];
		$sql_query = "SELECT id,role FROM admin WHERE email_id = '$username' AND password = '$password' AND ispresent =1";
		$result = pg_query($sql_query) or die('Query failed: ' . pg_last_error());
		$row = pg_fetch_array($result,NULL,PGSQL_ASSOC);
		$cnt = pg_num_rows($result);
		if($cnt==1)
		{
			$_SESSION['login_user']=$username;
			$_SESSION['position']=$row["role"];
			$_SESSION['id']=$row["id"];
			header("location:redirect.php");
		}
		else
		{
			$error = "Incorrect username or password";
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
	<div class="wrapper">
		<form id="login-form" action="" method="post">
			<div>
				<label for="username">Email</label>
				<input text="text" name="email" class="form-control" id = "username" placeholder="Email">
				<label for="password">Password</label>
				<input type="password" name="password" class="form-control" id = "password" placeholder="Password">
				<br>
				<div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            	</div>
            	<p>Don't have an account? <a href="signup_pg.php">Sign up now</a>.</p>
            </div>
		</form>
	</div>
</body>