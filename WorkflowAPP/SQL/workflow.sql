drop database if exists workflow;
create database workflow character set utf8;
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
    email                   varchar(50) not null
);

create table workorder(
    workorder_id            int not null primary key auto_increment,
    employee_frontdesk      int not null,
    employee_repairman      int,
    workstation             int,
    device                  int not null,
    malfunction             varchar(200) not null,
    receive_date            datetime,
    repair_status           int not null,
    work_done               varchar(200) not null,
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

insert into employee (employee_id,firstname,lastname,phonenum,email) values
(null,'Božidar','Mašić','0992587412','bozo@gmail.com'),
(null,'Robert','Dudaš','0955524563','robinjo@gmail.com'),
(null,'Mladen','Božić','0977897819','mladjahni@gmail.com');

insert into device(device_id,client,manufacturer,model,serialnum) values
(null,1,'Bosch','GSH 5 40',null),
(null,2,'Makita','GA 9020','95634578164'),
(null,3,'DeWalt','DCN 180',null),
(null,4,'Festool','Nepoznato','7451245786245123');

insert into workorder(workorder_id,employee_frontdesk,employee_repairman,workstation,device,malfunction,receive_date,repair_status,work_done,repair_date) values
(null,1,2,1,1,'Gubi kontakt','2021-11-10',3,'Obavljena dijagnostika, prekidač nagoren',null),
(null,1,2,2,2,'Čudno se čuje','2021-11-10',4,'Obavljena dijagnostika, oštećeni zupčanici, ugrađeni novi zupčanici','2021-11-10'),
(null,1,1,1,3,'','2021-11-10',2,'',null);