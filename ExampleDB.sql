CREATE DATABASE IF NOT EXISTS `dfd` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `dfd`;

DROP TABLE IF EXISTS `athlete_list`;
CREATE TABLE `athlete_list` (
  `AthID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL COMMENT '用户ID（与sys_user关联）',
  `Phone` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
  `RealName` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '真实姓名',
  `SchoolGrade` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '年级',
  `SchoolClass` varchar(2) COLLATE utf8_unicode_ci NOT NULL COMMENT '班别',
  `Sex` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '性别（男/女）',
  `YearGroup` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT '年龄组（4位年份）',
  `IDCard` varchar(18) COLLATE utf8_unicode_ci NOT NULL COMMENT '身份证',
  `IDCardType` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '证件类型（1二代身份证,2香港身份证,3护照）',
  `RegiDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='运动员 - 运动员资料库';

DROP TABLE IF EXISTS `cache_enroll_export`;
CREATE TABLE `cache_enroll_export` (
  `CacheID` int(11) NOT NULL,
  `SessionID` varchar(26) COLLATE utf8_unicode_ci NOT NULL,
  `UserID` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `Content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `CacheTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ExpTime` int(10) NOT NULL,
  `IP` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `cache_login`;
CREATE TABLE `cache_login` (
  `CacheID` int(11) NOT NULL,
  `UserID` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `RealName` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `SessionID` varchar(26) COLLATE utf8_unicode_ci NOT NULL,
  `ErrorTimes` int(1) DEFAULT NULL,
  `ExpTime` int(10) NOT NULL,
  `CacheTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IP` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `common_item`;
CREATE TABLE `common_item` (
  `id` int(11) NOT NULL,
  `ItemName` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='常用项目列表';

INSERT INTO `common_item` (`id`, `ItemName`) VALUES
(1, '25米自由泳'),
(2, '25米蝶泳'),
(3, '25米仰泳'),
(4, '25米蛙泳'),
(5, '25米自由泳扶板打腿'),
(6, '25米仰泳扶板打腿'),
(7, '25米蛙泳扶板打腿'),
(8, '25米蝶泳扶板打腿'),
(9, '50米自由泳'),
(10, '50米蝶泳'),
(11, '50米仰泳'),
(12, '50米蛙泳'),
(13, '50米自由泳扶板打腿'),
(14, '50米仰泳扶板打腿'),
(15, '50米蛙泳扶板打腿'),
(16, '50米蝶泳扶板打腿'),
(17, '100米自由泳'),
(18, '100米蝶泳'),
(19, '100米仰泳'),
(20, '100米蛙泳'),
(21, '200米自由泳'),
(22, '200米混合泳'),
(23, '400米自由泳'),
(24, '4X25米混合泳扶板打腿接力'),
(25, '4X25米自由泳扶板打腿接力'),
(26, '4X50米自由泳接力'),
(27, '4X50米混合泳接力');

DROP TABLE IF EXISTS `enroll_item`;
CREATE TABLE `enroll_item` (
  `id` int(11) NOT NULL,
  `AthID` int(11) NOT NULL COMMENT '运动员ID（关联athlete_list）',
  `GamesID` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT '比赛ID（关联games_list）',
  `ItemID` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT '项目ID（关联item_list）',
  `EnrollTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '报名时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='报名 - 运动员报名项目关联';

DROP TABLE IF EXISTS `file_list`;
CREATE TABLE `file_list` (
  `FileID` int(11) NOT NULL,
  `FilePath` text COLLATE utf8_unicode_ci NOT NULL COMMENT '文件路径（相对、包含文件名）',
  `FileName` text COLLATE utf8_unicode_ci NOT NULL COMMENT '文件显示的名称',
  `Code` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '文件密钥',
  `UploadUser` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT '上传用户名称',
  `UploadTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '上传时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文件 - 上传附件的列表';

DROP TABLE IF EXISTS `games_item`;
CREATE TABLE `games_item` (
  `id` int(11) NOT NULL,
  `GamesID` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '关联games_list',
  `ItemID` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '关联item_list'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='比赛 - 比赛与项目关联';

DROP TABLE IF EXISTS `games_list`;
CREATE TABLE `games_list` (
  `GamesID` int(11) NOT NULL COMMENT '比赛ID',
  `GamesName` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '比赛名称',
  `EndDate` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '报名截止日期（格式：年月日，如20140711）',
  `StartDate` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '比赛开始日期（格式：年月日，如20140711）',
  `Venue` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '比赛地点',
  `isEnd` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否关闭报名',
  `isPrivate` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否限制报名',
  `AllowUser` text COLLATE utf8_unicode_ci COMMENT '允许报名的运动员ID（JSON，关联athlete_list）'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='比赛 - 比赛列表';

DROP TABLE IF EXISTS `games_notice`;
CREATE TABLE `games_notice` (
  `NoticeID` int(11) NOT NULL,
  `GamesID` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '比赛ID（与games_list关联）',
  `Type` int(1) NOT NULL COMMENT '通知类型（1通知2规程3秩序册）',
  `Title` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '通知标题',
  `Content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '通知内容',
  `FileJSON` text COLLATE utf8_unicode_ci COMMENT '文件信息JSON [Name,Code]',
  `PageView` int(11) NOT NULL DEFAULT '0' COMMENT '浏览量',
  `PubTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布时间',
  `isDelete` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否已经删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='比赛 - 通知和文件列表';

DROP TABLE IF EXISTS `item_list`;
CREATE TABLE `item_list` (
  `ItemID` int(11) NOT NULL COMMENT '项目ID',
  `YearGroup` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT '年龄组（4位年份）',
  `ItemName` text COLLATE utf8_unicode_ci NOT NULL COMMENT '项目名称'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='比赛 - 项目库';

INSERT INTO `item_list` (`ItemID`, `YearGroup`, `ItemName`) VALUES
(1, '2009', '50米自由泳'),
(2, '2009', '50米蝶泳'),
(3, '2009', '50米仰泳'),
(4, '2009', '50米蛙泳'),
(5, '2009', '50米自由泳扶板打腿'),
(6, '2009', '50米仰泳扶板打腿'),
(7, '2009', '50米蛙泳扶板打腿'),
(8, '2009', '50米蝶泳扶板打腿'),
(9, '2009', '4X50米自由泳接力'),
(10, '2009', '4X50米混合泳接力'),
(11, '2010', '25米自由泳'),
(12, '2010', '25米蝶泳'),
(13, '2010', '25米仰泳'),
(14, '2010', '25米蛙泳'),
(15, '2010', '25米自由泳扶板打腿'),
(16, '2010', '25米仰泳扶板打腿'),
(17, '2010', '25米蛙泳扶板打腿'),
(18, '2010', '25米蝶泳扶板打腿'),
(19, '2010', '4X25米混合泳扶板打腿接力'),
(20, '2010', '4X25米自由泳扶板打腿接力'),
(21, '2011', '25米自由泳扶板打腿'),
(22, '2011', '25米仰泳扶板打腿'),
(23, '2011', '25米蛙泳扶板打腿'),
(24, '2011', '25米蝶泳扶板打腿'),
(25, '2011', '4X25米混合泳扶板打腿接力'),
(26, '2011', '4X25米自由泳扶板打腿接力'),
(27, '2006', '50米自由泳'),
(28, '2006', '50米蝶泳'),
(29, '2006', '50米仰泳'),
(30, '2006', '50米蛙泳'),
(31, '2006', '100米自由泳'),
(32, '2006', '100米蝶泳'),
(33, '2006', '100米仰泳'),
(34, '2006', '100米蛙泳'),
(35, '2006', '200米混合泳'),
(36, '2006', '4X50米自由泳接力'),
(37, '2006', '4X50米混合泳接力'),
(38, '2007', '50米自由泳'),
(39, '2007', '50米蝶泳'),
(40, '2007', '50米仰泳'),
(41, '2007', '50米蛙泳'),
(42, '2007', '100米自由泳'),
(43, '2007', '100米蝶泳'),
(44, '2007', '100米仰泳'),
(45, '2007', '100米蛙泳'),
(46, '2007', '200米混合泳'),
(47, '2007', '4X50米自由泳接力'),
(48, '2007', '4X50米混合泳接力'),
(49, '2008', '50米自由泳'),
(50, '2008', '50米蝶泳'),
(51, '2008', '50米仰泳'),
(52, '2008', '50米蛙泳'),
(53, '2008', '50米自由泳扶板打腿'),
(54, '2008', '50米仰泳扶板打腿'),
(55, '2008', '50米蛙泳扶板打腿'),
(56, '2008', '50米蝶泳扶板打腿'),
(57, '2008', '100米自由泳'),
(58, '2008', '4X50米自由泳接力'),
(59, '2008', '4X50米混合泳接力'),
(60, '2009', '100米自由泳'),
(61, '2011', '25米自由泳扶板打腿、25米仰泳腿、25米蛙泳扶板蹬腿、25米蝶泳扶板打腿'),
(62, '2010', '25米自由泳、25米仰泳、25米蛙泳、25米蝶泳'),
(63, '2009', '50米自由泳、50米仰泳、50米蛙泳、50米蝶泳'),
(64, '2006', '50米自由泳、50米仰泳、50米蛙泳、50米蝶泳'),
(65, '2007', '50米自由泳、50米仰泳、50米蛙泳、50米蝶泳'),
(66, '2008', '50米自由泳、50米仰泳、50米蛙泳、50米蝶泳'),
(72, '2012', '25米蝶泳扶板打腿、25米仰泳腿、25米蛙泳扶板蹬腿、25米自由泳扶板打腿'),
(73, '2011', '25米自由泳、25米仰泳、25米蛙泳、25米蝶泳'),
(74, '2010', '50米自由泳、50米仰泳、50米蛙泳、50米蝶泳'),
(75, '2010', '4X50米自由泳接力'),
(76, '2010', '4X50米混合泳接力'),
(77, '2011', '4X50米自由泳接力'),
(78, '2011', '4X50米混合泳接力'),
(79, '2012', '4X25米自由泳扶板打腿接力'),
(80, '2012', '4X25米混合泳扶板打腿接力'),
(81, '2012', '25米蝶泳扶板打腿'),
(82, '2012', '25米仰泳腿'),
(83, '2012', '25米蛙泳扶板蹬腿'),
(84, '2012', '25米自由泳扶板打腿'),
(85, '2011', '50米自由泳、50米仰泳、50米蛙泳、50米蝶泳'),
(86, '2010', '50米自由泳'),
(87, '2010', '50米仰泳'),
(88, '2010', '50米蛙泳'),
(89, '2010', '50米蝶泳'),
(90, '2002', '测试项目');

DROP TABLE IF EXISTS `role_list`;
CREATE TABLE `role_list` (
  `RoleID` int(11) NOT NULL COMMENT '角色ID',
  `RoleName` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `Brief` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '备注',
  `isSuper` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否系统角色(0非1是)(系统角色不可删除)',
  `isAthlete` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否运动员角色(0非1是)'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色 - 角色列表';

INSERT INTO `role_list` (`RoleID`, `RoleName`, `Brief`, `isSuper`, `isAthlete`) VALUES
(1, '超级管理员', '最高权限用户，内置角色不可删除', '1', '0'),
(2, '领队', '行使比赛管理、运动员信息管理权力', '0', '0'),
(3, '运动员', '行使比赛报名、运动员资料维护权力', '0', '1');

DROP TABLE IF EXISTS `role_purview`;
CREATE TABLE `role_purview` (
  `id` int(11) NOT NULL,
  `RoleID` int(11) NOT NULL COMMENT '角色ID（与role_list关联）',
  `PurvID` int(11) NOT NULL COMMENT '权限ID（与sys_menu关联）'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色 - 角色与权限关联';

INSERT INTO `role_purview` (`id`, `RoleID`, `PurvID`) VALUES
(126, 1, 26),
(125, 1, 25),
(124, 1, 24),
(123, 1, 23),
(122, 1, 22),
(121, 1, 21),
(120, 1, 20),
(119, 1, 19),
(118, 1, 18),
(117, 1, 17),
(116, 1, 16),
(115, 1, 15),
(114, 1, 12),
(113, 1, 11),
(112, 1, 9),
(137, 2, 22),
(136, 2, 21),
(135, 2, 20),
(134, 2, 8),
(133, 2, 7),
(132, 2, 19),
(131, 2, 11),
(130, 2, 13),
(59, 3, 19),
(58, 3, 12),
(57, 3, 10),
(111, 1, 8),
(110, 1, 7),
(109, 1, 4),
(108, 1, 10),
(129, 2, 10),
(128, 2, 17),
(127, 2, 1),
(60, 3, 20),
(61, 3, 21),
(62, 3, 22),
(107, 1, 13),
(106, 1, 3),
(105, 1, 1),
(138, 2, 25),
(139, 2, 26);

DROP TABLE IF EXISTS `sys_log`;
CREATE TABLE `sys_log` (
  `LogID` int(11) NOT NULL,
  `LogType` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `LogContent` text COLLATE utf8_unicode_ci NOT NULL,
  `LogUser` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `LogIP` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `LogTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `sys_menu`;
CREATE TABLE `sys_menu` (
  `MenuID` int(11) NOT NULL COMMENT '菜单ID',
  `FatherID` int(11) NOT NULL DEFAULT '0' COMMENT '父菜单ID',
  `Menuname` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单名称',
  `MenuIcon` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单图标（FontAwesome类）',
  `PageFile` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'View' COMMENT '对应文件路径',
  `PageDOS` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '对应文件名',
  `isPublic` int(1) DEFAULT '0' COMMENT '是否公有页面'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统 - 菜单列表';

INSERT INTO `sys_menu` (`MenuID`, `FatherID`, `Menuname`, `MenuIcon`, `PageFile`, `PageDOS`, `isPublic`) VALUES
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
(19, 10, '比赛规程', 'newspaper-o', 'Games', 'toAllGamesNoticeList.php', 0),
(20, 0, '工单', 'envelope', '', '', 0),
(21, 20, '我的工单', 'list', 'WorkOrder', 'toList.php', 0),
(22, 20, '提交工单', 'envelope-open', 'WorkOrder', 'CreateWorkOrder.php', 0),
(23, 20, '工单回复（未启用）', 'reply-all', 'WorkOrder', 'ReplyWorkOrder.php', 0),
(24, 20, '所有工单', 'list-alt', 'WorkOrder', 'toAdminList.php', 0),
(25, 0, '项目', 'server', '', '', 0),
(26, 25, '年龄组项目管理', 'sitemap', 'Item', 'toYearGroupList.php', 0);

DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE `sys_user` (
  `UserID` int(11) NOT NULL,
  `UserName` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT '登录用户名',
  `RealName` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户真实姓名',
  `Password` text COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `salt` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '加密字符串',
  `RoleID` int(11) NOT NULL COMMENT '角色ID（与role_list关联）',
  `Status` int(1) NOT NULL COMMENT '状态',
  `originPassword` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '初始8位密码',
  `wxOpenID` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `RegiDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册时间',
  `LastDate` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '最后登录时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户 - 用户资料库';

INSERT INTO `sys_user` (`UserID`, `UserName`, `RealName`, `Password`, `salt`, `RoleID`, `Status`, `originPassword`, `RegiDate`) VALUES
(2, 'lingdui', '测试领队', 'eb7d8d2f3f91cde53cfe2839d38614f6abe57617', 'nrJBJPad', 2, 1, '27R42A73', '2017-06-16 00:00:00'),
(3, 'athlete', '运动员', '1e7cff2cb649889d1ebeaf75ea1a51c4955328be', 'GCKnfLJx', 3, 1, '27Q34N27', '2017-06-16 00:00:00'),
(4, 'admin', '管理员', '8a7f54943ce89ec46070aae113410da4432bcd5d', 'qyLadhcw', 1, 1, '71W63H45', '2017-06-16 00:00:00');

DROP TABLE IF EXISTS `workorder_list`;
CREATE TABLE `workorder_list` (
  `OrderID` int(11) NOT NULL COMMENT '工单ID',
  `CreateRealName` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT '下单人真实姓名',
  `Type` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '工单类型',
  `Status` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '工单状态（0关闭1待处理2待评分）',
  `OrderTime` varchar(19) COLLATE utf8_unicode_ci NOT NULL COMMENT '下单时间',
  `Modules` text COLLATE utf8_unicode_ci NOT NULL COMMENT '工单对应的模块',
  `Title` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '工单标题',
  `Content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '工单具体内容',
  `ReplyMethod` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '回复方式（1邮件2QQ）',
  `ReplyUserInfo` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '回复用户资料',
  `ReplyRealName` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '回复人的真实姓名',
  `ReplyTime` varchar(19) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '回复时间',
  `ReplyContent` text COLLATE utf8_unicode_ci COMMENT '回复内容',
  `ReplyStar` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '回复得分（1~5）'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `athlete_list`
  ADD PRIMARY KEY (`AthID`),
  ADD UNIQUE KEY `Unique Key` (`IDCard`);

ALTER TABLE `cache_enroll_export`
  ADD PRIMARY KEY (`CacheID`);

ALTER TABLE `cache_login`
  ADD PRIMARY KEY (`CacheID`);

ALTER TABLE `common_item`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `enroll_item`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `file_list`
  ADD PRIMARY KEY (`FileID`),
  ADD UNIQUE KEY `UNIQUE` (`Code`);

ALTER TABLE `games_item`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `games_list`
  ADD PRIMARY KEY (`GamesID`);

ALTER TABLE `games_notice`
  ADD PRIMARY KEY (`NoticeID`);

ALTER TABLE `item_list`
  ADD PRIMARY KEY (`ItemID`);

ALTER TABLE `role_list`
  ADD PRIMARY KEY (`RoleID`);

ALTER TABLE `role_purview`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sys_log`
  ADD PRIMARY KEY (`LogID`);

ALTER TABLE `sys_menu`
  ADD PRIMARY KEY (`MenuID`);

ALTER TABLE `sys_user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserName` (`UserName`),
  ADD UNIQUE KEY `wxOpenID` (`wxOpenID`);

ALTER TABLE `workorder_list`
  ADD PRIMARY KEY (`OrderID`);


ALTER TABLE `athlete_list`
  MODIFY `AthID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `cache_enroll_export`
  MODIFY `CacheID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `cache_login`
  MODIFY `CacheID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `common_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
ALTER TABLE `enroll_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `file_list`
  MODIFY `FileID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `games_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `games_list`
  MODIFY `GamesID` int(11) NOT NULL AUTO_INCREMENT COMMENT '比赛ID';
ALTER TABLE `games_notice`
  MODIFY `NoticeID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `item_list`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT COMMENT '项目ID', AUTO_INCREMENT=91;
ALTER TABLE `role_list`
  MODIFY `RoleID` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色ID', AUTO_INCREMENT=4;
ALTER TABLE `role_purview`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;
ALTER TABLE `sys_log`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `sys_menu`
  MODIFY `MenuID` int(11) NOT NULL AUTO_INCREMENT COMMENT '菜单ID', AUTO_INCREMENT=27;
ALTER TABLE `sys_user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `workorder_list`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT COMMENT '工单ID';