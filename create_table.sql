DROP TABLE IF EXISTS `news` ;

CREATE TABLE `news` (
`news_id` int(10) NOT NULL AUTO_INCREMENT,
`desc_short` VARCHAR(255) NOT NULL DEFAULT '',
`desc_full` TEXT NOT NULL DEFAULT '',
`added_time` INT (10) NOT NULL,
`active` SET ('Y', 'N') DEFAULT 'Y',
PRIMARY KEY (`news_id`),
UNIQUE KEY news_id (`news_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS `users` ;

CREATE TABLE `users` (
`user_id` int(10) NOT NULL AUTO_INCREMENT,
`user_email` VARCHAR(30) NOT NULL DEFAULT '',
`user_login` VARCHAR(20) NOT NULL DEFAULT '',
`user_pass` VARCHAR(20) NOT NULL DEFAULT '',
PRIMARY KEY (`user_id`),
UNIQUE KEY user_id (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ;
