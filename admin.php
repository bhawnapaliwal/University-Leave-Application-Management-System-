<?php
	include('config.php');
	include('session.php');
	if (!isset($_SESSION['position']) || $_SESSION['position'] != 'ADMIN') {
        echo "ONLY ADMIN";
        die();
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
        <h1>Hi, <?php
			echo $login_session;
            ?>
        </h1>
        <h2>Change path:</h2>
        <form action="#" method="post">
            FOW WHOM: <input type="text" name="NAME1" values="NAME1">
            ENTER NEW PATH: <input type="text" name="PATH1" values="PATH1"/>
            <br>
            <br>
            <div class="form-group">
                <input type="submit" name ="change_path_faculty" class="btn btn-primary" value="Update Path">
            </div>
        </form>
        <h2>Delete Profile:</h2>
        <form action="#" method="post">
            USER ID: <input type="number" name="U1" values="U1">
            <br>
            <br>
            <div class="form-group">
                <input type="submit" name ="delete_profile" class="btn btn-primary" value="Delete">
            </div>
        </form>
        <h2>Update HOD:</h2>
        <form action="#" method="post">
        	<div class="form-group">
            <p>
                <label>Select Department</label>
                <br>
                <select name="selectDept">
                <option value="">Select</option>
                <option value="CSE">CSE</option>
                <option value="MECH">MECH</option>
                <option value="EE">EE</option>
            </select>
            </p>
            </div>
            USER ID: <input type="number" name="U2" values="U2">
            <br>
            <br>
            <div class="form-group">
                <input type="submit" name ="update_hod" class="btn btn-primary" value="Update">
            </div>
        </form>
        <h2>Update Cross-Cutting Faculty:</h2>
        <form action="#" method="post">
        	<div class="form-group">
            <p>
                <label>Select Role</label>
                <br>
                <select name="selectRole">
                <option value="">Select</option>
                <option value="DEAN_FACULTY_AFFAIRS">Dean Faculty Affairs</option>
                <option value="ASSOCIATE_DEAN_FACULTY_AFFAIRS">Associate Dean Faculty Affairs</option>
            </select>
            </p>
            </div>
            USER ID: <input type="number" name="U3" values="U3">
            <br>
            <br>
            <div class="form-group">
                <input type="submit" name ="update_cc" class="btn btn-primary" value="Update">
            </div>
        </form>
    </div>
    <?php
        if(isset($_POST["change_path_faculty"]))
        {
            $path1 = $_POST['PATH1'];
            $for_whom1 = $_POST['NAME1'];
            $q1 = "UPDATE leave_path SET path_to_be_followed='$path1' WHERE for_whom='$for_whom1'";
            $r1 = pg_query($q1);
            if($r1)
                echo "UPDATED";
            else
                echo "FAILED TO UPDATE";
        }
        if(isset($_POST["delete_profile"]))
        {
            $u_id = $_POST['U1'];
            $q2 = "UPDATE admin SET ispresent=0 WHERE id = '$u_id'";
	        $r2 = pg_query($dbconn,$q2);
	        if($r2)
	            echo "UPDATED";
	        else
	            echo "FAILED TO UPDATE";

        }
        if(isset($_POST["update_hod"]))
        {
        	$u_id = $_POST['U2'];
        	$dept = $_POST['selectDept'];
        	$currentTime = strval(date( 'd-m-Y h:i:s A', time () ));
        	$q = "UPDATE current_hod SET faculty_id = '$u_id', date_of_joining = '$currentTime' WHERE dept_name='$dept'";
        	$r = pg_query($dbconn,$q);
        }
        if(isset($_POST["update_cc"]))
        {
        	$u_id = $_POST['U3'];
        	$role = $_POST['selectRole'];
        	$currentTime = strval(date( 'd-m-Y h:i:s A', time () ));
        	$q1 = "SELECT * FROM Faculty WHERE faculty_id = '$u_id'";
        	$res1 = pg_query($dbconn,$q1);
        	$res1 = pg_fetch_array($res1,NULL,PGSQL_ASSOC);
        	$res1 = $res1['faculty_name'];
        	$q = "UPDATE cross_cutting_faculty SET id = '$u_id', name = '$res1', total_leaves = 20, regular_leaves = 0, borrowed_leaves = 0, joining_date = '$currentTime' WHERE user_role = '$role'";
        	$r = pg_query($dbconn,$q);
        }

    ?>
    <br>
    <p>
        <a href="logout_pg.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
    <br>
    <h4><a href="./admin.php">Refresh</a></h4>
</body>
</html>