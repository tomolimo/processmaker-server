
/* ---------------------------------------------------------------------- */
/* PERMISSIONS											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'RBAC_PERMISSIONS')
BEGIN
	 DECLARE @reftable_1 nvarchar(60), @constraintname_1 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'RBAC_PERMISSIONS'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_1, @constraintname_1
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_1+' drop constraint '+@constraintname_1)
	   FETCH NEXT from refcursor into @reftable_1, @constraintname_1
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [RBAC_PERMISSIONS]
END


CREATE TABLE [RBAC_PERMISSIONS]
(
	[PER_UID] VARCHAR(32) default '' NOT NULL,
	[PER_CODE] VARCHAR(64) default '' NOT NULL,
	[PER_CREATE_DATE] CHAR(19) default '0000-00-00 00:00:00' NOT NULL,
	[PER_UPDATE_DATE] CHAR(19) default '0000-00-00 00:00:00' NOT NULL,
	[PER_STATUS] INT default 1 NOT NULL,
	[PER_SYSTEM] VARCHAR(32) default '00000000000000000000000000000002' NOT NULL,
	CONSTRAINT RBAC_PERMISSIONS_PK PRIMARY KEY ([PER_UID])
);

/* ---------------------------------------------------------------------- */
/* ROLES											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'RBAC_ROLES')
BEGIN
	 DECLARE @reftable_2 nvarchar(60), @constraintname_2 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'ROLES'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_2, @constraintname_2
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_2+' drop constraint '+@constraintname_2)
	   FETCH NEXT from refcursor into @reftable_2, @constraintname_2
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [RBAC_ROLES]
END


CREATE TABLE [RBAC_ROLES]
(
	[ROL_UID] VARCHAR(32) default '' NOT NULL,
	[ROL_PARENT] VARCHAR(32) default '' NOT NULL,
	[ROL_SYSTEM] VARCHAR(32) default '' NOT NULL,
	[ROL_CODE] VARCHAR(32) default '' NOT NULL,
	[ROL_CREATE_DATE] CHAR(19) default '0000-00-00 00:00:00' NOT NULL,
	[ROL_UPDATE_DATE] CHAR(19) default '0000-00-00 00:00:00' NOT NULL,
	[ROL_STATUS] INT default 1 NOT NULL,
	CONSTRAINT RBAC_ROLES_PK PRIMARY KEY ([ROL_UID])
);

/* ---------------------------------------------------------------------- */
/* ROLES_PERMISSIONS											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'RBAC_ROLES_PERMISSIONS')
BEGIN
	 DECLARE @reftable_3 nvarchar(60), @constraintname_3 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'RBAC_ROLES_PERMISSIONS'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_3, @constraintname_3
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_3+' drop constraint '+@constraintname_3)
	   FETCH NEXT from refcursor into @reftable_3, @constraintname_3
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [RBAC_ROLES_PERMISSIONS]
END


CREATE TABLE [RBAC_ROLES_PERMISSIONS]
(
	[ROL_UID] VARCHAR(32) default '' NOT NULL,
	[PER_UID] VARCHAR(32) default '' NOT NULL,
	CONSTRAINT RBAC_ROLES_PERMISSIONS_PK PRIMARY KEY ([ROL_UID],[PER_UID])
);

/* ---------------------------------------------------------------------- */
/* SYSTEMS											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'RBAC_SYSTEMS')
BEGIN
	 DECLARE @reftable_4 nvarchar(60), @constraintname_4 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'RBAC_SYSTEMS'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_4, @constraintname_4
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_4+' drop constraint '+@constraintname_4)
	   FETCH NEXT from refcursor into @reftable_4, @constraintname_4
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [RBAC_SYSTEMS]
END


CREATE TABLE [RBAC_SYSTEMS]
(
	[SYS_UID] VARCHAR(32) default '' NOT NULL,
	[SYS_CODE] VARCHAR(32) default '' NOT NULL,
	[SYS_CREATE_DATE] CHAR(19) default '0000-00-00 00:00:00' NOT NULL,
	[SYS_UPDATE_DATE] CHAR(19) default '0000-00-00 00:00:00' NOT NULL,
	[SYS_STATUS] INT default 0 NOT NULL,
	CONSTRAINT RBAC_SYSTEMS_PK PRIMARY KEY ([SYS_UID])
);

/* ---------------------------------------------------------------------- */
/* RBAC_USERS											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'RBAC_USERS')
BEGIN
	 DECLARE @reftable_5 nvarchar(60), @constraintname_5 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'RBAC_USERS'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_5, @constraintname_5
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_5+' drop constraint '+@constraintname_5)
	   FETCH NEXT from refcursor into @reftable_5, @constraintname_5
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [RBAC_USERS]
END


CREATE TABLE [RBAC_USERS]
(
	[USR_UID] VARCHAR(32) default '' NOT NULL,
	[USR_USERNAME] VARCHAR(100) default '' NOT NULL,
	[USR_PASSWORD] VARCHAR(32) default '' NOT NULL,
	[USR_FIRSTNAME] VARCHAR(50) default '' NOT NULL,
	[USR_LASTNAME] VARCHAR(50) default '' NOT NULL,
	[USR_EMAIL] VARCHAR(100) default '' NOT NULL,
	[USR_DUE_DATE] CHAR(19) default '0000-00-00' NOT NULL,
	[USR_CREATE_DATE] CHAR(19) default '0000-00-00 00:00:00' NOT NULL,
	[USR_UPDATE_DATE] CHAR(19) default '0000-00-00 00:00:00' NOT NULL,
	[USR_STATUS] INT default 1 NOT NULL,
	[USR_AUTH_TYPE] VARCHAR(32) default '' NOT NULL,
	[UID_AUTH_SOURCE] VARCHAR(32) default '' NOT NULL,
	[USR_AUTH_USER_DN] VARCHAR(MAX) NULL,
	[USR_AUTH_SUPERVISOR_DN] VARCHAR(255) default '' NOT NULL,
	CONSTRAINT RBAC_USERS_PK PRIMARY KEY ([USR_UID])
);

/* ---------------------------------------------------------------------- */
/* USERS_ROLES											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'RBAC_USERS_ROLES')
BEGIN
	 DECLARE @reftable_6 nvarchar(60), @constraintname_6 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'RBAC_USERS_ROLES'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_6, @constraintname_6
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_6+' drop constraint '+@constraintname_6)
	   FETCH NEXT from refcursor into @reftable_6, @constraintname_6
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [RBAC_USERS_ROLES]
END


CREATE TABLE [RBAC_USERS_ROLES]
(
	[USR_UID] VARCHAR(32) default '' NOT NULL,
	[ROL_UID] VARCHAR(32) default '' NOT NULL,
	CONSTRAINT RBAC_USERS_ROLES_PK PRIMARY KEY ([USR_UID],[ROL_UID])
);

/* ---------------------------------------------------------------------- */
/* AUTHENTICATION_SOURCE											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'RBAC_AUTHENTICATION_SOURCE')
BEGIN
	 DECLARE @reftable_7 nvarchar(60), @constraintname_7 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'RBAC_AUTHENTICATION_SOURCE'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_7, @constraintname_7
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_7+' drop constraint '+@constraintname_7)
	   FETCH NEXT from refcursor into @reftable_7, @constraintname_7
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [RBAC_AUTHENTICATION_SOURCE]
END


CREATE TABLE [RBAC_AUTHENTICATION_SOURCE]
(
	[AUTH_SOURCE_UID] VARCHAR(32) default '' NOT NULL,
	[AUTH_SOURCE_NAME] VARCHAR(50) default '' NOT NULL,
	[AUTH_SOURCE_PROVIDER] VARCHAR(20) default '' NOT NULL,
	[AUTH_SOURCE_SERVER_NAME] VARCHAR(50) default '' NOT NULL,
	[AUTH_SOURCE_PORT] INT default 389 NULL,
	[AUTH_SOURCE_ENABLED_TLS] INT default 0 NULL,
	[AUTH_SOURCE_VERSION] VARCHAR(16) default '3' NOT NULL,
	[AUTH_SOURCE_BASE_DN] VARCHAR(128) default '' NOT NULL,
	[AUTH_ANONYMOUS] INT default 0 NULL,
	[AUTH_SOURCE_SEARCH_USER] VARCHAR(128) default '' NOT NULL,
	[AUTH_SOURCE_PASSWORD] VARCHAR(32) default '' NOT NULL,
	[AUTH_SOURCE_ATTRIBUTES] VARCHAR(255) default '' NOT NULL,
	[AUTH_SOURCE_OBJECT_CLASSES] VARCHAR(255) default '' NOT NULL,
	[AUTH_SOURCE_DATA] TEXT  NULL,
	CONSTRAINT RBAC_AUTHENTICATION_SOURCE_PK PRIMARY KEY ([AUTH_SOURCE_UID])
);


/* ---------------------------------------------------------------------- */
/* USERS											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'RBAC_USERS')
BEGIN
	 DECLARE @reftable_8 nvarchar(60), @constraintname_8 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'RBAC_USERS'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_8, @constraintname_8
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_8+' drop constraint '+@constraintname_8)
	   FETCH NEXT from refcursor into @reftable_8, @constraintname_8
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [RBAC_USERS]
END

CREATE TABLE [RBAC_USERS]
(
	[USR_UID] VARCHAR(32) default '' NOT NULL,
	[USR_USERNAME] VARCHAR(100) default '' NOT NULL,
	[USR_PASSWORD] VARCHAR(32) default '' NOT NULL,
	[USR_FIRSTNAME] VARCHAR(50) default '' NOT NULL,
	[USR_LASTNAME] VARCHAR(50) default '' NOT NULL,
	[USR_EMAIL] VARCHAR(100) default '' NOT NULL,
	[USR_DUE_DATE] CHAR(19) default '0000-00-00' NOT NULL,
	[USR_CREATE_DATE] CHAR(19) default '0000-00-00 00:00:00' NOT NULL,
	[USR_UPDATE_DATE] CHAR(19) default '0000-00-00 00:00:00' NOT NULL,
	[USR_STATUS] INT default 1 NOT NULL,
	
	[USR_AUTH_TYPE] VARCHAR(32) NOT NULL DEFAULT ('MSSQL'),
	[UID_AUTH_SOURCE] VARCHAR(32) NOT NULL DEFAULT ('00000000000000000000000000000000'),
	[USR_AUTH_USER_DN] VARCHAR(255) NOT NULL,
	[USR_AUTH_SUPERVISOR_DN] VARCHAR(255) NULL,

	[USR_REPLACED_BY] varchar(32) NULL,
	[USR_REPORTS_TO] varchar(32) NULL,


	CONSTRAINT RBAC_USERS_PK PRIMARY KEY ([USR_UID])
);



