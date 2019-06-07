整理了mysql语句，每天练习一遍，对你有帮助的！

MySQL服务的配置和使用

修改MySQL管理员的口令：mysqladmin –u root password 密码字符串

如：mysqldmin –u root password root

连接MySQL服务器，使用命令： mysql [-h 主机名或IP地址] [-u 用户名] [-p]

如：mysql –u root –p

如已有密码需修改root密码用命令: mysqladmin –u root –p password 新密码字符串

如：mysqladmin –u root –p password root

创建数据库格式为：CREATE DATABASE 数据库名称；

如：mysql>create database abc; 默认创建数据库保存在/var/lib/mysql中

查看数据库是 mysql>show abc;

选择数据库是 USE 数据库名称; 如：mysql>use abc;

删除数据库是 DROP DATABASE 数据库名称； 如：mysql>drop database abc;

数据库的创建和删除

创建表是 CREATE TABLE 表名称(字段1，字段2，…[表级约束]) [TYPE=表类型]；

其中字段(1,2 )格式为:字段名 字段类型 [字段约束]

如创建一个表student，如下：

mysql>create table student (

sno varchar(7) not null, 字段不允许为空

sname varchar (20 )not null,

ssex char (1) default ‘t’,

sbirthday date,

sdepa char (20),

primary key (sno) 表的主键

);
可用describe命令查看表的结构。

默认表的类型为MYISAM，并在/var/lib/mysql/abc 目录下建立student.frm(表定义文件)，student.MDY(数据文件)，stedent.MYI(索引文件)。

复制表 CREATE TABLE 新表名称 LIKE 原表名称；

如：mysql>create table xtable like student;

删除表 DROP TABLE 表名称1[表名称2…];

如：mysql> drop table xtale;

修改表 ALTER TABLE 表名称 更改动作1[动作2]；

动作有ADD(增加) DROP(删除)CHANGE、MODIFY(更改字段名和类型)RENAME

增加字段：mysql>alter table student add saddress varchar(25);

更改字段名和字段类型： mysql>alter table student change saddress sremark test;

即使不更改字段类型也要给出字段类型如：

mysql>alter table student change saddress sremark varchar (25);

更改字段类型　：mysql> alter table student modify sremark varchar(25);

删除字段：mysql>alter table student drop sremark；

更改表名称： mysql>alter table student rename to xs；

表中数据的插入、删除和修改

插入记录： INSERT INTO 表名称（字段名1,字段名2…

VALUES(字段1的值，字段2的值

如：mysql>insert into student (sno,sname,ssex,sbirthday,sdepa)

values(‘0321001’,’Liu Tao’,dagault,19870201,’math’);

查看表 mysql>select * from student;

插入与前面相同的记录，可用insert命令的缩写格式，

如: mysql>insert into student values (‘0321001’, ‘Liu Tao’, default, 19870201, ‘mth’);

如果字段名列表中没有给出表中的某些字段，那么这些字段设置为默认值，

如：mysql>insert into student (sno,sname,sbirthday)

values(‘0321002’,’Wang Jun’,1870112);

一个单独的insert语句中可使用多个valuse字句，插入多条记录，

如：mysql>insert into student values

(‘0322001’, ‘Zhang Liaoyun’, ‘f’ 1971102,’computer’),

(‘0322002’, ‘Li Ming’, ‘t’ 1971105,’computer’);

删除记录： DELETE FROM 表名称 WHERE 条件表达式；

如：mysql>delete from student where sno=’0321002’;

删除student表中sno字段值前4位为‘0322’的记录

如：mysql>delete from student where left (sno,4)=’0322’;

删除所以记录，可以不带where字句

如：mysql>delete from student;

删除所以记录可以用命令truncate 删除表，然后重建表，所以比delete命令快

如：mysql>truncate table student;

修改记录 UPDATE 表名称 SET 字段名1=字段值1

WHERE 条件表达式

如： mysql>update student set sbirthday=1920113, sdepa=’math’ where sno=’0321002’;

索引的创建与删除

在创建表的同时创建索引

创建表时，可用INDEX字句或UNIQUE(字段值必须惟一)字句创建索引

如：创建课程表course, 课程编号cno字段为主键，课程名称cname字段创建一个名为can的索引

mysql>create table course(

cno varchar(5) not null,

cname varchar(30) not null,

teacher varchar(20),

primary key (cno),

index can (cname)

);
向已存在的表添加索引 CREATE [UNIQUE ] INDEX 索引名ON表名称 (字段名1[(长度)])；

如：mysql>create index sna on student (sname);

对于类型为CHAR和VARCHAR的字段建立索引时还可指定长度值，类型为BLOB和TEXT的字段索引时必须指定长度值。

如 mysql>create index sna on student (sname(10));

删除索引 DROP INDEX 索引 ON表名称；

如：mysql>drop index sna on student;

用户的创建和删除

初始化时有5个MySQL授权表，其中host、tables_priv和columnts_priv 是空的，表user和db决定了MySQL默认的访问规则。默认有mysql和test两个数据库。

授权表：user 用户从哪些主机可以连接到数据库服务器，以及对所以数据库的访问权限（全局权限）

db 用户可以使用哪些权限，以及对数据库执行哪些操作（数据库级权限）

host 当表db 中的host 字段值为空时，用户从哪些主机可以连接到数据库服务器。

tables_priv 连接的用户可以访问哪些表（表级权限）

columnts_priv 连接的用户可以访问哪些字段 （字段级权限）

创建新用户

以MySQL管理员连接到数据库服务器： #mysql –u root –p

创建新用户guess并设置密码，同时可以从任何主机连接数据库服务器：

mysql>insert into mysql.user (host,user,password)

values (‘%’,’gusee’,password(‘guest’)); 使用password()函数，密码是加密的

重载MySQL授权表：mysql>flush privileges;

远程客户端连接数据库服务器 ：#mysql –h 192.168.0.50 –u guess –p 开放服务器的TCP断口3306

查看当前用户可用数据库： show database

删除用户

mysql>delete from mysql.user where user=’guest’;

mysql>flush privileges; 重载MySQL授权表

更改用户密码

如：更改guset密码为123456

mysql>update mysql.user set password=password(‘123456’)

where user =’guset’;

mysql>flush privileges;

或者是 mysql>set password for guset@’%’=password(‘123456’);

用户权限的设置

在表user、db和host中，所有字段声明为ENUM(‘N’,’Y’),默认是‘N’;

在表tables_priv和columns_priv中，权限字段声明为SET类型

修改授权表中的访问权限有两中方法，一是使用 INSERT、UPDATE和DELETE等DML语句，

另一中是GRANT和GRVOKE语句

使用GRANT语句授权：

格式如下：

GRANT 权限列表 [(字段列表)] on 数据库名称.表名称

TO 用户名@域名或IP地址

[INDETIFIED BY ‘密码值’] [WITH CRANT OPTION];

授权哪个用户能连接，从哪连接

如：授权用户guest从任意主机连接数据库服务器，并具有完全访问数据库abc的权限。

Mysql>grant all on abc.* to guset@’%’ identified by ‘guest’

注意几点：如指定用户不存在，则创建该新用户；

‘权限列表’处ALL表示授予全部权限，USAGE表不授予任何权限。

‘数据库名称.表名称’处可以使用通配符“*”。如“abc.*”表数据库abc中所有表

‘用户名@域名或IP地址’设置谁能连，从哪连。用户名 不能用通配符，但可以用‘ ’空字符串，表任何用户；域名或IP地址可以用通配符“%”，使用是用单引号括起来。

授权用户不同级别的访问权限

如：新建用户tom,能从子网192.168.16.0访问数据库服务器，可以读取数据库xsxk,并能修改表course 中字段teacher的值

mysql>grant select on xsxd.* to tom@’192.168.16.%’ indentifiend by ‘123456’;

mysql>grant update(teacher) on xsxd.course to tom@’192.168.16.%’’

注意几点：数据库名称.表名称 用来设置权限运用的级别，有全局的（*.*）,指定数据库的（xsxd.*）

和指定表的（xsxd.student）;

字段列表 设置权限运用中指定的表中的哪些字段，如update(cname,teacher)

权限列表 指定的权限与权限运行的级别有关，如有写权限（FILE、PROCESS、RELOAD、SHUTDOWN）作为管理权限用于全局级别；对于字段级别只能指定SELECT、INSERT、UPDATE、REFERENCES

授予用户管理权限的权利

如：管理员授予拥护admin可以从本地连接数据库服务器，对数据库xsxk具有完全访问权限，并可以

将拥有的权限赋予其他用户

mysql>grant all on xsxd.* to admin@localhost indentified by ‘123456’ with grant option;

其中with grant option 子句表示拥护拥有的权限可以赋予其他用户。

mysql>qrant select on xsxd.student to bill@localhost; 授予bill用户权限

mysql>show grants for admin@localhost; 查看用户权限

使用REVOKE语句撤权

格式如下：

REVOKE 权限列表[(字段列表)] on数据库名称.表名称

FROM用户名@域名或IP地址

如：撤消用户admin@localhost 对数据库xsxd的创建、删除数据库及表的权限，不撤消用户赋予其它用户的权限

mysql>revoke create,drop on xsxd.* from admin@localhost;

mysql>revoke grant option on xsxd.* from admin@localhost;
