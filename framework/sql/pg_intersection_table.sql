create sequence __table_name___id_seq start with 1;

CREATE TABLE __table_name__ (
  __table_name___id INTEGER PRIMARY KEY NOT NULL DEFAULT nextval('__table_name___id_seq'::regclass),
  sortable_id integer null,
  uuid UUID NOT NULL DEFAULT uuid_in((md5(((random())::text || (now())::text)))::cstring),
  __model1_name___id INTEGER NOT NULL,
  __model2_name___id INTEGER NOT NULL,
  --PLACEHOLDER
    created_date timestamp without time zone not null default now(),
  created_by integer null,
  last_modified_date TIMESTAMP WITHOUT TIME ZONE,
  last_modified_by integer null,

  FOREIGN KEY (__model1_name___id) REFERENCES "__model1_name__" (__model1_name___id)
  MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION,
  FOREIGN KEY (__model2_name___id) REFERENCES "__model2_name__" (__model2_name___id)
  
  MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION
);

CREATE UNIQUE INDEX __table_name___id_key ON __table_name__ USING BTREE (__table_name___id);

CREATE UNIQUE INDEX fk___table_name_____model1_name__ ON __table_name__ USING BTREE (__model1_name___id, __model2_name___id);

ALTER TABLE __table_name__ ADD CONSTRAINT __table_name___created_by_fk
FOREIGN KEY (created_by) REFERENCES "user" (user_id);

ALTER TABLE __table_name__ ADD CONSTRAINT __table_name___last_modified_by_fk
FOREIGN KEY (last_modified_by) REFERENCES "user" (user_id);
