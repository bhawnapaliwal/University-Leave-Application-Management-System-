<?php


ini_set('display_errors', 1);

require '/home/bhawna/vendor/autoload.php';
$client = new MongoDB\Client();
$collection = $client->part1->signedup;
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: dashboard.php");
    exit;
}
 

$username = $password = "";
$username_err = $password_err = "";
 

if(isset($_POST["Login"])){
 
   
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
   
    if(empty($username_err) && empty($password_err)){
        
        $look_for_user = $collection->find(['username'=>$_POST["username"]]);
        $lcnt=0;
        
        foreach ($look_for_user as $entry)
        {
            $lcnt = $lcnt+1;
        }
        if ($lcnt==0)
        {
            $username_err = "No account found with that username.";
        }
        else
        {
            $look_for_password = $collection->findOne(['username'=>$_POST["username"]]);
            $arr = iterator_to_array($look_for_password);
            if ($arr['password']==$_POST['password'])
            {
                session_start();
                            
                
                $_SESSION["loggedin"] = true;
                $_SESSION["username"] = $username; 
                header("location: dashboard.php");
            }
            else
            {
                $password_err = "The password you entered was not valid.";
            }
        }
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
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login" name ="Login">
            </div>
            <p>Don't have an account? <a href="phpinfo.php">Sign up now</a>.</p>
        </form>
    </div>    
</body>
</html>