-- import to SQLite by running: sqlite3.exe db.sqlite3 -init sqlite.sql

PRAGMA journal_mode = MEMORY;
PRAGMA synchronous = OFF;
PRAGMA foreign_keys = OFF;
PRAGMA ignore_check_constraints = OFF;
PRAGMA auto_vacuum = NONE;
PRAGMA secure_delete = OFF;
BEGIN TRANSACTION;

SET SQL_MODE = '';
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `admin` (
`id` INTEGER  NOT NULL ,
`username` TEXT NOT NULL,
`email` TEXT NOT NULL,
`password` TEXT NOT NULL,
`role` TEXT  NOT NULL DEFAULT 'admin',
`status` tinyINTEGER NOT NULL DEFAULT 1,
`this_login` datetime DEFAULT NULL,
`last_login` datetime DEFAULT NULL,
`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
PRIMARY KEY (`id`)
);

CREATE TABLE `app_settings` (
`id` INTEGER NOT NULL ,
`key_name` TEXT NOT NULL,
`value` text NOT NULL,
`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
PRIMARY KEY (`id`)
);
INSERT INTO `app_settings` (`id`, `key_name`, `value`, `updated_at`) VALUES
(1, 'admin_prefix', 'admin', '2025-04-22 08:21:43');
INSERT INTO `app_settings` (`id`, `key_name`, `value`, `updated_at`) VALUES
(2, 'email_interval', '120', '2025-04-22 08:21:43');
INSERT INTO `app_settings` (`id`, `key_name`, `value`, `updated_at`) VALUES
(3, 'email_verify', '0', '2025-04-22 08:21:43');
INSERT INTO `app_settings` (`id`, `key_name`, `value`, `updated_at`) VALUES
(4, 'contact_page_active', '1', '2025-04-22 08:21:43');

CREATE TABLE `gallery` (
`id` INTEGER  NOT NULL ,
`title` TEXT DEFAULT NULL,
`image_path` TEXT NOT NULL,
`created_at` datetime NOT NULL DEFAULT current_timestamp(),
`updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
`on_delete` tinyINTEGER NOT NULL DEFAULT 0,
`deleted_at` datetime DEFAULT NULL,
PRIMARY KEY (`id`)
);

CREATE TABLE `projects` (
`id` INTEGER  NOT NULL ,
`title` TEXT NOT NULL,
`slug` TEXT DEFAULT NULL,
`programming_language` TEXT DEFAULT NULL,
`framework` TEXT DEFAULT NULL,
`description` text DEFAULT NULL,
`image` TEXT DEFAULT NULL,
`created_at` datetime NOT NULL DEFAULT current_timestamp(),
`updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
`deleted_at` datetime DEFAULT NULL,
`on_delete` tinyINTEGER NOT NULL DEFAULT 0,
PRIMARY KEY (`id`)
);

CREATE TABLE `gallery_project` (
`gallery_id` INTEGER  NOT NULL,
`projects_id` INTEGER  NOT NULL,
PRIMARY KEY (`gallery_id`, `projects_id`),
FOREIGN KEY (`gallery_id`) REFERENCES `gallery` (`id`) ON DELETE CASCADE,
FOREIGN KEY (`projects_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
);

CREATE TABLE `uri` (
`id` INTEGER  NOT NULL ,
`uri` TEXT NOT NULL,
`view_count` INTEGER DEFAULT 0,
`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
PRIMARY KEY (`id`)
);

CREATE TABLE `views` (
`id` INTEGER  NOT NULL ,
`session_id` TEXT NOT NULL,
`ip_address` TEXT NOT NULL,
`device_name` TEXT NOT NULL,
`country` TEXT DEFAULT NULL,
`page_id` TEXT   NOT NULL DEFAULT '[]' CHECK (json_valid(`page_id`)),
`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
PRIMARY KEY (`id`)
);



CREATE UNIQUE INDEX `admin_username` ON `admin` (`username`);
CREATE UNIQUE INDEX `admin_email` ON `admin` (`email`);
CREATE UNIQUE INDEX `app_settings_key_name` ON `app_settings` (`key_name`);
CREATE UNIQUE INDEX `projects_slug` ON `projects` (`slug`);
CREATE UNIQUE INDEX `uri_uri` ON `uri` (`uri`);

COMMIT;
PRAGMA ignore_check_constraints = ON;
PRAGMA foreign_keys = ON;
PRAGMA journal_mode = WAL;
PRAGMA synchronous = NORMAL;
