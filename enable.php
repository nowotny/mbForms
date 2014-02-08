<?php

/**
 * mbForms
 *
 * Form Builder Plugin for WolfCMS
 * Please keep this message intact when redistributing this plugin.
 *
 * @author        Mike Barlow
 * @email        mike@mikebarlow.co.uk
 *
 * @file        enable.php
 * @date        16/08/2010
 *
 * Thanks to Sartas for SQLite Support.
 *
 */
// check for some constants
if (!defined("CMS_ROOT"))
{
    die("Invalid Action");
}

$PDO = Record::getConnection();

$driver = strtolower($PDO->getAttribute(Record::ATTR_DRIVER_NAME));

// setup the table
if($driver == 'mysql')
{
	$table1 = "CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "mbforms` (
	`id` int(10) NOT NULL auto_increment,
	`formname` varchar(255) NOT NULL,
	`emailto` varchar(255) NOT NULL,
	`usecaptcha` tinyint(1) NOT NULL default '0',
	`successtpl` text NOT NULL,
	PRIMARY KEY  (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	
	$table2 = "CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "mbforms_items` (
	`id` int(10) NOT NULL auto_increment,
	`formid` int(10) NOT NULL,
	`label` text NOT NULL,
	`extras` text NOT NULL,
	`orderno` int(5) NOT NULL,
	`type` varchar(10) NOT NULL,
	`formvalues` text NOT NULL,
	`isrequired` tinyint(1) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

} elseif($driver == 'sqlite')
{
	$table1 = "CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "mbforms` (
	`id` INTEGER NOT NULL PRIMARY KEY,
	`formname` varchar(255) NOT NULL,
	`emailto` varchar(255) NOT NULL,
	`usecaptcha` tinyint(1) NOT NULL default '0',
	`successtpl` text NOT NULL
	);";
	
	$table2 = "CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "mbforms_items` (
	`id` INTEGER NOT NULL PRIMARY KEY,
	`formid` int(10) NOT NULL,
	`label` text NOT NULL,
	`extras` text NOT NULL,
	`orderno` int(5) NOT NULL,
	`type` varchar(10) NOT NULL,
	`formvalues` text NOT NULL,
	`isrequired` tinyint(1) NOT NULL default '0'
	);";
}

$PDO->exec($table1);
$PDO->exec($table2);

$settings = Plugin::getAllSettings('mbforms');
if (!is_array($settings) OR count($settings) != 2)
{
    // setup the plugin settings
    Plugin::setAllSettings(array(
            'publickey' => '',
            'privatekey' => ''
            ), 'mbforms');
}
