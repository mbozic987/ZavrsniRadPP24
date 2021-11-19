drop database if exists workflow;
create database workflow character set utf8;
use workflow;

create table client(
    id              int not null primary key auto_increment,
    firstname       varchar(50) not null,
    lastname        varchar(50) not null,
    company         varchar(50),
    phonenum        varchar(20) not null,
    email           varchar(50)
);

create table employee(
    id              int not null primary key auto_increment,
    firstname       varchar(50) not null,
    lastname        varchar(50) not null,
    phonenum        varchar(20) not null,
    email           varchar(50) not null
);

create table frontdesk(
    id              int not null primary key auto_increment,
    employee        int not null
);

create table repairman(
    id              int not null primary key auto_increment,
    employee        int not null,
    workstation     int not null
);

create table device(
    id              int not null primary key auto_increment,
    client          int not null,
    frontdesk       int not null,
    manufacturer    varchar(30) not null,
    model           varchar(30) not null,
    serialnum       int,
    malfunction     varchar(200) not null,
    receivedate     datetime,
    repairstatus    boolean
);

create table workorder(
    id              int not null primary key auto_increment,
    device          int not null,
    repairman       int not null,
    repairdate      datetime
);

alter table frontdesk add foreign key (employee) references employee(id);

alter table repairman add foreign key (employee) references employee(id);

alter table device add foreign key (client) references client(id);
alter table device add foreign key (frontdesk) references frontdesk(id);

alter table workorder add foreign key (device) references device(id);
alter table workorder add foreign key (repairman) references repairman(id);