create sequence __table_name___id_seq start with 1;

CREATE TABLE __table_name__ (
  __table_name___id INTEGER PRIMARY KEY NOT NULL DEFAULT nextval('__table_name___id_seq'::regclass),
  sortable_id integer null,
  uuid UUID NOT NULL DEFAULT uuid_in((md5(((random())::text || (now())::text)))::cstring),
  name CHARACTER VARYING(50) NOT NULL,
  seo_name CHARACTER VARYING(50),
  image_filename varchar(100),
  title varchar(100) NOT NULL,
  description text,
  created_date timestamp without time zone not null default now(),
  created_by integer null,
  last_modified_date TIMESTAMP WITHOUT TIME ZONE,
  last_modified_by integer null
);
CREATE UNIQUE INDEX __table_name___name_uindex ON __table_name__ USING BTREE (name);
CREATE UNIQUE INDEX __table_name___id ON __table_name__ USING BTREE (__table_name___id);

ALTER TABLE __table_name__ ADD CONSTRAINT __table_name___created_by_fk
FOREIGN KEY (created_by) REFERENCES "user" (user_id);

ALTER TABLE __table_name__ ADD CONSTRAINT __table_name___last_modified_by_fk
FOREIGN KEY (last_modified_by) REFERENCES "user" (user_id);