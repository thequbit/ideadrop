create database ideadrop;

grant usage on __database__.* to __username__ identified by '__password__';

grant all on __database__.* to __username__;

use ideadrop;

create table users(
	userid int not null auto_increment primary key,
	username varchar(255) not null,
	passwordhash varchar(255) not null,
	permissionid int not null,
	validationcode varchar(255) not null,
	validated bool not null,
	profileid int not null
);
	
create table permissions(
	permissionid int not null auto_increment primary key,
	enabled bool not null,
	isadmin bool not null,
	banned bool not null
);

create table profiles(
	profileid int not null auto_increment primary key,
	profileimageid int,
	userdescription text,
	creationdate date,
	lastlogin datetime
);

create table images(
	imageid int not null auto_increment primary key,
	filename text not null
);

create table chops(
	chopsid int not null auto_increment primary key,
	chopsname varchar(255),
	chopsdescription text not null
);

create table userchops(
	userchopsid int not null auto_increment primary key,
	userid int not null,
	chopsid int not null
);

create table ideas(
	ideaid int not null auto_increment primary key,
	creationdatetime datetime not null,
	ownerid int not null,
	name varchar(255) not null,
	description text not null
);

create table ideausers(
	ideauserid int not null auto_increment primary key,
	ideauser int not null,
	userid int not null
);

create table ideachops(
	ideachopsid int not null auto_increment primary key,
	ideaid int not null,
	chopsid int not null
);

create table workrequests(
	workrequestid int not null auto_increment primary key,
	ideaid int not null,
	userid int not null,
	requesttext text not null,
	creationdatetime datetime not null,
	closeddatetime datetime not null,
	accepted bool not null 
);