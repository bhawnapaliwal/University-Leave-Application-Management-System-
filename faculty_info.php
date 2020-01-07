<?php
// Include config file
ini_set('display_errors', 1);

require '/home/bhawna/vendor/autoload.php';
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View All Personal Profiles</title>
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
	<div class="b">
        <form action="#" method="POST">
        <?php 
        echo '  ';
        // echo '<input type="submit" name ="view34" class="btn btn-primary"value="view_faculty_info">';
        // if(isset($_POST["view34"]))
        // {
           // echo 'helooooooooooooooooooooooo';
            //echo '<h1>All faculty info</h1>';

            $client1 = new MongoDB\Client();
            $collection1 = $client1->part1->signedup;
            $username = $password = $confirm_password = "";
            $username_err = $password_err = $confirm_password_err = "";
            $cnt =0;
            $result2 = $collection1->find();
            $list1 = array();
            $str1='';
            echo '<h1>View All Personal Profiles:</h1>';
            foreach($result2 as $entry)
            {
               // echo 'hrrrr';
                $a = $entry['username'];
                $doc1 = $client1->part1->$a->findOne();
                $p = implode(', ',(array)$doc1['Publication']);
                $e = implode(', ',(array)$doc1['Education']);
                $a = implode(', ',(array)$doc1['Award']);
                $g = implode(', ',(array)$doc1['Grants']);
                $t = implode(', ',(array)$doc1['Teaching']);
                $tempspace='    ';
                echo '<h2>';
                echo 'Name::'.$entry['username'].'<br>';
                if($p!='')
                echo 'Publications::'.$p.'<br>';
                else
                echo'Publications::No Publications mentioned yet'.'<br>';
                if($e!='')
                echo 'Education::'.$e.'<br>';
                else
                echo'Education::Not mentioned'.'<br>';
                if($a!='')
                echo 'Awards::'.$a.'<br>';
                else
                echo'Awards::No awards updated yet'.'<br>';
                if($t!='')
                echo 'Teaching::'.$t.'<br>';
                else
                echo'Teaching::No courses updated yet'.'<br>';
                if($g!='')
                echo 'Grants:: '.$g.'<br>';
                else
                echo'Grants::No grants mentioned yet'.'<br>';
                // echo 'Eductation::'
                // //echo $doc1;
                // echo $p;
                // echo $e;
                // echo $g;
                // echo $t;
                echo '</h2>';

                // $str1 = $entry['username'].':<br>'.$tempspace.'Publications: '.$p.'<br>'.'Education: '.$e.'<br>'.'Awards: '.$a.'<br>'.'Grants: '.$g.'<br>'.'Teaching: '.$t.'<br><br>';
                // echo $str1.'<br>';
                // array_push($list, $entry['username']=>)
                // $uc = $client->part1->$entry;
                // echo $uc->find();
            // }
        }
        
        ?>
        <p>DO YOU WANT TO SIGN UP? <a href="phpinfo.php">Sign Up here</a>.</p>
        <p>DO YOU WANT TO LOGIN?<a href="login.php">Login here</a>.</p>
    </form>
	</div>
</body>
</html>