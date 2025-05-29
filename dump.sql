create table if not exists calling(
    idCalling int not null auto_increment, 
    time datetime not null DEFAULT CURRENT_TIMESTAMP,
    sector varchar(100) not null,
    server varchar(100) not null,
    type varchar(100) not null,
    status varchar(100) not null,
    primary key(idCalling)
);


use callingbox;
drop table calling;
select * from calling;
show databases;