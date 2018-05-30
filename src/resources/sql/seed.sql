DROP TABLE IF EXISTS "user" CASCADE;
DROP TABLE IF EXISTS project CASCADE;
DROP TABLE IF EXISTS sprint CASCADE;
DROP TABLE IF EXISTS task CASCADE;
DROP TABLE IF EXISTS thread CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS category CASCADE;
DROP TABLE IF EXISTS project_members CASCADE;
DROP TABLE IF EXISTS project_categories CASCADE;
DROP TABLE IF EXISTS administrator CASCADE;
DROP TABLE IF EXISTS report CASCADE;
DROP TABLE IF EXISTS invite CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS task_state_record CASCADE;
DROP TABLE IF EXISTS sprint_state_record CASCADE;

CREATE TABLE "user" (
	id SERIAL NOT NULL,
	name text NOT NULL,
	username text NOT NULL,
	email text NOT NULL,
	image text,
	disable boolean NOT NULL DEFAULT FALSE,
	password text NOT NULL,
	remember_token TEXT,
	isAdmin boolean NOT NULL DEFAULT FALSE
);

CREATE TABLE project (
	id SERIAL NOT NULL,
	name text NOT NULL,
	description text NOT NULL,
	isPublic boolean NOT NULL
);

CREATE TABLE sprint (
	id SERIAL NOT NULL,
	name text NOT NULL,
	deadline TIMESTAMP WITH TIME zone NOT NULL,
	effort INTEGER NOT NULL,
	project_id INTEGER NOT NULL,
	user_creator_id INTEGER NOT NULL,
	CONSTRAINT deadline CHECK (deadline > now())
);

CREATE TABLE task (
	id SERIAL NOT NULL,
	name text NOT NULL,
	description text,
	effort INTEGER NOT NULL,
	project_id INTEGER NOT NULL,
	sprint_id INTEGER
);

CREATE TABLE thread (
	id SERIAL NOT NULL,
	name text NOT NULL,
	description text,
	date TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
	project_id INTEGER NOT NULL,
	user_creator_id INTEGER NOT NULL 
);

CREATE TABLE comment (
	id SERIAL NOT NULL,
	content text NOT NULL,
	date TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
	user_id INTEGER NOT NULL,
	task_id INTEGER,
	thread_id INTEGER,
	CONSTRAINT belongs CHECK ((task_id != NULL AND thread_id = NULL) OR (task_id = NULL AND thread_id != NULL))
);

CREATE TABLE category (
	id SERIAL NOT NULL,
	name text NOT NULL
);

CREATE TABLE project_members (
	user_id INTEGER NOT NULL,
	project_id INTEGER NOT NULL,
	iscoordinator boolean NOT NULL,
	date TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL
);

CREATE TABLE administrator (
	id SERIAL NOT NULL,
	username text NOT NULL,
	password text NOT NULL
);

CREATE TABLE report (
	id SERIAL NOT NULL,
	date TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
	type text NOT NULL,
	summary text NOT NULL,
	user_id INTEGER NOT NULL,
	comment_reported_id INTEGER,
	user_reported_id INTEGER,
	CONSTRAINT reportType CHECK ((type = ANY(ARRAY['commentReported'::text, 'userReported'::text]))),
	CONSTRAINT typeConstraint CHECK ((type = 'commentReported' AND comment_reported_id != NULL AND user_reported_id = NULL) OR
											(type = 'userReported' AND user_reported_id != NULL AND comment_reported_id = NULL))
);

CREATE TABLE notification (
	id SERIAL NOT NULL,
	date TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
	notification_type text NOT NULL,
	user_id INTEGER NOT NULL,
	project_id INTEGER,
	comment_id INTEGER,
	user_action_id INTEGER,
	CONSTRAINT notificationType CHECK ((notification_type = ANY(ARRAY['comment'::text, 'commentreported'::text, 'promotion'::text, 'removedfromproject'::text, 'invite'::text, 'request'::text]))),
	CONSTRAINT notificationConstraint CHECK ((notification_type = 'comment' AND comment_id != NULL) OR
											(notification_type = 'commentreported' AND comment_id != NULL) OR
											(notification_type = 'promotion' AND project_id != NULL) OR
											(notification_type = 'removedfromproject' AND project_id != NULL) OR
											(notification_type = 'invite' AND project_id != NULL AND user_action_id != NULL) OR
											(notification_type = 'request' AND project_id != NULL))
);

CREATE TABLE invite (
	id SERIAL NOT NULL,
	date TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
	user_invited_id INTEGER NOT NULL,
	project_id INTEGER NOT NULL,
	user_who_invited_id INTEGER
);

CREATE TABLE task_state_record(
	id SERIAL NOT NULL,
	date TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
	state text NOT NULL,
	user_completed_id INTEGER,
	task_id INTEGER NOT NULL,
	CONSTRAINT state CHECK ((state = ANY(ARRAY['Completed'::text, 'Assigned'::text, 'Unassigned'::text, 'Created'::text])))
);

CREATE TABLE sprint_state_record(
	id SERIAL NOT NULL,
	date TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
	state text NOT NULL,
	sprint_id INTEGER NOT NULL,
	CONSTRAINT state CHECK ((state = ANY(ARRAY['Completed'::text, 'Created'::text])))
);

CREATE TABLE project_categories (
	project_id INTEGER NOT NULL,
	category_id INTEGER NOT NULL
);


/* Primary Keys and Uniques*/

ALTER TABLE ONLY "user"
	ADD CONSTRAINT user_pkey PRIMARY KEY (id);

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_email_key UNIQUE (email);

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_username_key UNIQUE (username);

ALTER TABLE ONLY project
	ADD CONSTRAINT project_pkey PRIMARY KEY (id);

ALTER TABLE ONLY sprint
	ADD CONSTRAINT sprint_pkey PRIMARY KEY (id);

ALTER TABLE ONLY task
	ADD CONSTRAINT task_pkey PRIMARY KEY (id);

ALTER TABLE ONLY thread
	ADD CONSTRAINT thread_pkey PRIMARY KEY (id);

ALTER TABLE ONLY comment
	ADD CONSTRAINT comment_pkey PRIMARY KEY (id);

ALTER TABLE ONLY category
	ADD CONSTRAINT category_pkey PRIMARY KEY (id);

ALTER TABLE ONLY project_members
	ADD CONSTRAINT project_members_pkey PRIMARY KEY (user_id, project_id);

ALTER TABLE ONLY administrator
	ADD CONSTRAINT administrator_pkey PRIMARY KEY (id);

ALTER TABLE ONLY report
	ADD CONSTRAINT report_pkey PRIMARY KEY (id);

ALTER TABLE ONLY notification
	ADD CONSTRAINT notification_pkey PRIMARY KEY (id);

ALTER TABLE ONLY invite
	ADD CONSTRAINT invite_pkey PRIMARY KEY (id);

ALTER TABLE ONLY task_state_record
	ADD CONSTRAINT task_state_record_pkey PRIMARY KEY (id);

ALTER TABLE ONLY sprint_state_record
	ADD CONSTRAINT sprint_state_record_pkey PRIMARY KEY (id);

ALTER TABLE ONLY project_categories
	ADD CONSTRAINT project_categories_pkey PRIMARY KEY (project_id, category_id);
 	

/* Foreign Keys */

ALTER TABLE ONLY sprint
	ADD CONSTRAINT task_id_project_fkey FOREIGN KEY (project_id) REFERENCES project(id) ON UPDATE CASCADE ON DELETE CASCADE; 

ALTER TABLE ONLY sprint
	ADD CONSTRAINT sprint_id_user_creator_fkey FOREIGN KEY (user_creator_id) REFERENCES "user"(id) ON UPDATE CASCADE; 

ALTER TABLE ONLY task
	ADD CONSTRAINT task_id_user_project_fkey FOREIGN KEY (project_id) REFERENCES project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY task
	ADD CONSTRAINT task_id_user_sprint_fkey FOREIGN KEY (sprint_id) REFERENCES sprint(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY thread
	ADD CONSTRAINT thread_id_project_fkey FOREIGN KEY (project_id) REFERENCES project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY thread
	ADD CONSTRAINT thread_id_user_creator_fkey FOREIGN KEY (user_creator_id) REFERENCES "user"(id) ON UPDATE CASCADE;

ALTER TABLE ONLY comment
	ADD CONSTRAINT comment_id_user_fkey FOREIGN KEY (user_id) REFERENCES "user"(id) ON UPDATE CASCADE;

ALTER TABLE ONLY comment
	ADD CONSTRAINT comment_id_task_fkey FOREIGN KEY (task_id) REFERENCES task(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY comment
	ADD CONSTRAINT comment_id_thread_fkey FOREIGN KEY (thread_id) REFERENCES thread(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY project_members
	ADD CONSTRAINT members_id_user_fkey FOREIGN KEY (user_id) REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY project_members
	ADD CONSTRAINT members_id_project_fkey FOREIGN KEY (project_id) REFERENCES project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY report
	ADD CONSTRAINT report_id_user_fkey FOREIGN KEY (user_id) REFERENCES "user"(id) ON UPDATE CASCADE;

ALTER TABLE ONLY report
	ADD CONSTRAINT report_id_comment_reported_fkey FOREIGN KEY (comment_reported_id) REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY report
	ADD CONSTRAINT report_id_user_reported_fkey FOREIGN KEY (user_reported_id) REFERENCES "user"(id) ON UPDATE CASCADE;

ALTER TABLE ONLY notification
	ADD CONSTRAINT notification_id_user_fkey FOREIGN KEY (user_id) REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE;    

ALTER TABLE ONLY notification
	ADD CONSTRAINT notification_id_project_fkey FOREIGN KEY (project_id) REFERENCES project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY notification
	ADD CONSTRAINT notification_id_comment_fkey FOREIGN KEY (comment_id) REFERENCES comment(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY notification
	ADD CONSTRAINT notification_id_user_action_fkey FOREIGN KEY (user_action_id) REFERENCES "user"(id) ON UPDATE CASCADE;  

ALTER TABLE ONLY invite
	ADD CONSTRAINT invite_id_user_fkey FOREIGN KEY (user_invited_id) REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY invite
	ADD CONSTRAINT invite_id_user_who_invited_fkey FOREIGN KEY (user_who_invited_id) REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY invite
	ADD CONSTRAINT invite_id_project_fkey FOREIGN KEY (project_id) REFERENCES project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY task_state_record
	ADD CONSTRAINT task_state_record_id_user_fkey FOREIGN KEY (user_completed_id) REFERENCES "user"(id) ON UPDATE CASCADE;

ALTER TABLE ONLY task_state_record
	ADD CONSTRAINT task_state_record_id_task_fkey FOREIGN KEY (task_id) REFERENCES task(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY sprint_state_record
	ADD CONSTRAINT sprint_state_record_id_sprint_fkey FOREIGN KEY (sprint_id) REFERENCES sprint(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY project_categories
	ADD CONSTRAINT project_categories_id_project_fkey FOREIGN KEY (project_id) REFERENCES project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY project_categories
	ADD CONSTRAINT project_categories_id_category_fkey FOREIGN KEY (category_id) REFERENCES category(id) ON UPDATE CASCADE ON DELETE CASCADE;



/* TRIGGERS */
-- Checks if the effort of a sprint tasks exceeds the sprint effort
DROP FUNCTION IF EXISTS check_effort();
CREATE FUNCTION check_effort() RETURNS TRIGGER AS
$BODY$
BEGIN
	IF (NEW.sprint_id <> NULL) THEN
		
		IF ((SELECT SUM(effort) FROM task WHERE NEW.sprint_id = task.sprint_id) > 
			(SELECT effort FROM sprint WHERE id = NEW.sprint_id))
		THEN RAISE EXCEPTION 'This task exceeds the limit effort of the sprint.';
		END IF;

	END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_effort ON task;
CREATE TRIGGER check_effort
	BEFORE INSERT OR UPDATE ON task
	FOR EACH ROW
		EXECUTE PROCEDURE check_effort();

-- Create a notification when a report is created
DROP FUNCTION IF EXISTS add_notification_report();
CREATE FUNCTION add_notification_report() RETURNS TRIGGER AS
$BODY$
BEGIN
	IF(NEW.type = 'commentreported')
	THEN INSERT INTO "notification" (date,notification_type,user_id,comment_id) 
			VALUES (now(),'commentreported',NEW.user_id,NEW.comment_reported_id);
	END IF;
	RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS add_notification_report ON report;
CREATE TRIGGER add_notification_report
	AFTER INSERT ON report
	FOR EACH ROW
		EXECUTE PROCEDURE add_notification_report();

-- Create a notification when an invite is created (not a request)
DROP FUNCTION IF EXISTS add_notification_invite();
CREATE FUNCTION add_notification_invite() RETURNS TRIGGER AS
$BODY$
BEGIN
	IF(NEW.user_who_invited_id IS NULL) THEN RETURN NEW;
	ELSE 
		INSERT INTO notification (date,notification_type,user_id,project_id,comment_id,user_action_id) 
			VALUES (now(),'invite',NEW.user_invited_id,NEW.project_id,NULL,NEW.user_who_invited_id);
	END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS add_notification_invite ON invite;
CREATE TRIGGER add_notification_invite
	AFTER INSERT ON invite
	FOR EACH ROW
		EXECUTE PROCEDURE add_notification_invite();

-- Complete a sprint when its tasks are completed
DROP FUNCTION IF EXISTS check_completed_sprint();
CREATE FUNCTION check_completed_sprint() RETURNS TRIGGER AS
$BODY$
DECLARE 
	sprint_id "task".sprint_id%TYPE;
BEGIN
	SELECT task.sprint_id INTO sprint_id FROM task WHERE task.id = NEW.task_id;

	IF(sprint_id <> NULL) THEN
		IF (NEW.state = 'Completed') THEN 
			IF(
				(SELECT COUNT(*) FROM task WHERE task.sprint_id = 
					(SELECT task.sprint_id FROM task WHERE task.id = NEW.task_id)) -- tasks of respective sprint
				=
				(SELECT COUNT(*) FROM task_state_record t, task a 
					WHERE t.state = 'Completed' AND a.sprint_id = 
						(SELECT task.sprint_id FROM task WHERE task.id = NEW.task_id))
				)
			THEN 
				INSERT INTO sprint_state_record (id, date, state, sprint_id) VALUES (DEFAULT, now(), 'Completed', sprint_id);
			END IF;
		END IF;
	END IF;
	RETURN NULL;
END;
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_completed_sprint ON task_state_record;
CREATE TRIGGER check_completed_sprint
	AFTER INSERT ON task_state_record
	FOR EACH ROW
		EXECUTE PROCEDURE check_completed_sprint();

-- Check if a user invited isn't already on the project
DROP FUNCTION IF EXISTS check_user_member();
CREATE FUNCTION check_user_member() RETURNS TRIGGER AS
$BODY$
BEGIN
	IF (EXISTS(SELECT * FROM project_members WHERE project_id = 
			NEW.project_id AND user_id = NEW.user_id)) 
	THEN RAISE EXCEPTION 'This member is already in the project.';
	END IF;
	RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_user_member ON project_members;
CREATE TRIGGER check_user_member
	BEFORE INSERT ON project_members
	FOR EACH ROW
		EXECUTE PROCEDURE check_user_member();

-- Insert into Notification of the user who wrote the thread, when a comment is made on it
DROP FUNCTION IF EXISTS add_notification_comment();
CREATE FUNCTION add_notification_comment() RETURNS TRIGGER AS
$BODY$
DECLARE 
	user_thread_id "thread".user_creator_id%TYPE;
BEGIN
	IF(NEW.thread_id != NULL) THEN
		SELECT thread.user_creator_id INTO user_thread_id 
			FROM thread WHERE thread.id = NEW.thread_id;
		INSERT INTO notification (date,notification_type,user_id,project_id,comment_id,user_action_id)
			VALUES (NEW.date,'comment',user_thread_id,NULL,NEW.id,NULL);
	END IF;
	RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS add_notification_comment ON comment;
CREATE TRIGGER add_notification_comment
	AFTER INSERT ON comment
	FOR EACH ROW
		EXECUTE PROCEDURE add_notification_comment();

-- Insert into Notification if user as been updated to Coordenator
DROP FUNCTION IF EXISTS add_notification_promotion();
CREATE FUNCTION add_notification_promotion() RETURNS TRIGGER AS
$BODY$
BEGIN
	IF(OLD.iscoordinator != NEW.iscoordinator) THEN
		INSERT INTO Notification (date,notification_type,user_id,project_id,comment_id,user_action_id)
			VALUES (now(),'promotion',NEW.user_id,NEW.project_id,NULL,NULL);
	END IF;
	RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS add_notification_promotion ON project_members;
CREATE TRIGGER add_notification_promotion
	AFTER UPDATE ON project_members
	FOR EACH ROW
		EXECUTE PROCEDURE add_notification_promotion();

-- Insert into Notification if user as been expelled from a project
DROP FUNCTION IF EXISTS add_notification_remove();
CREATE FUNCTION add_notification_remove() RETURNS TRIGGER AS
$BODY$ 
BEGIN
	IF EXISTS(SELECT * FROM project WHERE id = OLD.project_id) THEN
		INSERT INTO Notification (date,notification_type,user_id,project_id,comment_id,user_action_id)
			VALUES (now(),'removedfromproject',OLD.user_id,OLD.project_id,NULL,NULL);
	END IF;
	RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS add_notification_remove ON project_members;
CREATE TRIGGER add_notification_remove
	AFTER DELETE ON project_members
	FOR EACH ROW
		EXECUTE PROCEDURE add_notification_remove();


-- Check if a sprint is completed and revoke that if a task is added to it
DROP FUNCTION IF EXISTS change_sprint_state();
CREATE FUNCTION change_sprint_state() RETURNS TRIGGER AS
$BODY$
DECLARE
	temprow RECORD;
BEGIN
	IF(NEW.sprint_id <> NULL) THEN
		FOR temprow IN 
			SELECT state FROM sprint_state_record WHERE NEW.sprint_id = sprint_state_record.sprint_id
			ORDER BY date
		LOOP 
			IF(temprow.state = 'Created' OR temprow.state = 'Outdated') THEN
				RETURN NULL;
			ELSE 
				IF(temprow.state = 'Completed') THEN
					INSERT INTO sprint_state_record (date,state,sprint_id) 
						VALUES (now(), 'Created', NEW.sprint_id);
				END IF;
			END IF;
		END LOOP;
	END IF;
	RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS change_sprint_state ON task;
CREATE TRIGGER change_sprint_state
	AFTER INSERT ON task
	FOR EACH ROW
		EXECUTE PROCEDURE change_sprint_state();

-- Change state of sprint if a sprint's deadline is updated
DROP FUNCTION IF EXISTS update_sprint_state_deadline();
CREATE FUNCTION update_sprint_state_deadline() RETURNS TRIGGER AS
$BODY$
BEGIN
	IF(NEW.deadline <> OLD.deadline) THEN
		IF((SELECT state FROM sprint_state_record WHERE NEW.id = sprint_state_record.sprint_id
			ORDER BY date LIMIT 1) = 'Outdated')
		THEN 
			INSERT INTO sprint_state_record (date,state,sprint_id) 
						VALUES (now(), 'Created', NEW.id);
		END IF;
	END IF;
	RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS update_sprint_state_deadline ON task;
CREATE TRIGGER update_sprint_state_deadline
	AFTER UPDATE ON sprint
	FOR EACH ROW
		EXECUTE PROCEDURE update_sprint_state_deadline();

-- When a task is created, add an entry to the task_state_record
DROP FUNCTION IF EXISTS task_created();
CREATE FUNCTION task_created() RETURNS TRIGGER AS
$BODY$
BEGIN
	INSERT INTO task_state_record (date,state,user_completed_id,task_id) 
		VALUES (now(), 'Created', NULL, NEW.id);
	RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS task_created ON task;
CREATE TRIGGER task_created
	AFTER INSERT ON task
	FOR EACH ROW
		EXECUTE PROCEDURE task_created();

-- When a sprint is created, add an entry to the sprint_state_record
DROP FUNCTION IF EXISTS sprint_created();
CREATE FUNCTION sprint_created() RETURNS TRIGGER AS
$BODY$
BEGIN
	INSERT INTO sprint_state_record (date,state,sprint_id) 
		VALUES (now(), 'Created', NEW.id);
	RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS sprint_created ON task;
CREATE TRIGGER sprint_created
	AFTER INSERT ON sprint
	FOR EACH ROW
		EXECUTE PROCEDURE sprint_created();

-- Check if a invite to a project was made by a coordinator of said project
DROP FUNCTION IF EXISTS check_invite_creator();
CREATE FUNCTION check_invite_creator() RETURNS TRIGGER AS
$BODY$
BEGIN
	IF(NEW.user_who_invited_id <> NULL) THEN
		IF((SELECT iscoordinator FROM project_members 
			WHERE user_id = NEW.user_who_invited_id AND project_id = NEW.project_id) = TRUE) THEN
			RETURN NEW;
		ELSE
			RAISE EXCEPTION 'The user who invited isn''t a coordinator of the project';
		END IF;
	END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_invite_creator ON invite;
CREATE TRIGGER check_invite_creator
	BEFORE INSERT ON invite
	FOR EACH ROW
		EXECUTE PROCEDURE check_invite_creator();

-- When a user is assigned, unassign the other user who is assigned
DROP FUNCTION IF EXISTS assign_user_task();
CREATE FUNCTION assign_user_task() RETURNS TRIGGER AS
$BODY$
DECLARE
	user_task_id task_state_record.user_completed_id%TYPE;
BEGIN

	SELECT a.user_completed_id INTO user_task_id FROM task_state_record a 
			WHERE NEW.task_id = a.task_id
			AND a.user_completed_id <> NEW.user_completed_id AND a.state = 'Assigned'
			AND NOT EXISTS (SELECT * FROM task_state_record b WHERE a.task_id = b.task_id
								AND a.user_completed_id = b.user_completed_id AND
								b.state = 'Unassigned' AND a.date < b.date);

	IF (user_task_id IS NOT NULL AND NEW.state <> 'Completed') THEN

		INSERT INTO task_state_record (date, state, user_completed_id, task_id) 
			VALUES (now(), 'Unassigned', user_task_id, NEW.task_id);
			
	END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS assign_user_task ON task_state_record;
CREATE TRIGGER assign_user_task
	BEFORE INSERT ON task_state_record
	FOR EACH ROW
		EXECUTE PROCEDURE assign_user_task();


/* INDEXES */

CREATE INDEX username_user ON "user" USING hash(username);

CREATE INDEX task_project ON task USING hash(project_id);
CREATE INDEX task_sprint ON task USING hash(sprint_id);

CREATE INDEX sprint_project ON sprint USING hash(project_id);

CREATE INDEX comment_creator ON comment USING hash(user_id);
CREATE INDEX comment_task ON comment USING hash(task_id);
CREATE INDEX comment_thread ON comment USING hash(thread_id);

CREATE INDEX thread_project ON thread USING hash(project_id);

CREATE INDEX notification_user ON notification USING hash(user_id);

CREATE INDEX task_state_task ON task_state_record USING hash(task_id);
CREATE INDEX task_state_user ON task_state_record USING hash(user_completed_id);
CREATE INDEX task_record_state ON task_state_record USING hash(state);

CREATE INDEX sprint_state_sprint ON sprint_state_record USING hash(sprint_id);
CREATE INDEX sprint_record_state ON sprint_state_record USING hash(state);

CREATE INDEX task_state_record_date ON task_state_record USING btree(date);
CREATE INDEX sprint_state_record_date ON sprint_state_record USING btree(date);
CREATE INDEX sprint_deadline ON sprint USING btree(deadline);

CREATE INDEX project_text_search ON project USING GIST(to_tsvector('english', name || ' ' || description));
CREATE INDEX task_text_search ON task USING GIST(to_tsvector('english', name || ' ' || description));
CREATE INDEX sprint_text_search_name ON sprint USING GIST(to_tsvector('english',name));

/* INSERTS */

INSERT INTO administrator (id, username, password) VALUES (1, 'admin', '1234Admin-');

INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Pedro Reis','partelinuxes','pedroreis@gmail.com',NULL,'$2a$04$A8A9VFFpZQzrL3tECqVeY..wCpv8Mi3RLqhNy7rmgb9z32obgQBQy',TRUE); --eunaverdadegostodewindows
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Ana Margarida','PortugueseCountryFan','just2playgg@gmail.com',NULL,'$2a$04$gtmJyF/vHKCyie9/95zs5.fNhyiST2QLIo7RPipzbw7gASx/2ogU6',FALSE); --asdasdparecemeseguro123
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Luis Correia','luigi_darkside','luigi_mei<3@gmail.com',NULL,'$2a$04$p8mMBaGAuVd9WykkcgEwVe8k8r2fAwURfUyC0SNA22mhiVjL/XtXi',FALSE); --passwordprofissional123
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Vicente Espinha','vespinha','up201505505@gcloud.fe.up.pt',NULL,'$2a$04$0l7NUZaVkLggU/zvUfZcHu2YZFlmuH6pnyN35/ptVKyfCmNihEarS',FALSE); --queroverosportingcampeao
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Marco Silva','Marcus_97','marcus_silva_97@gmail.com',NULL,'$2a$04$.mPl.hlwqbD.tX/ulwLfR.ocprsbpfeEhwZnRC8UbnHzhZsxGTKI.',FALSE); --1234Marcus-
INSERT INTO "user" (name,username,email,image,password,isAdmin, disable) VALUES ('André Ribeiro','programmer_rib','andre_ribeiro@gmail.com',NULL,'$2a$04$UvVGqhkYDhuj4n3OvglI3efBw5jfNi70r2ujTP9knwB56lvQcMdOW',FALSE,TRUE); --1234Andre-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Diana Salgado','dianne_sal','diana_salgado_2@hotmail.com',NULL,'$2a$04$8rnLzQsMvPSpCgdPZq4OsezYmnHSMtrg0hsTlyxVbZ/yuXQTa6cf.',FALSE); --1234Diana-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Andrew Tanenbaum','minix_lover','tanenbaum@gmail.com',NULL,'$2a$04$JvOpczD9Mmh.y90ZC4V5/uFXbcgJRgkx2KByJE9v7oBpE/D5h7tpG',FALSE); --1234Andrew-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Linus Torvalds','linux_lover_52','linus_torvalds@gmail.com',NULL,'$2a$04$P/Nd2IZSWTFtXUGFKIfRdetcEsonPMQ0zWMd1gFJAn2lXU.Gkk.Eu',FALSE); --1234Linus-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Susana Torres','susana_torres_92','susana_torres_92@gmail.com',NULL,'$2a$04$MGNQLACkm1H1E7UPyg5sJe7pEAc9dNwnH0YyCd3HMC9JoyixCuimq',FALSE);--1234Susana-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Diogo Mateus','diogo_76','diogo.mateus@gmail.com',NULL,'$2a$04$N/dq9RimubASPdNEnZAMkuVXFxaifT2tcpSavQVfIVMMoewze3Bwa',FALSE); --1234Diogo-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Adelino Bastos','adele_boy_67','adelino.bastos@gmail.com',NULL,'$2a$04$o9HPoqIJlqfbW20qj3YcR.xH8hmuVSs0VcZ.ko2FNPtIdzcGUbXba',FALSE); --1234Adelino-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Analisa Correia','anacruza_dacapo','analisa_correia.93@gmail.com',NULL,'$2a$04$BPjI1NPjfHprFPOM.pKGVu.ugHsJ5r5/H/C1Ik0VeqFdle8jOB7de',FALSE); --1234Analisa-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Madalena Soares','madalena_muffin','madalena_muffin@gmail.com',NULL,'$2a$04$dSsohrJB0QpV.3bi5i5v.u.g0AZ8zGCbOhQVT1aqvGONXQxptaFau',FALSE); --1234Madalena-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Pedro Batista','batista_89','pedro.batista@gmail.com',NULL,'$2a$04$2N95FBUIfm.ThJCFAyeXA.W5yKnbA3ixW8kcfFAU/Pn1aK2mb16Ua',FALSE); --1234Pedro-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Raul Vidal','sejam_felizes','raul.vidal@gmail.com',NULL,'$2a$04$bZm3UQ2YoxzkmeVqc4JIPO3yXF9PZOSb7/haEZK5QeZPkHDPU1Oie',FALSE);--1234Vidal-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Elliot Alderson','Mr_Robot','im_not_mr_robot@gmail.com',NULL,'$2a$04$pN01sGBVWCwf0bV8SeRiDecopFcctLcVRxDEy4BIzK3GXpAlN90pO',FALSE);--1234Elliot-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Felix Kjellberg','Pewdiepie','meme_review@gmail.com',NULL,'$2a$04$hdUC2rQF4FLqngwja19JIeU0B9Wq/aHKKGG46TrS4jpxR/d4eBLP2',FALSE); --1234Pewdiepie-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Helga Smith','helga_93','helga_legit@gmail.com',NULL,'$2a$04$R3uIlr3HrNBkahguqeg0J.6g5kZhTYPh12Fv4RfDP0bTOcjaiCDXy',FALSE);--1234Helga-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Jeff Sessions','my_name_jeff','my_name_jeff@gmail.com',NULL,'$2a$04$NIsrD.U6a2Wv62ytWFo1UOOAe3X3JugMEMlw4E5KlMu.3es.Mw.My',FALSE);--1234Jeff-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Diogo Dores','d_pain','fortnite@gmail.com',NULL,'$2a$04$Xn6W/4qPjcTSbUgDyzR2VupuTw73TkthIJ6jt8TJMwVRLfqMSqRsS',FALSE);--fortniteisluv
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Ventura Pereira','fcparasempre','vp@gmail.com',NULL,'$2a$04$gIR/Cw2wNl296flMetPMH.O8UCH0D6g/VQFOap7FRh3lJQJHhKfle',FALSE);--mourossucc
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Miguel Mano','blunky','fantano<3@gmail.com',NULL,'$2a$04$q1DLyw50VjD/r/dcjsjdiOJ.i318WB8xi3yN8gOn2EgoSuq0saP46',FALSE);--headwasclean
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Ze Borges','nbamaster','shaquille@gmail.com',NULL,'$2a$04$imBO52FcJXq2RC4VhdflTum4gSlP6vjUPLUBzzxiEuLtvSGmz0hgu',FALSE);--memes
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Joao Seixas','seixano','seixo@gmail.com',NULL,'$2a$04$5og4RqLNWcp47iU26gYXAeDk0A569uqOQkkm70hbQZvGsZL20VrMW',FALSE);-- -seixas-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Joao Conde','saltyaf','lol@gmail.com',NULL,'$2a$04$C26/7vANV0fJztQNYb/NKeGTwwjejbkoLaU33tC6q4Uwo4Gs9i1Y6',FALSE); --iactuallyhatelol
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Sofia Silva','aesthetic','blogger@gmail.com',NULL,'$2a$04$sX2MnJG5wKNY6KND9HjAtOxIBZ9JF78JPCuHAMql59ze1wdJBuHm.',FALSE);-- sofs
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Lil Pump','eskeeetit','gucci_gang@gmail.com',NULL,'$2a$04$rTo1sMj3eFy80jYRQ7YOme/A1EiP8zo2oMFtncFNZYyALMZr4zn2K',FALSE); --fantanolovesme
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Jaden Smith','eyes','im_an_icon@gmail.com',NULL,'$2a$04$hAKgYV/Mm1/wXyL0uCg30e8eLiR6gERFkEjQATgMYI9kgUbK8/0Pi',FALSE);--seriously_im_an_icon-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Mac DeMarco','pepperoni_playboy','blueboy@gmail.com',NULL,'$2a$04$MwvlQrfSfL7aY7nh4MsSJetQUvTW2IN.FoGf.1q.w0XgEI2vsQsc.',FALSE);--freaking-
INSERT INTO "user" (name,username,email,image,password,isAdmin) VALUES ('Sir Admin','admin','admin@dmin.com',NULL,'$2a$04$MwvlQrfSfL7aY7nh4MsSJetQUvTW2IN.FoGf.1q.w0XgEI2vsQsc.',TRUE);--freaking-


INSERT INTO project (name,description,isPublic) VALUES ('Education Through the Web','A web page, made specifically to support students on their quest to learn more efficiently.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Cryptocurrency applied to auction houses','The rise of cryptocurrency demands that such a profitable business such as online auction houses remain up to date with technology.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Character design pipeline for a videogame','The struggle of designing a character for 3d in Blender', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('LBAW project','Developing a entire web site in just a couple of months!', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('SDIS project','Developing a distributed application', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Secure platform for wire transfers','Platform that is totally secure for wire transfers between accounts in the same or different banks. The main focus is security.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Sigarra Website','New Sigarra Website, one more sensible and with more usability. Also with a mobile site that makes sense.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Backup Program using distributed systems','System that uses several servers to backup files through the network.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Open Source Half Life 3','The making of a dream, the highly requested Half Life 3. Will it achieve the high expectations?', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('The Elder Scrolls V : Skyrim Mods','Because we just don''t have anything to do, and Bethesda doesn''t stop the exploitation of this game', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Security Course','Help us build this course on security with your knowledge, so we can inform and teach this important subject to everyone who wants to learn', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Minix 4','Minix is the best OS ever. This project will build a more complete and secure version, with new features!', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('8-bit Rick Roll', 'A way better version of Never Gonna Give You Up by Rick Astley.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Price Comparator', 'Developing a platform that provides you the best price from stores around you.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Minix GUI', 'Crating a graphical interface for the best operating system ever!', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Paragon 2.0', 'Rebuilding the well-know MOBA Paragon, ditched by Epic Games.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('CGRA Submarine', 'Building a moving submarine able to shoot torpedos, using WebGL.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('To-do List', 'A simple to-do list, for the everyday life. Project developed in NodeJS.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('NECGM website', 'Rebuilding a new website for the NECGM from FEUP, now in NodeJS. New features will be added.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Fallout New Vegas VR', 'The best fallout game, now in VR. In development by Obsidian Entertainment.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Heart-rate app', 'Developing an app that allows you to measure your heart-rate, and analyse the data.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Fakecloud','A platform for music sharing ,similar to Soundcloud, but only covers are allowed.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('FCPStream', 'Creating a streaming platform broadcasting matches and info about Futebol Clube do Porto.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Krita Mobile', 'An open-source mobile port of the designing tool, Krita.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Musebot', 'Open-source project for a music streaming bot for Discord.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Fashion platform', 'A platform to help you find the best deals in fashion retail.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('NodeJS guide', 'Building a complete guide for NodeJS enthusiasts.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('WebGL Water Asset', 'Open-source asset that emulates water physics', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Civ V Mods', 'Open-source mods for Civilization V, bringing some exciting features to the game.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Facial expression analyser','Software that allows to figure the mood of a person based on facial expressions.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Daily meme bot','A web bot that posts a meme everyday for your amusement', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Sports stats app','Developing an app that delivers the most reliable data about statitics in every sport', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Meme reviewer platform','A website to review the freshest memes and rate them!', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('NoFakeNews', 'A web platform that delivers the most unbiased and reliable news', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('PReis','Developing a high-performance linux distro that aims to be beautiful. Can also be used for gaming!', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Pizza4All','An app created to increase the number of propotionally sliced pizzas', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Samurai Souls','Dark souls...but with samurais!!', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Rambox', 'An app that has all your message apps.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Vinyl Finder', 'A platform for finding the best deals in vinyl records.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('LaterX','A open-source laTEX editor. Made for students in a rush!', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Education Through the Web','A web page, made specifically to support students on their quest to learn more efficiently.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Cryptocurrency applied to auction houses','The rise of cryptocurrency demands that such a profitable business such as online auction houses remain up to date with technology.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Character design pipeline for a videogame','The struggle of designing a character for 3d in Blender', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('LBAW Project','Developing a entire web site in just a couple of months!', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('CGRA Submarine', 'Building a moving submarine able to shoot torpedos, using WebGL.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Secure platform for wire transfers','Platform that is totally secure for wire transfers between accounts in the same or different banks. The main focus is security.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Sigarra Website','New Sigarra Website, one more sensible and with more usability. Also with a mobile site that makes sense.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Backup Program using distributed systems','System that uses several servers to backup files through the network.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Open Source Half Life 3','The making of a dream, the highly requested Half Life 3. Will it achieve the high expectations?', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('The Elder Scrolls V : Skyrim Mods','Because we just don''t have anything to do, and Bethesda doesn''t stop the exploitation of this game', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Security Course','Help us build this course on security with your knowledge, so we can inform and teach this important subject to everyone who wants to learn', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Minix 4','Minix is the best OS ever. This project will build a more complete and secure version, with new features!', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('8-bit Rick Roll', 'A way better version of Never Gonna Give You Up by Rick Astley.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Price Comparator', 'Developing a platform that provides you the best price from stores around you.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Minix GUI', 'Crating a graphical interface for the best operating system ever!', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Paragon 2.0', 'Rebuilding the well-know MOBA Paragon, ditched by Epic Games.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('CGRA Submarine', 'Building a moving submarine able to shoot torpedos, using WebGL.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('To-do List', 'A simple to-do list, for the everyday life. Project developed in NodeJS.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('NECGM website', 'Rebuilding a new website for the NECGM from FEUP, now in NodeJS. New features will be added.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Fallout New Vegas VR', 'The best fallout game, now in VR. In development by Obsidian Entertainment.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Heart-rate app', 'Developing an app that allows you to measure your heart-rate, and analyse the data.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Fakecloud','A platform for music sharing ,similar to Soundcloud, but only covers are allowed.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('FCPStream', 'Creating a streaming platform broadcasting matches and info about Futebol Clube do Porto.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Krita Mobile', 'An open-source mobile port of the designing tool, Krita.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Musebot', 'Open-source project for a music streaming bot for Discord.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Fashion platform', 'A platform to help you find the best deals in fashion retail.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('NodeJS guide', 'Building a complete guide for NodeJS enthusiasts.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('WebGL Water Asset', 'Open-source asset that emulates water physics', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Civ V Mods', 'Open-source mods for Civilization V, bringing some exciting features to the game.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Facial expression analyser','Software that allows to figure the mood of a person based on facial expressions.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Daily meme bot','A web bot that posts a meme everyday for your amusement', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Sports stats app','Developing an app that delivers the most reliable data about statitics in every sport', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Meme reviewer platform','A website to review the freshest memes and rate them!', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('NoFakeNews', 'A web platform that delivers the most unbiased and reliable news', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('PReis','Developing a high-performance linux distro that aims to be beautiful. Can also be used for gaming!', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Pizza4All','An app created to increase the number of propotionally sliced pizzas', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Samurai Souls','Dark souls...but with samurais!!', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Rambox', 'An app that has all your message apps.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Vinyl Finder', 'A platform for finding the best deals in vinyl records.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('LaterX','A open-source laTEX editor. Made for students in a rush!', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Education Through the Web','A web page, made specifically to support students on their quest to learn more efficiently.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Cryptocurrency applied to auction houses','The rise of cryptocurrency demands that such a profitable business such as online auction houses remain up to date with technology.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Character design pipeline for a videogame','The struggle of designing a character for 3d in Blender', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('LBAW Project','Developing a entire web site in just a couple of months!', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Security Course','Help us build this course on security with your knowledge, so we can inform and teach this important subject to everyone who wants to learn', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Secure platform for wire transfers','Platform that is totally secure for wire transfers between accounts in the same or different banks. The main focus is security.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('CGRA Submarine', 'Building a moving submarine able to shoot torpedos, using WebGL.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Sigarra Website','New Sigarra Website, one more sensible and with more usability. Also with a mobile site that makes sense.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Backup Program using distributed systems','System that uses several servers to backup files through the network.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Open Source Half Life 3','The making of a dream, the highly requested Half Life 3. Will it achieve the high expectations?', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('The Elder Scrolls V : Skyrim Mods','Because we just don''t have anything to do, and Bethesda doesn''t stop the exploitation of this game', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Minix 4','Minix is the best OS ever. This project will build a more complete and secure version, with new features!', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('8-bit Rick Roll', 'A way better version of Never Gonna Give You Up by Rick Astley.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Price Comparator', 'Developing a platform that provides you the best price from stores around you.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('Minix GUI', 'Crating a graphical interface for the best operating system ever!', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Paragon 2.0', 'Rebuilding the well-know MOBA Paragon, ditched by Epic Games.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('CGRA Submarine', 'Building a moving submarine able to shoot torpedos, using WebGL.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('To-do List', 'A simple to-do list, for the everyday life. Project developed in NodeJS.', TRUE);
INSERT INTO project (name,description,isPublic) VALUES ('NECGM website', 'Rebuilding a new website for the NECGM from FEUP, now in NodeJS. New features will be added.', FALSE);
INSERT INTO project (name,description,isPublic) VALUES ('Fallout New Vegas VR', 'The best fallout game, now in VR. In development by Obsidian Entertainment.', FALSE);

INSERT INTO category (id, name) VALUES (1,'Entertainment');
INSERT INTO category (id, name) VALUES (3,'Productivity');
INSERT INTO category (id, name) VALUES (4,'Software');
INSERT INTO category (id, name) VALUES (5,'Application');
INSERT INTO category (id, name) VALUES (6,'Education');
INSERT INTO category (id, name) VALUES (7,'Business');
INSERT INTO category (id, name) VALUES (8,'Web');
INSERT INTO category (id, name) VALUES (9,'Game');
INSERT INTO category (id, name) VALUES (10,'Open Source');
INSERT INTO category (id, name) VALUES (11,'Graphic');
INSERT INTO category (id, name) VALUES (12,'Design');
INSERT INTO category (id, name) VALUES (13,'Sports');
INSERT INTO category (id, name) VALUES (14,'Music');
INSERT INTO category (id, name) VALUES (15,'Financial');
INSERT INTO category (id, name) VALUES (16,'Medical');
INSERT INTO category (id, name) VALUES (17,'Information');
INSERT INTO category (id, name) VALUES (18,'Lifestyle');
INSERT INTO category (id, name) VALUES (19,'Shopping');
INSERT INTO category (id, name) VALUES (20,'Social Networking');
INSERT INTO category (id, name) VALUES (21,'Multimedia');

INSERT INTO project_categories (project_id, category_id) VALUES (1,4);
INSERT INTO project_categories (project_id, category_id) VALUES (1,6);
INSERT INTO project_categories (project_id, category_id) VALUES (2,7);
INSERT INTO project_categories (project_id, category_id) VALUES (2,4);
INSERT INTO project_categories (project_id, category_id) VALUES (3,1);
INSERT INTO project_categories (project_id, category_id) VALUES (3,3);
INSERT INTO project_categories (project_id, category_id) VALUES (4,6);
INSERT INTO project_categories (project_id, category_id) VALUES (6,7);
INSERT INTO project_categories (project_id, category_id) VALUES (7,6);
INSERT INTO project_categories (project_id, category_id) VALUES (6,4);
INSERT INTO project_categories (project_id, category_id) VALUES (7,8);
INSERT INTO project_categories (project_id, category_id) VALUES (8,4);
INSERT INTO project_categories (project_id, category_id) VALUES (9,9);
INSERT INTO project_categories (project_id, category_id) VALUES (9,1);
INSERT INTO project_categories (project_id, category_id) VALUES (10,9);
INSERT INTO project_categories (project_id, category_id) VALUES (10,1);
INSERT INTO project_categories (project_id, category_id) VALUES (9,10);
INSERT INTO project_categories (project_id, category_id) VALUES (11,6);
INSERT INTO project_categories (project_id, category_id) VALUES (12,4);
INSERT INTO project_categories (project_id, category_id) VALUES (13,14);
INSERT INTO project_categories (project_id, category_id) VALUES (14,4);
INSERT INTO project_categories (project_id, category_id) VALUES (15,6);
INSERT INTO project_categories (project_id, category_id) VALUES (16,7);
INSERT INTO project_categories (project_id, category_id) VALUES (17,4);
INSERT INTO project_categories (project_id, category_id) VALUES (18,1);
INSERT INTO project_categories (project_id, category_id) VALUES (19,3);
INSERT INTO project_categories (project_id, category_id) VALUES (20,6);
INSERT INTO project_categories (project_id, category_id) VALUES (21,7);
INSERT INTO project_categories (project_id, category_id) VALUES (22,6);
INSERT INTO project_categories (project_id, category_id) VALUES (23,4);
INSERT INTO project_categories (project_id, category_id) VALUES (24,8);
INSERT INTO project_categories (project_id, category_id) VALUES (25,4);
INSERT INTO project_categories (project_id, category_id) VALUES (26,9);
INSERT INTO project_categories (project_id, category_id) VALUES (27,1);
INSERT INTO project_categories (project_id, category_id) VALUES (28,9);
INSERT INTO project_categories (project_id, category_id) VALUES (29,1);
INSERT INTO project_categories (project_id, category_id) VALUES (30,10);
INSERT INTO project_categories (project_id, category_id) VALUES (31,6);
INSERT INTO project_categories (project_id, category_id) VALUES (32,4);
INSERT INTO project_categories (project_id, category_id) VALUES (32,14);
INSERT INTO project_categories (project_id, category_id) VALUES (33,4);
INSERT INTO project_categories (project_id, category_id) VALUES (33,14);
INSERT INTO project_categories (project_id, category_id) VALUES (34,4);
INSERT INTO project_categories (project_id, category_id) VALUES (35,6);
INSERT INTO project_categories (project_id, category_id) VALUES (36,7);
INSERT INTO project_categories (project_id, category_id) VALUES (37,4);
INSERT INTO project_categories (project_id, category_id) VALUES (38,1);
INSERT INTO project_categories (project_id, category_id) VALUES (39,3);
INSERT INTO project_categories (project_id, category_id) VALUES (40,6);
INSERT INTO project_categories (project_id, category_id) VALUES (41,7);
INSERT INTO project_categories (project_id, category_id) VALUES (42,6);
INSERT INTO project_categories (project_id, category_id) VALUES (43,4);
INSERT INTO project_categories (project_id, category_id) VALUES (44,8);
INSERT INTO project_categories (project_id, category_id) VALUES (45,4);
INSERT INTO project_categories (project_id, category_id) VALUES (46,9);
INSERT INTO project_categories (project_id, category_id) VALUES (47,1);
INSERT INTO project_categories (project_id, category_id) VALUES (48,9);
INSERT INTO project_categories (project_id, category_id) VALUES (49,1);
INSERT INTO project_categories (project_id, category_id) VALUES (50,10);
INSERT INTO project_categories (project_id, category_id) VALUES (51,6);
INSERT INTO project_categories (project_id, category_id) VALUES (52,4);
INSERT INTO project_categories (project_id, category_id) VALUES (52,14);
INSERT INTO project_categories (project_id, category_id) VALUES (53,4);
INSERT INTO project_categories (project_id, category_id) VALUES (53,14);
INSERT INTO project_categories (project_id, category_id) VALUES (54,4);
INSERT INTO project_categories (project_id, category_id) VALUES (55,6);
INSERT INTO project_categories (project_id, category_id) VALUES (56,7);
INSERT INTO project_categories (project_id, category_id) VALUES (57,4);
INSERT INTO project_categories (project_id, category_id) VALUES (58,1);
INSERT INTO project_categories (project_id, category_id) VALUES (59,3);
INSERT INTO project_categories (project_id, category_id) VALUES (60,6);
INSERT INTO project_categories (project_id, category_id) VALUES (61,7);
INSERT INTO project_categories (project_id, category_id) VALUES (62,6);
INSERT INTO project_categories (project_id, category_id) VALUES (63,4);
INSERT INTO project_categories (project_id, category_id) VALUES (64,8);
INSERT INTO project_categories (project_id, category_id) VALUES (65,4);
INSERT INTO project_categories (project_id, category_id) VALUES (66,9);
INSERT INTO project_categories (project_id, category_id) VALUES (67,1);
INSERT INTO project_categories (project_id, category_id) VALUES (68,9);
INSERT INTO project_categories (project_id, category_id) VALUES (69,1);
INSERT INTO project_categories (project_id, category_id) VALUES (60,10);
INSERT INTO project_categories (project_id, category_id) VALUES (61,6);
INSERT INTO project_categories (project_id, category_id) VALUES (62,4);
INSERT INTO project_categories (project_id, category_id) VALUES (62,14);
INSERT INTO project_categories (project_id, category_id) VALUES (63,14);
INSERT INTO project_categories (project_id, category_id) VALUES (64,4);
INSERT INTO project_categories (project_id, category_id) VALUES (65,6);
INSERT INTO project_categories (project_id, category_id) VALUES (66,7);
INSERT INTO project_categories (project_id, category_id) VALUES (67,4);
INSERT INTO project_categories (project_id, category_id) VALUES (68,11);
INSERT INTO project_categories (project_id, category_id) VALUES (69,3);
INSERT INTO project_categories (project_id, category_id) VALUES (70,6);
INSERT INTO project_categories (project_id, category_id) VALUES (71,17);
INSERT INTO project_categories (project_id, category_id) VALUES (72,14);
INSERT INTO project_categories (project_id, category_id) VALUES (73,4);
INSERT INTO project_categories (project_id, category_id) VALUES (74,8);
INSERT INTO project_categories (project_id, category_id) VALUES (75,4);
INSERT INTO project_categories (project_id, category_id) VALUES (76,9);
INSERT INTO project_categories (project_id, category_id) VALUES (77,1);
INSERT INTO project_categories (project_id, category_id) VALUES (78,9);
INSERT INTO project_categories (project_id, category_id) VALUES (79,1);
INSERT INTO project_categories (project_id, category_id) VALUES (80,10);
INSERT INTO project_categories (project_id, category_id) VALUES (81,6);
INSERT INTO project_categories (project_id, category_id) VALUES (82,16);
INSERT INTO project_categories (project_id, category_id) VALUES (82,14);
INSERT INTO project_categories (project_id, category_id) VALUES (83,4);
INSERT INTO project_categories (project_id, category_id) VALUES (83,14);
INSERT INTO project_categories (project_id, category_id) VALUES (84,4);
INSERT INTO project_categories (project_id, category_id) VALUES (85,6);
INSERT INTO project_categories (project_id, category_id) VALUES (86,7);
INSERT INTO project_categories (project_id, category_id) VALUES (87,4);
INSERT INTO project_categories (project_id, category_id) VALUES (88,1);
INSERT INTO project_categories (project_id, category_id) VALUES (89,16);
INSERT INTO project_categories (project_id, category_id) VALUES (90,6);
INSERT INTO project_categories (project_id, category_id) VALUES (91,7);
INSERT INTO project_categories (project_id, category_id) VALUES (92,6);
INSERT INTO project_categories (project_id, category_id) VALUES (93,4);
INSERT INTO project_categories (project_id, category_id) VALUES (94,18);
INSERT INTO project_categories (project_id, category_id) VALUES (95,4);
INSERT INTO project_categories (project_id, category_id) VALUES (96,21);
INSERT INTO project_categories (project_id, category_id) VALUES (97,1);
INSERT INTO project_categories (project_id, category_id) VALUES (98,9);
INSERT INTO project_categories (project_id, category_id) VALUES (99,1);
INSERT INTO project_categories (project_id, category_id) VALUES (100,10);

INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (2, '2018-05-08 10:00:00+01', 1, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (2, '2018-05-08 10:00:00+01', 2, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (3, '2018-05-08 10:00:00+01', 3, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (4, '2018-05-08 10:00:00+01', 3, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (2, '2018-05-08 10:00:00+01', 4, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (3, '2018-05-08 10:00:00+01', 4, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (4, '2018-05-08 10:00:00+01', 4, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (4, '2018-05-08 10:00:00+01', 5, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (8, '2018-05-08 10:00:00+01', 12, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (9, '2018-05-08 10:00:00+01', 12, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (7, '2018-05-08 10:00:00+01', 12, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (6, '2018-05-08 10:00:00+01', 7, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (16, '2018-05-08 10:00:00+01', 12, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (12, '2018-05-08 10:00:00+01', 6, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (6, '2018-05-08 10:00:00+01', 6, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (17, '2018-05-08 10:00:00+01', 11, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (19, '2018-05-08 10:00:00+01', 11, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (18, '2018-05-08 10:00:00+01', 9, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (2, '2018-05-08 10:00:00+01', 11, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (11, '2018-05-08 10:00:00+01', 8, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (20, '2018-05-08 10:00:00+01', 10, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (8, '2018-05-08 10:00:00+01', 9, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (15, '2018-05-08 10:00:00+01', 7, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (5, '2018-05-08 10:00:00+01', 12, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (13, '2018-05-08 10:00:00+01', 12, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (10, '2018-05-08 10:00:00+01', 6, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (9, '2018-05-08 10:00:00+01', 6, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (12, '2018-05-08 10:00:00+01', 7, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (11, '2018-05-08 10:00:00+01', 7, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (14, '2018-05-08 10:00:00+01', 8, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (15, '2018-05-08 10:00:00+01', 8, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (17, '2018-05-08 10:00:00+01', 10, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (18, '2018-05-08 10:00:00+01', 10, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (3, '2018-05-08 10:00:00+01', 10, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (6, '2018-05-08 10:00:00+01', 12, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (14, '2018-05-08 10:00:00+01', 12, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (17, '2018-05-08 10:00:00+01', 2, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (6, '2018-05-08 10:00:00+01', 2, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (11, '2018-05-08 10:00:00+01', 3, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (1, '2018-05-08 10:00:00+01', 3, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (7, '2018-05-08 10:00:00+01', 4, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (18, '2018-05-08 10:00:00+01', 4, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (16, '2018-05-08 10:00:00+01', 2, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (2, '2018-05-08 10:00:00+01', 15, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (2, '2018-05-08 10:00:00+01', 16, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (3, '2018-05-08 10:00:00+01', 17, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (4, '2018-05-08 10:00:00+01', 18, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (2, '2018-05-08 10:00:00+01', 20, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (3, '2018-05-08 10:00:00+01', 21, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (4, '2018-05-08 10:00:00+01', 22, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (8, '2018-05-08 10:00:00+01', 23, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (9, '2018-05-08 10:00:00+01', 24, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (7, '2018-05-08 10:00:00+01', 25, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (6, '2018-05-08 10:00:00+01', 26, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (16, '2018-05-08 10:00:00+01', 27, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (12, '2018-05-08 10:00:00+01', 28, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (6, '2018-05-08 10:00:00+01', 29, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (17, '2018-05-08 10:00:00+01', 30, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (19, '2018-05-08 10:00:00+01', 31, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (18, '2018-05-08 10:00:00+01', 32, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (2, '2018-05-08 10:00:00+01', 34, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (11, '2018-05-08 10:00:00+01', 35, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (20, '2018-05-08 10:00:00+01', 36, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (8, '2018-05-08 10:00:00+01', 37, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (15, '2018-05-08 10:00:00+01', 38, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (5, '2018-05-08 10:00:00+01', 39, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (13, '2018-05-08 10:00:00+01', 40, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (10, '2018-05-08 10:00:00+01', 41, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (9, '2018-05-08 10:00:00+01', 42, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (12, '2018-05-08 10:00:00+01', 43, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (11, '2018-05-08 10:00:00+01', 44, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (14, '2018-05-08 10:00:00+01', 45, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (15, '2018-05-08 10:00:00+01', 46, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (17, '2018-05-08 10:00:00+01', 47, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (18, '2018-05-08 10:00:00+01', 48, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (3, '2018-05-08 10:00:00+01', 49, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (6, '2018-05-08 10:00:00+01', 50, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (14, '2018-05-08 10:00:00+01', 51, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (17, '2018-05-08 10:00:00+01', 52, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (6, '2018-05-08 10:00:00+01', 53, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (11, '2018-05-08 10:00:00+01', 54, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (7, '2018-05-08 10:00:00+01',56, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (18, '2018-05-08 10:00:00+01', 58, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (16, '2018-05-08 10:00:00+01', 59, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (16, '2018-05-08 10:00:00+01', 60, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (3, '2018-05-08 10:00:00+01', 61, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (4, '2018-05-08 10:00:00+01', 62, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (8, '2018-05-08 10:00:00+01', 63, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (9, '2018-05-08 10:00:00+01', 64, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (7, '2018-05-08 10:00:00+01', 65, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (6, '2018-05-08 10:00:00+01', 66, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (16, '2018-05-08 10:00:00+01',67, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (12, '2018-05-08 10:00:00+01', 68, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (6, '2018-05-08 10:00:00+01', 69, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (17, '2018-05-08 10:00:00+01', 70, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (19, '2018-05-08 10:00:00+01', 71, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (18, '2018-05-08 10:00:00+01', 72, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (2, '2018-05-08 10:00:00+01', 74, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (11, '2018-05-08 10:00:00+01', 75, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (20, '2018-05-08 10:00:00+01', 76, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (8, '2018-05-08 10:00:00+01', 77, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (15, '2018-05-08 10:00:00+01', 78, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (5, '2018-05-08 10:00:00+01', 79, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (13, '2018-05-08 10:00:00+01', 80, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (10, '2018-05-08 10:00:00+01', 81, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (9, '2018-05-08 10:00:00+01', 82, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (12, '2018-05-08 10:00:00+01', 83, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (11, '2018-05-08 10:00:00+01', 84, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (14, '2018-05-08 10:00:00+01', 85, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (15, '2018-05-08 10:00:00+01', 86, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (17, '2018-05-08 10:00:00+01', 87, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (18, '2018-05-08 10:00:00+01', 88, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (3, '2018-05-08 10:00:00+01', 89, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (6, '2018-05-08 10:00:00+01', 90, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (14, '2018-05-08 10:00:00+01', 91, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (17, '2018-05-08 10:00:00+01', 92, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (6, '2018-05-08 10:00:00+01', 93, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (11, '2018-05-08 10:00:00+01', 94, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (7, '2018-05-08 10:00:00+01',96, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (18, '2018-05-08 10:00:00+01', 98, FALSE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (16, '2018-05-08 10:00:00+01', 99, TRUE);
INSERT INTO project_members (user_id, date, project_id, iscoordinator) VALUES (16, '2018-05-08 10:00:00+01', 100, TRUE);

INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('Could there be a section about Programming?','I think we are focusing more on mathematics and programming is being left out. It is an interesting subject and very useful these days!', '2018-05-08 12:00:00+01',1,2);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('Could there be a section about Programming?','I think there should.', '2018-05-08 12:00:00+01',6,6);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('I think I broke the project....oopsie!','Ah...guys, it ain''t working! Could someone fix this please!?\n*screeching*', '2018-05-08 12:00:00+01',4,18);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('Another game with a game with a female lead character....boring!','Guys, come on! Not again! I know it is a trend, but why not vary and make, for example, a game with several principal characters, where you can play with different characters, both in gender but also in race. This game could do it, the story allows it!', now(),3,4);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('I don''t know...will this really work?','Will it be really possible to make this game? It is HL3 and, well, is open source. By the way, isn''t it kinda illegal? Doesn''t Valve has the rights to this?\nJust saying...', '2018-05-08 12:00:00+01',9,18);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('I have a great idea!','Lets make the character like Geralt of Witcher 3 and the dragons will be Roach! Ah, hilarious!\nMy name''s Jeff!', '2018-05-08 12:00:00+01',10,20);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('Did you know?','Linux is kinda based on Minix...well not really, but first I wanted to improve Minix features but Andrew didn''t wanted me to, so I based some of Linux in Minix... but I changed lots of things, of course!', '2018-05-08 12:00:00+01',12,9);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('I believe the mock ups are kinda ugly...','We should do it again','2018-05-08 12:00:00+01',4,18);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('I have a great idea!','Let''s make the character like Geralt of Witcher 3 and the dragons will be Roach! Ah, hilarious!\nMy name''s Jeff!', '2018-05-08 12:00:00+01',10,20);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('Did you know?','Linux is kinda based on Minix...well not really, but first I wanted to improve Minix features but Andrew didn''t wanted me to, so I based some of Linux in Minix... but I changed lots of things, of course!', '2018-05-08 12:00:00+01',12,9);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('Witcher 3 quest!','Could someone give some hints about where i can find celandine in witcher 3?', '2018-05-08 12:00:00+01',90,2);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('Could there be a section about Programming?','I think we are focusing more on mathematics and programming is being left out. It is an interesting subject and very useful these days!', '2018-05-08 12:00:00+01',1,2);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('I think I broke the project....oopsie!','Ah...guys, it ain''t working! Could someone fix this please!?\n*screeching*', '2018-05-08 12:00:00+01',4,18);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('Another game with a game with a female lead character....boring!','Guys, come on! Not again! I know it is a trend, but why not vary and make, for example, a game with several principal characters, where you can play with different characters, both in gender but also in race. This game could do it, the story allows it!', now(),3,4);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('I don''t know...will this really work?','Will it be really possible to make this game? It is HL3 and, well, is open source. By the way, isn''t it kinda illegal? Doesn''t Valve has the rights to this?\nJust saying...', '2018-05-08 12:00:00+01',9,18);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('I have a great idea!','Let''s make the character like Geralt of Witcher 3 and the dragons will be Roach! Ah, hilarious!\nMy name''s Jeff!', '2018-05-08 12:00:00+01',10,20);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('Did you know?	','Linux is kinda based on Minix...well not really, but first I wanted to improve Minix features but Andrew didn''t wanted me to, so I based some of Linux in Minix... but I changed lots of things, of course!', '2018-05-08 12:00:00+01',12,9);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('Witcher 3 quest!','Could someone give some hints about where i can find cedaline in witcher 3?', '2018-05-08 12:00:00+01',33,3);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('I have a great idea!','Let''s make the character like Geralt of Witcher 3 and the dragons will be Roach! Ah, hilarious!\nMy name''s Jeff!', '2018-05-08 12:00:00+01',10,20);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('Did you know?	','Linux is kinda based on Minix...well not really, but first I wanted to improve Minix features but Andrew didn''t wanted me to, so I based some of Linux in Minix... but I changed lots of things, of course!', '2018-05-08 12:00:00+01',12,9);
INSERT INTO thread (name,description,date,project_id,user_creator_id) VALUES ('Witcher 3 quest!','Could someone give some hints about where i can find cedaline in witcher 3?', '2018-05-08 12:00:00+01',78,1);

INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Mock-Ups','2018-06-28 00:00:00+01',1,2,5);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Database structure','2018-06-28 00:00:00+01',1,2,3);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Website','2018-06-28 12:00:00+01',2,16,5);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Build Security','2018-06-28 08:00:00+01',2,16,5);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Draw Mock-up','2018-06-30 23:59:00+01',3,11,3);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Design with blender','2018-06-28 22:59:00+01',3,1,7);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Database','2018-06-30 23:00:00+01',4,3,7);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Make Website','2018-06-28 23:00:00+01',4,2,10);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Mobile App','2018-06-28 23:00:00+01',6,6,10);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Security Verifications','2018-06-04 23:00:00+01',6,6,8);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Mock-Ups','2018-06-28 23:00:00+01',7,6,7);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Security','2018-06-30 23:00:00+01',7,6,7);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Client RMI','2018-06-29 23:00:00+01',8,11,7);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Communications between servers','2018-06-28 23:00:00+01',8,11,8);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Write history','2018-06-28 23:00:00+01',9,1,6);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Draw characters','2018-06-28 00:00:00+01',9,1,8);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Decide Improvements','2018-06-30 23:00:00+01',10,18,5);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Make models 3D','2018-06-28 23:00:00+01',10,17,10);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Design Course Program','2018-06-28 23:00:00+01',11,17,3);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Introduction','2018-06-28 23:00:00+01',11,17,5);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Decide Improvements','2018-06-28 23:00:00+01',12,8,3);
INSERT INTO sprint (name,deadline,project_id,user_creator_id,effort) VALUES ('Kernel','2018-06-28 23:00:00+01',12,8,20);

INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Index Page','Make a responsive mock up of the index page, with tonalities of blue and gold. Images will be added next',1,1,1);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Video Page','Responsive page to allocate many videos',2,1,1);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Basic database','Solid structure of basic database to support video',1,1,2);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Security','Implement mechanism to prevent SQL Injections',2,1,2);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Database','Solid and secure database',2,2,3);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Transfer Page','',2,2,3);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Cross-Site Scripting Security','Implement mechanism to prevent XSS',2,2,4);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Cross-Site Request Forgery','Implement mechanism to prevent CSRF',2,2,4);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Make principal character','Female, long dark hair, blue jeans and flannel shirt, nerdy look',1,3,5);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Villain character','Guy, normal person, glasses and with a trustworthy expression',1,3,5);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Sidekick character','Flashy character, guy, always smiling and with a funny haircut and style.',1,3,5);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Basic design','',3,3,6);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Animations','Walking, jumping, rolling',4,3,6);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Populate','At least 25 tasks',3,4,7);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Make queries','To all the tables',2,4,7);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Triggers','',1,4,7);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('project Page','Use AJAX to switch between the possible pages of the project page.\nMake animations fluid and natural.',6,4,8);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Resolve bug on the forum page','CSS and Javascript bug, doesn''t show information about the date because it is cut off, and the date is wrongly calculated',2,4,8);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Put in Google Play','Share the application in Google Play',1,6,9);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Connect with several banks','Get agreements with several banks to access to their platform.',2,6,9);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Security','Make the mobile app secure',4,6,9);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Hire company specialized in security','',2,6,10);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Design Index Page','Make a pleasant and informative index page',4,7,11);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Make responsive to mobile devices','',3,7,11);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('XXS security','',2,7,12);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('CSRF Security','',2,7,12);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('SQL Injections Verification','Very important verification!',2,7,12);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Make reference to the registry','Don''t forget to use the right instructions, here:\nhttps://docs.oracle.com/javase/tutorial/rmi/client.html',2,8,13);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Code','',4,8,13);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Create multicast channels to every socket used','Don''t forget to join by group and use different IPs to each socket',2,8,14);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Concurrent Mechanism','Don''t forget to check the replicationDegree and send only to that number of servers. Check if the stored messages are received, and in their correct number.\nAlso, it has to be possible to process several requests at once!\nUse threads and/or threadPools!',5,8,14);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Main Quest','It has to start where the previous one has ended',2,9,15);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Write 3 side-quests','Have to be at least 45min long',3,9,15);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Principal Character - Gordon Freeman','Keep it close to the original one',2,9,16);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('G-Man','Keep it mysterious',2,9,16);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Current Meme incorporation','What meme to use in this mod?',1,10,17);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Decision to make this a serious or a stupid mod','',2,10,17);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Chicken Model','Yap, a chicken model, we are going with that',2,10,18);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Decide number of chapters','',1,11,19);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Pen Testing?','Is it possible to make a chapter about this one, and an extensive one?',1,11,19);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Introduce yourself and the course','Explain who you are, what you do for a living and your motivations.\nExplain what are the objectives of the course, the resources needed and the degree of difficulty.',1,11,20);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Course mapping','Explain the different topics that will be covered, as well as their importance.',1,11,20);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Write in the comments bellow your opinion','',1,12,21);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Rewrite function about sound drivers','This function contains a bug with specific sound cards',4,12,22);
INSERT INTO task (name,description,effort,project_id,sprint_id) VALUES ('Clean Code','Before delivering the project, the code has to be clean',2,3,NULL);

INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('There will be a part of the website that will focus totally on Programming but, for now, it is more imperative that we finish the Mathematics chapters.','2018-05-08 12:30:00+01',1,NULL,1);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('Ah, I didn''t know! Thank you!','2018-05-08 12:30:00+01',2,NULL,1);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('Oh man, not again! I will see what is broke then, but please say something before you go there. I don''t know what you do, but you have a knack for breaking websites!','2018-05-08 12:30:00+01',3,NULL,2);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('I agree with him, it makes total sense! It is a history similar to Doctor Who, we have the material to make it like it.','2018-05-08 12:30:00+01',3,NULL,3);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('Okay, we will see about it! For now keep working on the character chosen, and we will see about changing the history.\n\nThank you for the suggestion!','2018-05-08 12:30:00+01',11,NULL,3);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('Well, we won''t gain money from this, so I guess it is legal...ah, right?','2018-05-08 12:30:00+01',1,NULL,4);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('In my opinion, that is an awful idea. It doesn''t make any sense whatsoever!','2018-05-08 12:30:00+01',17,NULL,5);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('I kinda like it!','2018-05-08 12:30:00+01',18,NULL,5);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('Why are you always telling this story? Everyone knows it!','2018-05-08 12:30:00+01',8,NULL,6);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('It is an interesting fact','2018-05-08 12:30:00+01',9,NULL,6);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('Is it possible for someone to give more detailed points about this one?',now(),3,10,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('The point is for the villain to be like a normal person, like a friendly neighbor or a friendly coworker','2018-05-08 12:30:00+01',11,10,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('Is it only related to checking if a member is a coordinator or team member when doing some type of action?','2018-05-08 12:30:00+01',7,16,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('Not only, but also checking if a value of effort on a sprint is exceeded by its tasks.','2018-05-08 12:30:00+01',2,16,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('Well, of course it would be this','2018-05-08 12:30:00+01',3,38,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('I would like to do this one','2018-05-08 12:30:00+01',2,7,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('I think this one isn''t really a Javascript bug but a PHP error...there isn''t any way of showing the date in the php file','2018-05-08 12:30:00+01',7,18,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('I can do this one, I have experience with security. It helps to save money','2018-05-08 12:30:00+01',10,22,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('Isn''t there an easier way of doing this? It is a lot of work','2018-05-08 12:30:00+01',18,14,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('I don''t think so, this is the only way','2018-05-08 12:30:00+01',3,14,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('It would help if there were more than one person doing this','2018-05-08 12:30:00+01',2,14,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('I''ll help has well!','2018-05-08 12:30:00+01',1,14,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('Can it use glasses or some kind of googles?','2018-05-08 12:30:00+01',4,11,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('Sure, any suggestion can be done','2018-05-08 12:30:00+01',11,11,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('I''ve done this, but it isn''t working. Can someone help?','2018-05-08 12:30:00+01',14,30,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('I think I know how...I think you are forgetting to create e InetAddress and are passing only a string with the address.','2018-05-08 12:30:00+01',15,30,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('You''re right, thanks!','2018-05-08 12:30:00+01',14,30,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('Yes! I think that kind of content would be very important! If there isn''t any problem, I would like to do it','2018-05-08 12:30:00+01',19,40,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('Of course!','2018-05-08 12:30:00+01',17,40,NULL);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('Oh, shut up, you know nothing!','2018-05-08 12:30:00+01',4,NULL,3);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('Jeeeesss, you are so dumb!','2018-05-08 12:30:00+01',7,NULL,2);
INSERT INTO comment (content,date,user_id,task_id,thread_id) VALUES ('SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM SPAM','2018-05-08 12:30:00+01',18,NULL,4);

INSERT INTO sprint_state_record (date,state,sprint_id) VALUES (now(),'Completed',5);
INSERT INTO sprint_state_record (date,state,sprint_id) VALUES (now(),'Completed',13);
INSERT INTO sprint_state_record (date,state,sprint_id) VALUES (now(),'Completed',21);
INSERT INTO sprint_state_record (date,state,sprint_id) VALUES (now(),'Completed',22);

INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 12:30:00+01','Assigned',1,1);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 12:30:00+01','Assigned',1,3);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 12:30:00+01','Assigned',2,6);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 12:30:00+01','Assigned',3,9);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 12:34:00+01','Unassigned',3,9);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 12:34:00+01','Assigned',4,9);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 12:35:00+01','Assigned',3,10);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 12:35:39+01','Unassigned',3,10);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 12:35:39+01','Assigned',4,10);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 12:41:00+01','Completed',3,9);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 12:44:00+01','Completed',4,10);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 12:44:00+01','Assigned',4,11);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 12:50:00+01','Completed',4,11);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 13:30:00+01','Assigned',7,14);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 13:30:00+01','Completed',15,16);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 13:30:00+01','Assigned',14,28);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 13:50:00+01','Completed',14,28);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 14:30:00+01','Assigned',15,29);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 14:40:00+01','Unassigned',15,29);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 14:40:00+01','Assigned',14,29);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 16:30:00+01','Completed',14,29);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 16:30:00+01','Completed',8,43);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-05-08 19:30:00+01','Completed',9,43);
INSERT INTO task_state_record (date,state,user_completed_id,task_id) VALUES ('2018-04-08 19:30:00+01','Completed',8,44);

INSERT INTO report (date,summary,user_id,type,comment_reported_id,user_reported_id) VALUES ('2018-05-08 19:30:00+01','It is an offensive comment and totally out of place',3,'commentReported',30,NULL);
INSERT INTO report (date,summary,user_id,type,comment_reported_id,user_reported_id) VALUES ('2018-05-08 19:30:00+01','comment extremely offensive',18,'commentReported',32,NULL);
INSERT INTO report (date,summary,user_id,type,comment_reported_id,user_reported_id) VALUES ('2018-05-08 19:30:00+01','It''s clearly spam, and it should be removed, as well as the user',8,'commentReported',31,NULL);
INSERT INTO report (date,summary,user_id,type,comment_reported_id,user_reported_id) VALUES ('2018-05-08 19:30:00+01','Clearly a Nazi, it''s what J.K.Rowling and the WSJ says...',20,'userReported',NULL,18);

INSERT INTO invite (date,user_invited_id,project_id,user_who_invited_id) VALUES ('2018-05-08 19:30:00+01',7,9,1);
INSERT INTO invite (date,user_invited_id,project_id,user_who_invited_id) VALUES ('2018-05-08 19:30:00+01',4,12,NULL);
INSERT INTO invite (date,user_invited_id,project_id,user_who_invited_id) VALUES ('2018-05-08 19:30:00+01',4,11,NULL);
INSERT INTO invite (date,user_invited_id,project_id,user_who_invited_id) VALUES ('2018-05-08 19:30:00+01',6,8,11);
INSERT INTO invite (date,user_invited_id,project_id,user_who_invited_id) VALUES ('2018-05-08 19:30:00+01',4,6,6);
INSERT INTO invite (date,user_invited_id,project_id,user_who_invited_id) VALUES ('2018-05-08 19:30:00+01',8,6,6);
INSERT INTO invite (date,user_invited_id,project_id,user_who_invited_id) VALUES ('2018-05-08 19:30:00+01',13,6,NULL);