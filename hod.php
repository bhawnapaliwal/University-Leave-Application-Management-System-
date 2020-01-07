<?php
    ini_set('display_errors', 1);
    include ('session.php');
    if(!isset($_SESSION['position'])||$_SESSION['position']!='HOD')
    {
        echo "Invalid login";
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HOD Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <?php
            echo $login_session;
            $list_of_facultys = "SELECT * FROM Faculty WHERE faculty_id = " . $_SESSION["id"];
                $result = pg_query($dbconn, $list_of_facultys);
                if($result && pg_num_rows($result)>0)
                {
                    $row = pg_fetch_array($result,NULL,PGSQL_ASSOC);
                    echo '<h2>Faculty Id: ' . $row['faculty_id'] . '</h2>';
                    echo '<h2>Department: ' . $row['faculty_dept'] . '</h2>';
                    echo '<h2>Total Leaves: '.$row['total_leaves'].'</h2>';
                    echo '<h2>Regular Leaves: '.$row['regular_leaves'].'</h2>';
                    echo '<h2>Borrowed Leaves: '.$row['borrowed_leaves'].'</h2>';
                }
                else
                    echo 'No Leaves Taken';
            ?>  
        </h1>
    </div>
    <div id ="view_leave_status">
        <h1>View Status of your Leave Applications</h1>
        <?php
            ini_set('display_errors', 1);
            $sql_query = "SELECT * FROM leave_table WHERE user_role = 'HOD' AND user_id = ".$_SESSION['id']."";
            $result = pg_query($dbconn,$sql_query);
            if($result && pg_num_rows($result))
            {
                while($row = pg_fetch_array($result,NULL,PGSQL_ASSOC))
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
                        echo "<h3>currently at: ".$row["next_hop"].'</h3>';
                        if($row["path_rem"]!='')
                            echo "<h3>Remaining Path: ".$row["path_rem"].'</h3>';
                    }
                    else if($row['status']==-1)
                    {
                        echo "<h3>status of the application  : REJECTED".'</h3>';
                        echo "<h3>Remaining Path: ".$row["path_rem"].'</h3>';
                    }
                    else if($row['status']==2)
                    {
                    	echo "<h3>status of the application  : REDIRECTED".'</h3>';
                    	if($row["path_rem"]!='')
                        	echo "<h3>Remaining Path: ".$row["path_rem"].'</h3>';
                    }
                    if($row['comments']!='')
                        echo "<h3>Comments: ".$row['comments']."</h3>";
                }
            }
            else
            {
                echo "No Leaves Requested";
            }

        ?>
    </div>
    <hr>
    <div id="new_leave">
        <h1>Request New Leave</h1>
        <form action="#"  method="post">
            FROM: <input type="datetime-local" name="FROM" values="FROM:"/>
            LEAVE DURATION: <input type="number" name="DUR" values="LEAVE DURATION:"/>
            COMMENTS: <input type="text" name="comm" values="COMMENTS:"/>
            <br>
            <br>
            <div class="form-group">
                <input type="submit" name ="add" class="btn btn-primary" value="Send Request">
            </div>
        </form>
        <?php
            // ini_set('display_errors', 1);
            if(isset($_POST["add"]))
            {
                $sql = "SELECT * FROM leave_table WHERE user_role='HOD' AND user_id=".$_SESSION["id"]." AND (status=0 or status=1)";
                $result = pg_query($dbconn,$sql);
                if($result && pg_num_rows($result)>0)
                {
                    echo 'Already have pending leaves';
                }
                else
                {
                    $from = $_POST["FROM"];
                    $dur = $_POST["DUR"];
                    $id = $_SESSION["id"];
                    $comm = $_POST['comm'];
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
                        $sql1 = "SELECT path_to_be_followed FROM leave_path WHERE for_whom = 'HOD'";
                        $result1 = pg_query($dbconn,$sql1);
                        if($result1)
                        {
                            $old_path=pg_fetch_array($result1,NULL,PGSQL_ASSOC);
                            $items = explode("+", $old_path["path_to_be_followed"],2);
                            $remaining_path = $items[1];
                            $next_hop = $items[0];
                            $q = "SELECT leave_id FROM leave_table WHERE status = -2";
                            $temp_res = pg_query($dbconn,$q);
                            $temp_res = pg_fetch_array($temp_res,NULL,PGSQL_ASSOC);
                            $curr_id = $temp_res['leave_id'];
                            $curr_id = $curr_id+1;
                            $q = "UPDATE leave_table SET leave_id = '$curr_id' WHERE status=-2";
                            pg_query($dbconn,$q);
                            $curr_id = $curr_id-1;
                            $query = "INSERT INTO leave_table VALUES('$curr_id','$from','$dur','HOD','$id','$comm','$remaining_path','$next_hop',0)";
                            $result2 = pg_query($dbconn,$query);
                            
                            if($result2)
                            {
                                echo 'LEAVE REQUEST GENERATED';
                            }
                            else
                            {
                                echo 'QUERY FAILED';
                            }
                        }
                        else
                        {
                            echo '<br>PATH NOT FOUND';
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
    <div id = "redirected_leave">
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
                        echo '<h3>';
                        echo "Leave id: ".$row["leave_id"]."  ";
                        echo "From Date: ".$row["from_date"]."  ";
                        echo "Duration: ".$row["leave_duration"]."  ";
                        echo '<input type="text" name="comments" value=""'. htmlspecialchars($row['user_id']) . '">';
                        if(isset($_POST["view_resend"]))
                        {
                            $from = $row['from_date'];
                            $dur = $row['leave_duration'];
                            $id = $row['user_id'];
                            $to_update_comment="";
                            if($_POST['comments']!='')
                            {
                                $to_update_comment = $row['comments']."---New Request: ".$_POST['comments'];
                            }
                            else
                            {
                            	$to_update_comment = $row['comments'];
                            }
                            $l_id = $row['leave_id'];
                            $sql_query2 = "SELECT path_to_be_followed FROM leave_path WHERE for_whom='HOD'";
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
                                $query = "UPDATE leave_table SET status=0, next_hop='$next_hop',path_rem='$remaining_path',comments = '$to_update_comment' WHERE leave_id = '$l_id'";
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
                    echo '<br>';
                    echo '<input type="submit" name ="view_resend" class="btn btn-primary"value="Resend Application"/>';
                }
                else
                    echo 'No Redirected Leaves';
            ?>
        </form>
    </div> 
    <hr>
    <div id="leave_approval">
        <h1>Approve Leaves</h1>
        <form  action ="#" method="POST">
            <?php
                ini_set('display_errors',1);
                $u_id = $_SESSION['id'];
                $t1 = $_SESSION["position"];
                $sql_query = "SELECT * FROM leave_table WHERE status = 0 AND next_hop='$t1'";
                $result = pg_query($dbconn,$sql_query);
                
               // echo 'hi12345';
                if($result && pg_num_rows($result)>0)
                {
                    //echo 'hiiii123';
                    echo '<select name="selectLeave" >';
                    $flag=0;
                    while($row = pg_fetch_array($result,NULL,PGSQL_ASSOC))
                    {
                        $f_id = $row['user_id'];
                        $q = "SELECT faculty_dept FROM Faculty WHERE faculty_id = ".$f_id."";
                        $q = pg_query($dbconn,$q);
                        $q = pg_fetch_array($q,NULL,PGSQL_ASSOC);
                        $q1 = "SELECT faculty_dept FROM Faculty WHERE faculty_id = ".$_SESSION['id']."";
                        $q1 = pg_query($dbconn,$q1);
                        $q1 = pg_fetch_array($q1,NULL,PGSQL_ASSOC);
                        if($q1['faculty_dept']==$q['faculty_dept'])
                        {
                            $flag=1;
                            $temp = "Leave id: ".$row["leave_id"]." User role: ".$row["user_role"]." User id: ".$row["user_id"]." From Date: ".$row["from_date"]." Duration: ".$row["leave_duration"]." Comments: ".$row["comments"]."";
                            $l_id = $row['leave_id'];
                            echo '<option value='.$l_id.'>'.$temp.'</option>';
                        }
                    }
                    echo '</select>';
                    if($flag==1)
                    {
                        echo '<input type="text" name="comments" value=""'. htmlspecialchars($row['user_id']) . '">';
                        echo '  ';
                        echo '<input type="submit" name ="view34" class="btn btn-primary"value="Approve/Forward">';
                        echo '  ';
                        echo '<input type="submit" name ="view35" class="btn btn-primary"value="Reject">';
                        echo '  ';
                        echo '<input type="submit" name ="view36" class="btn btn-primary"value="Redirect Back">';
                        echo '  ';
                    }
                    if(isset($_POST["view34"]))
                    {
                        $leave_id = $_POST["selectLeave"];
                     //   echo 'selected: '.$leave_id.'hii';
                        $check_query = "SELECT * FROM leave_table WHERE leave_id = '$leave_id'";
                        $result = pg_query($dbconn,$check_query);
                        if(pg_num_rows($result)>0)
                        {
                            $sql_query1 = "SELECT * FROM leave_table WHERE leave_id = '$leave_id'";
                            $result1 = pg_query($dbconn,$sql_query1);
                            $row1 = pg_fetch_array($result,NULL,PGSQL_ASSOC);
                            $old_path = $row1["path_rem"];
                            $old_path = explode("+", $old_path,2);
                            $next_hop = $old_path[0];
                            $state = count($old_path);
                            $new_path = "SANCTIONED";
                         //  echo 'helllllooooooooooo';
                            $to_update_comment="";
                            if($_POST['comments']!='')
                            {
                                $to_update_comment = $row1['comments']."---Comments By ".$t1.": ".$_POST['comments'];
                            }
                            else
                            {
                            	$to_update_comment = $row1['comments'];
                            }
                            if($state!=1)
                            {
                                $new_path=$old_path[1];
                                $state = 0;
                            }
                            $currentTime = strval(date( 'd-m-Y h:i:s A', time () ));
                            $act = 1;
                            $details_query = "INSERT INTO signing_details VALUES ('$currentTime','$t1','$u_id','$leave_id','$to_update_comment','$act')";
                            $result5 = pg_query($dbconn,$details_query);
                            $sql = "UPDATE leave_table SET next_hop = '$next_hop', path_rem='$new_path',status='$state', comments = '$to_update_comment' WHERE leave_id = '$leave_id'";
                            $result4 = pg_query($dbconn,$sql);
                        }
                    }
                    if(isset($_POST["view35"]))
                    {
                        $leave_id = $_POST["selectLeave"];
                        $check_query = "SELECT * FROM leave_table WHERE leave_id = '$leave_id'";
                        $result = pg_query($dbconn,$check_query);
                        if(pg_num_rows($result)>0)
                        {
                            $sql_query1 = "SELECT * FROM leave_table WHERE leave_id = '$leave_id'";
                            $result1 = pg_query($dbconn,$sql_query1);
                            $row1 = pg_fetch_array($result,NULL,PGSQL_ASSOC);
                            $old_path = $row1["path_rem"];
                            $old_path = explode("+", $old_path,2);
                          //  echo 'helllllooooooooooo';
                            $to_update_comment="";
                            if($_POST['comments']!='')
                            {
                                $to_update_comment = $row1['comments']."---Comments By ".$t1.": ".$_POST['comments'];
                            }
                            else
                            {
                            	$to_update_comment = $row1['comments'];
                            }
                            $next_hop = $old_path[0];
                            $new_path = "REJECTED";
                            //$to_update_comment = $row1['comments']." Comments By ".$t1.": ".$_POST['comments'];
                            $state=-1;
                            $currentTime = strval(date( 'd-m-Y h:i:s A', time () ));
                            $act = -1;
                            $details_query = "INSERT INTO signing_details VALUES ('$currentTime','$t1','$u_id','$leave_id',
                            '$to_update_comment','$act')";
                            $result5 = pg_query($dbconn,$details_query);
                            $sql = "UPDATE leave_table SET next_hop = '$next_hop', path_rem='$new_path',status='$state', comments = '$to_update_comment' WHERE leave_id = '$leave_id'";
                            $result4 = pg_query($dbconn,$sql);
                        }
                    }
                    if(isset($_POST["view36"]))
                    {
                        $leave_id = $_POST["selectLeave"];
                        $check_query = "SELECT * FROM leave_table WHERE leave_id = '$leave_id'";
                        $result = pg_query($dbconn,$check_query);
                        if(pg_num_rows($result)>0)
                        {
                            $sql_query1 = "SELECT * FROM leave_table WHERE leave_id = '$leave_id'";
                            $result1 = pg_query($dbconn,$sql_query1);
                            $row1 = pg_fetch_array($result,NULL,PGSQL_ASSOC);
                            $old_path = $row1["path_rem"];
                            $old_path = explode("+", $old_path,2);
                            $next_hop = $old_path[0];
                            $new_path = "REDIRECTED";
                           // echo 'helllllooooooooooo';
                            $to_update_comment="";
                            if($_POST['comments']!='')
                            {
                                $to_update_comment = $row1['comments']."---Comments By ".$t1.": ".$_POST['comments'];
                            }
                            else
                            {
                            	$to_update_comment = $row1['comments'];
                            }
                            $state=2;
                            $currentTime = strval(date( 'd-m-Y h:i:s A', time () ));
                            $act = 2;
                            $details_query = "INSERT INTO signing_details VALUES ('$currentTime','$t1','$u_id','$leave_id',
                            '$to_update_comment','$act')";
                            $result5 = pg_query($dbconn,$details_query);
                            $sql = "UPDATE leave_table SET next_hop = '', path_rem='$new_path',status='$state', 
                            comments = '$to_update_comment' WHERE leave_id = '$leave_id'";
                            $result4 = pg_query($dbconn,$sql);
                        }    
                    }
                }
                else
                {
                    echo 'No Unapproved Leaves';
                }
            ?>
        
    </div>
    <p>
        <br>
        <a href="logout_pg.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
    <h4><a href="./hod.php">Refresh</a></h4>
</body>
</html>