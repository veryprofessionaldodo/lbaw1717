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
	project_id INTEGER NOT NULL, /*!*/
	user_creator_id INTEGER NOT NULL, /*!*/
	CONSTRAINT deadline CHECK (deadline > now()),
	CONSTRAINT effort CHECK ((effort = ANY(ARRAY['Low'::text, 'Medium'::text, 'High'::text])))
	/* Add STATUS ?*/
);

CREATE TABLE Task (
	id SERIAL NOT NULL,
	name text NOT NULL,
	description text,
	effort text NOT NULL, /* adicionar trigger para isto depois .... */
	CONSTRAINT effort CHECK ((effort = ANY(ARRAY['Low'::text, 'Medium'::text, 'High'::text])))
	/*creator_id INTEGER NOT NULL  not sure */
);

CREATE TABLE Thread (
	id SERIAL NOT NULL,
	name text NOT NULL,
	description text,
	"date" TIMESTAMP DEFAULT now() NOT NULL,
	project_id INTEGER NOT NULL, /*!*/
	user_creator_id INTEGER NOT NULL /* ! */
);

CREATE TABLE Comment (
	id SERIAL NOT NULL,
	content text NOT NULL,
	"date" TIMESTAMP DEFAULT now() NOT NULL,
	user_id INTEGER NOT NULL, /* ! */
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
	imagePath text NOT NULL
);

CREATE TABLE Report (
	id SERIAL NOT NULL,
	"date" TIMESTAMP DEFAULT now() NOT NULL,
	summary text NOT NULL,
	content text NOT NULL,
	user_id INTEGER /* ! */
);

CREATE TABLE TaskStateRecord(
	id SERIAL NOT NULL,
	"date" TIMESTAMP DEFAULT now() NOT NULL,
	state text NOT NULL,
	user_completed_id INTEGER NOT NULL, /* ! */
	task_id INTEGER NOT NULL, /* ! */
	CONSTRAINT state CHECK ((state = ANY(ARRAY['Completed'::text, 'Assigned'::text, 'Created'::text])))
);

CREATE TABLE Notification (
	id SERIAL NOT NULL,
	"date" TIMESTAMP DEFAULT now() NOT NULL
);

CREATE TABLE PromotionNotification(
	id INTEGER NOT NULL, /* ! Notification */
	coordinator_user_id INTEGER NOT NULL, /* ! */
	user_id INTEGER NOT NULL, /* ! */
	project_id INTEGER NOT NULL /* ! */
);

CREATE TABLE RemovedNotification(
	id INTEGER NOT NULL, /* ! Notification */
	user_id INTEGER NOT NULL, /* ! */
	project_id INTEGER NOT NULL /* ! */
);

CREATE TABLE InviteNotification(
	id INTEGER NOT NULL, /* ! Notification */
	coordinator_user_id INTEGER NOT NULL, /* ! */
	user_id INTEGER NOT NULL, /* ! */
	project_id INTEGER NOT NULL /* ! */
);

CREATE TABLE CommentNotification(
	id INTEGER NOT NULL, /* ! Notification */
	thread_id INTEGER NOT NULL, /* ! */
	creator_user_id INTEGER NOT NULL /* ! */
);

CREATE TABLE ReportCommentNotification(
	id INTEGER NOT NULL, /* ! Notification */
	admin_id INTEGER NOT NULL, /* ! */
	comment_id INTEGER NOT NULL /* ! */
);

CREATE TABLE Users_in_project ( 
	user_id INTEGER NOT NULL, /* ! */
	project_id INTEGER NOT NULL, /* ! */
	role_id INTEGER NOT NULL /* ! */
);

CREATE TABLE Project_categories (
	project_id INTEGER NOT NULL, /* ! */
	category_id INTEGER NOT NULL
);

CREATE TABLE User_assigned_tasks (
	user_id INTEGER NOT NULL, /* ! */
	task_id INTEGER NOT NULL /* ! */
);

CREATE TABLE Sprint_tasks (
	sprint_id INTEGER NOT NULL, /* ! */
	task_id INTEGER NOT NULL /* ! */
);

CREATE TABLE Thread_comments (
	thread_id INTEGER NOT NULL, /* ! */
	comment_id INTEGER NOT NULL /* ! */
);

CREATE TABLE Contains_image (
	task_id INTEGER NOT NULL, /* ! */
	image_id INTEGER NOT NULL /* ! */
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

ALTER TABLE ONLY TaskStateRecord
	ADD CONSTRAINT taskstaterecord_pkey PRIMARY KEY (id);

/* ADD THE REST */



/* Foreign Keys */

ALTER TABLE ONLY Task
	ADD CONSTRAINT task_id_user_fkey FOREIGN KEY (creator_id) REFERENCES User(id) ON UPDATE CASCADE; /* On update? */ 

/* ADD THE REST */