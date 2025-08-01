PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE admin (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT NOT NULL,
  email TEXT NOT NULL,
  password TEXT NOT NULL,
  role TEXT NOT NULL DEFAULT 'admin',
  status INTEGER NOT NULL DEFAULT 1,
  this_login DATETIME DEFAULT NULL,
  last_login DATETIME DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO admin VALUES(1,'Ramtin Ganji pour','armandesktop520@gmail.com','$2y$12$lM0xEvyeKq0yWlr.SrwoRe0niCfrLkSnAAhM36S4jvBVIqqHnBsoe','owner',1,NULL,NULL,'2025-07-13 13:17:55','2025-08-01 08:46:25');
CREATE TABLE app_settings (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  key_name TEXT NOT NULL UNIQUE,
  value TEXT NOT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  comment TEXT NOT NULL DEFAULT ''
);
INSERT INTO app_settings VALUES(1,'admin_prefix','admin','2025-04-22 08:21:43', 'This is The Web Path Use in Url of AdminPanel, Don''t Write ''/'' in Start and end of Path, you can use in middle of path.');
INSERT INTO app_settings VALUES(2,'email_interval','120','2025-04-22 08:21:43', 'How Much Time To Resend Verification Email.');
INSERT INTO app_settings VALUES(3,'email_verify','0',1745310103000, 'Toggle 0/1 to Enable/Disable Login Email Verification.');
INSERT INTO app_settings VALUES(4,'contact_page_active','1',1745310103000, 'Toggle 0/1 to Enable/Disable Showing Contact Page.');
INSERT INTO app_settings VALUES(5,'time_zone','Asia/Tehran',1745310103000, 'Do not Change This Without Consultation With Developer.');
CREATE TABLE gallery (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  title TEXT DEFAULT NULL,
  image_path TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  on_delete INTEGER NOT NULL DEFAULT 0,
  deleted_at DATETIME DEFAULT NULL
);
CREATE TABLE projects (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  title TEXT NOT NULL,
  slug TEXT DEFAULT NULL UNIQUE,
  programming_language TEXT DEFAULT NULL,
  framework TEXT DEFAULT NULL,
  description TEXT DEFAULT NULL,
  image TEXT DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  deleted_at DATETIME DEFAULT NULL,
  on_delete INTEGER NOT NULL DEFAULT 0
);
CREATE TABLE gallery_project (
  gallery_id INTEGER NOT NULL,
  projects_id INTEGER NOT NULL,
  PRIMARY KEY (gallery_id, projects_id),
  FOREIGN KEY (gallery_id) REFERENCES gallery (id) ON DELETE CASCADE,
  FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE
);
CREATE TABLE uri (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  uri TEXT NOT NULL UNIQUE,
  view_count INTEGER DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE views (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  session_id TEXT NOT NULL,
  ip_address TEXT NOT NULL,
  device_name TEXT NOT NULL,
  country TEXT DEFAULT NULL,
  page_id TEXT NOT NULL DEFAULT '[]' CHECK (json_valid(page_id)),
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
DELETE FROM sqlite_sequence;
INSERT INTO sqlite_sequence VALUES('app_settings',4);
INSERT INTO sqlite_sequence VALUES('uri',5);
INSERT INTO sqlite_sequence VALUES('views',826);
INSERT INTO sqlite_sequence VALUES('admin',1);
COMMIT;
