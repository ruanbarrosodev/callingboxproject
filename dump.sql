create database callingboxproject;
use callingboxproject;
create table if not exists calling(
    idCalling int not null auto_increment, 
    time datetime not null DEFAULT CURRENT_TIMESTAMP,
    updateTime datetime null,
    doneTime datetime null,
    sector varchar(100) not null,
    server varchar(100) not null,
    type varchar(100) not null,
    status varchar(100) not null,
    description varchar(500) null,
    user_key varchar(200) not null,
    nota int null,
    primary key(idCalling)
);


use callingbox;
drop table calling;
select * from calling;
show databases;