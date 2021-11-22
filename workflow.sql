drop database if exists workflow;
create database workflow character set utf8;
use workflow;

create table client(
    id                      int not null primary key auto_increment,
    firstname               varchar(50) not null,
    lastname                varchar(50) not null,
    company                 varchar(50),
    phonenum                varchar(20) not null,
    email                   varchar(50)
);

create table employee(
    id                      int not null primary key auto_increment,
    firstname               varchar(50) not null,
    lastname                varchar(50) not null,
    phonenum                varchar(20) not null,
    email                   varchar(50) not null
);

create table workorder(
    id                      int not null primary key auto_increment,
    employee_frontdesk      int not null,
    employee_repairman      int,
    workstation             int,
    device                  int not null,
    malfunction             varchar(200) not null,
    receive_date            datetime,
    repair_status           boolean,
    work_done               varchar(200) not null,
    repair_date             datetime
);

create table device(
    id                      int not null primary key auto_increment,
    client                  int not null,
    manufacturer            varchar(30) not null,
    model                   varchar(30) not null,
    serialnum               varchar(20)
);

alter table workorder add foreign key (employee_frontdesk) references employee(id);
alter table workorder add foreign key (employee_repairman) references employee(id);
alter table workorder add foreign key (device) references device(id);

alter table device add foreign key (client) references client(id);