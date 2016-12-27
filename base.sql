create database if not exists journal;

USE journal;

drop table if exists `joureg`;

CREATE TABLE `joureg` (
  `regnum` int(11) unsigned auto_increment,
  `exitnum` varchar(20) NOT NULL default '',
  `regdocdate` date NOT NULL,
  `docdate` date NOT NULL,
  `fromorg` text NOT NULL,
  `controldocdate` date NOT NULL,
  `controlcheck` varchar(50) NOT NULL default '',
  `ispolnitel` text,
  `about` text NOT NULL,
  `comments` text,
 	`uporgflag` bool default '0',
  PRIMARY KEY  (`regnum`)
);

/*Table structure for table `jouregout` */

drop table if exists `jouregout`;
 
CREATE TABLE `jouregout` (
  `exitnum` int(11) unsigned auto_increment,
  `regdocdate` date NOT NULL,
  `docnum` varchar(20) NOT NULL default '0',
  `regnum` int(11) unique,
  `ispolnitel` text,
  `comments` text,
 	-- `uporgflag` bool default 0,
  PRIMARY KEY  (`exitnum`)
);


/*Data for the table `joureg` */

insert into `joureg` values (1,'12312','2006-02-12','2003-05-24','porganizacija 1','2006-06-5','an','ar','asdf','hfghfg', 1),
														(2,'345','2006-02-12','2003-05-24','iorganizacija 2','2006-06-5','bn','ar','фывапро','hfghfg', 0),
														(3,'654','2006-02-12','2003-05-24','uorganizacija 3','2006-06-5','kn','ar','cdrh','hfghfg', 1),
														(4,'891','2006-02-12','2003-05-24','yorganizacija 4','2006-06-11','en','ar','mdrh','hfghfg', 0),
														(5,'15','2006-02-12','2003-05-24','trganizacija 5','2006-06-23','pn','ar','pdrh','hfghfg', 1),
														(6,'76','2006-02-12','2003-05-24','rrganizacija 6','2006-07-01','ln','ar','wdrh','hfghfg', 0),
														(7,'876','2006-02-12','2003-05-24','erganizacija 7','2006-06-1','hn','ar','fdrh','hfghfg', 1),
														(8,'4576','2006-02-12','2003-05-24','wrganizacija 8','2006-05-3','dn','ar','idrh','hfghfg', 0),
														(9,'7859','2006-02-12','2003-05-24','qrganizacija 9','2006-05-10','cn','ar','xdrh','hfghfg', 1),
														(10,'9087','2006-02-12','2003-05-24','trganizacija 10','2006-02-20','zn','ar','rdrh','hfghfg', 0),
														(11,'12345','2006-02-12','2003-05-24','rrganizacija 11','2006-03-13','yn','ar','gadrh','hfghfg', 1),
														(12,'0765','2006-02-12','2003-05-24','brganizacija 12','2006-01-13','qn','ar','xdrh','hfghfg', 0),
														(22,'8762','2003-06-07','2003-06-07','arganizacija 13','2006-07-07','rn','fg','ng','fg',0);


/*Data for the table `jouregout` */

insert into `jouregout` values (1,'2006-06-1','fgh',7,'vfghfgh','hfgh'),
															 (2,'2006-05-3','56',8,'nfgb','hg'),
															 (3,'2006-05-10','56',9,'cfgb','hg'),
															 (4,'2006-02-20','qweetry',10,'asdf','tgbfc'),
 															 (5,'2006-03-13','56',11,'fgb','lkjh'),
															 (6,'2006-01-13','ghjku',12,'zxccv','qwer');


drop table if exists `users`;

CREATE TABLE `users` (
  `user` varchar(50) binary NOT NULL default '',
  `password` char(32) binary NOT NULL default '',
  `name` varchar(150) binary NOT NULL default '',
	`rules` enum('w','r','a','n') NOT NULL default 'n',
  PRIMARY KEY  (`user`)
);

/*Data for the table `users` */

insert into `users` values ('damien','oxygen','n1','a');
