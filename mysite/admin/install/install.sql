CREATE TABLE IF NOT EXISTS `#__mysite_config` (
  `config_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `config_name` VARCHAR(255) NOT NULL ,
  `value` TEXT NOT NULL ,
  PRIMARY KEY (`config_id`) )
TYPE=MyISAM 
DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `#__mysite_items` (
  `item_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `url` VARCHAR(255) NOT NULL ,
  `itemid` INT(11) NOT NULL COMMENT 'Itemid from #__menu' ,
  `ordering` INT(11) NOT NULL ,
  `enabled` BINARY NOT NULL DEFAULT '1' ,
  `parent` int(11) NOT NULL default '0',
  `sublevel` INT(11) NOT NULL ,
  `last_modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `change_frequency` VARCHAR(15) default '',
  `priority` CHAR(3) default '',
  `menutype` VARCHAR(75) NOT NULL ,
  PRIMARY KEY (`item_id`) )
TYPE=MyISAM 
DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `#__mysite_menus` (
  `menu_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `menutype` VARCHAR(75) NOT NULL ,
  `enabled` BINARY NOT NULL DEFAULT '1' ,
  `ordering` INT(11) NOT NULL ,
  `description` VARCHAR(255) NOT NULL default '',
  PRIMARY KEY (`menu_id`) )
TYPE=MyISAM 
DEFAULT CHARACTER SET utf8;