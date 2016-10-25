create sequence __table_name___id_seq start with 1;

CREATE TABLE __table_name__ (
  __table_name___id INTEGER PRIMARY KEY NOT NULL DEFAULT nextval('__table_name___id_seq'::regclass),
  sortable_id integer null,
  uuid UUID NOT NULL DEFAULT uuid_in((md5(((random())::text || (now())::text)))::cstring),
  full_name CHARACTER VARYING(50) NOT NULL,
  email varchar(50) not null,
  subject varchar(255),
  message text not null,
  is_sent boolean not null default false,
  send_error varchar(100),
  sent_date timestamp without time zone,
  created_date timestamp without time zone not null default now(),
  created_by integer null,
  last_modified_date TIMESTAMP WITHOUT TIME ZONE,
  last_modified_by integer null
);
CREATE UNIQUE INDEX __table_name___id ON __table_name__ USING BTREE (__table_name___id);

ALTER TABLE __table_name__ ADD CONSTRAINT __table_name___created_by_fk
FOREIGN KEY (created_by) REFERENCES "user" (user_id);

ALTER TABLE __table_name__ ADD CONSTRAINT __table_name___last_modified_by_fk
FOREIGN KEY (last_modified_by) REFERENCES "user" (user_id);