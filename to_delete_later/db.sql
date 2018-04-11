CREATE TABLE User (
	id SERIAL NOT NULL,
	name text NOT NULL,
	username text NOT NULL,
	email text NOT NULL,
	image text,
	password text NOT NULL
);

CREATE TABLE Project (
	id SERIAL NOT NULL,
	name text NOT NULL,
	description text NOT NULL,
	isPublic boolean NOT NULL
);

CREATE TABLE Sprint (
	id SERIAL NOT NULL,
	name text NOT NULL,
	deadline TIMESTAMP WITH TIME zone NOT NULL,
	effort INTEGER NOT NULL,
	project_id INTEGER NOT NULL,
	user_creator_id INTEGER NOT NULL,
	CONSTRAINT deadline CHECK (deadline > now())
);

CREATE TABLE Task (
	id SERIAL NOT NULL,
	name text NOT NULL,
	description text,
	effort INTEGER NOT NULL,
	project_id INTEGER NOT NULL,
	sprint_id INTEGER
);

CREATE TABLE Thread (
	id SERIAL NOT NULL,
	name text NOT NULL,
	description text,
	"date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
	project_id INTEGER NOT NULL,
	user_creator_id INTEGER NOT NULL 
);

CREATE TABLE Comment (
	id SERIAL NOT NULL,
	content text NOT NULL,
	"date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
	user_id INTEGER NOT NULL,
	task_id INTEGER,
	thread_id INTEGER,
	CONSTRAINT belongs CHECK ((task_id != NULL AND thread_id = NULL) OR (task_id = NULL AND thread_id != NULL))
);

CREATE TABLE Category (
	id SERIAL NOT NULL,
	name text NOT NULL
);

CREATE TABLE Project_members (
	user_id INTEGER NOT NULL,
	project_id INTEGER NOT NULL,
	isCoordinator boolean NOT NULL,
	"date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL
);

CREATE TABLE Administrator (
	id SERIAL NOT NULL,
	username text NOT NULL,
	password text NOT NULL
);

CREATE TABLE Report (
	id SERIAL NOT NULL,
	"date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
	type text NOT NULL,
	summary text NOT NULL,
	user_id INTEGER NOT NULL,
	comment_reported_id INTEGER,
	user_reported_id INTEGER,
	CONSTRAINT reportType CHECK ((type = ANY(ARRAY['CommentReported'::text, 'UserReported'::text]))),
	CONSTRAINT typeConstraint CHECK ((type = 'CommentReported' AND comment_reported_id != NULL AND user_reported_id = NULL) OR
											(type = 'UserReported' AND user_reported_id != NULL AND comment_reported_id = NULL))
);

CREATE TABLE Notification (
	id SERIAL NOT NULL,
	"date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
	notification_type text NOT NULL,
	user_id INTEGER NOT NULL,
	project_id INTEGER,
	comment_id INTEGER,
	user_action_id INTEGER,
	CONSTRAINT notificationType CHECK ((notification_type = ANY(ARRAY['Comment'::text, 'CommentReported'::text, 'Promotion'::text, 'RemovedFromProject'::text, 'Invite'::text, 'Request'::text]))),
	CONSTRAINT notificationConstraint CHECK ((notification_type = 'Comment' AND comment_id != NULL) OR
											(notification_type = 'CommentReported' AND comment_id != NULL) OR
											(notification_type = 'Promotion' AND project_id != NULL AND user_action_id != NULL) OR
											(notification_type = 'RemovedFromProject' AND project_id != NULL) OR
											(notification_type = 'Invite' AND project_id != NULL AND user_action_id != NULL) OR
											(notification_type = 'Request' AND project_id != NULL))
);

CREATE TABLE Invite (
	id SERIAL NOT NULL,
	"date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
	user_invited_id INTEGER NOT NULL,
	project_id INTEGER NOT NULL,
	user_who_invited_id INTEGER
);

CREATE TABLE TaskStateRecord(
	id SERIAL NOT NULL,
	"date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
	state text NOT NULL,
	user_completed_id INTEGER NOT NULL,
	task_id INTEGER NOT NULL,
	CONSTRAINT state CHECK ((state = ANY(ARRAY['Completed'::text, 'Assigned'::text, 'Unnassigned'::text, 'Created'::text])))
);

CREATE TABLE SprintStateRecord(
	id SERIAL NOT NULL,
	"date" TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
	state text NOT NULL,
	sprint_id INTEGER NOT NULL,
	CONSTRAINT state CHECK ((state = ANY(ARRAY['Completed'::text, 'Outdated'::text, 'Created'::text])))
);

CREATE TABLE Project_categories (
	project_id INTEGER NOT NULL,
	category_id INTEGER NOT NULL
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

ALTER TABLE ONLY Project_members
	ADD CONSTRAINT project_members_pkey PRIMARY KEY (user_id, project_id);

ALTER TABLE ONLY Administrator
	ADD CONSTRAINT administrator_pkey PRIMARY KEY (id);

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
 	

/* Foreign Keys */

ALTER TABLE ONLY Sprint
	ADD CONSTRAINT task_id_project_fkey FOREIGN KEY (project_id) REFERENCES Project(id) ON UPDATE CASCADE ON DELETE CASCADE; 

ALTER TABLE ONLY Sprint
	ADD CONSTRAINT sprint_id_user_creator_fkey FOREIGN KEY (user_creator_id) REFERENCES User(id) ON UPDATE CASCADE; 

ALTER TABLE ONLY Task
	ADD CONSTRAINT task_id_user_project_fkey FOREIGN KEY (project_id) REFERENCES Project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Task
	ADD CONSTRAINT task_id_user_sprint_fkey FOREIGN KEY (sprint_id) REFERENCES Sprint(id) ON UPDATE CASCADE;

ALTER TABLE ONLY Thread
	ADD CONSTRAINT thread_id_project_fkey FOREIGN KEY (project_id) REFERENCES Project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Thread
	ADD CONSTRAINT thread_id_user_creator_fkey FOREIGN KEY (user_creator_id) REFERENCES User(id) ON UPDATE CASCADE;

ALTER TABLE ONLY Comment
	ADD CONSTRAINT comment_id_user_fkey FOREIGN KEY (user_id) REFERENCES User(id) ON UPDATE CASCADE;

ALTER TABLE ONLY Comment
	ADD CONSTRAINT comment_id_task_fkey FOREIGN KEY (task_id) REFERENCES Task(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Comment
	ADD CONSTRAINT comment_id_thread_fkey FOREIGN KEY (thread_id) REFERENCES Thread(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Project_members
	ADD CONSTRAINT members_id_user_fkey FOREIGN KEY (user_id) REFERENCES User(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Project_members
	ADD CONSTRAINT members_id_project_fkey FOREIGN KEY (project_id) REFERENCES Project(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY Report
	ADD CONSTRAINT report_id_user_fkey FOREIGN KEY (user_id) REFERENCES User(id) ON UPDATE CASCADE;

ALTER TABLE ONLY Report
	ADD CONSTRAINT report_id_comment_reported_fkey FOREIGN KEY (comment_reported_id) REFERENCES Comment(id) ON UPDATE CASCADE;

ALTER TABLE ONLY Report
	ADD CONSTRAINT report_id_user_reported_fkey FOREIGN KEY (user_reported_id) REFERENCES User(id) ON UPDATE CASCADE;

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



/* TRIGGERS */
-- Checks if the effort of a sprint tasks exceeds the sprint effort
CREATE FUNCTION check_effort() RETURN TRIGGER AS
$BODY$
BEGIN
	IF ((SELECT SUM(effort) FROM Task WHERE NEW.sprint_id = Task.sprint_id) > 
		(SELECT effort FROM Sprint WHERE id = NEW.sprint_id))
	THEN RAISE EXCEPTION 'This task exceeds the limit effort of the sprint.';
	END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER check_effort
	BEFORE INSERT OR UPDATE ON Task
	FOR EACH ROW
		EXECUTE PROCEDURE check_effort();

-- Create a Notification when a report is created
CREATE FUNCTION add_notification_report() RETURN TRIGGER AS
$BODY$
BEGIN
	IF(NEW.type = 'CommentReported')
	THEN INSERT INTO Notification (date,notification_type,user_id,comment_id) 
			VALUES (now(),'CommentReported',NEW.user_reported_id,NEW.comment_reported_id);
	END IF;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER add_notification_report
	AFTER INSERT ON Report
	FOR EACH ROW
		EXECUTE PROCEDURE add_notification_report();

-- Create a Notification when an invite is created (not a request)
CREATE FUNCTION add_notification_invite() RETURN TRIGGER AS
$BODY$
BEGIN
	IF(NEW.user_who_invited_id != NULL)
	THEN INSERT INTO Notification (date,notification_type,user_id,project_id,user_action_id) 
			VALUES (now(),'Invite',NEW.user_invited_id,NEW.project_id,NEW.user_who_invited_id);
	END IF;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER add_notification_invite
	AFTER INSERT ON Invite
	FOR EACH ROW
		EXECUTE PROCEDURE add_notification_invite();



/* INSERTS */
INSERT INTO Administrator (id, username, password) VALUES (1, 'admin', '1234Admin-');

INSERT INTO User (id,name,username,email,image,password) VALUES (1,'Pedro Reis','partelinuxes','pedroreis@gmail.com',NULL,'eunaverdadegostodewindows');
INSERT INTO User (id,name,username,email,image,password) VALUES (2,'Ana Margarida','PortugueseCountryFan','just2playgg@gmail.com',NULL,'asdasdparecemeseguro123');
INSERT INTO User (id,name,username,email,image,password) VALUES (3,'Luis Correia','luigi_darkside','luigi_mei<3@gmail.com',NULL,'passwordprofissional123');
INSERT INTO User (id,name,username,email,image,password) VALUES (4,'Vicente Espinha','vespinha','sdds_do_liedson@gmail.com','http://i.dailymail.co.uk/i/pix/2008/04/01/article-1004361-00A0672B00000578-20_468x321_popup.jpg','queroverosportingcampeao');
INSERT INTO User (id,name,username,email,image,password) VALUES (5,'Marco Silva','Marcus_97','marcus_silva_97@gmail.com',NULL,'1234Marcus-');
INSERT INTO User (id,name,username,email,image,password) VALUES (6,'André Ribeiro','programmer_rib','andre_ribeiro@gmail.com',NULL,'1234Andre-');
INSERT INTO User (id,name,username,email,image,password) VALUES (7,'Diana Salgado','dianne_sal','diana_salgado_2@hotmail.com',NULL,'1234Diana-');
INSERT INTO User (id,name,username,email,image,password) VALUES (8,'Andrew Tanenbaum','minix_lover','tanenbaum@gmail.com','https://pt.wikipedia.org/wiki/Andrew_Stuart_Tanenbaum','1234Andrew-');
INSERT INTO User (id,name,username,email,image,password) VALUES (9,'Linus Torvalds','linux_lover_52','linus_torvalds@gmail.com','https://en.wikipedia.org/wiki/Linus_Torvalds','1234Linus-');
INSERT INTO User (id,name,username,email,image,password) VALUES (10,'Susana Torres','susana_torres_92','susana_torres_92@gmail.com',NULL,'1234Susana-');
INSERT INTO User (id,name,username,email,image,password) VALUES (11,'Diogo Mateus','diogo_76','diogo.mateus@gmail.com',NULL,'1234Diogo-');
INSERT INTO User (id,name,username,email,image,password) VALUES (12,'Adelino Bastos','adele_boy_67','adelino.bastos@gmail.com',NULL,'1234Adelino-');
INSERT INTO User (id,name,username,email,image,password) VALUES (13,'Analisa Correia','anacruza_dacapo','analisa_correia.93@gmail.com',NULL,'1234Analisa-');
INSERT INTO User (id,name,username,email,image,password) VALUES (14,'Madalena Soares','madalena_muffin','madalena_muffin@gmail.com',NULL,'1234Madalena-');
INSERT INTO User (id,name,username,email,image,password) VALUES (15,'Pedro Batista','batista_89','pedro.batista@gmail.com',NULL,'1234Pedro-');
INSERT INTO User (id,name,username,email,image,password) VALUES (16,'Raul Vidal','sejam_felizes','raul.vidal@gmail.com',NULL,'1234Vidal-');
INSERT INTO User (id,name,username,email,image,password) VALUES (17,'Elliot Alderson','Mr_Robot','im_not_mr_robot@gmail.com','https://shiiftyshift.deviantart.com/art/Hackerman-643435212','1234Elliot-');
INSERT INTO User (id,name,username,email,image,password) VALUES (18,'Felix Kjellberg','Pewdiepie','meme_review@gmail.com','https://gfycat.com/gifs/detail/hilariouseagerarmednylonshrimp','1234Pewdiepie-');
INSERT INTO User (id,name,username,email,image,password) VALUES (19,'Helga Smith','helga_93','helga_legit@gmail.com',NULL,'1234Helga-');
INSERT INTO User (id,name,username,email,image,password) VALUES (20,'Jeff Sessions','my_name_jeff','my_name_jeff@gmail.com',NULL,'1234Jeff-');
INSERT INTO User (id,name,username,email,image,password) VALUES (21,'Diogo Dores','d_pain','fortnite@gmail.com',NULL,'fortniteisluv');
INSERT INTO User (id,name,username,email,image,password) VALUES (22,'Ventura Pereira','fcparasempre','vp@gmail.com',NULL,'mourossucc');
INSERT INTO User (id,name,username,email,image,password) VALUES (23,'Miguel Mano','blunky','fantano<3@gmail.com',NULL,'headwasclean');
INSERT INTO User (id,name,username,email,image,password) VALUES (24,'Ze Borges','nbamaster','shaquille@gmail.com',NULL,'memes');
INSERT INTO User (id,name,username,email,image,password) VALUES (25,'Joao Seixas','seixano','seixo@gmail.com',NULL,'-seixas-');
INSERT INTO User (id,name,username,email,image,password) VALUES (26,'Joao Conde','saltyaf','lol@gmail.com',NULL,'iactuallyhatelol');
INSERT INTO User (id,name,username,email,image,password) VALUES (27,'Sofia Silva','aesthetic','blogger@gmail.com',NULL,'sofs');
INSERT INTO User (id,name,username,email,image,password) VALUES (28,'Lil Pump','eskeeetit','gucci_gang@gmail.com',NULL,'fantanolovesme');
INSERT INTO User (id,name,username,email,image,password) VALUES (29,'Jaden Smith','eyes','im_an_icon@gmail.com',NULL,'seriously_im_an_icon-');
INSERT INTO User (id,name,username,email,image,password) VALUES (30,'Mac DeMarco','pepperoni_playboy','blueboy@gmail.com',NULL,'freaking-');

INSERT INTO Project (id,name,description,isPublic) VALUES (1,'Education Through the Web','A web page, made specifically to support students on their quest to learn more efficiently.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (2,'Cryptocurrency applied to auction houses','The rise of cryptocurrency demands that such a profitable business such as online auction houses remain up to date with technology.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (3,'Character design pipeline for a videogame','The struggle of designing a character for 3d in Blender', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (4,'LBAW Project','Developing a entire web site in just a couple of months!', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (5,'CGRA Submarine', 'Building a moving submarine able to shoot torpedos, using WebGL.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (6,'Secure platform for wire transfers','Platform that is totally secure for wire transfers between accounts in the same or different banks. The main focus is security.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (7,'Sigarra Website','New Sigarra Website, one more sensible and with more usability. Also with a mobile site that makes sense.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (8,'Backup Program using distributed systems','System that uses several servers to backup files through the network.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (9,'Open Source Half Life 3','The making of a dream, the highly requested Half Life 3. Will it achieve the high expectations?', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (10,'The Elder Scrolls V : Skyrim Mods','Because we just don\'t have anything to do, and Bethesda doesn\'t stop the exploitation of this game', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (11,'Security Course','Help us build this course on security with your knowledge, so we can inform and teach this important subject to everyone who wants to learn', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (12,'Minix 4','Minix is the best OS ever. This project will build a more complete and secure version, with new features!', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (13,'8-bit Rick Roll', 'A way better version of Never Gonna Give You Up by Rick Astley.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (14,'Price Comparator', 'Developing a platform that provides you the best price from stores around you.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (15,'Minix GUI', 'Crating a graphical interface for the best operating system ever!', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (16,'Paragon 2.0', 'Rebuilding the well-know MOBA Paragon, ditched by Epic Games.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (17,'CGRA Submarine', 'Building a moving submarine able to shoot torpedos, using WebGL.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (18,'To-do List', 'A simple to-do list, for the everyday life. Project developed in NodeJS.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (19,'NECGM website', 'Rebuilding a new website for the NECGM from FEUP, now in NodeJS. New features will be added.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (20,'Fallout New Vegas VR', 'The best fallout game, now in VR. In development by Obsidian Entertainment.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (21,'Heart-rate app', 'Developing an app that allows you to measure your heart-rate, and analyse the data.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (22,'Fakecloud','A platform for music sharing ,similar to Soundcloud, but only covers are allowed.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (23,'FCPStream', 'Creating a streaming platform broadcasting matches and info about Futebol Clube do Porto.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (24,'Krita Mobile', 'An open-source mobile port of the designing tool, Krita.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (25,'Musebot', 'Open-source project for a music streaming bot for Discord.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (26,'Fashion platform', 'A platform to help you find the best deals in fashion retail.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (27,'NodeJS guide', 'Building a complete guide for NodeJS enthusiasts.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (28,'WebGL Water Asset', 'Open-source asset that emulates water physics', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (29,'Civ V Mods', 'Open-source mods for Civilization V, bringing some exciting features to the game.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (30,'Facial expression analyser','Software that allows to figure the mood of a person based on facial expressions.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (31,'Daily meme bot','A web bot that posts a meme everyday for your amusement', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (32,'Sports stats app','Developing an app that delivers the most reliable data about statitics in every sport', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (33,'Meme reviewer platform','A website to review the freshest memes and rate them!', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (34,'NoFakeNews', 'A web platform that delivers the most unbiased and reliable news', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (35,'PReis','Developing a high-performance linux distro that aims to be beautiful. Can also be used for gaming!', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (36,'Pizza4All','An app created to increase the number of propotionally sliced pizzas', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (37,'Samurai Souls','Dark souls...but with samurais!!', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (38,'Rambox', 'An app that has all your message apps.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (39,'Vinyl Finder', 'A platform for finding the best deals in vinyl records.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (40,'LaterX','A open-source laTEX editor. Made for students in a rush!', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (41,'Education Through the Web','A web page, made specifically to support students on their quest to learn more efficiently.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (42,'Cryptocurrency applied to auction houses','The rise of cryptocurrency demands that such a profitable business such as online auction houses remain up to date with technology.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (43,'Character design pipeline for a videogame','The struggle of designing a character for 3d in Blender', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (44,'LBAW Project','Developing a entire web site in just a couple of months!', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (45,'CGRA Submarine', 'Building a moving submarine able to shoot torpedos, using WebGL.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (46,'Secure platform for wire transfers','Platform that is totally secure for wire transfers between accounts in the same or different banks. The main focus is security.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (47,'Sigarra Website','New Sigarra Website, one more sensible and with more usability. Also with a mobile site that makes sense.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (48,'Backup Program using distributed systems','System that uses several servers to backup files through the network.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (49,'Open Source Half Life 3','The making of a dream, the highly requested Half Life 3. Will it achieve the high expectations?', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (50,'The Elder Scrolls V : Skyrim Mods','Because we just don\'t have anything to do, and Bethesda doesn\'t stop the exploitation of this game', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (51,'Security Course','Help us build this course on security with your knowledge, so we can inform and teach this important subject to everyone who wants to learn', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (52,'Minix 4','Minix is the best OS ever. This project will build a more complete and secure version, with new features!', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (53,'8-bit Rick Roll', 'A way better version of Never Gonna Give You Up by Rick Astley.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (54,'Price Comparator', 'Developing a platform that provides you the best price from stores around you.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (55,'Minix GUI', 'Crating a graphical interface for the best operating system ever!', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (56,'Paragon 2.0', 'Rebuilding the well-know MOBA Paragon, ditched by Epic Games.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (57,'CGRA Submarine', 'Building a moving submarine able to shoot torpedos, using WebGL.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (58,'To-do List', 'A simple to-do list, for the everyday life. Project developed in NodeJS.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (59,'NECGM website', 'Rebuilding a new website for the NECGM from FEUP, now in NodeJS. New features will be added.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (60,'Fallout New Vegas VR', 'The best fallout game, now in VR. In development by Obsidian Entertainment.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (61,'Heart-rate app', 'Developing an app that allows you to measure your heart-rate, and analyse the data.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (62,'Fakecloud','A platform for music sharing ,similar to Soundcloud, but only covers are allowed.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (63,'FCPStream', 'Creating a streaming platform broadcasting matches and info about Futebol Clube do Porto.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (64,'Krita Mobile', 'An open-source mobile port of the designing tool, Krita.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (65,'Musebot', 'Open-source project for a music streaming bot for Discord.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (66,'Fashion platform', 'A platform to help you find the best deals in fashion retail.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (67,'NodeJS guide', 'Building a complete guide for NodeJS enthusiasts.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (68,'WebGL Water Asset', 'Open-source asset that emulates water physics', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (69,'Civ V Mods', 'Open-source mods for Civilization V, bringing some exciting features to the game.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (70,'Facial expression analyser','Software that allows to figure the mood of a person based on facial expressions.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (71,'Daily meme bot','A web bot that posts a meme everyday for your amusement', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (72,'Sports stats app','Developing an app that delivers the most reliable data about statitics in every sport', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (73,'Meme reviewer platform','A website to review the freshest memes and rate them!', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (74,'NoFakeNews', 'A web platform that delivers the most unbiased and reliable news', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (75,'PReis','Developing a high-performance linux distro that aims to be beautiful. Can also be used for gaming!', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (76,'Pizza4All','An app created to increase the number of propotionally sliced pizzas', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (77,'Samurai Souls','Dark souls...but with samurais!!', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (78,'Rambox', 'An app that has all your message apps.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (79,'Vinyl Finder', 'A platform for finding the best deals in vinyl records.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (80,'LaterX','A open-source laTEX editor. Made for students in a rush!', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (81,'Education Through the Web','A web page, made specifically to support students on their quest to learn more efficiently.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (82,'Cryptocurrency applied to auction houses','The rise of cryptocurrency demands that such a profitable business such as online auction houses remain up to date with technology.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (83,'Character design pipeline for a videogame','The struggle of designing a character for 3d in Blender', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (84,'LBAW Project','Developing a entire web site in just a couple of months!', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (85,'Security Course','Help us build this course on security with your knowledge, so we can inform and teach this important subject to everyone who wants to learn', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (86,'Secure platform for wire transfers','Platform that is totally secure for wire transfers between accounts in the same or different banks. The main focus is security.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (87,'CGRA Submarine', 'Building a moving submarine able to shoot torpedos, using WebGL.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (88,'Sigarra Website','New Sigarra Website, one more sensible and with more usability. Also with a mobile site that makes sense.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (89,'Backup Program using distributed systems','System that uses several servers to backup files through the network.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (90,'Open Source Half Life 3','The making of a dream, the highly requested Half Life 3. Will it achieve the high expectations?', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (91,'The Elder Scrolls V : Skyrim Mods','Because we just don\'t have anything to do, and Bethesda doesn\'t stop the exploitation of this game', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (92,'Minix 4','Minix is the best OS ever. This project will build a more complete and secure version, with new features!', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (93,'8-bit Rick Roll', 'A way better version of Never Gonna Give You Up by Rick Astley.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (94,'Price Comparator', 'Developing a platform that provides you the best price from stores around you.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (95,'Minix GUI', 'Crating a graphical interface for the best operating system ever!', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (96,'Paragon 2.0', 'Rebuilding the well-know MOBA Paragon, ditched by Epic Games.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (97,'CGRA Submarine', 'Building a moving submarine able to shoot torpedos, using WebGL.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (98,'To-do List', 'A simple to-do list, for the everyday life. Project developed in NodeJS.', TRUE);
INSERT INTO Project (id,name,description,isPublic) VALUES (99,'NECGM website', 'Rebuilding a new website for the NECGM from FEUP, now in NodeJS. New features will be added.', FALSE);
INSERT INTO Project (id,name,description,isPublic) VALUES (100,'Fallout New Vegas VR', 'The best fallout game, now in VR. In development by Obsidian Entertainment.', FALSE);

INSERT INTO Category (id, name) VALUES (1,'Entertainment');
INSERT INTO Category (id, name) VALUES (3,'Productivity');
INSERT INTO Category (id, name) VALUES (4,'Software');
INSERT INTO Category (id, name) VALUES (5,'Application');
INSERT INTO Category (id, name) VALUES (6,'Education');
INSERT INTO Category (id, name) VALUES (7,'Business');
INSERT INTO Category (id, name) VALUES (8,'Web');
INSERT INTO Category (id, name) VALUES (9,'Game');
INSERT INTO Category (id, name) VALUES (10,'Open Source');
INSERT INTO Category (id, name) VALUES (11,'Graphic');
INSERT INTO Category (id, name) VALUES (12,'Design');
INSERT INTO Category (id, name) VALUES (13,'Sports');
INSERT INTO Category (id, name) VALUES (14,'Music');
INSERT INTO Category (id, name) VALUES (15,'Financial');
INSERT INTO Category (id, name) VALUES (16,'Medical');
INSERT INTO Category (id, name) VALUES (17,'Information');
INSERT INTO Category (id, name) VALUES (18,'Lifestyle');
INSERT INTO Category (id, name) VALUES (19,'Shopping');
INSERT INTO Category (id, name) VALUES (20,'Social Networking');
INSERT INTO Category (id, name) VALUES (21,'Multimedia');



INSERT INTO Project_categories (project_id, category_id) VALUES (1,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (1,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (2,7);
INSERT INTO Project_categories (project_id, category_id) VALUES (2,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (3,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (3,3);
INSERT INTO Project_categories (project_id, category_id) VALUES (4,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (6,7);
INSERT INTO Project_categories (project_id, category_id) VALUES (7,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (6,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (7,8);
INSERT INTO Project_categories (project_id, category_id) VALUES (8,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (9,9);
INSERT INTO Project_categories (project_id, category_id) VALUES (9,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (10,9);
INSERT INTO Project_categories (project_id, category_id) VALUES (10,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (9,10);
INSERT INTO Project_categories (project_id, category_id) VALUES (11,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (12,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (13,14);
INSERT INTO Project_categories (project_id, category_id) VALUES (14,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (15,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (16,7);
INSERT INTO Project_categories (project_id, category_id) VALUES (17,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (18,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (19,3);
INSERT INTO Project_categories (project_id, category_id) VALUES (20,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (21,7);
INSERT INTO Project_categories (project_id, category_id) VALUES (22,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (23,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (24,8);
INSERT INTO Project_categories (project_id, category_id) VALUES (25,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (26,9);
INSERT INTO Project_categories (project_id, category_id) VALUES (27,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (28,9);
INSERT INTO Project_categories (project_id, category_id) VALUES (29,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (30,10);
INSERT INTO Project_categories (project_id, category_id) VALUES (31,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (32,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (32,14);
INSERT INTO Project_categories (project_id, category_id) VALUES (33,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (33,14);
INSERT INTO Project_categories (project_id, category_id) VALUES (34,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (35,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (36,7);
INSERT INTO Project_categories (project_id, category_id) VALUES (37,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (38,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (39,3);
INSERT INTO Project_categories (project_id, category_id) VALUES (40,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (41,7);
INSERT INTO Project_categories (project_id, category_id) VALUES (42,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (43,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (44,8);
INSERT INTO Project_categories (project_id, category_id) VALUES (45,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (46,9);
INSERT INTO Project_categories (project_id, category_id) VALUES (47,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (48,9);
INSERT INTO Project_categories (project_id, category_id) VALUES (49,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (50,10);
INSERT INTO Project_categories (project_id, category_id) VALUES (51,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (52,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (52,14);
INSERT INTO Project_categories (project_id, category_id) VALUES (53,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (53,14);
INSERT INTO Project_categories (project_id, category_id) VALUES (54,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (55,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (56,7);
INSERT INTO Project_categories (project_id, category_id) VALUES (57,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (58,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (59,3);
INSERT INTO Project_categories (project_id, category_id) VALUES (60,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (61,7);
INSERT INTO Project_categories (project_id, category_id) VALUES (62,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (63,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (64,8);
INSERT INTO Project_categories (project_id, category_id) VALUES (65,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (66,9);
INSERT INTO Project_categories (project_id, category_id) VALUES (67,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (68,9);
INSERT INTO Project_categories (project_id, category_id) VALUES (69,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (60,10);
INSERT INTO Project_categories (project_id, category_id) VALUES (61,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (62,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (62,14);
INSERT INTO Project_categories (project_id, category_id) VALUES (63,14);
INSERT INTO Project_categories (project_id, category_id) VALUES (64,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (65,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (66,7);
INSERT INTO Project_categories (project_id, category_id) VALUES (67,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (68,11);
INSERT INTO Project_categories (project_id, category_id) VALUES (69,3);
INSERT INTO Project_categories (project_id, category_id) VALUES (70,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (71,17);
INSERT INTO Project_categories (project_id, category_id) VALUES (72,14);
INSERT INTO Project_categories (project_id, category_id) VALUES (73,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (74,8);
INSERT INTO Project_categories (project_id, category_id) VALUES (75,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (76,9);
INSERT INTO Project_categories (project_id, category_id) VALUES (77,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (78,9);
INSERT INTO Project_categories (project_id, category_id) VALUES (79,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (80,10);
INSERT INTO Project_categories (project_id, category_id) VALUES (81,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (82,16);
INSERT INTO Project_categories (project_id, category_id) VALUES (82,14);
INSERT INTO Project_categories (project_id, category_id) VALUES (83,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (83,14);
INSERT INTO Project_categories (project_id, category_id) VALUES (84,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (85,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (86,7);
INSERT INTO Project_categories (project_id, category_id) VALUES (87,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (88,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (89,16);
INSERT INTO Project_categories (project_id, category_id) VALUES (90,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (91,7);
INSERT INTO Project_categories (project_id, category_id) VALUES (92,6);
INSERT INTO Project_categories (project_id, category_id) VALUES (93,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (94,18);
INSERT INTO Project_categories (project_id, category_id) VALUES (95,4);
INSERT INTO Project_categories (project_id, category_id) VALUES (96,21);
INSERT INTO Project_categories (project_id, category_id) VALUES (97,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (98,9);
INSERT INTO Project_categories (project_id, category_id) VALUES (99,1);
INSERT INTO Project_categories (project_id, category_id) VALUES (100,10);



INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (1, now(), 1, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (2, now(), 1, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (2, now(), 2, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (3, now(), 3, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (4, now(), 3, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (1, now(), 4, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (2, now(), 4, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (3, now(), 4, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (4, now(), 4, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (8, now(), 12, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (9, now(), 12, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (7, now(), 12, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (6, now(), 7, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (16, now(), 12, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (12, now(), 6, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (6, now(), 6, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (17, now(), 11, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (19, now(), 11, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (18, now(), 9, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (1, now(), 9, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (2, now(), 11, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (11, now(), 8, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (20, now(), 10, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (8, now(), 9, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (15, now(), 7, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (5, now(), 12, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (13, now(), 12, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (10, now(), 6, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (9, now(), 6, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (12, now(), 7, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (11, now(), 7, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (14, now(), 8, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (15, now(), 8, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (17, now(), 10, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (18, now(), 10, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (3, now(), 10, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (6, now(), 12, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (14, now(), 12, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (17, now(), 2, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (6, now(), 2, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (11, now(), 3, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (1, now(), 3, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (7, now(), 4, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (18, now(), 4, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (16, now(), 2, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (1, now(), 14, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (2, now(), 15, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (2, now(), 16, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (3, now(), 17, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (4, now(), 18, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (1, now(), 19, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (2, now(), 20, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (3, now(), 21, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (4, now(), 22, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (8, now(), 23, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (9, now(), 24, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (7, now(), 25, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (6, now(), 26, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (16, now(), 27, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (12, now(), 28, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (6, now(), 29, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (17, now(), 30, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (19, now(), 31, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (18, now(), 32, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (1, now(), 33, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (2, now(), 34, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (11, now(), 35, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (20, now(), 36, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (8, now(), 37, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (15, now(), 38, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (5, now(), 39, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (13, now(), 40, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (10, now(), 41, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (9, now(), 42, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (12, now(), 43, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (11, now(), 44, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (14, now(), 45, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (15, now(), 46, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (17, now(), 47, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (18, now(), 48, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (3, now(), 49, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (6, now(), 50, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (14, now(), 51, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (17, now(), 52, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (6, now(), 53, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (11, now(), 54, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (1, now(), 55, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (7, now(),56, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (18, now(), 58, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (16, now(), 59, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (16, now(), 60, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (3, now(), 61, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (4, now(), 62, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (8, now(), 63, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (9, now(), 64, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (7, now(), 65, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (6, now(), 66, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (16, now(),67, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (12, now(), 68, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (6, now(), 69, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (17, now(), 70, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (19, now(), 71, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (18, now(), 72, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (1, now(), 73, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (2, now(), 74, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (11, now(), 75, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (20, now(), 76, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (8, now(), 77, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (15, now(), 78, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (5, now(), 79, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (13, now(), 80, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (10, now(), 81, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (9, now(), 82, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (12, now(), 83, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (11, now(), 84, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (14, now(), 85, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (15, now(), 86, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (17, now(), 87, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (18, now(), 88, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (3, now(), 89, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (6, now(), 90, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (14, now(), 91, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (17, now(), 92, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (6, now(), 93, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (11, now(), 94, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (1, now(), 95, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (7, now(),96, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (18, now(), 98, FALSE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (16, now(), 99, TRUE);
INSERT INTO Project_members (user_id, 'date', project_id, isCoordinator) VALUES (16, now(), 100, TRUE);


INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (1,'Could there be a section about Programming?','I think we are focusing more on mathematics and programming is being left out. It is an interesting subject and very useful these days!', now(),1,2);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (2,'I think I broke the project....oopsie!','Ah...guys, it ain\'t working! Could someone fix this please!?\n*screeching*', now(),4,18);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (3,'Another game with a game with a female lead character....boring!','Guys, come on! Not again! I know it is a trend, but why not vary and make, for example, a game with several principal characters, where you can play with different characters, both in gender but also in race. This game could do it, the story allows it!', now(),3,4);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (4,'I don\'t know...will this really work?','Will it be really possible to make this game? It is HL3 and, well, is open source. By the way, isn\'t it kinda illegal? Doesn\'t Valve has the rights to this?\nJust saying...', now(),9,18);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (5,'I have a great idea!','Let\'s make the character like Geralt of Witcher 3 and the dragons will be Roach! Ah, hilarious!\nMy name\'s Jeff!	', now(),10,20);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (6,'Did you know?	','Linux is kinda based on Minix...well not really, but first I wanted to improve Minix features but Andrew didn\'t wanted me to, so I based some of Linux in Minix... but I changed lots of things, of course!	', now(),12,9);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (7,'Witcher 3 quest!','Could someone give some hints about where i can find cedaline in witcher 3?', now(),67,12);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (8,'I have a great idea!','Let\'s make the character like Geralt of Witcher 3 and the dragons will be Roach! Ah, hilarious!\nMy name\'s Jeff!	', now(),10,20);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (9,'Did you know?	','Linux is kinda based on Minix...well not really, but first I wanted to improve Minix features but Andrew didn\'t wanted me to, so I based some of Linux in Minix... but I changed lots of things, of course!	', now(),12,9);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (10,'Witcher 3 quest!','Could someone give some hints about where i can find cedaline in witcher 3?', now(),90,2);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (11,'Could there be a section about Programming?','I think we are focusing more on mathematics and programming is being left out. It is an interesting subject and very useful these days!', now(),1,2);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (12,'I think I broke the project....oopsie!','Ah...guys, it ain\'t working! Could someone fix this please!?\n*screeching*', now(),4,18);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (13,'Another game with a game with a female lead character....boring!','Guys, come on! Not again! I know it is a trend, but why not vary and make, for example, a game with several principal characters, where you can play with different characters, both in gender but also in race. This game could do it, the story allows it!', now(),3,4);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (14,'I don\'t know...will this really work?','Will it be really possible to make this game? It is HL3 and, well, is open source. By the way, isn\'t it kinda illegal? Doesn\'t Valve has the rights to this?\nJust saying...', now(),9,18);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (15,'I have a great idea!','Let\'s make the character like Geralt of Witcher 3 and the dragons will be Roach! Ah, hilarious!\nMy name\'s Jeff!	', now(),10,20);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (16,'Did you know?	','Linux is kinda based on Minix...well not really, but first I wanted to improve Minix features but Andrew didn\'t wanted me to, so I based some of Linux in Minix... but I changed lots of things, of course!	', now(),12,9);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (17,'Witcher 3 quest!','Could someone give some hints about where i can find cedaline in witcher 3?', now(),33,3);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (18,'I have a great idea!','Let\'s make the character like Geralt of Witcher 3 and the dragons will be Roach! Ah, hilarious!\nMy name\'s Jeff!	', now(),10,20);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (19,'Did you know?	','Linux is kinda based on Minix...well not really, but first I wanted to improve Minix features but Andrew didn\'t wanted me to, so I based some of Linux in Minix... but I changed lots of things, of course!	', now(),12,9);
INSERT INTO Thread (id,name,description,'date',project_id,user_creator_id) VALUES (20,'Witcher 3 quest!','Could someone give some hints about where i can find cedaline in witcher 3?', now(),78,1);


INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (2,'Mock-Ups','2018-05-20 00:00:00+01',1,2,5);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (3,'Database structure','2018-05-20 00:00:00+01',1,2,3);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (4,'Website','2018-04-20 12:00:00+01',2,16,5);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (5,'Build Security','2018-05-20 08:00:00+01',2,16,5);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (6,'Draw Mock-up','2018-04-12 23:59:00+01',3,11,3);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (7,'Design with blender','2018-04-20 22:59:00+01',3,1,7);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (8,'Database','2018-04-01 23:00:00+01',4,3,7);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (9,'Make Website','2018-05-21 23:00:00+01',4,2,10);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (10,'Mobile App','2018-05-20 23:00:00+01',6,6,10);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (11,'Security Verifications','2018-05-25 23:00:00+01',6,6,8);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (12,'Mock-Ups','2018-05-20 23:00:00+01',7,6,7);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (13,'Security','2018-05-30 23:00:00+01',7,6,7);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (14,'Client RMI','2018-04-29 23:00:00+01',8,11,7);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (15,'Communications between servers','2018-05-02 23:00:00+01',8,11,8);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (16,'Write history','2018-05-20 23:00:00+01',9,1,6);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (17,'Draw characters','2018-05-20 00:00:00+01',9,1,8);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (18,'Decide Improvements','2018-04-18 23:00:00+01',10,18,5);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (19,'Make models 3D','2018-04-30 23:00:00+01',10,17,10);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (20,'Design Course Program','2018-04-18 23:00:00+01',11,17,3);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (21,'Introduction','2018-04-22 23:00:00+01',11,17,5);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (22,'Decide Improvements','2018-04-18 23:00:00+01',12,8,3);
INSERT INTO Sprint (id,name,deadline,project_id,user_creator_id,effort) VALUES (22,'Kernel','2018-04-30 23:00:00+01',12,8,20);

INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (1,'Index Page','Make a responsive mock up of the index page, with tonalities of blue and gold. Images will be added next',1,1,2);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (2,'Video Page','Responsive page to allocate many videos',2,1,2);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (3,'Basic database','Solid structure of basic database to support video',1,1,3);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (4,'Security','Implement mechanism to prevent SQL Injections',2,1,3);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (5,'Database','Solid and secure database',2,2,4);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (6,'Transfer Page','',2,2,4);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (7,'Cross-Site Scripting Security','Implement mechanism to prevent XSS',2,2,5);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (8,'Cross-Site Request Forgery','Implement mechanism to prevent CSRF',2,2,5);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (9,'Make principal character','Female, long dark hair, blue jeans and flannel shirt, nerdy look',1,3,6);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (10,'Villain character','Guy, normal person, glasses and with a trustworthy expression',1,3,6);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (11,'Sidekick character','Flashy character, guy, always smiling and with a funny haircut and style.',1,3,6);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (12,'Basic design','',3,3,7);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (13,'Animations','Walking, jumping, rolling',4,3,7);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (14,'Populate','At least 25 tasks',3,4,8);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (15,'Make queries','To all the tables',2,4,8);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (16,'Triggers','',1,4,8);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (17,'Project Page','Use AJAX to switch between the possible pages of the Project page.\nMake animations fluid and natural.',6,4,9);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (18,'Resolve bug on the forum page','CSS and Javascript bug, doesn\'t show information about the date because it is cut off, and the date is wrongly calculated',2,4,9);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (19,'Put in Google Play','Share the application in Google Play',1,6,10);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (20,'Connect with several banks','Get agreements with several banks to access to their platform.',2,6,10);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (21,'Security','Make the mobile app secure',4,6,10);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (22,'Hire company specialized in security','',2,6,11);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (23,'Design Index Page','Make a pleasant and informative index page',4,7,12);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (24,'Make responsive to mobile devices','',3,7,12);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (25,'XXS security','',2,7,13);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (26,'CSRF Security','',2,7,13);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (27,'SQL Injections Verification','Very important verification!',2,7,13);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (28,'Make reference to the registry','Don\'t forget to use the right instructions, here:\nhttps://docs.oracle.com/javase/tutorial/rmi/client.html',2,8,14);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (29,'Code','',4,8,14);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (30,'Create multicast channels to every socket used','Don\'t forget to join by group and use different IPs to each socket',2,8,15);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (31,'Concurrent Mechanism','Don\'t forget to check the replicationDegree and send only to that number of servers. Check if the stored messages are received, and in their correct number.\nAlso, it has to be possible to process several requests at once!\nUse threads and/or threadPools!',5,8,15);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (32,'Main Quest','It has to start where the previous one has ended',2,9,16);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (33,'Write 3 side-quests','Have to be at least 45min long',3,9,16);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (34,'Principal Character - Gordon Freeman','Keep it close to the original one',2,9,17);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (35,'G-Man','Keep it mysterious',2,9,17);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (36,'Current Meme incorporation','What meme to use in this mod?',1,10,18);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (37,'Decision to make this a serious or a stupid mod','',2,10,18);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (38,'Chicken Model','Yap, a chicken model, we are going with that',2,10,19);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (39,'Decide number of chapters','',1,11,20);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (40,'Pen Testing?','Is it possible to make a chapter about this one, and an extensive one?',1,11,20);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (41,'Introduce yourself and the course','Explain who you are, what you do for a living and your motivations.\nExplain what are the objectives of the course, the resources needed and the degree of difficulty.',1,11,21);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (42,'Course mapping','Explain the different topics that will be covered, as well as their importance.',1,11,21);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (43,'Write in the comments bellow your opinion','',1,12,22);
INSERT INTO Task (id,name,description,effort,project_id,sprint_id) VALUES (44,'Rewrite function about sound drivers','This function contains a bug with specific sound cards',4,12,23);

INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (1,'There will be a part of the website that will focus totally on Programming but, for now, it is more imperative that we finish the Mathematics chapters.',now(),1,NULL,1);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (2,'Ah, I didn\'t know! Thank you!',now(),2,NULL,1);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (3,'Oh man, not again! I will see what is broke then, but please say something before you go there. I don\'t know what you do, but you have a knack for breaking websites!',now(),3,NULL,2);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (4,'I agree with him, it makes total sense! It is a history similar to Doctor Who, we have the material to make it like it.',now(),3,NULL,3);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (5,'Okay, we will see about it! For now keep working on the character chosen, and we will see about changing the history.\n\nThank you for the suggestion!',now(),11,NULL,3);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (6,'Well, we won\'t gain money from this, so I guess it is legal...ah, right?',now(),1,NULL,4);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (7,'In my opinion, that is an awful idea. It doesn\'t make any sense whatsoever!',now(),17,NULL,5);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (8,'I kinda like it!',now(),18,NULL,5);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (9,'Why are you always telling this story? Everyone knows it!',now(),8,NULL,6);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (10,'It is an interesting fact',now(),9,NULL,6);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (11,'Is it possible for someone to give more detailed points about this one?',now(),3,10,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (12,'The point is for the villain to be like a normal person, like a friendly neighbor or a friendly coworker',now(),11,10,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (13,'Is it only related to checking if a member is a coordinator or team member when doing some type of action?',now(),7,16,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (14,'Not only, but also checking if a value of effort on a sprint is exceeded by its tasks.',now(),2,16,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (15,'Well, of course it would be this',now(),3,38,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (16,'I would like to do this one',now(),2,7,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (17,'I think this one isn\'t really a Javascript bug but a PHP error...there isn\'t any way of showing the date in the php file',now(),7,18,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (18,'I can do this one, I have experience with security. It helps to save money',now(),10,22,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (19,'Isn\'t there an easier way of doing this? It is a lot of work',now(),18,14,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (20,'I don\'t think so, this is the only way',now(),3,14,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (21,'It would help if there were more than one person doing this',now(),2,14,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (22,'I\'ll help has well!',now(),1,14,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (23,'Can it use glasses or some kind of googles?',now(),4,11,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (24,'Sure, any suggestion can be done',now(),11,11,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (25,'I\'ve done this, but it isn\'t working. Can someone help?',now(),14,30,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (26,'I think I know how...I think you are forgetting to create e InetAddress and are passing only a string with the address.',now(),15,30,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (27,'You\'re right, thanks!',now(),14,30,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (28,'Yes! I think that kind of content would be very important! If there isn\'t any problem, I would like to do it',now(),19,40,NULL);
INSERT INTO Comment (id,content,'date',user_id,task_id,thread_id) VALUES (29,'Of course!',now(),17,40,NULL);