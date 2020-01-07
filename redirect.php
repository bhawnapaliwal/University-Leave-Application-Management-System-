<?php
	session_start();
	if($_SESSION['position']=='FACULTY')
		header("location:faculty.php");
	else if($_SESSION['position']=='HOD')
		header("location:hod.php");
	else if($_SESSION['position']=='ADMIN')
		header("location:admin.php");
	else if($_SESSION['position']=='DEAN_STUDENT_AFFAIRS' || $_SESSION['position']=='DEAN_FACULTY_AFFAIRS' || $_SESSION['position']=='DEAN_RESEARCH' || $_SESSION['position']=='DEAN_ACADEMIC_AFFAIRS'||$_SESSION['position']=='ASSOCIATE_DEAN STUDENT_AFFAIRS' || $_SESSION['position']=='ASSOCIATE_DEAN_FACULTY_AFFAIRS' || $_SESSION['position']=='ASSOCIATE_DEAN_RESEARCH' || $_SESSION['position']=='ASSOCIATE_DEAN_ACADEMIC_AFFAIRS')
		header("location:cross_cutting.php");
	else if($_SESSION['position']=='DIRECTOR')
		header("location:Director_file.php");
?>