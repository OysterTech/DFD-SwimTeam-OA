CREATE DATABASE IF NOT EXISTS `dfd` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `dfd`;

CREATE TABLE IF NOT EXISTS `athlete_list` (
  `AthID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` varchar(3) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户ID（与sys_user关联）',
  `Phone` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号（用户名）',
  `RealName` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '真实姓名（JSON格式）',
  `SchoolGrade` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '年级',
  `SchoolClass` varchar(2) COLLATE utf8_unicode_ci NOT NULL COMMENT '班别',
  `Sex` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '性别（男/女）',
  `YearGroup` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT '年龄组（4位年份）',
  `IDCard` varchar(18) COLLATE utf8_unicode_ci NOT NULL COMMENT '身份证',
  `IDCardType` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '证件类型（1二代身份证,2香港身份证,3护照）',
  `RegiDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册时间',
  `LastDate` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`AthID`),
  UNIQUE KEY `Unique Key` (`IDCard`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='运动员 - 运动员资料库';

INSERT INTO `athlete_list` (`AthID`, `UserID`, `Phone`, `RealName`, `SchoolGrade`, `SchoolClass`, `Sex`, `YearGroup`, `IDCard`, `IDCardType`, `RegiDate`, `LastDate`) VALUES
(1, '3', '13318707941', '运动员', '4', '10', '男', '2002', '440104200204092218', '1', '2017-06-16 06:13:57', NULL);

CREATE TABLE IF NOT EXISTS `cache_enroll_export` (
  `CacheID` int(11) NOT NULL AUTO_INCREMENT,
  `SessionID` varchar(26) COLLATE utf8_unicode_ci NOT NULL,
  `UserID` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `Content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `CacheTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ExpTime` int(10) NOT NULL,
  `IP` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`CacheID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_login` (
  `CacheID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `RealName` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `SessionID` varchar(26) COLLATE utf8_unicode_ci NOT NULL,
  `ErrorTimes` int(1) DEFAULT NULL,
  `ExpTime` int(10) NOT NULL,
  `CacheTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IP` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`CacheID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `enroll_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `AthID` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT '运动员ID（关联athlete_list）',
  `GamesID` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT '比赛ID（关联games_list）',
  `ItemID` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT '项目ID（关联item_list）',
  `EnrollTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '报名时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='报名 - 运动员报名项目关联';

CREATE TABLE IF NOT EXISTS `games_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `GamesID` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '关联games_list',
  `ItemID` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '关联item_list',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='比赛 - 比赛与项目关联';

CREATE TABLE IF NOT EXISTS `games_list` (
  `GamesID` int(11) NOT NULL AUTO_INCREMENT COMMENT '比赛ID',
  `GamesName` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '比赛名称',
  `EndDate` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '结束报名日期（格式：年月日，如20140711）',
  `isEnd` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '是否开放报名',
  `isPrivate` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否限制报名',
  `AllowUser` text COLLATE utf8_unicode_ci COMMENT '允许报名的运动员ID（JSON，关联athlete_list）',
  PRIMARY KEY (`GamesID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='比赛 - 比赛列表';

INSERT INTO `games_list` (`GamesID`, `GamesName`, `EndDate`, `isEnd`, `isPrivate`, `AllowUser`) VALUES
(1, '2017广州市赛', '20170901', '1', '1', '1');

CREATE TABLE IF NOT EXISTS `games_notice` (
  `NoticeID` int(11) NOT NULL AUTO_INCREMENT,
  `GamesID` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '比赛ID（与games_list关联）',
  `Type` int(1) NOT NULL COMMENT '通知类型（1通知2规程3秩序册）',
  `Title` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '通知标题',
  `Content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '通知内容',
  `FileJSON` text COLLATE utf8_unicode_ci COMMENT '文件信息JSON [Name,Path]',
  `PageView` int(11) NOT NULL DEFAULT '0' COMMENT '浏览量',
  `PubTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布时间',
  `isDelete` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否已经删除',
  PRIMARY KEY (`NoticeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='比赛 - 通知和文件列表';

CREATE TABLE IF NOT EXISTS `item_list` (
  `ItemID` int(11) NOT NULL AUTO_INCREMENT COMMENT '项目ID',
  `YearGroup` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT '年龄组（4位年份）',
  `ItemName` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '项目名称',
  PRIMARY KEY (`ItemID`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='比赛 - 项目库';

INSERT INTO `item_list` (`ItemID`, `YearGroup`, `ItemName`) VALUES
(1, '2004', '50米蝶泳'),
(2, '2004', '50米仰泳'),
(3, '2004', '50米蛙泳'),
(4, '2004', '50米自由泳'),
(5, '2004', '100米蝶泳'),
(6, '2004', '100米仰泳'),
(7, '2004', '100米蛙泳'),
(8, '2004', '100米自由泳'),
(9, '2004', '200米自由泳'),
(10, '2004', '400米自由泳'),
(11, '2004', '4x50米自由泳接力'),
(12, '2004', '4x50米混合泳接力'),
(13, '2005', '50米蝶泳'),
(14, '2005', '50米仰泳'),
(15, '2005', '50米蛙泳'),
(16, '2005', '50米自由泳'),
(17, '2005', '100米蝶泳'),
(18, '2005', '100米仰泳'),
(19, '2005', '100米蛙泳'),
(20, '2005', '100米自由泳'),
(21, '2005', '200米自由泳'),
(22, '2005', '400米自由泳'),
(23, '2005', '4x50米自由泳接力'),
(24, '2005', '4x50米混合泳接力'),
(25, '2006', '50米蝶泳'),
(26, '2006', '50米仰泳'),
(27, '2006', '50米蛙泳'),
(28, '2006', '50米自由泳'),
(30, '2006', '100米仰泳'),
(31, '2006', '100米蛙泳'),
(32, '2006', '100米自由泳'),
(33, '2006', '200米自由泳'),
(34, '2006', '400米自由泳'),
(35, '2006', '4x50米自由泳接力'),
(36, '2006', '4x50米混合泳接力'),
(37, '2007', '50米蝶泳'),
(38, '2007', '50米仰泳'),
(39, '2007', '50米蛙泳'),
(40, '2007', '50米自由泳'),
(42, '2007', '100米仰泳'),
(43, '2007', '100米蛙泳'),
(44, '2007', '100米自由泳'),
(45, '2007', '200米自由泳'),
(47, '2007', '4x50米自由泳接力'),
(48, '2007', '4x50米混合泳接力'),
(49, '2008', '50米蝶泳'),
(50, '2008', '50米仰泳'),
(51, '2008', '50米蛙泳'),
(52, '2008', '50米自由泳'),
(54, '2008', '100米仰泳'),
(55, '2008', '100米蛙泳'),
(56, '2008', '100米自由泳'),
(59, '2003', '4x50米自由泳接力'),
(60, '2008', '4x50米混合泳接力');

CREATE TABLE IF NOT EXISTS `role_list` (
  `Roleid` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `RoleName` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `Brief` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '备注',
  `isSuper` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否系统角色(0非1是)(系统角色不可删除)',
  `isAthlete` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否运动员角色(0非1是)',
  PRIMARY KEY (`Roleid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色 - 角色列表';

INSERT INTO `role_list` (`Roleid`, `RoleName`, `Brief`, `isSuper`, `isAthlete`) VALUES
(1, '超级管理员', '最高权限用户，内置角色不可删除', '1', '0'),
(2, '领队', '行使比赛管理、运动员信息管理权力', '0', '0'),
(3, '运动员', '行使比赛报名、运动员资料维护权力', '0', '1');

CREATE TABLE IF NOT EXISTS `role_purview` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Roleid` int(11) NOT NULL COMMENT '角色ID（与role_list关联）',
  `Purvid` int(11) NOT NULL COMMENT '权限ID（与sys_menu关联）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色 - 角色与权限关联';

INSERT INTO `role_purview` (`id`, `Roleid`, `Purvid`) VALUES
(1, 2, 1),
(2, 2, 17),
(3, 2, 10),
(4, 2, 13),
(5, 2, 11),
(6, 2, 19),
(7, 2, 7),
(8, 2, 8),
(9, 1, 19),
(10, 1, 18),
(11, 1, 17),
(12, 1, 16),
(13, 1, 15),
(14, 1, 12),
(15, 1, 11),
(16, 1, 9),
(17, 1, 8),
(18, 1, 7),
(19, 1, 4),
(20, 1, 10),
(21, 1, 13),
(22, 1, 3),
(23, 1, 1),
(24, 3, 10),
(25, 3, 12),
(26, 3, 19);

CREATE TABLE IF NOT EXISTS `sys_log` (
  `LogID` int(11) NOT NULL AUTO_INCREMENT,
  `LogType` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `LogContent` text COLLATE utf8_unicode_ci NOT NULL,
  `LogUser` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `LogIP` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `LogTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`LogID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `sys_log` (`LogID`, `LogType`, `LogContent`, `LogUser`, `LogIP`, `LogTime`) VALUES
(1, '系统', '清空系统操作记录', '管理员', '127.0.0.1', '2017-06-27 00:00:00');

CREATE TABLE IF NOT EXISTS `sys_menu` (
  `Menuid` int(11) NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `Fatherid` int(11) NOT NULL DEFAULT '0' COMMENT '父菜单ID',
  `Menuname` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单名称',
  `MenuIcon` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单图标（FontAwesome类）',
  `PageFile` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'View' COMMENT '对应文件路径',
  `PageDOS` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '对应文件名',
  `isPublic` int(1) DEFAULT '0' COMMENT '是否公有页面',
  PRIMARY KEY (`Menuid`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统 - 菜单列表';

INSERT INTO `sys_menu` (`Menuid`, `Fatherid`, `Menuname`, `MenuIcon`, `PageFile`, `PageDOS`, `isPublic`) VALUES
(1, 0, '系统', 'cogs', '', '', 0),
(3, 1, '菜单管理', 'bars', 'Sys', 'ManageMenu.php', 0),
(13, 10, '比赛报名统计', 'bar-chart', 'Statistics', 'toGamesList.php', 0),
(10, 0, '赛事', 'trophy', '', '', 0),
(4, 1, '用户管理', 'user-circle', 'User', 'toList.php', 0),
(7, 0, '运动员', 'users', '', '', 0),
(8, 7, '运动员管理', 'user', 'Athlete', 'toList.php', 0),
(9, 1, '角色管理', 'users', 'Role', 'toList.php', 0),
(11, 10, '赛事管理', 'list-alt', 'Games', 'toList.php', 0),
(12, 10, '赛事报名', 'sign-in', 'Enroll', 'toGamesList.php', 0),
(15, 7, '运动员升年级（暂未启用）', 'graduation-cap', 'Athlete', 'toGradeGrowUp.php', 0),
(16, 1, '清空缓存', 'trash', 'Sys', 'EmptyCache.php', 0),
(17, 1, '发布全局公告', 'bullhorn', 'Sys', 'toPubGlobalNotice.php', 0),
(18, 1, '操作记录', 'list-alt', 'Sys', 'toLogList.php', 0),
(19, 10, '比赛规程', 'newspaper-o', 'Games', 'toAllGamesNoticeList.php', 0);

CREATE TABLE IF NOT EXISTS `sys_user` (
  `Userid` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT '登录用户名',
  `RealName` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户真实姓名',
  `Password` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `salt` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '加密字符串',
  `Roleid` int(11) NOT NULL COMMENT '角色ID（与role_list关联）',
  `Status` int(1) NOT NULL COMMENT '状态',
  `originPassword` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '初始8位密码',
  `RegiDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册时间',
  `LastDate` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`Userid`),
  UNIQUE KEY `Index 2` (`UserName`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户 - 用户资料库';

INSERT INTO `sys_user` (`Userid`, `UserName`, `RealName`, `Password`, `salt`, `Roleid`, `Status`, `originPassword`, `RegiDate`, `LastDate`) VALUES
(2, 'lingdui', '测试领队', 'eb7d8d2f3f91cde53cfe2839d38614f6abe57617', 'nrJBJPad', 2, 1, '27R42A73', '2017-06-16 06:03:41', '2017-06-20 19:57:16'),
(3, 'athlete', '运动员', '1e7cff2cb649889d1ebeaf75ea1a51c4955328be', 'GCKnfLJx', 3, 1, '27Q34N27', '2017-06-16 06:13:29', '2017-06-27 16:51:14'),
(4, 'admin', '管理员', '8a7f54943ce89ec46070aae113410da4432bcd5d', 'qyLadhcw', 1, 1, '71W63H45', '2017-06-27 09:23:47', NULL);