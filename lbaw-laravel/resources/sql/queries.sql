/* Queries */

/* INDEX PAGE AND SEARCH BAR */

-- Search project by name 
SELECT "name", description FROM project
WHERE "name" LIKE '%$search%' OR description LIKE '%$search%'
AND isPublic = TRUE
ORDER BY name
LIMIT 10 OFFSET $n;

-- Search project by category 
SELECT project.name, project.description, category.name FROM project, category, project_categories
WHERE category.name LIKE '%$search%' AND category.id = project_categories.category_id 
AND project_categories.project_id = project.id AND isPublic = TRUE
ORDER BY project.name
LIMIT 10 OFFSET $n;


/* AUTHENTICATION */

/* Check if user exists and password is valid */ 
SELECT * FROM "user" WHERE username = $username AND password = $password;


/* ADMIN PAGE */

/* List user reports - Admin Page*/
SELECT * FROM report WHERE type = 'userReported';

/* List comment reports - Admin Page*/
SELECT * FROM report WHERE type = 'commentReported';


/* PROFILE PAGE */

-- List all notification of logged in user 
SELECT * from notification WHERE user_id = $user_id;

-- List all projects of a specific user, with their info, nº of members and nº of sprints and role
SELECT project.name, project.description, project_members.isCoordinator, num.num_members, sprints.sprints_num
FROM "user", project_members, project
	INNER JOIN 
	(SELECT project_id, COUNT(project_id) AS num_members
	FROM project_members GROUP BY project_members.project_id) num 
	ON project.id = num.project_id
	INNER JOIN
	(SELECT project_id, COUNT(*) AS sprints_num FROM sprint
	GROUP BY project_id) sprints 
	ON project.id = sprints.project_id
WHERE "user".username = $username AND project_members.user_id = "user".id 
AND project_members.project_id = project.id AND num.project_id = project.id
LIMIT 5 OFFSET $n;

-- Visitor version 
SELECT project.name, project.description, project_members.isCoordinator, num.num_members
FROM "user", project_members, project
	INNER JOIN 
	(SELECT project_id, COUNT(project_id) AS num_members
	FROM project_members GROUP BY project_members.project_id) num 
	ON project.id = num.project_id
WHERE "user".username = $username AND project_members.user_id = "user".id 
AND project_members.project_id = project.id AND num.project_id = project.id
LIMIT 5 OFFSET $n;


-- Show user information 
SELECT * FROM "user" WHERE username = $username;

-- User statistics

-- this contains some problems (f.e: if a task has been completed 
-- and then that decision has been revoked, this isn't counted here)

-- tasks completed this week
SELECT COUNT(*) FROM task_state_record 
WHERE user_completed_id = $user_id AND state = 'Completed'
AND (SELECT extract('week' FROM task_state_record.date)) = (select extract('week' from current_date)
);
-- tasks completed this month
SELECT COUNT(*) FROM task_state_record 
WHERE user_completed_id = $user_id AND state = 'Completed'
AND (SELECT extract('month' FROM task_state_record.date)) = (select extract('month' from current_date)
);
-- sprints contributed to, ever
SELECT COUNT(task.sprint_id) FROM task_state_record, task
WHERE task_state_record.user_completed_id = 4 
AND task_state_record.state != 'Created'
AND task_state_record.task_id = task.id;

-- Search user projects
-- by ROLE
SELECT project.name, project.description, project_members.isCoordinator, num.num_members, sprints.sprints_num
FROM "user", project_members, project
	INNER JOIN 
	(SELECT project_id, COUNT(project_id) AS num_members
	FROM project_members GROUP BY project_members.project_id) num 
	ON project.id = num.project_id
	INNER JOIN
	(SELECT project_id, COUNT(*) AS sprints_num FROM sprint
	GROUP BY project_id) sprints 
	ON project.id = sprints.project_id
WHERE "user".id = $user_id AND project_members.user_id = "user".id 
AND project_members.project_id = project.id AND num.project_id = project.id
AND project_members.isCoordinator = $isCoordinator
LIMIT 5 OFFSET $n;

-- by project name or description
SELECT project.name, project.description, project_members.isCoordinator, num.num_members, sprints.sprints_num
FROM "user", project_members, project
	INNER JOIN 
	(SELECT project_id, COUNT(project_id) AS num_members
	FROM project_members GROUP BY project_members.project_id) num 
	ON project.id = num.project_id
	INNER JOIN
	(SELECT project_id, COUNT(*) AS sprints_num FROM sprint
	GROUP BY project_id) sprints 
	ON project.id = sprints.project_id
WHERE "user".id = $user_id AND project_members.user_id = "user".id 
AND project_members.project_id = project.id AND num.project_id = project.id
AND (project.name LIKE '%$search%' OR project.description LIKE '%$search%')
LIMIT 5 OFFSET $n;

-- by category
SELECT project.name, project.description, project_members.isCoordinator, num.num_members, sprints.sprints_num
FROM "user", project_members, project_categories, category, project
	INNER JOIN 
	(SELECT project_id, COUNT(project_id) AS num_members
	FROM project_members GROUP BY project_members.project_id) num 
	ON project.id = num.project_id
	INNER JOIN
	(SELECT project_id, COUNT(*) AS sprints_num FROM sprint
	GROUP BY project_id) sprints 
	ON project.id = sprints.project_id
WHERE "user".id = $user_id AND project_members.user_id = "user".id 
AND project_members.project_id = project.id AND num.project_id = project.id
AND category.name LIKE '%$search%' AND category.id = project_categories.category_id
AND project_categories.project_id = project.id
LIMIT 5 OFFSET $n;



/* PROJECT PAGE */

-- VISITOR PAGE 
-- show project info with all his team members and coordenators and categories - Visitor Page 
SELECT name, description FROM project WHERE id = $project_id;

SELECT "user".username, "user".image, project_members.isCoordinator
FROM project_members, "user"
WHERE project_members.project_id = $project_id AND project_members.user_id = "user".id
LIMIT 10 OFFSET $n;

SELECT category.name FROM category, project_categories
WHERE category.id = project_categories.category_id AND project_categories.project_id = $project_id;

-- PROJECT SETTINGS
-- Search (team member)
SELECT "user".username, "user".image, project_members.isCoordinator
FROM project_members, "user"
WHERE project_members.project_id = $project_id AND project_members.user_id = "user".id
AND "user".username LIKE '%$search%'
LIMIT 10 OFFSET $n;

-- Search users to invite (OPTIONAL)
SELECT "user".username 
FROM "user" 
WHERE "user".username LIKE '%$search%';

-- Requests 
-- List all requests to participate in a specific project
SELECT "user".username FROM invite, "user" 
WHERE project_id = $project_id AND invite.user_who_invited_id IS NULL 
AND invite.user_invited_id = "user".id
LIMIT 10 OFFSET $n;

-- Members
-- MEMBERS PAGE AS WELL
-- PROJECT MEMBERS  (similar to above)
SELECT "user".username, "user".image, project_members.isCoordinator
FROM project_members, "user"
WHERE project_members.project_id = $project_id AND project_members.user_id = "user".id
LIMIT 10 OFFSET $n;

	
-- TASK PAGE IN PROJECT
-- get state and task info 
SELECT task.name, task_state_record.state FROM task, task_state_record
WHERE task.project_id = $project_id AND task_state_record.task_id = task.id
AND task_state_record.state = 
(SELECT "state" FROM task_state_record WHERE task_id = task.id GROUP BY "state", date ORDER BY date DESC LIMIT 1)
GROUP BY task.name, task_state_record.state;
-- users assigned - NEEDS TO BE IMPROVED
SELECT "user".username, "user".image FROM task, "user", task_state_record
WHERE task.id = $task_id AND task.id = task_state_record.task_id AND task_state_record.state = 'Assigned' 
AND task_state_record.user_completed_id = "user".id;
-- comments of task
SELECT "comment".content, "comment".date, "user".username, "user".image
FROM "comment", task, "user"
WHERE task.id = $task_id AND "comment".task_id = task.id AND "comment".user_id = "user".id;

-- SPRINT PAGE
-- List all sprints of project and their current state
-- List all comments of a task - project sprints Page 
SELECT sprint.id, sprint.name, sprint.deadline, sprint_state_record.state FROM sprint, sprint_state_record
WHERE sprint.project_id = $project_id AND sprint_state_record.sprint_id = sprint.id
AND sprint_state_record.state = 
(SELECT "state" FROM sprint_state_record WHERE sprint_id = sprint.id 
	GROUP BY "state", date ORDER BY date DESC LIMIT 1)
GROUP BY sprint.id, sprint.name, sprint.deadline, sprint_state_record.state
ORDER BY deadline ASC;
-- List all tasks of a sprint
SELECT task.name, task_state_record.state FROM task, task_state_record
WHERE task.sprint_id = $sprint_id AND task_state_record.task_id = task.id
AND task_state_record.state = (SELECT "state" FROM task_state_record WHERE task_id = task.id
	GROUP BY "state", date ORDER BY date DESC LIMIT 1)
GROUP BY task.name, task_state_record.state;
-- (SIMILAR TO ABOVE)
-- users assigned to task - NEEDS TO BE IMPROVED
SELECT "user".username, "user".image FROM task, "user", task_state_record
WHERE task.id = $task_id AND task.id = task_state_record.task_id AND task_state_record.state = 'Assigned' 
AND task_state_record.user_completed_id = "user".id;
/* comments of task */
SELECT "comment".content, "comment".date, "user".username, "user".image
FROM "comment", task, "user"
WHERE task.id = $task_id AND "comment".task_id = task.id AND "comment".user_id = "user".id;

-- TASK PAGE INFO
-- Get information about one specific task
SELECT name, description, effort
FROM task
WHERE task.id = $task_id;
-- users assigned to the task (similar to above)
SELECT "user".username, "user".image FROM task, "user", task_state_record
WHERE task.id = $task_id AND task.id = task_state_record.task_id AND task_state_record.state = 'Assigned' 
AND task_state_record.user_completed_id = "user".id;

/* 
SELECT "user".id, "user".username, "user".image 
FROM "user", task_state_record, task
JOIN
	(SELECT user_completed_id AS user_id, task_id, date FROM task_state_record WHERE 
	task_state_record.state = 'Unnassigned' ) unnassigned 
	ON unnassigned.task_id = task.id
WHERE task.id = 9 AND task.id = task_state_record.task_id AND task_state_record.state = 'Assigned' 
AND task_state_record.user_completed_id = "user".id
AND unnassigned.user_id = "user".id AND unnassigned.date < task_state_record.date;
*/

/*
SELECT "user".id, "user".username, "user".image
FROM task, task_state_record, "user"
INNER JOIN 
	(SELECT user_completed_id AS user_id, state 
	 FROM task_state_record
	WHERE (state = 'Assigned' OR state = 'Unnassigned')
	GROUP BY user_completed_id, state, date
	ORDER BY date DESC) states ON states.user_id = "user".id
WHERE task.id = 10 AND task_state_record.task_id = task.id 
AND "user".id = task_state_record.user_completed_id AND task_state_record.state = 'Assigned';
*/

/*
DROP VIEW IF EXISTS users_assigned;
CREATE VIEW users_assigned AS
	SELECT user_completed_id AS user_id, task_id, state, task_state_record.date 
	FROM task_state_record
	LEFT JOIN
		(SELECT user_completed_id AS user_id, task_state_record.date 
		FROM task_state_record 
		WHERE state = 'Assigned'
		GROUP BY user_id, date) assigned 
		ON (assigned.user_id = user_id AND assigned.date > task_state_record.date)
	WHERE (state = 'Assigned' OR state = 'Unnassigned')
	GROUP BY user_completed_id, state, task_state_record.date, task_id
	ORDER BY date DESC;
	
 SELECT * FROM users_assigned;
 */


/* FORUM PAGE */

-- List all threads of one specific project
SELECT thread.name, "user".username, thread.date FROM thread, "user"
WHERE thread.project_id = $project_id AND "user".id = thread.user_creator_id
LIMIT 20 OFFSET $n;

-- FORUM THREAD
-- Thread info and description
SELECT thread.name, thread.description, "user".username, "user".image, thread.date FROM thread, "user"
WHERE thread.id = $thread_id AND "user".id = thread.user_creator_id;
-- List all comments of a specific thread
SELECT comment.content, comment.date, "user".username, "user".image
FROM comment, "user"
WHERE comment.thread_id = $thread_id AND comment.user_id = "user".id
LIMIT 10 OFFSET $n;

/* STATISTICS OF PROJECT */

-- Number of tasks completed
	-- problem if a task is marked completed and then not
SELECT COUNT(*)
FROM task, task_state_record
WHERE task.project_id = $project_id
AND task_state_record.task_id = task.id 
AND task_state_record.state = 'Completed';

-- Number of sprints completed
	-- problem if a sprint is marked completed and then not
SELECT COUNT(*)
FROM sprint, sprint_state_record
WHERE sprint.project_id = 3
AND sprint_state_record.sprint_id = sprint.id 
AND sprint_state_record.state = 'Completed';

-- Top 3 contributors
SELECT "user".username, "user".image, COUNT(*) AS num
FROM "user", task_state_record, task
WHERE task.project_id = $project_id
AND task_state_record.task_id = task.id
AND "user".id = task_state_record.user_completed_id
AND task_state_record.state = 'Completed'
GROUP BY "user".username, "user".image
ORDER BY num DESC LIMIT 3;

-- Num of tasks completed this month, by each day
SELECT COUNT(*), date_part('day',date) AS day
FROM task_state_record, task
WHERE task.project_id = $project_id AND task_state_record.task_id = task.id
AND task_state_record.state = 'Completed' AND date_part('month',date) = date_part('month',now())
GROUP BY day;

-- Num of sprints completed this year, by each month
SELECT COUNT(*), date_part('month',date) AS month
FROM sprint_state_record, sprint
WHERE sprint.project_id = $project_id AND sprint_state_record.sprint_id = sprint.id
AND sprint_state_record.state = 'Completed' AND date_part('year',date) = date_part('year',now())
GROUP BY month;



/* INSERTS*/
/* Create new user */ 
INSERT INTO user (name, username, email, image, password)
VALUES ($name, $username, $email, $image, $password);

/* Create new sprint */
INSERT INTO sprint (name, deadline, effort, project_id, user_creator_id)
VALUES ($name, $deadline, $effort, $project_id, $user_creator_id);

/* Create new task */
-- If task of project
INSERT INTO task (name, description, effort, project_id)
VALUES ($name, $description, $effort, $project_id);

-- Else if task of sprint
INSERT INTO task (name, description, effort, project_id, sprint_id)
VALUES ($name, $description, $effort, $project_id, $sprint_id);

/* Create new task_state_record */ 
INSERT INTO task_state_record (state, user_completed_id, task_id)
VALUES ($state, $user_completed_id, $task_id);

/* Create new sprint_state_record */ 
INSERT INTO sprint_state_record (state, user_completed_id, sprint_id)
VALUES ($state, $user_completed_id, $sprint_id);

/* Create new thread */
INSERT INTO thread (name, description, project_id, user_creator_id)
VALUES ($name, $description, $project_id, $user_creator_id);

/* Create new project */ 
INSERT INTO project (name, description, isPublic)
VALUES ($name, $description, $isPublic);

/* Create new comment */ 
-- IF comment is a task comment
INSERT INTO comment (content, user_id, task_id)
VALUES ($content, $user_id, $task_id);

-- ELSE comment is a thread comment
INSERT INTO comment (content, user_id, thread_id)
VALUES ($content, $user_id, $thread_id);

/* Create new report */
-- IF report is to a comment
INSERT INTO report (date,type,summary,user_id,comment_reported_id,user_reported_id)
VALUES (now(),'commentReported',$summary,$user_id,$comment_reported_id,NULL);
-- ELSE report is to a user
INSERT INTO report (date,type,summary,user_id,comment_reported_id,user_reported_id)
VALUES (now(),'userReported',$summary,$user_id,NULL,$user_reported_id);

/* Create new invite */
-- IF invite is indeed a invite
INSERT INTO invite (date,user_invited_id,project_id,user_who_invited_id)
VALUES (now(),$user_invited_id,$project_id,$user_who_invited_id);
-- ELSE invite is a request
INSERT INTO invite (date,user_invited_id,project_id,user_who_invited_id)
VALUES (now(),$user_invited_id,$project_id,NULL);

/* Insert new category in project */
INSERT INTO project_categories (project_id,user_id)
VALUES ($project_id,$user_id);

/* Create new category */
INSERT INTO category (name)
VALUES ($name);


/* UPDATES */

/* Update user info */
UPDATE user
SET name = $name, username = $username, email = $email, password = $password, image = $image
WHERE id = $user_id;

/* Update project info */ 
UPDATE project
SET name = $name, description = $description, isPublic = $isPublic
WHERE id = $project_id;

/* Update task info */ 
UPDATE task
SET name = $name, description = $description, effort = $effort
WHERE id = $task_id;

/* Update sprint info */ 
UPDATE sprint
SET name = $name, deadline = $deadline, effort = $effort
WHERE id = $sprint_id;

/* Update comment info */
UPDATE comment
SET content = $content
WHERE id = $comment_id;



/* DELETES */

/* Remove user from project */ 
DELETE FROM project_members
	WHERE user_id = (SELECT id FROM user WHERE username = $username);

/* Remove comment from thread or task */ 
DELETE FROM comment WHERE comment_id = $comment_id;

/* Remove project */
DELETE FROM project WHERE project = $project_id;

/* Remove task from project */
DELETE FROM task WHERE id = $task_id;

/* Remove sprint from project */ 
DELETE FROM sprint WHERE id = $sprint_id;

/* Remove report */ 
DELETE FROM report WHERE id = $report_id;

/* Remove invite */
DELETE FROM invite WHERE id = $invite_id;

/* Remove category from project */

DELETE 

