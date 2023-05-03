DROP TABLE IF EXISTS myacle_adminlog;
CREATE TABLE myacle_adminlog (
   adminlogid int(15) NOT NULL auto_increment,
   action varchar(50) NOT NULL,
   script varchar(255) NOT NULL,
   date varchar(10) NOT NULL,
   ipaddress varchar(16) NOT NULL,
   PRIMARY KEY (adminlogid)
);

INSERT INTO myacle_adminlog VALUES('1', 'login', '/phpweb/admin/index.php', '1683114119', '::1');
INSERT INTO myacle_adminlog VALUES('2', 'menu', '/phpweb/admin/index.php?action=menu', '1683114119', '::1');
INSERT INTO myacle_adminlog VALUES('3', 'main', '/phpweb/admin/index.php?action=main', '1683114119', '::1');
INSERT INTO myacle_adminlog VALUES('4', 'phpinfo', '/phpweb/admin/configurate.php?action=phpinfo', '1683114131', '::1');
INSERT INTO myacle_adminlog VALUES('5', 'edit', '/phpweb/admin/article.php?action=edit', '1683114136', '::1');
INSERT INTO myacle_adminlog VALUES('6', 'update', '/phpweb/admin/configurate.php', '1683114161', '::1');
INSERT INTO myacle_adminlog VALUES('7', 'add', '/phpweb/admin/article.php?action=add', '1683114165', '::1');
INSERT INTO myacle_adminlog VALUES('8', 'edit', '/phpweb/admin/sort.php?action=edit', '1683114175', '::1');
INSERT INTO myacle_adminlog VALUES('9', 'add', '/phpweb/admin/sort.php?action=add', '1683114178', '::1');
INSERT INTO myacle_adminlog VALUES('10', 'edit', '/phpweb/admin/sort.php?action=edit', '1683114180', '::1');
INSERT INTO myacle_adminlog VALUES('11', 'backup', '/phpweb/admin/database.php?action=backup', '1683114185', '::1');
INSERT INTO myacle_adminlog VALUES('12', 'dobackup', '/phpweb/admin/database.php?action=backup', '1683114191', '::1');



DROP TABLE IF EXISTS myacle_article;
CREATE TABLE myacle_article (
   articleid int(15) NOT NULL auto_increment,
   pid int(11) NOT NULL,
   sortid int(11) NOT NULL,
   title varchar(120) NOT NULL,
   author varchar(20) NOT NULL,
   email varchar(100) NOT NULL,
   source varchar(100) NOT NULL,
   addtime varchar(10) NOT NULL,
   content text NOT NULL,
   comment int(11) NOT NULL,
   hits int(11) NOT NULL,
   iscommend int(11) NOT NULL,
   isparseurl int(11) DEFAULT '1' NOT NULL,
   ishtml int(11) NOT NULL,
   visible int(11) DEFAULT '1' NOT NULL,
   PRIMARY KEY (articleid)
);




DROP TABLE IF EXISTS myacle_bas;
CREATE TABLE myacle_bas (
   bid int(15) NOT NULL auto_increment,
   bas_type int(15) NOT NULL,
   bas_site_name varchar(120) NOT NULL,
   bas_site_url varchar(120) NOT NULL,
   bas_url varchar(120) NOT NULL,
   bas_url_tag text NOT NULL,
   bas_sort_tag text NOT NULL,
   bas_title_tag text NOT NULL,
   bas_author_tag text NOT NULL,
   bas_content_tag text NOT NULL,
   bas_start int(15) DEFAULT '1' NOT NULL,
   bas_end int(15) DEFAULT '1' NOT NULL,
   bas_down_pic int(15) NOT NULL,
   bas_update int(15) NOT NULL,
   bas_cleanhtml int(15) NOT NULL,
   bas_pic_dir varchar(120) NOT NULL,
   PRIMARY KEY (bid)
);




DROP TABLE IF EXISTS myacle_basdata;
CREATE TABLE myacle_basdata (
   articleid int(15) NOT NULL auto_increment,
   sortname varchar(120) NOT NULL,
   title varchar(120) NOT NULL,
   author varchar(20) NOT NULL,
   content text NOT NULL,
   pic varchar(120) NOT NULL,
   path varchar(120) NOT NULL,
   PRIMARY KEY (articleid)
);




DROP TABLE IF EXISTS myacle_loginlog;
CREATE TABLE myacle_loginlog (
   loginlogid int(15) NOT NULL auto_increment,
   username varchar(100) NOT NULL,
   password varchar(100) NOT NULL,
   date varchar(10) NOT NULL,
   ipaddress varchar(16) NOT NULL,
   result int(11) NOT NULL,
   PRIMARY KEY (loginlogid)
);

INSERT INTO myacle_loginlog VALUES('1', 'root', '密码正确', '1683114119', '::1', '1');



DROP TABLE IF EXISTS myacle_setting;
CREATE TABLE myacle_setting (
   settingid int(11) NOT NULL auto_increment,
   title varchar(200) NOT NULL,
   description varchar(200) NOT NULL,
   name varchar(255) NOT NULL,
   value mediumtext NOT NULL,
   type varchar(100) NOT NULL,
   PRIMARY KEY (settingid)
);

INSERT INTO myacle_setting VALUES('1', '文章系统名称', '将在所有页面标题上显示该名称', 'title', '文章中心', 'string');
INSERT INTO myacle_setting VALUES('2', '文章系统地址', '文章系统在网络上的地址', 'url', 'http://localhost', 'string');
INSERT INTO myacle_setting VALUES('3', '前台模板', '', 'template', 'default', 'templates');
INSERT INTO myacle_setting VALUES('4', '每页显示多少条文章', '', 'articlenum', '20', 'integer');
INSERT INTO myacle_setting VALUES('5', '文章分多少列显示', '', 'colnum', '2', 'integer');
INSERT INTO myacle_setting VALUES('6', '每页显示多少条评论', '', 'commentnum', '20', 'integer');
INSERT INTO myacle_setting VALUES('7', '搜索引擎显示多少个结果', '', 'searchnum', '20', 'integer');
INSERT INTO myacle_setting VALUES('8', '是否开放评论功能', '', 'iscomment', '1', 'yesno');
INSERT INTO myacle_setting VALUES('9', '提交评论时间间隔', '可以防止他人灌水,单位秒,0为不限制', 'post_time', '20', 'integer');
INSERT INTO myacle_setting VALUES('10', 'HTML页存放目录', '', 'htmldir', 'html', 'string');
INSERT INTO myacle_setting VALUES('11', '附件存放目录', '', 'attachdir', 'attachments', 'string');
INSERT INTO myacle_setting VALUES('12', '允许的最大附件大小', '上传的附件的最大字节数，设为0将不做限制。<br>1 KB = 1024 字节 1 MB = 1048576 字节', 'maxattachsize', '1048576', 'integer');



DROP TABLE IF EXISTS myacle_sort;
CREATE TABLE myacle_sort (
   sortid int(15) NOT NULL auto_increment,
   parentid int(15) NOT NULL,
   sortname varchar(20) NOT NULL,
   sortdir varchar(20) NOT NULL,
   count int(15) NOT NULL,
   displayorder int(15) NOT NULL,
   PRIMARY KEY (sortid)
);




DROP TABLE IF EXISTS myacle_user;
CREATE TABLE myacle_user (
   userid int(15) NOT NULL auto_increment,
   username varchar(16) NOT NULL,
   password varchar(50) NOT NULL,
   PRIMARY KEY (userid)
);

INSERT INTO myacle_user VALUES('1', 'root', 'a6ab9888ba85b12a7d03e751af530cb0');



