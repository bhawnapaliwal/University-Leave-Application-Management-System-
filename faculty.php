<?php
	include('config.php');
	include('session.php');
	if (!isset($_SESSION['position']) || $_SESSION['position'] != 'FACULTY') {
        echo "ONLY PROFESSORS";
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <?php
			echo $login_session;
			$list_of_faculties = "SELECT * FROM Faculty WHERE faculty_id = " . $_SESSION["id"];
                $result = pg_query($dbconn, $list_of_faculties);
                $row = pg_fetch_array($result,NULL,PGSQL_ASSOC);
                echo '<h2>Faculty ID: ' . $row['faculty_id'] . '</h2>';
                echo '<h2>Department: ' . $row['faculty_dept'] . '</h2>';
				echo '<h2>Total Leaves: '.$row['total_leaves'].'</h2>';
                echo '<h2>Regular Leaves: '.$row['regular_leaves'].'</h2>';
                echo '<h2>Borrowed Leaves: '.$row['borrowed_leaves'].'</h2>';
            ?>	
        </h1>
    </div>
    <div id="view_data">
    	<h1>View the Status of your Leave Applications</h1>
    	<?php
    		$query = "SELECT * FROM leave_table WHERE user_role = 'FACULTY' AND user_id = ".$_SESSION['id']." ";
    		$result = pg_query($dbconn,$query);
    		if($result && pg_num_rows($result)>0)
    		{
    			while($row=pg_fetch_array($result,NULL,PGSQL_ASSOC))
    			{
    				echo"<h3> Leave_ID: ".$row["leave_id"].'</h3>';
    				echo "<h3>From Date : ".$row["from_date"].'</h3>';
					echo "<h3>Duration : ".$row["leave_duration"].'</h3>';
					if($row['status']==1)
					{
						echo "<h3>status of the application  : SANCTIONED".'</h3>';
					}
					else if ($row['status']==0)
					{
						echo "<h3>status of the application  : PENDING".'</h3>';
						if($row["path_rem"]!='')
							echo "<h3>Remaining Path: ".$row["path_rem"].'</h3>';
						echo "<h3>Currently at: ".$row["next_hop"].'</h3>';
					}
					else if($row['status']==-1)
					{
						echo "<h3>status of the application  : REJECTED".'</h3>';
						echo "<h3>Remaining Path: ".$row["path_rem"].'</h3>';
					}
					else if($row['status']==2)
					{
                        echo "<h3>status of the application  : REDIRECTED".'</h3>';
					}
					if($row['comments']!='')
						echo "<h3>comments: ".$row['comments'].'</h3>';
    			}
    		}
    		else
    		{
    			echo "No Leaves Requested";
    		}
    	?>
    </div>
    <div id="new_leave">
    	<h1>Start Application for New Leave</h1>
    	<form action="#" method="post">
    		FROM: <input type="datetime-local" name="FROM" values="FROM:"/>
    		LEAVE DURATION: <input type="number" name="DUR" values="LEAVE DURATION:"/>
            COMMENTS: <input type="text" name="comm" values="COMMENTS:"/>
    		<br>
    		<br>
    		<div class="form-group">
                <input type="submit" name ="add" class="btn btn-primary" value="Send Request">
            </div>
    	</form>
    	<hr>
    	<?php
    		if(isset($_POST["add"]))
    		{
    			$sql_query1 = "SELECT * FROM leave_table WHERE user_role='FACULTY' AND user_id = ".$_SESSION['id']." AND (status = 0 or status=2)";
    			$result1 = pg_query($dbconn,$sql_query1);
    			if($result1 && pg_num_rows($result1)>0)
    			{
    				echo "<h2>You already have pending leaves.</h2>";
    			}
    			else
    			{
    				$from = $_POST["FROM"];
    				$dur = $_POST["DUR"];
                    $comm = $_POST["comm"];
    				$id = $_SESSION['id'];
                    $tle;
                    $rle;
                    $ble;
                    $sql_qw="SELECT total_leaves FROM FACULTY WHERE faculty_id=$id";
                    $result6=pg_query($dbconn,$sql_qw);
                    if($result6)
                    {
                        $tle=pg_fetch_row($result6,NULL,PGSQL_ASSOC);
                        $tle= $tle['total_leaves'];
                    }
                    $sql_qw1="SELECT regular_leaves FROM FACULTY WHERE faculty_id=$id";
                    $result7=pg_query($dbconn,$sql_qw1);
                    if($result7)
                    {
                        $rle=pg_fetch_row($result7,NULL,PGSQL_ASSOC);
                        $rle = $rle['regular_leaves'];
                    }
                    $sql_qw2="SELECT borrowed_leaves FROM FACULTY WHERE faculty_id=$id";
                    $result8=pg_query($dbconn,$sql_qw2);
                    if($result8)
                    {
                        $ble=pg_fetch_row($result8,NULL,PGSQL_ASSOC);
                        $ble = $ble['borrowed_leaves'];
                    }
                    $dwe=2*$tle-$rle-$ble-$dur;
                    if($dwe>=0)
    				{
                        $sql_query2 = "SELECT path_to_be_followed FROM leave_path WHERE for_whom='FACULTY'";
        				$result2 = pg_query($dbconn,$sql_query2);
        				if($result2)
        				{
        					$old_path = pg_fetch_array($result2,NULL,PGSQL_ASSOC);
        					// echo "old_path: ".$old_path;
        					$items = explode("+",$old_path["path_to_be_followed"],2);
        					$next_hop = $items[0];
        					// echo $items[0];
        					$remaining_path=$items[1];
        					$q = "SELECT leave_id FROM leave_table WHERE status = -2";
                            $temp_res = pg_query($dbconn,$q);
                            $temp_res = pg_fetch_array($temp_res,NULL,PGSQL_ASSOC);
                            $curr_id = $temp_res['leave_id'];
                            $curr_id = $curr_id+1;
                            $q = "UPDATE leave_table SET leave_id = '$curr_id' WHERE status=-2";
                            pg_query($dbconn,$q);
                            $curr_id = $curr_id-1;
                            $sql_query3 = "INSERT INTO leave_table VALUES('$curr_id','$from','$dur','FACULTY','$id','$comm','$remaining_path','$next_hop',0)";
                            $result3 = pg_query($dbconn,$sql_query3);
                            
        					if($result3)
        					{
        						echo '<h2>LEAVE REQUEST GENERATED</h2>';
        					}
        					else
        					{
        						echo 'QUERY FAILED';
        					}
        				}
        				else
        				{
        					echo '<h2><br>Path Not Found</h2>';
        				}
                    }
                    else
                    {
                        echo 'LEAVE LIMIT EXCEEDED';
                    }
    			}
    		}
    	?>
    </div>
    <div id="redirected_leave">
        <h1>Resend Leave</h1>
        <form action="#" method="POST">
            <?php
                ini_set('display_errors',1);
                $u_id = $_SESSION['id'];
                $sql_query = "SELECT * FROM leave_table WHERE status =2 AND user_id = '$u_id'";
                $result = pg_query($dbconn,$sql_query);
                if($result && pg_num_rows($result)>0)
                {
                    while($row = pg_fetch_array($result,NULL,PGSQL_ASSOC))
                    {
                        echo "Leave id: ".$row["leave_id"];
                        echo "From Date: ".$row["from_date"];
                        echo "Duration: ".$row["leave_duration"];
                        echo "comments: ".$row["comments"];
                        echo '<input type="text" name="comments" value=""'. htmlspecialchars($row['user_id']) . '">';
                        if(isset($_POST["view_resend"]))
                        {
                            $from = $row['from_date'];
                            $dur = $row['leave_duration'];
                            $id = $row['user_id'];
                            $l_id = $row['leave_id'];
                            $sql_query2 = "SELECT path_to_be_followed FROM leave_path WHERE for_whom='FACULTY'";
                            $to_update_comment="";
                         //   echo "hello".$row['comments']."hi";
                            if($_POST['comments']!='')
                            {
                                $to_update_comment = $row['comments']."---New Request: ".$_POST['comments'];
                            }
                            else
                            {
                                $to_update_comment = $row['comments'];
                            }
                            $result2 = pg_query($dbconn,$sql_query2);
                            if($result2)
                            {
                                $old_path = pg_fetch_array($result2,NULL,PGSQL_ASSOC);
                                $items = explode("+",$old_path["path_to_be_followed"],2);
                                $next_hop = $items[0];
                                $remaining_path=$items[1];
                                $q = "SELECT leave_id FROM leave_table WHERE status = -2";
                                $temp_res = pg_query($dbconn,$q);
                                $temp_res = pg_fetch_array($temp_res,NULL,PGSQL_ASSOC);
                                $curr_id = $temp_res['leave_id'];
                                $query = "UPDATE leave_table SET status=0, next_hop='$next_hop',path_rem='$remaining_path', comments = '$to_update_comment' WHERE leave_id = '$l_id'";
                                $result = pg_query($dbconn,$query);
                                if($result)
                                {
                                    echo 'RESENT';
                                }
                                else
                                {
                                    echo 'THERE WAS AN ERROR IN RESENDING';
                                }
                            }
                        }
                    }
                   // echo '<input type="submit" name="view_resend" value="Resend Application" />';
                    echo '<br>';
                    echo '<input type="submit" name ="view_resend" class="btn btn-primary"value="Resend Application" />';
                    
                }
                else
                    echo 'No Redirected Leaves';
            ?>
        </form>
    </div>
    <br>
    <p>
        <a href="logout_pg.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
    <br>
    <h4><a href="./faculty.php">Refresh</a></h4>
</body>
</html>