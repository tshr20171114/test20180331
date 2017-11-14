CREATE TABLE t_contents
(
 uri character varying(512) NOT NULL PRIMARY KEY,
 title text NOT NULL,
 thumbnail character varying(512) NOT NULL,
 time character varying(6) NOT NULL,
 create_time timestamp DEFAULT localtimestamp NOT NULL,
 page character varying(6) NOT NULL
);

CREATE TABLE m_words
(
 word character varying(256) NOT NULL PRIMARY KEY,
 type integer
);
