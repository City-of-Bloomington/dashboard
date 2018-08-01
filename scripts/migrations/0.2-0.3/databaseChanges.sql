alter table cards add disabled tinyint(1) unsigned not null default 0;
alter table cards modify name varchar(64) not null;
