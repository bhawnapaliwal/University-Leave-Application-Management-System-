<?php
// Include config file
include 'config.php';
include 'signup_pg.php';
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$dept = $_POST['selectDept'];
	$currentTime = strval(date( 'd-m-Y h:i:s A', time () ));
	$sql = "INSERT INTO Faculty VALUES ('$curr_id','$username','$dept',20,0,0,'-','$currentTime')";
    $result = pg_query($dbconn,$sql);
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
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">   
            <div class="form-group">
            <p>
                <label>Select Department</label>
                <br>
                <select name="selectDept">
                <option value="">Select</option>
                <option value="CSE">CSE</option>
                <option value="ME">MECHANICAL</option>
                <option value="EE">ELECTRICAL</option>
            </select>
            </p>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
    </div>    
</body>
</html>