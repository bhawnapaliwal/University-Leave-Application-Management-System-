START TRANSACTION;

CREATE TABLE admin(
	username VARCHAR(50),
	password VARCHAR(50),
	id INT NOT NULL,
	role VARCHAR(50), 
	PRIMARY KEY(id)
);
INSERT INTO admin(username,password,id,role) 
VALUES ('a','IIT ROPAR',1,'FACULTY'),
 ('b','IIT ROPAR',2,'HOD'),
 ('c','IIT ROPAR',3,'DEAN FACULTY AFFAIRS'),
 ('d','IIT ROPAR',4,'DEAN ACADEMIC AFFAIRS'),
 ('e','IIT ROPAR',5,'DEAN RESEARCH'),
 ('f','IIT ROPAR',6,'DEAN STUDENT AFFAIRS');

CREATE TABLE department(
	name VARCHAR(50),
	PRIMARY KEY(name)
);

CREATE TABLE Faculty (
  faculty_id INT NOT NULL,
  faculty_name varchar(30) NOT NULL,
  faculty_dept varchar(20) NOT NULL,
  total_leaves INT,
  dept_name VARCHAR(30),
  PRIMARY KEY(faculty_id),
  FOREIGN KEY(dept_name) REFERENCES department(name)
) ;
INSERT INTO Faculty(faculty_id,faculty_name,faculty_dept,total_leaves)
VALUES(1,'a','CSE',19),
(7,'g','MECH',19);


CREATE TABLE leave_path(
for_whom varchar(50),
path_to_be_followed varchar(255) 
);
INSERT INTO leave_path(for_whom,path_to_be_followed) 
VALUES ('FACULTY','HOD+DEAN_FACULTY_AFFAIRS+'),('HOD','DIRECTOR+'),('DEAN_FACULTY_AFFAIRS','DIRECTOR+');

CREATE TABLE leave_table (
  leave_id INT ,
  from_date timestamp  DEFAULT CURRENT_TIMESTAMP,
  to_date timestamp,
  user_role varchar(50) ,
  user_id INT ,
  comments text,
  path_rem text ,
  next_hop varchar(50) ,
  status INT 
);
INSERT INTO leave_table (leave_id, from_date, to_date, user_role, user_id, comments, path_rem, next_hop, status) VALUES
(5, '2019-04-26 19:22:11', '2020-12-31 19:30:00', 'FACULTY', 1, '', 'SANCTIONED', '', 1),
(6, '2019-04-26 19:20:23', '2020-12-31 19:30:00', 'FACULTY', 4, '', 'SANCTIONED', '', 1),
(7, '2019-04-26 19:48:32', '2020-02-02 08:31:00', 'FACULTY', 6, '', 'SANCTIONED', '', 1),
(11, '2019-04-26 19:48:36', '2020-07-09 18:30:00', 'FACULTY', 4, '', 'SANCTIONED', '', 1),
(13, '2019-04-26 19:48:40', '2020-12-31 19:31:00', 'FACULTY', 7, '', 'SANCTIONED', '', 1),
(14, '2019-04-26 19:48:42', '2020-01-02 07:30:00', 'STAFF', 7, '', 'SANCTIONED', '', 1),
(15, '2019-04-26 21:51:15', '2020-01-02 00:00:00', 'FACULTY', 4, '::', '', 'REG', 1),
(16, '2019-04-26 20:06:44', '2020-01-02 00:00:00', 'FACULTY', 4, '::', 'SANCTIONED', 'SANCTIONED', 1),
(17, '2019-04-26 20:20:08', '2020-02-02 07:30:00', 'FACULTY', 4, '::', 'SANCTIONED', '', 1),
(18, '2019-04-26 20:25:27', '2020-01-02 00:00:00', 'STAFF', 7, '::', 'SANCTIONED', '', 1),
(20, '2019-04-26 21:51:38', '2020-01-02 00:00:00', 'FACULTY', 4, '::', 'DIRECTOR+REG+', 'REG', 1),
(21, '2019-04-26 21:52:13', '2020-01-02 00:00:00', 'FACULTY', 4, '::', 'DIRECTOR+REG+', 'REG', 1),
(22, '2019-04-26 21:56:12', '2020-12-31 19:30:00', 'FACULTY', 4, '::', 'DIRECTOR+REG+', 'REG', 1),
(23, '2019-04-26 22:02:46', '2020-03-03 19:30:00', 'FACULTY', 4, '::', 'DIRECTOR+REG+', 'REG', 1),
(24, '2019-04-26 22:04:49', '2020-01-02 00:00:00', 'FACULTY', 4, '::', 'DIRECTOR+REG+', 'REG', 1),
(25, '2019-04-26 22:05:16', '2020-01-02 00:00:00', 'FACULTY', 4, '::', 'DIRECTOR+REG+', 'REG', 1),
(26, '2020-01-02 19:30:00', '2022-01-01 07:30:00', 'FACULTY', 4, '::', 'DIRECTOR+REG+', 'REG', 0),
(27, '2020-11-10 00:00:00', '2020-01-02 00:00:00', 'FACULTY', 3, '::', 'DIRECTOR+REG+', 'REG', 0),
(29, '2019-04-26 22:14:47', '2020-01-02 00:00:00', 'HOD', 5, '::', 'SANCTIONED', '', 1);
CREATE TABLE hod_history(
	date_of_joining timestamp,
	date_of_leaving timestamp,
	id INT,
	dept_name VARCHAR(100),
	approved_leave_ids VARCHAR(100),
	PRIMARY KEY(id,dept_name),
	FOREIGN KEY(id) REFERENCES admin(id),
	FOREIGN KEY(dept_name) REFERENCES department(name)
);
CREATE TABLE history_dean_student_affairs(
	date_of_joining timestamp,
	date_of_leaving timestamp,
	id INT,
	approved_leave_ids VARCHAR(100),
	PRIMARY KEY(id),
	FOREIGN KEY(id) REFERENCES admin(id)
);
CREATE TABLE history_dean_faculty_affairs(
	date_of_joining timestamp,
	date_of_leaving timestamp,
	id INT,
	approved_leave_ids VARCHAR(100),
	PRIMARY KEY(id),
	FOREIGN KEY(id) REFERENCES admin(id)
);
CREATE TABLE history_dean_research(
	date_of_joining timestamp,
	date_of_leaving timestamp,
	id INT,
	approved_leave_ids VARCHAR(100),
	PRIMARY KEY(id),
	FOREIGN KEY(id) REFERENCES admin(id)
);
CREATE TABLE history_associate_dean_student_affairs(
	date_of_joining timestamp,
	date_of_leaving timestamp,
	id INT,
	approved_leave_ids VARCHAR(100),
	PRIMARY KEY(id),
	FOREIGN KEY(id) REFERENCES admin(id)
);
CREATE TABLE history_associate_dean_faculty_affairs(
	date_of_joining timestamp,
	date_of_leaving timestamp,
	id INT,
	PRIMARY KEY(id)
);
CREATE TABLE cross_cutting_faculty(
	id INT;
	user_role Varchar(50),
	name VARCHAR(50),
	total_leaves INT,
	PRIMARY KEY(id);
);
INSERT INTO cross_cutting_faculty(id,user_role) VALUES
(4,'DEAN ACADEMIC AFFAIRS'),(3,'DEAN FACULTY AFFAIRS'),(5,'DEAN RESEARCH'),(6,'DEAN STUDENT AFFAIRS');
