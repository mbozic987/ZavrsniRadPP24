# c:\xampp\mysql\bin\mysql -uedunova -pedunova --default_character_set=utf8 < D:\PP24\ZavrsniRadPP24\WorkflowApp\workflow.sql

drop database if exists workflow;
create database workflow character set utf8mb4;
use workflow;

create table client(
    client_id               int not null primary key auto_increment,
    firstname               varchar(50) not null,
    lastname                varchar(50) not null,
    company                 varchar(50),
    phonenum                varchar(20) not null,
    email                   varchar(50)
);

create table employee(
    employee_id             int not null primary key auto_increment,
    firstname               varchar(50) not null,
    lastname                varchar(50) not null,
    phonenum                varchar(20) not null,
    email                   varchar(50) not null,
    userpassword            varchar(60) not null,
    employee_role           varchar(10) not null
);

create table workorder(
    workorder_id            int not null primary key auto_increment,
    employee_frontdesk      int not null,
    employee_repairman      int,
    device                  int not null,
    malfunction             varchar(255) not null,
    receive_date            datetime not null default now(),
    repair_status           int not null,
    work_done               varchar(255) not null,
    query_id                varchar(6) not null,
    repair_date             datetime
);

create table repair_status(
    repair_status_id        int not null primary key,
    status_name             varchar(50)
);

create table device(
    device_id               int not null primary key auto_increment,
    client                  int not null,
    manufacturer            varchar(30) not null,
    model                   varchar(30) not null,
    serialnum               varchar(20)
);

alter table workorder add foreign key (employee_frontdesk) references employee(employee_id);
alter table workorder add foreign key (employee_repairman) references employee(employee_id);
alter table workorder add foreign key (device) references device(device_id);
alter table workorder add foreign key (repair_status) references repair_status(repair_status_id);

alter table device add foreign key (client) references client(client_id);

insert into repair_status (repair_status_id,status_name) values
('1','Awaiting Diagnostics'),
('2','Diagnostics'),
('3','Awaiting Parts'),
('4','Device Repaird');

insert into client (client_id,firstname,lastname,company,phonenum,email) values
(null,'Andrija','Andrić',null,'0989879654',null),
(null,'Berislav','Berić','Bero d.o.o.','0911231247','bero@gmail.com'),
(null,'Drazen','Dragičević',null,'0973571596','draza@gmail.com'),
(null,'Goran','Glavurdić','Gepeto, stolarski obrt','092654256',null);

insert into employee (employee_id,firstname,lastname,phonenum,email,userpassword,employee_role) values
# userpassword a
(null,'Božidar','Mašić','0992587412','bozo@gmail.com','$2a$12$lBl3DQVaDw2zHkH9ht.64uEQ.TAqh18PLk/NKexRyEfpBeT/TMsbi','Admin'),
# userpassword s1
(null,'Robert','Dudaš','0955524563','robinjo@gmail.com','$2a$12$LZWiYT1ZrFtQJdf0YwqqxOW/wmNzHhD4N.crhTW7VIRzKiWrTv9Qq','Repairman'),
# userpassword s2
(null,'Mladen','Božić','0977897819','mladjahni@gmail.com','$2a$12$40ZLRJu6GrCgwqOZugAIauxK8CQphU6ZAUaYjB7qDSSm8AM.32I/G','Repairman');

insert into device(device_id,client,manufacturer,model,serialnum) values
(null,1,'Bosch','GSH 5 40',null),
(null,2,'Makita','GA 9020','95634578164'),
(null,3,'DeWalt','DCN 180',null),
(null,4,'Festool','Nepoznato','7451245786245123');

insert into workorder(workorder_id,employee_frontdesk,employee_repairman,device,malfunction,receive_date,repair_status,work_done,query_id,repair_date) values
(null,1,2,1,'Gubi kontakt','2021-11-10',3,'Obavljena dijagnostika, prekidač nagoren','aaaaaa',null),
(null,1,2,2,'Čudno se čuje','2021-11-10',4,'Obavljena dijagnostika, oštećeni zupčanici, ugrađeni novi zupčanici','0a0a0a','2021-11-10'),
(null,1,3,3,'','2021-11-10',2,'','a0a0a0',null);