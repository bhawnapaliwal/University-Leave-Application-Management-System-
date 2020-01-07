<?php

ini_set('display_errors', 1);

require '/home/bhawna/vendor/autoload.php';

$client = new MongoDB\Client();
$collection = $client->part1->signedup;
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
$cnt =0;
$result1 = $collection->find();
$list = array();
$str='';
foreach($result1 as $entry)
{
	$a = $entry['username'];
	$doc1 = $client->part1->$a->findOne();
	$p = implode(', ',(array)$doc1['Publication']);
	$e = implode(', ',(array)$doc1['Education']);
	$a = implode(', ',(array)$doc1['Award']);
	$g = implode(', ',(array)$doc1['Grants']);
	$t = implode(', ',(array)$doc1['Teaching']);
	$tempspace='	';
	$str = $str.$entry['username'].':<br>'.$tempspace.'Publications: '.$p.'<br>'.'Education: '.$e.'<br>'.'Awards: '.$a.'<br>'.'Grants: '.$g.'<br>'.'Teaching: '.$t.'<br><br>';
	
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    
    if(empty(trim($_POST["username"])))
    {
        $username_err = "Please enter a username.";
    } 
    else
    {
        $result = $collection->find(['username'=>$_POST["username"]]);
        foreach ($result as $entry)
        {
        	echo $entry['username'], "\n";
        	$cnt = $cnt+1;
        }
        if($cnt>0)
        {
        	$username_err = "This username is already taken.";
        }
        else
        {
            $username = trim($_POST["username"]);
        }
        
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        $param_username = $username;
        $param_password = $password; 
        $name = $_POST['name'];
        $result1 = $client->part1->signedup->insertOne(['username'=>$param_username,'password'=>$param_password, 'name'=>$name]);
        $collection1 = $client->part1->createCollection($username);
        $client->part1->$username->insertOne(['Publication'=>array(),'Education'=>array(),'Award'=>array(),'Grants'=>array(),'Teaching'=>array()]);
        header("location: login.php");
    }
 }
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
        .b{font: 14px sans-serif; text-align: center;}
        /*body {background-color:#66ccff}

        /*h1 {color:#ff8080}*/
    </style>
</head>
<body>
	<div class="wrapper">
        <form action="#" method="POST">
        <?php 
        echo '  ';
        
        if(isset($_POST["view34"]))
        {
            header("location:faculty_info.php");
        }
        
        ?>
    </form>
	</div>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
<!--                	<?php //echo 'hiiiiiiiiiii'.$username_err.'jiii'?>;
 -->                <span class="help-block"><?php echo $username_err; ?></span>
            </div>   
            <div class="form-group ">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="">
<!--                	<?php //echo 'hiiiiiiiiiii'.$username_err.'jiii'?>;
 -->                <!-- <span class="help-block"><?php //echo $username_err; ?></span> -->
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset"><br><br>
                <input type="submit" name ="view34" class="btn btn-primary"value="View Faculty Info">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>