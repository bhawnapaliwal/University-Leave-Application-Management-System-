<?php
session_start();
 ini_set('display_errors', 1);

require '/home/bhawna/vendor/autoload.php';
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$test_array = array('hello','hello1');
$client = new MongoDB\Client();
$temp_u = $_SESSION['username'];
$collection = $client->part1->$temp_u;
$doc = $collection->findOne();
if(isset($_POST['add_publ']))
{
	$temp_arr = (array)$doc['Publication'];
	array_push($temp_arr,$_POST['publication'] );
	$temp_arr1 = (array)$doc['Education'];
	$temp_arr2 = (array)$doc['Award'];
	$temp_arr3 = (array)$doc['Grants'];
	$temp_arr4 = (array)$doc['Teaching'];
	// $client->part1->$temp_u->findOne()['Publication']='sdlfmkdsl';
	// $newdata = array('$set' => array($_POST['publication']));
	$client->part1->$temp_u->replaceOne(array(),['Publication'=>$temp_arr,'Education'=>$temp_arr1,'Award'=>$temp_arr2,'Grants'=>$temp_arr3,'Teaching'=>$temp_arr4]);
	// array_push($temp_arr, $_POST['publication']);
	// $doc['Publication']=$temp_arr;
}
else if(isset($_POST['add_education']))
{
	$temp_arr = (array)$doc['Publication'];
	$temp_arr1 = (array)$doc['Education'];
	array_push($temp_arr1,$_POST['education'] );
	$temp_arr2 = (array)$doc['Award'];
	$temp_arr3 = (array)$doc['Grants'];
	$temp_arr4 = (array)$doc['Teaching'];
	// $client->part1->$temp_u->findOne()['Publication']='sdlfmkdsl';
	// $newdata = array('$set' => array($_POST['publication']));
	$client->part1->$temp_u->replaceOne(array(),['Publication'=>$temp_arr,'Education'=>$temp_arr1,'Award'=>$temp_arr2,'Grants'=>$temp_arr3,'Teaching'=>$temp_arr4]);
	// array_push($temp_arr, $_POST['publication']);
	// $doc['Publication']=$temp_arr;
}
else if(isset($_POST['add_award']))
{
	$temp_arr = (array)$doc['Publication'];
	$temp_arr1 = (array)$doc['Education'];
	$temp_arr2 = (array)$doc['Award'];
	array_push($temp_arr2,$_POST['award'] );
	$temp_arr3 = (array)$doc['Grants'];
	$temp_arr4 = (array)$doc['Teaching'];
	$client->part1->$temp_u->replaceOne(array(),['Publication'=>$temp_arr,'Education'=>$temp_arr1,'Award'=>$temp_arr2,'Grants'=>$temp_arr3,'Teaching'=>$temp_arr4]);
}
else if(isset($_POST['add_grant']))
{
	$temp_arr = (array)$doc['Publication'];
	$temp_arr1 = (array)$doc['Education'];
	$temp_arr2 = (array)$doc['Award'];
	$temp_arr3 = (array)$doc['Grants'];
	array_push($temp_arr3,$_POST['grant'] );
	$temp_arr4 = (array)$doc['Teaching'];
	$client->part1->$temp_u->replaceOne(array(),['Publication'=>$temp_arr,'Education'=>$temp_arr1,'Award'=>$temp_arr2,'Grants'=>$temp_arr3,'Teaching'=>$temp_arr4]);
}
else if(isset($_POST['add_teaching']))
{
	$temp_arr = (array)$doc['Publication'];
	$temp_arr1 = (array)$doc['Education'];
	$temp_arr2 = (array)$doc['Award'];
	$temp_arr3 = (array)$doc['Grants'];
	$temp_arr4 = (array)$doc['Teaching'];
	array_push($temp_arr4,$_POST['teaching'] );
	$client->part1->$temp_u->replaceOne(array(),['Publication'=>$temp_arr,'Education'=>$temp_arr1,'Award'=>$temp_arr2,'Grants'=>$temp_arr3,'Teaching'=>$temp_arr4]);
}
else if(isset($_POST['view_details']))
{
	header("location: view.php");
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to your personal profile portal.</h1>
    </div>
    
    <div class="wrapper">
        <h2>Personal Information (Maintained through noSQL)</h2>
<!--         <p>Please fill this form to create an account.</p>
 -->        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Add Publication:</label>
                <input type="text" name="publication" class="form-control" value="">
<!--                 <span class="help-block"><?php echo $username_err; ?></span>
 -->            </div>    
 			<div class="form-group">
                <input type="submit" name = "add_publ" class="btn btn-primary" value="Add">
            </div>
            <div class="form-group">
                <label>Add Education:</label>
                <input type="text" name="education" class="form-control" value="">
                <!-- <span class="help-block"><?php echo $password_err; ?></span> -->
            </div>
            <div class="form-group">
                <input type="submit" name = "add_education" class="btn btn-primary" value="Add">
            </div>
            <div class="form-group">
                <label>Add Award:</label>
                <input type="text" name="award" class="form-control" value="">
                <!-- <span class="help-block"><?php echo $confirm_password_err; ?></span> -->
            </div>
            <div class="form-group">
                <input type="submit" name = "add_award" class="btn btn-primary" value="Add">
            </div>
            <div class="form-group">
                <label>Add Grants:</label>
                <input type="text" name="grant" class="form-control" value="">
                <!-- <span class="help-block"><?php echo $confirm_password_err; ?></span> -->
            </div>
            <div class="form-group">
                <input type="submit" name = "add_grant" class="btn btn-primary" value="Add">
            </div>
            <div class="form-group">
                <label>Add Teaching:</label>
                <input type="text" name="teaching" class="form-control" value="">
                <!-- <span class="help-block"><?php echo $confirm_password_err; ?></span> -->
            </div>
            <div class="form-group">
                <input type="submit" name = "add_teaching" class="btn btn-primary" value="Add">
            </div>
            <div class="form-group">
                <input type="submit" name = "view_details" class="btn btn-primary" value="View Details">
            </div>
            <p>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
        </form>
    </div>    
</body>
</html>