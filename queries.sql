/* Queries */

/* Check if user exists and password is valid */ 
SELECT * FROM Users WHERE username = $username AND password = $password;

/* List user reports - Admin Page*/
SELECT * FROM Report WHERE reportType = 'UserReported';

/* List comment reports - Admin Page*/
SELECT * FROM Report WHERE reportType = 'CommentReported';

/* List all threads of one specific project */
SELECT * FROM Thread WHERE project_id = $project_id;

/* List all notification of logged in user */
SELECT * from Notification WHERE user_id = $user_id;

/* List all comments of a specific thread */
SELECT * FROM Comment WHERE thread_id = $thread_id; /* ? */

/* List all projects of a specific user */
SELECT Project.name, Project.description FROM Project_members 
	LEFT JOIN Project ON Project.id = Project_members.project_id 
	LEFT JOIN User ON User.id = Project_members.user_id; /* ? */

/* Show User information */
SELECT * FROM User WHERE username = $username;
/* ADICIONAR ESTATISTICA E AFINS */

/* show project info with all his team members and coordenators - Visitor Page */
SELECT Project.name, Project.description, User.username, User.image, Project_members.isCoordinator 
FROM Project_members 
	LEFT JOIN Project ON Project.id = Project_members.project_id 
	LEFT JOIN User ON User.id = Project_members.user_id; /* ? */
/* Get project categories */
SELECT Category.name FROM Category, Project_categories
WHERE Category.id = Project_categories.category_id AND Project_categories.project_id = $project_id;

/* List all requests to participate in a specific project */
SELECT Project.name, User.username FROM Invite WHERE user_invited_id = $user_id AND project_id = $project_id 
	LEFT JOIN User ON User.id = Invite.user_invited_id
	LEFT JOIN Project ON Project.id = Invite.project_id; /* ? */

/* List members of a specific project - Project Page members*/
SELECT User.username, User.image, Project_members.isCoordinator 
FROM Project_members 
	LEFT JOIN Project ON Project.id = Project_members.project_id 
	LEFT JOIN User ON User.id = Project_members.user_id;

/* List all tasks of a specific project - Project Tasks Page */
SELECT Task.name FROM Task
WHERE Task.project_id = $project_id;
/* state of task */ 
SELECT TaskStateRecord.state FROM TaskStateRecord
WHERE TaskStateRecord.state = "Completed"; 					/* ? */ 
/* users assigned*/
SELECT User.username, User.image FROM Task, User, TaskStateRecord
WHERE Task.id = TaskStateRecord.task_id AND TaskStateRecord.state = "Assigned" 
AND TaskStateRecord.user_completed_id = User.id;
/* comments of task */
SELECT Comment.content, Comment.date, User.username, User.image
FROM Comment, Task, User,
WHERE Comment.task_id = Task.id AND Comment.user_id = User.id;


/* List all sprints of project - Project Sprints Page */
/* List all tasks of a sprint - Project Sprints Page */
/* List all comments of a task - Project Sprints Page */
SELECT Sprint.id, Sprint.name, Sprint.deadline /* ? */
FROM Sprint 
WHERE Sprint.project_id = $project_id;
/* Get current state of sprint */ 
SELECT SprintStateRecord.state FROM SprintStateRecord
ORDER BY SprintStateRecord.date DESC 
LIMIT 1;				/* ? */ 
/* Get tasks names */
SELECT Task.name FROM Task
WHERE Task.sprint_id = $sprint_id;
/* state of task */ 
SELECT TaskStateRecord.state FROM TaskStateRecord
WHERE TaskStateRecord.state = "Completed"; 					/* ? */ 
/* users assigned*/
SELECT User.username, User.image FROM Task, User, TaskStateRecord
WHERE Task.id = TaskStateRecord.task_id AND TaskStateRecord.state = "Assigned" 
AND TaskStateRecord.user_completed_id = User.id;
/* comments of task */
SELECT Comment.content, Comment.date, User.username, User.image
FROM Comment, Task, User,
WHERE Comment.task_id = Task.id AND Comment.user_id = User.id;

/* Search project by name */
SELECT name, description FROM Project
WHERE name LIKE %$search% OR description LIKE %$search%
ORDER BY name;

/* Search project by category */
SELECT Project.name, Project.description FROM Project, Category, Project_categories
WHERE Category.name LIKE %$search% AND Category.id = Project_categories.category_id 
AND Project_categories.project_id = Project.id
ORDER BY Project.name;

/* Get information about one specific task */
SELECT name, description, effort
FROM Task
WHERE Task.id = $task_id;
/* users assigned */
SELECT User.image, User.name FROM Task, User, TaskStateRecord
WHERE Task.id = TaskStateRecord.task_id AND TaskStateRecord.state = "Assigned"
AND TaskStateRecord.user_id = User.user_id;




/* INSERTS*/

/* Create new user */ 
INSERT INTO User (name, username, email, image, password)
VALUES ($name, $username, $email, $image, $password);

/* Create new sprint */
INSERT INTO Sprint (name, deadline, effort, project_id, user_creator_id)
VALUES ($name, $deadline, $effort, $project_id, $user_creator_id);

/* Create new Task */
-- If task of project
INSERT INTO Task (name, description, effort, project_id)
VALUES ($name, $description, $effort, $project_id);

-- Else if task of sprint
INSERT INTO Task (name, description, effort, project_id, sprint_id)
VALUES ($name, $description, $effort, $project_id, $sprint_id);

/* Create new TaskStateRecord */ 
INSERT INTO TaskStateRecord (state, user_completed_id, task_id)
VALUES ($state, $user_completed_id, $task_id);

/* Create new SprintStateRecord */ 
INSERT INTO SprintStateRecord (state, user_completed_id, sprint_id)
VALUES ($state, $user_completed_id, $sprint_id);

/* Create new Thread */
INSERT INTO Thread (name, description, project_id, user_creator_id)
VALUES ($name, $description, $project_id, $user_creator_id);

/* Create new Project */ 
INSERT INTO Project (name, description, isPublic)
VALUES ($name, $description, $isPublic);

/* Create new Comment */ 
-- IF comment is a Task comment
INSERT INTO Comment (content, user_id, task_id)
VALUES ($content, $user_id, $task_id);

-- ELSE comment is a Thread comment
INSERT INTO Comment (content, user_id, thread_id)
VALUES ($content, $user_id, $thread_id);



/* UPDATES */

/* Update user info */
UPDATE User
SET name = $name, username = $username, email = $email, password = $password, image = $image
WHERE id = $user_id;

/* Update project info */ 
UPDATE Project
SET name = $name, description = $description, isPublic = $isPublic
WHERE id = $project_id;

/* Update task info */ 
UPDATE Task
SET name = $name, description = $description, effort = $effort
WHERE id = $task_id;

/* Update sprint info */ 
UPDATE Sprint
SET name = $name, deadline = $deadline, effort = $effort
WHERE id = $sprint_id;

/* Update Comment info */
UPDATE Comment
SET content = $content
WHERE id = $comment_id;

/* DELETES */

/* Remove user from project */ 
DELETE FROM Project_members
	WHERE user_id = (SELECT id FROM User WHERE username = $username);

/* Remove comment from thread or task */ 
DELETE FROM Comment
	WHERE comment_id = $comment_id;

