create sequence user_id_seq start with 1;

CREATE TABLE "user" (
  user_id INTEGER PRIMARY KEY NOT NULL DEFAULT nextval('user_id_seq'::regclass),
  email CHARACTER VARYING(100) NOT NULL,
  uuid UUID NOT NULL DEFAULT uuid_in((md5(((random())::text || (now())::text)))::cstring),
  last_modified_date TIMESTAMP WITHOUT TIME ZONE,
  last_modified_by INTEGER NULL,
  first_name CHARACTER VARYING(50),
  last_name CHARACTER VARYING(50),
  avatar CHARACTER VARYING(255) DEFAULT 'http://thesocietypages.org/socimages/files/2009/05/hotmail.png'::character varying,
  password CHARACTER VARYING(255),
  salt CHARACTER VARYING(255),
  created_date TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT now(),
  last_login_date TIMESTAMP WITHOUT TIME ZONE,
  num_logins INTEGER NOT NULL DEFAULT 0,
  ckey CHARACTER VARYING(50),
  ctime CHARACTER VARYING(50),
  telephone CHARACTER VARYING(16)
);
CREATE UNIQUE INDEX idx_unique_email ON "user" USING BTREE (email);
CREATE UNIQUE INDEX uq_user_id ON "user" USING BTREE (user_id);

ALTER TABLE "__table_name__" ADD CONSTRAINT __table_name__last_modified_by_fk
FOREIGN KEY (last_modified_by) REFERENCES "user" (user_id);
