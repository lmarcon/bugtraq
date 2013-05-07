create table developers (
    id_dev serial NOT NULL,
    name varchar,
    surname varchar,
    email varchar,
    pwd char(32)
) with oids;

create table projects (
    id_project serial not null,
    creation_date char(12),
    name varchar,
    id_dev integer,
    description text
) with oids;

CREATE TABLE bugz (
    id serial NOT NULL,
    id_project integer,
    id_dev integer,
    dataora character(12),
    status character varying,
    title text,
    description text,
    priorita character varying,
    lastupdate character(12),
    deadline character(8),
    completed char(3), -- percent
    eta integer, -- gg/uomo
    tipo_intervento varchar,
    risoluzione varchar
) WITH OIDS;

CREATE TABLE commentz (
    id_c serial NOT NULL,
    id integer,
    id_dev integer,
    dataora character(12),
    "comment" text
) WITH OIDS;

create table bug_dev (
    id_dev integer,
    id integer
) with oids;
