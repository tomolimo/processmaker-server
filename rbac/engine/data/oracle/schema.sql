

/* -----------------------------------------------------------------------
   PERMISSIONS
   ----------------------------------------------------------------------- */

DROP TABLE "RBAC_PERMISSIONS" CASCADE CONSTRAINTS;


CREATE TABLE "RBAC_PERMISSIONS"
(
	"PER_UID" VARCHAR2(32) default '' NOT NULL,
	"PER_CODE" VARCHAR2(64) default '' NOT NULL,
	"PER_CREATE_DATE" DATE default '0000-00-00 00:00:00' NOT NULL,
	"PER_UPDATE_DATE" DATE default '0000-00-00 00:00:00' NOT NULL,
	"PER_STATUS" NUMBER default 1 NOT NULL
);

	ALTER TABLE "RBAC_PERMISSIONS"
		ADD CONSTRAINT "PERMISSIONS_PK"
	PRIMARY KEY ("PER_UID");


/* -----------------------------------------------------------------------
   ROLES
   ----------------------------------------------------------------------- */

DROP TABLE "RBAC_ROLES" CASCADE CONSTRAINTS;


CREATE TABLE "RBAC_ROLES"
(
	"ROL_UID" VARCHAR2(32) default '' NOT NULL,
	"ROL_PARENT" VARCHAR2(32) default '' NOT NULL,
	"ROL_SYSTEM" VARCHAR2(32) default '' NOT NULL,
	"ROL_CODE" VARCHAR2(32) default '' NOT NULL,
	"ROL_CREATE_DATE" DATE default '0000-00-00 00:00:00' NOT NULL,
	"ROL_UPDATE_DATE" DATE default '0000-00-00 00:00:00' NOT NULL,
	"ROL_STATUS" NUMBER default 1 NOT NULL
);

	ALTER TABLE "RBAC_ROLES"
		ADD CONSTRAINT "ROLES_PK"
	PRIMARY KEY ("ROL_UID");


/* -----------------------------------------------------------------------
   ROLES_PERMISSIONS
   ----------------------------------------------------------------------- */

DROP TABLE "RBAC_ROLES_PERMISSIONS" CASCADE CONSTRAINTS;


CREATE TABLE "RBAC_ROLES_PERMISSIONS"
(
	"ROL_UID" VARCHAR2(32) default '' NOT NULL,
	"PER_UID" VARCHAR2(32) default '' NOT NULL
);

	ALTER TABLE "RBAC_ROLES_PERMISSIONS"
		ADD CONSTRAINT "RBAC_ROLES_PERMISSIONS_PK"
	PRIMARY KEY ("ROL_UID","PER_UID");


/* -----------------------------------------------------------------------
   SYSTEMS
   ----------------------------------------------------------------------- */

DROP TABLE "RBAC_SYSTEMS" CASCADE CONSTRAINTS;


CREATE TABLE "RBAC_SYSTEMS"
(
	"SYS_UID" VARCHAR2(32) default '' NOT NULL,
	"SYS_CODE" VARCHAR2(32) default '' NOT NULL,
	"SYS_CREATE_DATE" DATE default '0000-00-00 00:00:00' NOT NULL,
	"SYS_UPDATE_DATE" DATE default '0000-00-00 00:00:00' NOT NULL,
	"SYS_STATUS" NUMBER default 0 NOT NULL
);

	ALTER TABLE "RBAC_SYSTEMS"
		ADD CONSTRAINT "SYSTEMS_PK"
	PRIMARY KEY ("SYS_UID");


/* -----------------------------------------------------------------------
   USERS
   ----------------------------------------------------------------------- */

DROP TABLE "RBAC_USERS" CASCADE CONSTRAINTS;


CREATE TABLE "RBAC_USERS"
(
	"USR_UID" VARCHAR2(32) default '' NOT NULL,
	"USR_USERNAME" VARCHAR2(100) default '' NOT NULL,
	"USR_PASSWORD" VARCHAR2(32) default '' NOT NULL,
	"USR_FIRSTNAME" VARCHAR2(50) default '' NOT NULL,
	"USR_LASTNAME" VARCHAR2(50) default '' NOT NULL,
	"USR_EMAIL" VARCHAR2(100) default '' NOT NULL,
	"USR_DUE_DATE" DATE default '0000-00-00' NOT NULL,
	"USR_CREATE_DATE" DATE default '0000-00-00 00:00:00' NOT NULL,
	"USR_UPDATE_DATE" DATE default '0000-00-00 00:00:00' NOT NULL,
	"USR_STATUS" NUMBER default 1 NOT NULL
);

	ALTER TABLE "RBAC_USERS"
		ADD CONSTRAINT "USERS_PK"
	PRIMARY KEY ("USR_UID");


/* -----------------------------------------------------------------------
   USERS_ROLES
   ----------------------------------------------------------------------- */

DROP TABLE "RBAC_USERS_ROLES" CASCADE CONSTRAINTS;


CREATE TABLE "RBAC_USERS_ROLES"
(
	"USR_UID" VARCHAR2(32) default '' NOT NULL,
	"ROL_UID" VARCHAR2(32) default '' NOT NULL
);

	ALTER TABLE "RBAC_USERS_ROLES"
		ADD CONSTRAINT "USERS_ROLES_PK"
	PRIMARY KEY ("USR_UID","ROL_UID");
