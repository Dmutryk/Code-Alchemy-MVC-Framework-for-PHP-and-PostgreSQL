create sequence __table_name___id_seq start with 1;

CREATE TABLE __table_name__ (
  __table_name___id INTEGER PRIMARY KEY NOT NULL DEFAULT nextval('__table_name___id_seq'::regclass),
  sortable_id integer null,
  uuid UUID NOT NULL DEFAULT uuid_in((md5(((random())::text || (now())::text)))::cstring),
  website_page_id integer NOT NULL,
  website_image_id integer,
  name varchar(100) NOT NULL,
  seo_name varchar(100),
  title varchar(100),
  caption varchar(255),
  description text,
  button_label varchar(30),
  button_href varchar(30),
  css_classes varchar(100),
  handlebars_template text,
  created_date timestamp without time zone not null default now(),
  created_by integer null,
  last_modified_date TIMESTAMP WITHOUT TIME ZONE,
  last_modified_by integer null,
  FOREIGN KEY (website_page_id) REFERENCES "website_page" (website_page_id)
  MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION,
  FOREIGN KEY (website_image_id) REFERENCES "website_image" (website_image_id)
  MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION

);

CREATE UNIQUE INDEX __table_name___name_uindex ON __table_name__ USING BTREE (name);
CREATE UNIQUE INDEX __table_name___id_key ON __table_name__ USING BTREE (__table_name___id);

ALTER TABLE __table_name__ ADD CONSTRAINT __table_name___created_by_fk
FOREIGN KEY (created_by) REFERENCES "user" (user_id);

ALTER TABLE __table_name__ ADD CONSTRAINT __table_name___last_modified_by_fk
FOREIGN KEY (last_modified_by) REFERENCES "user" (user_id);

