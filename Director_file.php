<?php
    include('session.php');
    if (!isset($_SESSION['position']) || ($_SESSION['position'] != 
    	'DIRECTOR')   )
    {
        echo "Your login does not entail DIRECTOR portal";
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Director Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <?php
			echo $login_session;
			$sql="SELECT * FROM admin WHERE id=".$_SESSION["id"];
			$result=pg_query($dbconn,$sql);
			$row=pg_fetch_array($result,NULL,PGSQL_ASSOC);
			?>
		</h1>
	</div>
    <div id="leave_approval">
        <h1>Approve Leaves</h1>
        <form action="#" method="POST">
            <?php
                ini_set('display_errors',1);
                $u_id = $_SESSION['id'];
                $t1 = $_SESSION["position"];
                $sql_query = "SELECT * FROM leave_table WHERE status = 0 AND next_hop='$t1'";
                $result = pg_query($dbconn,$sql_query);
                
               // echo 'hi12345';
                if($result && pg_num_rows($result)>0)
                {
                   // echo 'hiiii123';
                    echo '<select name="selectLeave" >';
                    while($row = pg_fetch_array($result,NULL,PGSQL_ASSOC))
                    {
                        $temp = "Leave id: ".$row["leave_id"]." User role: ".$row["user_role"]." User id: ".$row["user_id"]." From Date: ".$row["from_date"]." Duration: ".$row["leave_duration"]." Comments: ".$row["comments"]."";
                        $l_id = $row['leave_id'];
                        echo '<option value='.$l_id.'>'.$temp.'</option>';
                    }
                    echo '</select>';
                    echo '<input type="text" name="comments" value=""'. htmlspecialchars($row['user_id']) . '">';
                    echo '  ';
                    echo '<input type="submit" name ="view34" class="btn btn-primary"value="Approve/Forward">';
                    echo '  ';
                    echo '<input type="submit" name ="view35" class="btn btn-primary"value="Reject">';
                    echo '  ';
                    echo '<input type="submit" name ="view36" class="btn btn-primary"value="Redirect Back">';
                    echo '  ';
                    if(isset($_POST["view34"]))
                    {
                        $leave_id = $_POST["selectLeave"];
                      //  echo 'selected: '.$leave_id.'hii';
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
                        $details_query = "INSERT INTO signing_details VALUES ('$currentTime','$t1','$u_id','$leave_id',
                        '$to_update_comment','$act')";
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
                            $to_update_comment="";
                            // echo 'hellllllllloooooooooooooooooooooo';
                            // echo $_POST['comments'];
                            // echo 'hellllllllloooooooooooooooooooooo';
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
                          //  $to_update_comment = $row1['comments']." Comments By ".$t1.": ".$_POST['comments'];
                            // echo 'to_be_updated';
                            // echo $to_update_comment;

                            $state=-1;
                            $currentTime = strval(date( 'd-m-Y h:i:s A', time () ));
                            $act = -1;
                            $details_query = "INSERT INTO signing_details VALUES ('$currentTime','$t1','$u_id','$leave_id','$to_update_comment','$act')";
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
                            $details_query = "INSERT INTO signing_details VALUES ('$currentTime','$t1','$u_id','$leave_id','$to_update_comment','$act')";
                            $result5 = pg_query($dbconn,$details_query);
                            $sql = "UPDATE leave_table SET next_hop = '', path_rem='$new_path',status='$state', comments = '$to_update_comment' WHERE leave_id = '$leave_id'";
                            $result4 = pg_query($dbconn,$sql);
                        }    
                    }
                }
                else
                {
                    echo 'No Unapproved Leaves';
                }
            ?>
    </form>
    </div>
    <br>
    <p>
        <a href="logout_pg.php" class="btn btn-danger">Sign Out of Your Account</a>
         <br>
         <a href="Director_file.php" >Refresh</a>
    </p>
		</body>
</html>





