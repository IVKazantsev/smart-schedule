<?php

use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;

function __scheduleMigrate(int $nextVersion, callable $callback): void
{
	global $DB;
	$moduleId = 'up.schedule';

	if (!ModuleManager::isModuleInstalled($moduleId))
	{
		return;
	}
	$currentVersion = (int)Option::get($moduleId, '~database_schema_version', 0);
	if ($currentVersion < $nextVersion)
	{
		include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/update_class.php');
		$updater = new CUpdater();
		$updater->Init('', 'mysql', '', '', $moduleId, 'DB');

		$callback($updater, $DB, 'mysql');
		Option::set($moduleId, '~database_schema_version', $nextVersion);
	}
}

__scheduleMigrate(3, function ($updater, $DB)
{
	if (
		$updater->CanUpdateDatabase()
		&& !$updater->TableExists('up_schedule_subject')
		&& !$updater->TableExists('up_schedule_audience')
		&& !$updater->TableExists('up_schedule_audience_type')
		&& !$updater->TableExists('up_schedule_group')
		&& !$updater->TableExists('up_schedule_subject_teacher')
		&& !$updater->TableExists('up_schedule_group_subject')
		&& !$updater->TableExists('up_schedule_role')
		&& !$updater->TableExists('up_schedule_couple')
	)
	{
		$DB->query("
			CREATE TABLE IF NOT EXISTS up_schedule_subject
			(
				ID INT NOT NULL AUTO_INCREMENT,
				TITLE VARCHAR(255) NOT NULL,
				AUDIENCE_TYPE_ID INT NOT NULL,
				PRIMARY KEY (ID)
			);");
		$DB->query("
			CREATE TABLE IF NOT EXISTS up_schedule_audience
			(
				ID INT NOT NULL AUTO_INCREMENT,
				NUMBER VARCHAR(10) NOT NULL,
				AUDIENCE_TYPE_ID INT NOT NULL,
				PRIMARY KEY (ID)
			);
		");
		$DB->query("
			CREATE TABLE IF NOT EXISTS up_schedule_audience_type
			(
				ID INT NOT NULL AUTO_INCREMENT,
				TITLE VARCHAR(100) NOT NULL,
				PRIMARY KEY (ID)
			);
		");
		$DB->query("
			CREATE TABLE IF NOT EXISTS up_schedule_group
			(
				ID INT NOT NULL AUTO_INCREMENT,
				TITLE varchar(255) NOT NULL,
				PRIMARY KEY (ID)
			);
		");
		$DB->query("	
			CREATE TABLE IF NOT EXISTS up_schedule_group_subject
			(
				SUBJECT_ID INT NOT NULL,
				GROUP_ID INT NOT NULL,
				HOURS_NUMBER INT NOT NULL,
				PRIMARY KEY (SUBJECT_ID, GROUP_ID)
			);
		");
		$DB->query("
			CREATE TABLE IF NOT EXISTS up_schedule_subject_teacher
			(
				SUBJECT_ID INT NOT NULL,
				TEACHER_ID INT NOT NULL,
				PRIMARY KEY(SUBJECT_ID, TEACHER_ID)
			);
		");
		$DB->query("
			CREATE TABLE IF NOT EXISTS up_schedule_role
			(
				ID INT NOT NULL AUTO_INCREMENT,
				TITLE VARCHAR(100) NOT NULL,
				PRIMARY KEY (ID)
			);
		");
		$DB->query("
			CREATE TABLE IF NOT EXISTS up_schedule_couple
			(
				GROUP_ID INT NOT NULL,
				SUBJECT_ID INT NOT NULL,
				TEACHER_ID INT NOT NULL,
				AUDIENCE_ID INT NOT NULL,
				WEEK_DAY INT NOT NULL,
				COUPLE_NUMBER_IN_DAY INT NOT NULL,
				WEEK_TYPE VARCHAR(10),
				PRIMARY KEY (GROUP_ID, SUBJECT_ID, TEACHER_ID, AUDIENCE_ID)
			);
		");
	}

});
