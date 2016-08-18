-- @copyright 2016 City of Bloomington, Indiana
-- @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
create table people (
	id int unsigned not null primary key auto_increment,
	firstname varchar(128) not null,
	lastname  varchar(128) not null,
	email     varchar(255) not null,
	username  varchar(40) unique,
	password  varchar(40),
	authenticationMethod varchar(40),
	role varchar(30)
);

create table cards (
    id int unsigned not null primary key auto_increment,
    description varchar(255) not null
);