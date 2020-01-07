<?php
session_start();
 ini_set('display_errors', 1);

require '/home/bhawna/vendor/autoload.php';
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$client = new MongoDB\Client();
$temp_u = $_SESSION['username'];
$doc = $client->part1->$temp_u->findOne();
$p = implode('<br>',(array)$doc['Publication']);
$e = implode('<br>',(array)$doc['Education']);
$a = implode('<br>',(array)$doc['Award']);
$g = implode('<br>',(array)$doc['Grants']);
$t = implode('<br>',(array)$doc['Teaching']);
$p1 = (array)$doc['Publication'];
$e1 = (array)$doc['Education'];
$a1 = (array)$doc['Award'];
$g1 = (array)$doc['Grants'];
$t1 = (array)$doc['Teaching'];
if(isset($_POST['delete_pb']))
{
	$temp_arr = (array)$doc['Publication'];
	$to_delete = $_POST['selectPub'];
	$key = array_search($to_delete, $temp_arr);
	unset($temp_arr[$key]);
	$temp_arr1 = (array)$doc['Education'];
	$temp_arr2 = (array)$doc['Award'];
	$temp_arr3 = (array)$doc['Grants'];
	$temp_arr4 = (array)$doc['Teaching'];
	$client->part1->$temp_u->replaceOne(array(),['Publication'=>$temp_arr,'Education'=>$temp_arr1,'Award'=>$temp_arr2,'Grants'=>$temp_arr3,'Teaching'=>$temp_arr4]);
	
}
if(isset($_POST['delete_ed']))
{
	$temp_arr = (array)$doc['Education'];
	$to_delete = $_POST['selectEd'];
	$key = array_search($to_delete, $temp_arr);
	unset($temp_arr[$key]);
	$temp_arr1 = (array)$doc['Publication'];
	$temp_arr2 = (array)$doc['Award'];
	$temp_arr3 = (array)$doc['Grants'];
	$temp_arr4 = (array)$doc['Teaching'];
	$client->part1->$temp_u->replaceOne(array(),['Publication'=>$temp_arr1,'Education'=>$temp_arr,'Award'=>$temp_arr2,'Grants'=>$temp_arr3,'Teaching'=>$temp_arr4]);
	
}
if(isset($_POST['delete_aw']))
{
	$temp_arr = (array)$doc['Award'];
	$to_delete = $_POST['selectAw'];
	$key = array_search($to_delete, $temp_arr);
	unset($temp_arr[$key]);
	$temp_arr1 = (array)$doc['Education'];
	$temp_arr2 = (array)$doc['Publication'];
	$temp_arr3 = (array)$doc['Grants'];
	$temp_arr4 = (array)$doc['Teaching'];
	$client->part1->$temp_u->replaceOne(array(),['Publication'=>$temp_arr2,'Education'=>$temp_arr1,'Award'=>$temp_arr,'Grants'=>$temp_arr3,'Teaching'=>$temp_arr4]);
	
}
if(isset($_POST['delete_gr']))
{
	$temp_arr = (array)$doc['Grants'];
	$to_delete = $_POST['selectGr'];
	$key = array_search($to_delete, $temp_arr);
	unset($temp_arr[$key]);
	$temp_arr1 = (array)$doc['Education'];
	$temp_arr2 = (array)$doc['Award'];
	$temp_arr3 = (array)$doc['Publication'];
	$temp_arr4 = (array)$doc['Teaching'];
	$client->part1->$temp_u->replaceOne(array(),['Publication'=>$temp_arr3,'Education'=>$temp_arr1,'Award'=>$temp_arr2,'Grants'=>$temp_arr,'Teaching'=>$temp_arr4]);
	
}
if(isset($_POST['delete_te']))
{
	$temp_arr = (array)$doc['Teaching'];
	$to_delete = $_POST['selectTe'];
	$key = array_search($to_delete, $temp_arr);
	unset($temp_arr[$key]);
	$temp_arr1 = (array)$doc['Education'];
	$temp_arr2 = (array)$doc['Award'];
	$temp_arr3 = (array)$doc['Publication'];
	$temp_arr4 = (array)$doc['Grants'];
	$client->part1->$temp_u->replaceOne(array(),['Publication'=>$temp_arr3,'Education'=>$temp_arr1,'Award'=>$temp_arr2,'Grants'=>$temp_arr4,'Teaching'=>$temp_arr]);
	
}
if(isset($_POST['back']))
{
	header('Location: dashboard.php');
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View and Edit Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
	<h1> <b><?php echo ($_SESSION["username"].' Details:'); ?></b> </h1>
	<h2> <b><?php echo ('Publications:'); ?></b> </h2>
	<h4> <b><?php echo ($p);?></b> </h4>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <p>
        <label>Select Publication to Remove</label>
        <br>
        <select name="selectPub">
        <option value="">Select</option>
        <?php
        	foreach($p1 as $key=>$value)
        	{
        		echo $value;
        		echo '<option value='.$value.'>'.$value.'</option>';
        	}
        ?>
        </select>
    </p>
    <input type="submit" name = "delete_pb" class="btn btn-primary" value="Delete Publication">
    </form>
	<h2> <b><?php echo ('Education:'); ?></b> </h2>
	<h4> <b><?php echo ($e); ?></b> </h4>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <p>
        <label>Select Education to Remove</label>
        <br>
        <select name="selectEd">
        <option value="">Select</option>
        <?php
        	foreach($e1 as $key=>$value)
        	{
        		echo $value;
        		echo '<option value='.$value.'>'.$value.'</option>';
        	}
        ?>
        </select>
    </p>
    <input type="submit" name = "delete_ed" class="btn btn-primary" value="Delete Education">
    </form>
	<h2> <b><?php echo ('Awards:'); ?></b> </h2>
	<h4> <b><?php echo ($a); ?></b> </h4>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <p>
        <label>Select Award to Remove</label>
        <br>
        <select name="selectAw">
        <option value="">Select</option>
        <?php
        	foreach($a1 as $key=>$value)
        	{
        		echo $value;
        		echo '<option value='.$value.'>'.$value.'</option>';
        	}
        ?>
        </select>
    </p>
    <input type="submit" name = "delete_aw" class="btn btn-primary" value="Delete Award">
    </form>
	<h2> <b><?php echo ('Grants:'); ?></b> </h2>
	<h4> <b><?php echo ($g); ?></b> </h4>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <p>
        <label>Select Education to Remove</label>
        <br>
        <select name="selectGr">
        <option value="">Select</option>
        <?php
        	foreach($g1 as $key=>$value)
        	{
        		// echo $value;
        		echo '<option value='.$value.'>'.$value.'</option>';
        	}
        ?>
        </select>
    </p>
    <input type="submit" name = "delete_gr" class="btn btn-primary" value="Delete Grant">
    </form>
	<h2> <b><?php echo ('Teaching:'); ?></b> </h2>
	<h4> <b><?php echo ($t); ?></b> </h4>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <p>
        <label>Select Teaching Value to Remove</label>
        <br>
        <select name="selectTe">
        <option value="">Select</option>
        <?php
        	foreach($t1 as $key=>$value)
        	{
        		// echo $value;
        		echo '<option value='.$value.'>'.$value.'</option>';
        	}
        ?>
        </select>
    </p>
    <input type="submit" name = "delete_te" class="btn btn-primary" value="Delete Teaching">
    </form>
    <p><a href="dashboard.php">Back</a></p>
    <p><a href="view.php">Refresh</a></p>
</body>
</html>