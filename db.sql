CREATE TABLE User (
	id SERIAL NOT NULL,
	name text NOT NULL,
	username text NOT NULL,
	email text NOT NULL,
	image text,
	password text NOT NULL,
	CONSTRAINT username CHECK ((username != 'admin'))
);

CREATE TABLE Project (
	id SERIAL NOT NULL,
	name text NOT NULL,
	description text NOT NULL,
	access text NOT NULL,
	CONSTRAINT access CHECK ((access = ANY(ARRAY['Private'::text, 'Public'::text])))
	/* Falta mais coisas*/
);

CREATE TABLE Sprint (
	id SERIAL NOT NULL,
	name text NOT NULL,
	deadline TIMESTAMP NOT NULL,
	effort text NOT NULL,
	project_id INTEGER NOT NULL,
	user_creator_id INTEGER NOT NULL,
	CONSTRAINT deadline CHECK (deadline > now()),
	CONSTRAINT effort CHECK ((effort = ANY(ARRAY['Low'::text, 'Medium'::text, 'High'::text])))
);

CREATE TABLE Task (
	id SERIAL NOT NULL,
	name text NOT NULL,
	description text,
	effort text NOT NULL, /* adicionar trigger para isto depois .... */
	CONSTRAINT effort CHECK ((effort = ANY(ARRAY['Low'::text, 'Medium'::text, 'High'::text])))
);

CREATE TABLE Thread (
	id SERIAL NOT NULL,
	name text NOT NULL,
	description text,
	"date" TIMESTAMP DEFAULT now() NOT NULL,
	project_id INTEGER NOT NULL,
	user_creator_id INTEGER NOT NULL 
);

CREATE TABLE Comment (
	id SERIAL NOT NULL,
	content text NOT NULL,
	"date" TIMESTAMP DEFAULT now() NOT NULL,
	user_id INTEGER NOT NULL
);

CREATE TABLE Category (
	id SERIAL NOT NULL,
	name text NOT NULL
);

CREATE TABLE Role (
	id SERIAL NOT NULL,
	name text NOT NULL,
	"date" TIMESTAMP DEFAULT now() NOT NULL,
	CONSTRAINT name CHECK ((name = ANY(ARRAY['Team Member'::text, 'Coordinator'::text])))
);

CREATE TABLE Administrator (
	id SERIAL NOT NULL, /* is it really needed? */
	username text NOT NULL,
	password text NOT NULL
);

CREATE TABLE Image(
	id SERIAL NOT NULL,
	url text NOT NULL
);

CREATE TABLE Report (
	id SERIAL NOT NULL,
	"date" TIMESTAMP DEFAULT now() NOT NULL,
	summary text NOT NULL,
	content text NOT NULL,
	user_id INTEGER
);

CREATE TABLE Notification ( /* ADD CONSTRAINTS TO DIFFERENT TYPES */
	id SERIAL NOT NULL,
	"date" TIMESTAMP DEFAULT now() NOT NULL,
	notificationType text NOT NULL,
	user_id INTEGER NOT NULL,
	project_id INTEGER,
	comment_id INTEGER,
	user_action_id INTEGER,
	CONSTRAINT notificationType CHECK ((notificationType = ANY(ARRAY['Comment'::text, 'CommentReported'::text, 'Promotion'::text, 'RemovedFromProject'::text, 'Invite'::text, 'Request'::text]))),
	CONSTRAINT notificationConstraint CHECK ((notificationType == 'Comment' AND comment_id NOT NULL) OR
											(notificationType == 'CommentReported' AND comment_id NOT NULL) OR
											(notificationType == 'Promotion' AND project_id NOT NULL AND user_action_id NOT NULL) OR
											(notificationType == 'RemovedFromProject' AND project_id NOT NULL) OR
											(notificationType == 'Invite' AND project_id NOT NULL AND user_action_id NOT NULL) OR
											(notificationType == 'Request' AND project_id NOT NULL))
);

CREATE TABLE Invite (
	id SERIAL NOT NULL,
	"date" TIMESTAMP DEFAULT now() NOT NULL,
	user_invited_id INTEGER NOT NULL,
	project_id INTEGER NOT NULL,
	user_who_invited_id INTEGER NOT NULL,
);

CREATE TABLE TaskStateRecord(
	id SERIAL NOT NULL,
	"date" TIMESTAMP DEFAULT now() NOT NULL,
	state text NOT NULL,
	user_completed_id INTEGER NOT NULL,
	task_id INTEGER NOT NULL,
	CONSTRAINT state CHECK ((state = ANY(ARRAY['Completed'::text, 'Assigned'::text, 'Created'::text])))
);

CREATE TABLE SprintStateRecord(
	id SERIAL NOT NULL,
	"date" TIMESTAMP DEFAULT now() NOT NULL,
	state text NOT NULL,
	sprint_id INTEGER NOT NULL,
	CONSTRAINT state CHECK ((state = ANY(ARRAY['Completed'::text, 'Outdated'::text, 'Created'::text])))
);

CREATE TABLE Project_categories (
	project_id INTEGER NOT NULL,
	category_id INTEGER NOT NULL
);

CREATE TABLE Project_sprints (
	project_id INTEGER NOT NULL,
	sprint_id INTEGER NOT NULL
);

CREATE TABLE Project_tasks (
	project_id INTEGER NOT NULL,
	task_id INTEGER NOT NULL
);

CREATE TABLE Project_members(
	project_id INTEGER NOT NULL,
	user_id INTEGER NOT NULL
);

CREATE TABLE Project_threads (
	project_id INTEGER NOT NULL,
	thread_id INTEGER NOT NULL
);

CREATE TABLE User_assigned_tasks (
	user_id INTEGER NOT NULL,
	task_id INTEGER NOT NULL
);

CREATE TABLE Sprint_tasks (
	sprint_id INTEGER NOT NULL,
	task_id INTEGER NOT NULL
);

CREATE TABLE Task_comments (
	task_id INTEGER NOT NULL,
	comment_id INTEGER NOT NULL
);

CREATE TABLE Thread_comments (
	thread_id INTEGER NOT NULL,
	comment_id INTEGER NOT NULL
);

CREATE TABLE Contains_image (
	task_id INTEGER NOT NULL,
	image_id INTEGER NOT NULL 
);



/* Primary Keys and Uniques*/

ALTER TABLE ONLY User
	ADD CONSTRAINT user_pkey PRIMARY KEY (id);

ALTER TABLE ONLY User
    ADD CONSTRAINT user_email_key UNIQUE (email);

ALTER TABLE ONLY User
    ADD CONSTRAINT user_username_key UNIQUE (username);

ALTER TABLE ONLY Project
	ADD CONSTRAINT project_pkey PRIMARY KEY (id);

ALTER TABLE ONLY Sprint
	ADD CONSTRAINT sprint_pkey PRIMARY KEY (id);

ALTER TABLE ONLY Task
	ADD CONSTRAINT task_pkey PRIMARY KEY (id);

ALTER TABLE ONLY Thread
	ADD CONSTRAINT thread_pkey PRIMARY KEY (id);

ALTER TABLE ONLY Comment
	ADD CONSTRAINT comment_pkey PRIMARY KEY (id);

ALTER TABLE ONLY Category
	ADD CONSTRAINT category_pkey PRIMARY KEY (id);

ALTER TABLE ONLY Role
	ADD CONSTRAINT role_pkey PRIMARY KEY (id);

ALTER TABLE ONLY Administrator
	ADD CONSTRAINT administrator_pkey PRIMARY KEY (id);

ALTER TABLE ONLY Image
	ADD CONSTRAINT image_pkey PRIMARY KEY (id);

ALTER TABLE ONLY Report
	ADD CONSTRAINT report_pkey PRIMARY KEY (id);

ALTER TABLE ONLY Notification
	ADD CONSTRAINT notification_pkey PRIMARY KEY (id);

ALTER TABLE ONLY Invite
	ADD CONSTRAINT invite_pkey PRIMARY KEY (id);

ALTER TABLE ONLY TaskStateRecord
	ADD CONSTRAINT taskstaterecord_pkey PRIMARY KEY (id);

ALTER TABLE ONLY SprintStateRecord
	ADD CONSTRAINT sprintstaterecord_pkey PRIMARY KEY (id);

ALTER TABLE ONLY Project_categories
	ADD CONSTRAINT project_categories_pkey PRIMARY KEY (project_id, category_id);

ALTER TABLE ONLY Project_sprints
	ADD CONSTRAINT project_sprints_pkey PRIMARY KEY (project_id);

ALTER TABLE ONLY Project_tasks
	ADD CONSTRAINT project_tasks_pkey PRIMARY KEY (project_id, task_id);

ALTER TABLE ONLY Project_members
	ADD CONSTRAINT project_members_pkey PRIMARY KEY (project_id, user_id);

ALTER TABLE ONLY Project_threads
	ADD CONSTRAINT project_threads_pkey PRIMARY KEY (project_id);

ALTER TABLE ONLY User_assigned_tasks
	ADD CONSTRAINT user_assigned_tasks_pkey PRIMARY KEY (user_id, task_id);

ALTER TABLE ONLY Sprint_tasks
	ADD CONSTRAINT sprint_tasks_pkey PRIMARY KEY (sprint_id, task_id);

ALTER TABLE ONLY Task_comments
	ADD CONSTRAINT task_comments_pkey PRIMARY KEY (task_id, comment_id);

ALTER TABLE ONLY Thread_comments
	ADD CONSTRAINT thread_comments_pkey PRIMARY KEY (thread_id, comment_id);

ALTER TABLE ONLY Contains_image
	ADD CONSTRAINT contains_image_pkey PRIMARY KEY (task_id, image_id);
/* ADD THE REST */



/* Foreign Keys */

ALTER TABLE ONLY Sprint
	ADD CONSTRAINT task_id_project_fkey FOREIGN KEY (project_id) REFERENCES Project(id) ON UPDATE CASCADE ON DELETE CASCADE; 

ALTER TABLE ONLY Sprint
	ADD CONSTRAINT sprint_id_user_creator_fkey FOREIGN KEY (user_creator_id) REFERENCES User(id) ON UPDATE CASCADE; 

ALTER TABLE ONLY Thread
	ADD CONSTRAINT thread_id_project_fkey FOREIGN KEY (project_id) REFERENCES Project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Thread
	ADD CONSTRAINT thread_id_user_creator_fkey FOREIGN KEY (user_creator_id) REFERENCES User(id) ON UPDATE CASCADE;

ALTER TABLE ONLY Comment
	ADD CONSTRAINT comment_id_user_fkey FOREIGN KEY (user_id) REFERENCES User(id) ON UPDATE CASCADE;

ALTER TABLE ONLY Report
	ADD CONSTRAINT report_id_user_fkey FOREIGN KEY (user_id) REFERENCES User(id) ON UPDATE CASCADE;

ALTER TABLE ONLY Notification
	ADD CONSTRAINT notification_id_user_fkey FOREIGN KEY (user_id) REFERENCES User(id) ON UPDATE CASCADE ON DELETE CASCADE;    

ALTER TABLE ONLY Notification
	ADD CONSTRAINT notification_id_project_fkey FOREIGN KEY (project_id) REFERENCES Project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Notification
	ADD CONSTRAINT notification_id_comment_fkey FOREIGN KEY (comment_id) REFERENCES Comment(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Notification
	ADD CONSTRAINT notification_id_user_action_fkey FOREIGN KEY (user_action_id) REFERENCES User(id) ON UPDATE CASCADE;  

ALTER TABLE ONLY Invite
	ADD CONSTRAINT invite_id_user_fkey FOREIGN KEY (user_invited_id) REFERENCES User(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Invite
	ADD CONSTRAINT invite_id_user_who_invited_fkey FOREIGN KEY (user_who_invited_id) REFERENCES User(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Invite
	ADD CONSTRAINT invite_id_project_fkey FOREIGN KEY (project_id) REFERENCES Project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY TaskStateRecord
	ADD CONSTRAINT taskStateRecord_id_user_fkey FOREIGN KEY (user_completed_id) REFERENCES User(id) ON UPDATE CASCADE;

ALTER TABLE ONLY TaskStateRecord
	ADD CONSTRAINT taskStateRecord_id_task_fkey FOREIGN KEY (task_id) REFERENCES Task(id) ON UPDATE CASCADE;

ALTER TABLE ONLY SprintStateRecord
	ADD CONSTRAINT SprintStateRecord_id_sprint_fkey FOREIGN KEY (sprint_id) REFERENCES Sprint(id) ON UPDATE CASCADE;

ALTER TABLE ONLY Project_categories
	ADD CONSTRAINT project_categories_id_project_fkey FOREIGN KEY (project_id) REFERENCES Project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Project_categories
	ADD CONSTRAINT project_categories_id_category_fkey FOREIGN KEY (category_id) REFERENCES Category(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Project_sprints
	ADD CONSTRAINT project_sprints_id_project_fkey FOREIGN KEY (project_id) REFERENCES Project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Project_sprints
	ADD CONSTRAINT project_sprints_id_sprint_fkey FOREIGN KEY (sprint_id) REFERENCES Sprint(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Project_tasks
	ADD CONSTRAINT project_tasks_id_project_fkey FOREIGN KEY (project_id) REFERENCES Project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Project_tasks
	ADD CONSTRAINT project_tasks_id_task_fkey FOREIGN KEY (task_id) REFERENCES Task(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Project_members
	ADD CONSTRAINT project_members_id_project_fkey FOREIGN KEY (project_id) REFERENCES Project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Project_members
	ADD CONSTRAINT project_members_id_user_fkey FOREIGN KEY (user_id) REFERENCES User(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Project_threads
	ADD CONSTRAINT project_threads_id_project_fkey FOREIGN KEY (project_id) REFERENCES Project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Project_threads
	ADD CONSTRAINT project_threads_id_thread_fkey FOREIGN KEY (thread_id) REFERENCES Thread(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY User_assigned_tasks
	ADD CONSTRAINT user_tasks_id_user_fkey FOREIGN KEY (user_id) REFERENCES User(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY User_assigned_tasks
	ADD CONSTRAINT user_tasks_id_task_fkey FOREIGN KEY (task_id) REFERENCES Task(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Sprint_tasks
	ADD CONSTRAINT sprint_tasks_id_sprint_fkey FOREIGN KEY (sprint_id) REFERENCES Sprint(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Sprint_tasks
	ADD CONSTRAINT sprint_tasks_id_task_fkey FOREIGN KEY (task_id) REFERENCES Task(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Task_comments
	ADD CONSTRAINT task_comments_id_task_fkey FOREIGN KEY (task_id) REFERENCES Task(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Task_comments
	ADD CONSTRAINT task_comments_id_comment_fkey FOREIGN KEY (comment_id) REFERENCES Comment(id) ON UPDATE CASCADE ON DELETE CASCADE;	

ALTER TABLE ONLY Contains_image
	ADD CONSTRAINT contains_image_id_task_fkey FOREIGN KEY (task_id) REFERENCES Task(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Task_comments
	ADD CONSTRAINT task_comments_id_image_fkey FOREIGN KEY (image_id) REFERENCES Image(id) ON UPDATE CASCADE ON DELETE CASCADE;

/* ADD THE REST */





/*
CREATE TABLE PromotionNotification(
	id INTEGER NOT NULL, 
	coordinator_user_id INTEGER NOT NULL,
	user_id INTEGER NOT NULL, 
	project_id INTEGER NOT NULL 
);

CREATE TABLE RemovedNotification(
	id INTEGER NOT NULL, 
	user_id INTEGER NOT NULL, 
	project_id INTEGER NOT NULL 
);

CREATE TABLE InviteNotification(
	id INTEGER NOT NULL, 
	coordinator_user_id INTEGER NOT NULL, 
	user_id INTEGER NOT NULL, 
	project_id INTEGER NOT NULL 
);

CREATE TABLE CommentNotification(
	id INTEGER NOT NULL, 
	thread_id INTEGER NOT NULL, 
	creator_user_id INTEGER NOT NULL 
);

CREATE TABLE ReportCommentNotification(
	id INTEGER NOT NULL, 
	admin_id INTEGER NOT NULL, 
	comment_id INTEGER NOT NULL 
);*/