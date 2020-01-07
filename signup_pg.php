<?php
include 'config.php';
 
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";
 $curr_id;
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
    } else{
        $email = $_POST['email'];
        $name = $_POST['name'];
        $sql = "SELECT id FROM admin WHERE ispresent=-1";
        $result = pg_query($dbconn,$sql);
        $result = pg_fetch_array($result,NULL,PGSQL_ASSOC);
        $curr_id = $result['id'];
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $role = $_POST['selectRole'];
        $temp = $curr_id+1;
        $sql1 = "UPDATE admin SET id = '$temp' WHERE ispresent=-1";
        $result1 = pg_query($dbconn,$sql1);
        $sql = "INSERT INTO admin VALUES ('$name','$password','$curr_id','$role',1,'$email')";
        $dept = $_POST['selectDept'];
        $result = pg_query($dbconn,$sql);
        if($role=='FACULTY')
        {
            $currentTime = strval(date( 'd-m-Y h:i:s A', time () ));
            $sql = "INSERT INTO Faculty VALUES ('$curr_id','$name','$dept',20,0,0,'$currentTime','-')";
            $result = pg_query($dbconn,$sql);
            header("location: login_pg.php");
        }
        else if($role=='DEAN_FACULTY_AFFAIRS'||$role=='ASSOCIATE_DEAN_FACULTY_AFFAIRS')
        {
            $currentTime = strval(date( 'd-m-Y h:i:s A', time () ));
            $sql1 = "INSERT INTO Faculty VALUES ('$curr_id','$name','$dept',20,0,0,'$currentTime','-')";
            $r1 = pg_query($dbconn,$sql1);
            $sql = "INSERT INTO cross_cutting_faculty VALUES ('$curr_id','$role','$name',20,0,0,'$currentTime')";
            $result = pg_query($dbconn,$sql);
            // header("location: login_pg.php");
        }
        else if($role=='HOD')
        {
            $currentTime = strval(date( 'd-m-Y h:i:s A', time () ));
            $sql = "INSERT INTO Faculty VALUES ('$curr_id','$name','$dept',20,0,0,'$currentTime','-')";
            $result = pg_query($dbconn,$sql);
            $currentTime = strval(date( 'd-m-Y h:i:s A', time () ));
            $q1 = "INSERT INTO current_hod VALUES ('$curr_id','$dept','$currentTime')";
            $result1 = pg_query($dbconn,$q1);
            // header("location: login_pg.php");
        }
        }
         
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
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Email-id</label>
                <input type="text" name="email" class="form-control" value="">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="">
<!--                 <span class="help-block"><?php //echo $username_err; ?></span>
 -->            </div>    
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
            <!-- <div class="form-group">
                <label>Role</label>
                <input type="text" name="role" class="form-control" value="">
            </div> -->
            <div class="form-group">
            <p>
                <label>Select Role</label>
                <br>
                <select name="selectRole">
                <option value="">Select</option>
                <option value="FACULTY">Faculty</option>
                <option value="HOD">HOD</option>
                <option value="DEAN_FACULTY_AFFAIRS">Dean Faculty Affairs</option>
                <option value="ASSOCIATE_DEAN_FACULTY_AFFAIRS">Associate Dean Faculty Affairs</option>
                <option value="DIRECTOR">Director</option>
            </select>
            </p>
            </div>
            <div class="form-group">
            <p>
                <label>Select Department</label>
                <br>
                <select name="selectDept">
                <option value="">Select</option>
                <option value="CSE">CSE</option>
                <option value="MECH">MECHANICAL</option>
                <option value="EE">ELECTRICAL</option>
            </select>
            </p>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Already have an account? <a href="login_pg.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>