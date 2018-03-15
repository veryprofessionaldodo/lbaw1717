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

ALTER TABLE ONLY Task
	ADD CONSTRAINT task_id_user_fkey FOREIGN KEY (creator_id) REFERENCES User(id) ON UPDATE CASCADE; /* On update? */ 