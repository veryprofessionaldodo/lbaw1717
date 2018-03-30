/* Queries */

/* INDEX PAGE AND SEARCH BAR */

-- Search project by name 
SELECT "name", description FROM project
WHERE "name" LIKE '%$search%' OR description LIKE '%$search%'
ORDER BY name
LIMIT 10 OFFSET $n;

-- Search project by category 
SELECT project.name, project.description, category.name FROM project, category, project_categories
WHERE category.name LIKE '%$search%' AND category.id = project_categories.category_id 
AND project_categories.project_id = project.id
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

/* List all notification of logged in user */
SELECT * from notification WHERE user_id = $user_id;

/* List all projects of a specific user */
SELECT project.name, project.description FROM project_members, project, "user"
WHERE "user".username = $username AND "user".id = project_members.user_id 
AND project_members.project_id = project.id;

/* Show user information */
SELECT * FROM "user" WHERE username = $username;
/* ADICIONAR ESTATISTICA E AFINS */

/* PROJECT PAGE */

-- VISITOR PAGE 
-- show project info with all his team members and coordenators and categories - Visitor Page 
SELECT name, description FROM project WHERE id = $project_id;

SELECT "user".username, "user".image, project_members.isCoordinator
FROM project_members, "user"
WHERE project_members.project_id = $project_id AND project_members.user_id = "user".id;

SELECT category.name FROM category, project_categories
WHERE category.id = project_categories.category_id AND project_categories.project_id = $project_id;

-- MEMBERS PAGE AS WELL
-- PROJECT MEMBERS  (similar to above)
SELECT "user".username, "user".image, project_members.isCoordinator
FROM project_members, "user"
WHERE project_members.project_id = $project_id AND project_members.user_id = "user".id;

-- REQUESTS 
-- List all requests to participate in a specific project
SELECT "user".username FROM invite, "user" 
WHERE project_id = $project_id AND invite.user_who_invited_id IS NULL 
AND invite.user_invited_id = "user".id; 
	

-- TASK PAGE IN PROJECT
-- get state and task info 
SELECT task.name, task_state_record.state FROM task, task_state_record
WHERE task.project_id = $project_id AND task_state_record.task_id = task.id
AND task_state_record.state = 
(SELECT "state" FROM task_state_record WHERE task_id = task.id GROUP BY "state", date ORDER BY date DESC LIMIT 1)
GROUP BY task.name, task_state_record.state;
-- users assigned
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
GROUP BY sprint.id, sprint.name, sprint.deadline, sprint_state_record.state;
-- List all tasks of a sprint
SELECT task.name, task_state_record.state FROM task, task_state_record
WHERE task.sprint_id = $sprint_id AND task_state_record.task_id = task.id
AND task_state_record.state = (SELECT "state" FROM task_state_record WHERE task_id = task.id
	GROUP BY "state", date ORDER BY date DESC LIMIT 1)
GROUP BY task.name, task_state_record.state;
-- (SIMILAR TO ABOVE)
-- users assigned to task
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


/* FORUM PAGE */
-- List all threads of one specific project
SELECT * FROM thread 
WHERE project_id = $project_id
LIMIT 10 OFFSET $n;

-- List all comments of a specific thread
SELECT * FROM comment 
WHERE thread_id = $thread_id
LIMIT 10 OFFSET $n;


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
DELETE FROM comment
	WHERE comment_id = $comment_id;

