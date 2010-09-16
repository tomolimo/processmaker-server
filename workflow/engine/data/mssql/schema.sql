
/* ---------------------------------------------------------------------- */
/* APPLICATION											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'APPLICATION')
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
		 and tables.name = 'APPLICATION'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_1, @constraintname_1
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_1+' drop constraint '+@constraintname_1)
	   FETCH NEXT from refcursor into @reftable_1, @constraintname_1
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [APPLICATION]
END


CREATE TABLE [APPLICATION]
(
	[APP_UID] VARCHAR(32) default '' NOT NULL,
	[APP_NUMBER] INT default 0 NOT NULL,
	[APP_PARENT] VARCHAR(32) default '0' NOT NULL,
	[APP_STATUS] VARCHAR(100) default '' NOT NULL,
	[PRO_UID] VARCHAR(32) default '' NOT NULL,
	[APP_PROC_STATUS] VARCHAR(100) default '' NOT NULL,
	[APP_PROC_CODE] VARCHAR(100) default '' NOT NULL,
	[APP_PARALLEL] VARCHAR(32) default 'NO' NOT NULL,
	[APP_INIT_USER] VARCHAR(32) default '' NOT NULL,
	[APP_CUR_USER] VARCHAR(32) default '' NOT NULL,
	[APP_CREATE_DATE] DATETIME default '0000-00-00 00:00:00' NOT NULL,
	[APP_INIT_DATE] DATETIME default '0000-00-00 00:00:00' NOT NULL,
	[APP_FINISH_DATE] DATETIME default '0000-00-00 00:00:00' NOT NULL,
	[APP_UPDATE_DATE] DATETIME default '0000-00-00 00:00:00' NOT NULL,
	[APP_DATA] TEXT  NOT NULL,
	CONSTRAINT APPLICATION_PK PRIMARY KEY ([APP_UID])
);

CREATE INDEX [indexApp] ON [APPLICATION] ([PRO_UID],[APP_UID]);

/* ---------------------------------------------------------------------- */
/* APP_DELEGATION											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'APP_DELEGATION')
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
		 and tables.name = 'APP_DELEGATION'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_2, @constraintname_2
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_2+' drop constraint '+@constraintname_2)
	   FETCH NEXT from refcursor into @reftable_2, @constraintname_2
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [APP_DELEGATION]
END


CREATE TABLE [APP_DELEGATION]
(
	[APP_UID] VARCHAR(32) default '' NOT NULL,
	[DEL_INDEX] INT default 0 NOT NULL,
	[DEL_PREVIOUS] INT default 0 NOT NULL,
	[PRO_UID] VARCHAR(32) default '' NOT NULL,
	[TAS_UID] VARCHAR(32) default '' NOT NULL,
	[USR_UID] VARCHAR(32) default '' NOT NULL,
	[DEL_TYPE] VARCHAR(32) default 'NORMAL' NOT NULL,
	[DEL_THREAD] INT default 0 NOT NULL,
	[DEL_THREAD_STATUS] VARCHAR(32) default 'OPEN' NOT NULL,
	[DEL_PRIORITY] VARCHAR(32) default '0' NOT NULL,
	[DEL_DELEGATE_DATE] DATETIME default '0000-00-00 00:00:00' NOT NULL,
	[DEL_INIT_DATE] DATETIME  NOT NULL,
	[DEL_TASK_DUE_DATE] DATETIME default '' NOT NULL,
	[DEL_FINISH_DATE] DATETIME  NULL,
	CONSTRAINT APP_DELEGATION_PK PRIMARY KEY ([APP_UID],[DEL_INDEX])
);

/* ---------------------------------------------------------------------- */
/* APP_DOCUMENT											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'APP_DOCUMENT')
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
		 and tables.name = 'APP_DOCUMENT'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_3, @constraintname_3
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_3+' drop constraint '+@constraintname_3)
	   FETCH NEXT from refcursor into @reftable_3, @constraintname_3
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [APP_DOCUMENT]
END


CREATE TABLE [APP_DOCUMENT]
(
	[APP_DOC_UID] VARCHAR(32) default '' NOT NULL,
	[APP_UID] VARCHAR(32) default '' NOT NULL,
	[DEL_INDEX] INT default 0 NOT NULL,
	[DOC_UID] VARCHAR(32) default '' NOT NULL,
	[USR_UID] VARCHAR(32) default '' NOT NULL,
	[APP_DOC_TYPE] VARCHAR(32) default '' NOT NULL,
	[APP_DOC_CREATE_DATE] DATETIME default '0000-00-00 00:00:00' NOT NULL,
	CONSTRAINT APP_DOCUMENT_PK PRIMARY KEY ([APP_DOC_UID])
);

/* ---------------------------------------------------------------------- */
/* APP_MESSAGE											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'APP_MESSAGE')
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
		 and tables.name = 'APP_MESSAGE'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_4, @constraintname_4
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_4+' drop constraint '+@constraintname_4)
	   FETCH NEXT from refcursor into @reftable_4, @constraintname_4
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [APP_MESSAGE]
END


CREATE TABLE [APP_MESSAGE]
(
	[APP_MSG_UID] VARCHAR(32) default '' NOT NULL,
	[MSG_UID] VARCHAR(32)  NULL,
	[APP_UID] VARCHAR(32) default '' NOT NULL,
	[DEL_INDEX] INT default 0 NOT NULL,
	[APP_MSG_TYPE] VARCHAR(100) default 'CUSTOM_MESSAGE' NOT NULL,
	[APP_MSG_SUBJECT] VARCHAR(150) default '' NOT NULL,
	[APP_MSG_FROM] VARCHAR(100) default '' NOT NULL,
	[APP_MSG_TO] TEXT  NOT NULL,
	[APP_MSG_BODY] TEXT  NOT NULL,
	[APP_MSG_DATE] DATETIME default '0000-00-00 00:00:00' NOT NULL,
	[APP_MSG_CC] TEXT  NULL,
	[APP_MSG_BCC] TEXT  NULL,
	[APP_MSG_ATTACH] TEXT  NULL,
	CONSTRAINT APP_MESSAGE_PK PRIMARY KEY ([APP_MSG_UID])
);

/* ---------------------------------------------------------------------- */
/* APP_OWNER											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'APP_OWNER')
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
		 and tables.name = 'APP_OWNER'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_5, @constraintname_5
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_5+' drop constraint '+@constraintname_5)
	   FETCH NEXT from refcursor into @reftable_5, @constraintname_5
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [APP_OWNER]
END


CREATE TABLE [APP_OWNER]
(
	[APP_UID] VARCHAR(32) default '' NOT NULL,
	[OWN_UID] VARCHAR(32) default '' NOT NULL,
	[USR_UID] VARCHAR(32) default '' NOT NULL,
	CONSTRAINT APP_OWNER_PK PRIMARY KEY ([APP_UID],[OWN_UID],[USR_UID])
);

/* ---------------------------------------------------------------------- */
/* CONFIGURATION											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'CONFIGURATION')
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
		 and tables.name = 'CONFIGURATION'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_6, @constraintname_6
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_6+' drop constraint '+@constraintname_6)
	   FETCH NEXT from refcursor into @reftable_6, @constraintname_6
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [CONFIGURATION]
END


CREATE TABLE [CONFIGURATION]
(
	[CFG_UID] VARCHAR(32) default '' NOT NULL,
	[OBJ_UID] VARCHAR(128) default '' NOT NULL,
	[CFG_VALUE] TEXT  NOT NULL,
	[PRO_UID] VARCHAR(32) default '' NOT NULL,
	[USR_UID] VARCHAR(32) default '' NOT NULL,
	[APP_UID] VARCHAR(32) default '' NOT NULL,
	CONSTRAINT CONFIGURATION_PK PRIMARY KEY ([CFG_UID],[OBJ_UID],[PRO_UID],[USR_UID],[APP_UID])
);

/* ---------------------------------------------------------------------- */
/* CONTENT											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'CONTENT')
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
		 and tables.name = 'CONTENT'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_7, @constraintname_7
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_7+' drop constraint '+@constraintname_7)
	   FETCH NEXT from refcursor into @reftable_7, @constraintname_7
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [CONTENT]
END


CREATE TABLE [CONTENT]
(
	[CON_CATEGORY] VARCHAR(30) default '' NOT NULL,
	[CON_PARENT] VARCHAR(32) default '' NOT NULL,
	[CON_ID] VARCHAR(100) default '' NOT NULL,
	[CON_LANG] VARCHAR(10) default '' NOT NULL,
	[CON_VALUE] TEXT  NOT NULL,
	CONSTRAINT CONTENT_PK PRIMARY KEY ([CON_CATEGORY],[CON_PARENT],[CON_ID],[CON_LANG])
);

/* ---------------------------------------------------------------------- */
/* DEPARTMENT											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'DEPARTMENT')
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
		 and tables.name = 'DEPARTMENT'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_8, @constraintname_8
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_8+' drop constraint '+@constraintname_8)
	   FETCH NEXT from refcursor into @reftable_8, @constraintname_8
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [DEPARTMENT]
END


CREATE TABLE [DEPARTMENT]
(
	[DEP_UID] VARCHAR(32) default '' NOT NULL,
	[DEP_PARENT] VARCHAR(32) default '' NOT NULL,
	[DEP_MANAGER] VARCHAR(32) default '' NOT NULL,
	[DEP_LOCATION] INT default 0 NOT NULL,
	[DEP_STATUS] CHAR(1) default 'A' NOT NULL,
	[DEP_TYPE] VARCHAR(5) default 'INTER' NOT NULL,
	[DEP_REF_CODE] VARCHAR(10) default '' NOT NULL,
	CONSTRAINT DEPARTMENT_PK PRIMARY KEY ([DEP_UID])
);

CREATE INDEX [DEP_BYPARENT] ON [DEPARTMENT] ([DEP_PARENT]);

/* ---------------------------------------------------------------------- */
/* DYNAFORM											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'DYNAFORM')
BEGIN
	 DECLARE @reftable_9 nvarchar(60), @constraintname_9 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'DYNAFORM'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_9, @constraintname_9
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_9+' drop constraint '+@constraintname_9)
	   FETCH NEXT from refcursor into @reftable_9, @constraintname_9
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [DYNAFORM]
END


CREATE TABLE [DYNAFORM]
(
	[DYN_UID] VARCHAR(32) default '' NOT NULL,
	[PRO_UID] VARCHAR(32) default '0' NOT NULL,
	[DYN_TYPE] VARCHAR(20) default 'xmlform' NOT NULL,
	[DYN_FILENAME] VARCHAR(100) default '' NOT NULL,
	CONSTRAINT DYNAFORM_PK PRIMARY KEY ([DYN_UID])
);

/* ---------------------------------------------------------------------- */
/* GROUPWF											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'GROUPWF')
BEGIN
	 DECLARE @reftable_10 nvarchar(60), @constraintname_10 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'GROUPWF'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_10, @constraintname_10
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_10+' drop constraint '+@constraintname_10)
	   FETCH NEXT from refcursor into @reftable_10, @constraintname_10
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [GROUPWF]
END


CREATE TABLE [GROUPWF]
(
	[GRP_UID] VARCHAR(32) default '' NOT NULL,
	[GRP_STATUS] CHAR(8) default 'ACTIVE' NOT NULL,
	CONSTRAINT GROUPWF_PK PRIMARY KEY ([GRP_UID])
);

/* ---------------------------------------------------------------------- */
/* GROUP_USER											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'GROUP_USER')
BEGIN
	 DECLARE @reftable_11 nvarchar(60), @constraintname_11 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'GROUP_USER'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_11, @constraintname_11
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_11+' drop constraint '+@constraintname_11)
	   FETCH NEXT from refcursor into @reftable_11, @constraintname_11
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [GROUP_USER]
END


CREATE TABLE [GROUP_USER]
(
	[GRP_UID] VARCHAR(32) default '0' NOT NULL,
	[USR_UID] VARCHAR(32) default '0' NOT NULL,
	CONSTRAINT GROUP_USER_PK PRIMARY KEY ([GRP_UID],[USR_UID])
);

/* ---------------------------------------------------------------------- */
/* HOLIDAY											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'HOLIDAY')
BEGIN
	 DECLARE @reftable_12 nvarchar(60), @constraintname_12 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'HOLIDAY'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_12, @constraintname_12
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_12+' drop constraint '+@constraintname_12)
	   FETCH NEXT from refcursor into @reftable_12, @constraintname_12
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [HOLIDAY]
END


CREATE TABLE [HOLIDAY]
(
	[HLD_UID] INT  NOT NULL IDENTITY,
	[HLD_DATE] VARCHAR(10) default '0000-00-00' NOT NULL,
	[HLD_DESCRIPTION] VARCHAR(200) default '' NOT NULL,
	CONSTRAINT HOLIDAY_PK PRIMARY KEY ([HLD_UID])
);

/* ---------------------------------------------------------------------- */
/* INPUT_DOCUMENT											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'INPUT_DOCUMENT')
BEGIN
	 DECLARE @reftable_13 nvarchar(60), @constraintname_13 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'INPUT_DOCUMENT'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_13, @constraintname_13
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_13+' drop constraint '+@constraintname_13)
	   FETCH NEXT from refcursor into @reftable_13, @constraintname_13
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [INPUT_DOCUMENT]
END


CREATE TABLE [INPUT_DOCUMENT]
(
	[INP_DOC_UID] VARCHAR(32) default '' NOT NULL,
	[PRO_UID] VARCHAR(32) default '0' NOT NULL,
	[INP_DOC_FORM_NEEDED] VARCHAR(20) default 'REAL' NOT NULL,
	[INP_DOC_ORIGINAL] VARCHAR(20) default 'COPY' NOT NULL,
	[INP_DOC_PUBLISHED] VARCHAR(20) default 'PRIVATE' NOT NULL,
	CONSTRAINT INPUT_DOCUMENT_PK PRIMARY KEY ([INP_DOC_UID])
);

/* ---------------------------------------------------------------------- */
/* ISO_COUNTRY											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'ISO_COUNTRY')
BEGIN
	 DECLARE @reftable_14 nvarchar(60), @constraintname_14 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'ISO_COUNTRY'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_14, @constraintname_14
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_14+' drop constraint '+@constraintname_14)
	   FETCH NEXT from refcursor into @reftable_14, @constraintname_14
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [ISO_COUNTRY]
END


CREATE TABLE [ISO_COUNTRY]
(
	[IC_UID] VARCHAR(2) default '' NOT NULL,
	[IC_NAME] VARCHAR(255)  NULL,
	[IC_SORT_ORDER] VARCHAR(255)  NULL,
	CONSTRAINT ISO_COUNTRY_PK PRIMARY KEY ([IC_UID])
);

/* ---------------------------------------------------------------------- */
/* ISO_LOCATION											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'ISO_LOCATION')
BEGIN
	 DECLARE @reftable_15 nvarchar(60), @constraintname_15 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'ISO_LOCATION'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_15, @constraintname_15
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_15+' drop constraint '+@constraintname_15)
	   FETCH NEXT from refcursor into @reftable_15, @constraintname_15
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [ISO_LOCATION]
END


CREATE TABLE [ISO_LOCATION]
(
	[IC_UID] VARCHAR(2) default '' NOT NULL,
	[IL_UID] VARCHAR(5) default '' NOT NULL,
	[IL_NAME] VARCHAR(255)  NULL,
	[IL_NORMAL_NAME] VARCHAR(255)  NULL,
	[IS_UID] VARCHAR(4)  NULL,
	CONSTRAINT ISO_LOCATION_PK PRIMARY KEY ([IC_UID],[IL_UID])
);

/* ---------------------------------------------------------------------- */
/* ISO_SUBDIVISION											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'ISO_SUBDIVISION')
BEGIN
	 DECLARE @reftable_16 nvarchar(60), @constraintname_16 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'ISO_SUBDIVISION'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_16, @constraintname_16
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_16+' drop constraint '+@constraintname_16)
	   FETCH NEXT from refcursor into @reftable_16, @constraintname_16
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [ISO_SUBDIVISION]
END


CREATE TABLE [ISO_SUBDIVISION]
(
	[IC_UID] VARCHAR(2) default '' NOT NULL,
	[IS_UID] VARCHAR(4) default '' NOT NULL,
	[IS_NAME] VARCHAR(255) default '' NOT NULL,
	CONSTRAINT ISO_SUBDIVISION_PK PRIMARY KEY ([IC_UID],[IS_UID])
);

/* ---------------------------------------------------------------------- */
/* KT_APPLICATION											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'KT_APPLICATION')
BEGIN
	 DECLARE @reftable_17 nvarchar(60), @constraintname_17 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'KT_APPLICATION'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_17, @constraintname_17
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_17+' drop constraint '+@constraintname_17)
	   FETCH NEXT from refcursor into @reftable_17, @constraintname_17
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [KT_APPLICATION]
END


CREATE TABLE [KT_APPLICATION]
(
	[APP_UID] VARCHAR(32) default '' NOT NULL,
	[KT_FOLDER_ID] INT default 0 NOT NULL,
	[KT_PARENT_ID] INT default 0 NOT NULL,
	[KT_FOLDER_NAME] VARCHAR(100) default '' NOT NULL,
	[KT_FULL_PATH] VARCHAR(255) default '' NOT NULL,
	[KT_CREATE_USER] VARCHAR(32) default '' NOT NULL,
	[KT_CREATE_DATE] DATETIME default '0000-00-00 00:00:00' NOT NULL,
	[KT_UPDATE_DATE] DATETIME default '0000-00-00 00:00:00' NOT NULL,
	CONSTRAINT KT_APPLICATION_PK PRIMARY KEY ([APP_UID])
);

CREATE INDEX [indexApp] ON [KT_APPLICATION] ([KT_FOLDER_ID]);

/* ---------------------------------------------------------------------- */
/* KT_PROCESS											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'KT_PROCESS')
BEGIN
	 DECLARE @reftable_18 nvarchar(60), @constraintname_18 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'KT_PROCESS'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_18, @constraintname_18
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_18+' drop constraint '+@constraintname_18)
	   FETCH NEXT from refcursor into @reftable_18, @constraintname_18
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [KT_PROCESS]
END


CREATE TABLE [KT_PROCESS]
(
	[PRO_UID] VARCHAR(32) default '' NOT NULL,
	[KT_FOLDER_ID] INT default 0 NOT NULL,
	[KT_PARENT_ID] INT default 0 NOT NULL,
	[KT_FOLDER_NAME] VARCHAR(100) default '' NOT NULL,
	[KT_FULL_PATH] VARCHAR(255) default '' NOT NULL,
	[KT_CREATE_USER] VARCHAR(32) default '' NOT NULL,
	[KT_CREATE_DATE] DATETIME default '0000-00-00 00:00:00' NOT NULL,
	[KT_UPDATE_DATE] DATETIME default '0000-00-00 00:00:00' NOT NULL,
	CONSTRAINT KT_PROCESS_PK PRIMARY KEY ([PRO_UID])
);

CREATE INDEX [indexApp] ON [KT_PROCESS] ([KT_FOLDER_ID]);

/* ---------------------------------------------------------------------- */
/* LANGUAGE											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'LANGUAGE')
BEGIN
	 DECLARE @reftable_19 nvarchar(60), @constraintname_19 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'LANGUAGE'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_19, @constraintname_19
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_19+' drop constraint '+@constraintname_19)
	   FETCH NEXT from refcursor into @reftable_19, @constraintname_19
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [LANGUAGE]
END


CREATE TABLE [LANGUAGE]
(
	[LAN_ID] VARCHAR(4) default '' NOT NULL,
	[LAN_NAME] VARCHAR(30) default '' NOT NULL,
	[LAN_NATIVE_NAME] VARCHAR(30) default '' NOT NULL,
	[LAN_DIRECTION] CHAR(1) default 'L' NOT NULL,
	[LAN_WEIGHT] INT default 0 NOT NULL,
	[LAN_ENABLED] CHAR(1) default '1' NOT NULL,
	[LAN_CALENDAR] VARCHAR(30) default 'GREGORIAN' NOT NULL
);

/* ---------------------------------------------------------------------- */
/* LEXICO											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'LEXICO')
BEGIN
	 DECLARE @reftable_20 nvarchar(60), @constraintname_20 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'LEXICO'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_20, @constraintname_20
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_20+' drop constraint '+@constraintname_20)
	   FETCH NEXT from refcursor into @reftable_20, @constraintname_20
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [LEXICO]
END


CREATE TABLE [LEXICO]
(
	[LEX_TOPIC] VARCHAR(64) default '' NOT NULL,
	[LEX_KEY] VARCHAR(128) default '' NOT NULL,
	[LEX_VALUE] VARCHAR(128) default '' NOT NULL,
	[LEX_CAPTION] VARCHAR(128) default '' NOT NULL,
	CONSTRAINT LEXICO_PK PRIMARY KEY ([LEX_TOPIC],[LEX_KEY])
);

/* ---------------------------------------------------------------------- */
/* OUTPUT_DOCUMENT											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'OUTPUT_DOCUMENT')
BEGIN
	 DECLARE @reftable_21 nvarchar(60), @constraintname_21 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'OUTPUT_DOCUMENT'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_21, @constraintname_21
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_21+' drop constraint '+@constraintname_21)
	   FETCH NEXT from refcursor into @reftable_21, @constraintname_21
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [OUTPUT_DOCUMENT]
END


CREATE TABLE [OUTPUT_DOCUMENT]
(
	[OUT_DOC_UID] VARCHAR(32) default '' NOT NULL,
	[PRO_UID] VARCHAR(32) default '' NOT NULL,
	CONSTRAINT OUTPUT_DOCUMENT_PK PRIMARY KEY ([OUT_DOC_UID])
);

/* ---------------------------------------------------------------------- */
/* PROCESS											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'PROCESS')
BEGIN
	 DECLARE @reftable_22 nvarchar(60), @constraintname_22 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'PROCESS'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_22, @constraintname_22
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_22+' drop constraint '+@constraintname_22)
	   FETCH NEXT from refcursor into @reftable_22, @constraintname_22
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [PROCESS]
END


CREATE TABLE [PROCESS]
(
	[PRO_UID] VARCHAR(32) default '' NOT NULL,
	[PRO_PARENT] VARCHAR(32) default '0' NOT NULL,
	[PRO_TIME] FLOAT default 1 NOT NULL,
	[PRO_TIMEUNIT] VARCHAR(20) default 'DAYS' NOT NULL,
	[PRO_STATUS] VARCHAR(20) default 'ACTIVE' NOT NULL,
	[PRO_TYPE_DAY] CHAR(1) default '0' NOT NULL,
	[PRO_TYPE] VARCHAR(20) default 'NORMAL' NOT NULL,
	[PRO_ASSIGNMENT] VARCHAR(20) default 'FALSE' NOT NULL,
	[PRO_SHOW_MAP] TINYINT default 1 NOT NULL,
	[PRO_SHOW_MESSAGE] TINYINT default 1 NOT NULL,
	[PRO_SHOW_DELEGATE] TINYINT default 1 NOT NULL,
	[PRO_SHOW_DYNAFORM] TINYINT default 0 NOT NULL,
	[PRO_CATEGORY] VARCHAR(48) default '' NOT NULL,
	[PRO_SUB_CATEGORY] VARCHAR(48) default '' NOT NULL,
	[PRO_INDUSTRY] INT default 1 NOT NULL,
	[PRO_UPDATE_DATE] DATETIME default '' NULL,
	[PRO_CREATE_DATE] DATETIME default '' NOT NULL,
	[PRO_CREATE_USER] VARCHAR(32) default '' NOT NULL,
	[PRO_HEIGHT] INT default 5000 NOT NULL,
	[PRO_WIDTH] INT default 10000 NOT NULL,
	[PRO_TITLE_X] INT default 0 NOT NULL,
	[PRO_TITLE_Y] INT default 6 NOT NULL,
	CONSTRAINT PROCESS_PK PRIMARY KEY ([PRO_UID])
);

/* ---------------------------------------------------------------------- */
/* PROCESS_OWNER											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'PROCESS_OWNER')
BEGIN
	 DECLARE @reftable_23 nvarchar(60), @constraintname_23 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'PROCESS_OWNER'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_23, @constraintname_23
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_23+' drop constraint '+@constraintname_23)
	   FETCH NEXT from refcursor into @reftable_23, @constraintname_23
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [PROCESS_OWNER]
END


CREATE TABLE [PROCESS_OWNER]
(
	[OWN_UID] VARCHAR(32) default '' NOT NULL,
	[PRO_UID] VARCHAR(32) default '' NOT NULL,
	CONSTRAINT PROCESS_OWNER_PK PRIMARY KEY ([OWN_UID],[PRO_UID])
);

/* ---------------------------------------------------------------------- */
/* ROUTE											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'ROUTE')
BEGIN
	 DECLARE @reftable_24 nvarchar(60), @constraintname_24 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'ROUTE'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_24, @constraintname_24
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_24+' drop constraint '+@constraintname_24)
	   FETCH NEXT from refcursor into @reftable_24, @constraintname_24
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [ROUTE]
END


CREATE TABLE [ROUTE]
(
	[ROU_UID] VARCHAR(32) default '' NOT NULL,
	[ROU_PARENT] VARCHAR(32) default '0' NOT NULL,
	[PRO_UID] VARCHAR(32) default '' NOT NULL,
	[TAS_UID] VARCHAR(32) default '' NOT NULL,
	[ROU_NEXT_TASK] VARCHAR(32) default '0' NOT NULL,
	[ROU_CASE] INT default 0 NOT NULL,
	[ROU_TYPE] VARCHAR(25) default 'SEQUENTIAL' NOT NULL,
	[ROU_CONDITION] VARCHAR(255) default '' NOT NULL,
	[ROU_TO_LAST_USER] VARCHAR(20) default 'FALSE' NOT NULL,
	[ROU_OPTIONAL] VARCHAR(20) default 'FALSE' NOT NULL,
	[ROU_SEND_EMAIL] VARCHAR(20) default 'TRUE' NOT NULL,
	[ROU_SOURCEANCHOR] INT default 1 NULL,
	[ROU_TARGETANCHOR] INT default 0 NULL,
	CONSTRAINT ROUTE_PK PRIMARY KEY ([ROU_UID])
);

/* ---------------------------------------------------------------------- */
/* STEP											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'STEP')
BEGIN
	 DECLARE @reftable_25 nvarchar(60), @constraintname_25 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'STEP'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_25, @constraintname_25
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_25+' drop constraint '+@constraintname_25)
	   FETCH NEXT from refcursor into @reftable_25, @constraintname_25
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [STEP]
END


CREATE TABLE [STEP]
(
	[STEP_UID] VARCHAR(32) default '' NOT NULL,
	[PRO_UID] VARCHAR(32) default '0' NOT NULL,
	[TAS_UID] VARCHAR(32) default '0' NOT NULL,
	[STEP_TYPE_OBJ] VARCHAR(20) default 'DYNAFORM' NOT NULL,
	[STEP_UID_OBJ] VARCHAR(32) default '0' NOT NULL,
	[STEP_CONDITION] TEXT  NOT NULL,
	[STEP_POSITION] INT default 0 NOT NULL,
	CONSTRAINT STEP_PK PRIMARY KEY ([STEP_UID])
);

/* ---------------------------------------------------------------------- */
/* STEP_TRIGGER											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'STEP_TRIGGER')
BEGIN
	 DECLARE @reftable_26 nvarchar(60), @constraintname_26 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'STEP_TRIGGER'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_26, @constraintname_26
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_26+' drop constraint '+@constraintname_26)
	   FETCH NEXT from refcursor into @reftable_26, @constraintname_26
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [STEP_TRIGGER]
END


CREATE TABLE [STEP_TRIGGER]
(
	[STEP_UID] VARCHAR(32) default '' NOT NULL,
	[TAS_UID] VARCHAR(32) default '' NOT NULL,
	[TRI_UID] VARCHAR(32) default '' NOT NULL,
	[ST_TYPE] VARCHAR(20) default '' NOT NULL,
	[ST_CONDITION] VARCHAR(255) default '' NOT NULL,
	[ST_POSITION] INT default 0 NOT NULL,
	CONSTRAINT STEP_TRIGGER_PK PRIMARY KEY ([STEP_UID],[TAS_UID],[TRI_UID],[ST_TYPE])
);

/* ---------------------------------------------------------------------- */
/* SWIMLANES_ELEMENTS											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'SWIMLANES_ELEMENTS')
BEGIN
	 DECLARE @reftable_27 nvarchar(60), @constraintname_27 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'SWIMLANES_ELEMENTS'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_27, @constraintname_27
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_27+' drop constraint '+@constraintname_27)
	   FETCH NEXT from refcursor into @reftable_27, @constraintname_27
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [SWIMLANES_ELEMENTS]
END


CREATE TABLE [SWIMLANES_ELEMENTS]
(
	[SWI_UID] VARCHAR(32) default '' NOT NULL,
	[PRO_UID] VARCHAR(32) default '' NOT NULL,
	[SWI_TYPE] VARCHAR(20) default 'LINE' NOT NULL,
	[SWI_X] INT default 0 NOT NULL,
	[SWI_Y] INT default 0 NOT NULL,
	CONSTRAINT SWIMLANES_ELEMENTS_PK PRIMARY KEY ([SWI_UID])
);

/* ---------------------------------------------------------------------- */
/* TASK											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'TASK')
BEGIN
	 DECLARE @reftable_28 nvarchar(60), @constraintname_28 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'TASK'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_28, @constraintname_28
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_28+' drop constraint '+@constraintname_28)
	   FETCH NEXT from refcursor into @reftable_28, @constraintname_28
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [TASK]
END


CREATE TABLE [TASK]
(
	[PRO_UID] VARCHAR(32) default '' NOT NULL,
	[TAS_UID] VARCHAR(32) default '' NOT NULL,
	[TAS_TYPE] VARCHAR(20) default 'NORMAL' NOT NULL,
	[TAS_DURATION] FLOAT default 0 NOT NULL,
	[TAS_DELAY_TYPE] VARCHAR(30) default '' NOT NULL,
	[TAS_TEMPORIZER] FLOAT default 0 NOT NULL,
	[TAS_TYPE_DAY] CHAR(1) default '1' NOT NULL,
	[TAS_TIMEUNIT] VARCHAR(20) default 'DAYS' NOT NULL,
	[TAS_ALERT] VARCHAR(20) default 'FALSE' NOT NULL,
	[TAS_PRIORITY_VARIABLE] VARCHAR(100) default '' NOT NULL,
	[TAS_ASSIGN_TYPE] VARCHAR(30) default 'BALANCED' NOT NULL,
	[TAS_ASSIGN_VARIABLE] VARCHAR(100) default '@@SYS_NEXT_USER_TO_BE_ASSIGNED' NOT NULL,
	[TAS_ASSIGN_LOCATION] VARCHAR(20) default 'FALSE' NOT NULL,
	[TAS_ASSIGN_LOCATION_ADHOC] VARCHAR(20) default 'FALSE' NOT NULL,
	[TAS_TRANSFER_FLY] VARCHAR(20) default 'FALSE' NOT NULL,
	[TAS_LAST_ASSIGNED] VARCHAR(32) default '0' NOT NULL,
	[TAS_USER] VARCHAR(32) default '0' NOT NULL,
	[TAS_CAN_UPLOAD] VARCHAR(20) default 'FALSE' NOT NULL,
	[TAS_VIEW_UPLOAD] VARCHAR(20) default 'FALSE' NOT NULL,
	[TAS_VIEW_ADDITIONAL_DOCUMENTATION] VARCHAR(20) default 'FALSE' NOT NULL,
	[TAS_CAN_CANCEL] VARCHAR(20) default 'FALSE' NOT NULL,
	[TAS_OWNER_APP] VARCHAR(32) default '' NOT NULL,
	[STG_UID] VARCHAR(32) default '' NOT NULL,
	[TAS_CAN_PAUSE] VARCHAR(20) default 'FALSE' NOT NULL,
	[TAS_CAN_SEND_MESSAGE] VARCHAR(20) default 'TRUE' NOT NULL,
	[TAS_CAN_DELETE_DOCS] VARCHAR(20) default 'FALSE' NOT NULL,
	[TAS_SELF_SERVICE] VARCHAR(20) default 'FALSE' NOT NULL,
	[TAS_START] VARCHAR(20) default 'FALSE' NOT NULL,
	[TAS_TO_LAST_USER] VARCHAR(20) default 'FALSE' NOT NULL,
	[TAS_SEND_LAST_EMAIL] VARCHAR(20) default 'TRUE' NOT NULL,
	[TAS_DERIVATION] VARCHAR(100) default 'NORMAL' NOT NULL,
	[TAS_POSX] INT default 0 NOT NULL,
	[TAS_POSY] INT default 0 NOT NULL,
	[TAS_COLOR] VARCHAR(32) default '' NOT NULL,
	CONSTRAINT TASK_PK PRIMARY KEY ([TAS_UID])
);

/* ---------------------------------------------------------------------- */
/* TASK_USER											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'TASK_USER')
BEGIN
	 DECLARE @reftable_29 nvarchar(60), @constraintname_29 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'TASK_USER'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_29, @constraintname_29
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_29+' drop constraint '+@constraintname_29)
	   FETCH NEXT from refcursor into @reftable_29, @constraintname_29
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [TASK_USER]
END


CREATE TABLE [TASK_USER]
(
	[TAS_UID] VARCHAR(32) default '' NOT NULL,
	[USR_UID] VARCHAR(32) default '' NOT NULL,
	[TU_TYPE] INT default 1 NOT NULL,
	[TU_RELATION] INT default 0 NOT NULL,
	CONSTRAINT TASK_USER_PK PRIMARY KEY ([TAS_UID],[USR_UID],[TU_TYPE],[TU_RELATION])
);

/* ---------------------------------------------------------------------- */
/* TRANSLATION											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'TRANSLATION')
BEGIN
	 DECLARE @reftable_30 nvarchar(60), @constraintname_30 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'TRANSLATION'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_30, @constraintname_30
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_30+' drop constraint '+@constraintname_30)
	   FETCH NEXT from refcursor into @reftable_30, @constraintname_30
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [TRANSLATION]
END


CREATE TABLE [TRANSLATION]
(
	[TRN_CATEGORY] VARCHAR(100) default '' NOT NULL,
	[TRN_ID] VARCHAR(100) default '' NOT NULL,
	[TRN_LANG] VARCHAR(10) default 'en' NOT NULL,
	[TRN_VALUE] VARCHAR(200) default '' NOT NULL,
	CONSTRAINT TRANSLATION_PK PRIMARY KEY ([TRN_CATEGORY],[TRN_ID],[TRN_LANG])
);

/* ---------------------------------------------------------------------- */
/* TRIGGERS											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'TRIGGERS')
BEGIN
	 DECLARE @reftable_31 nvarchar(60), @constraintname_31 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'TRIGGERS'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_31, @constraintname_31
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_31+' drop constraint '+@constraintname_31)
	   FETCH NEXT from refcursor into @reftable_31, @constraintname_31
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [TRIGGERS]
END


CREATE TABLE [TRIGGERS]
(
	[TRI_UID] VARCHAR(32) default '' NOT NULL,
	[PRO_UID] VARCHAR(32) default '' NOT NULL,
	[TRI_TYPE] VARCHAR(20) default 'SCRIPT' NOT NULL,
	[TRI_WEBBOT] TEXT  NOT NULL,
	CONSTRAINT TRIGGERS_PK PRIMARY KEY ([TRI_UID])
);

/* ---------------------------------------------------------------------- */
/* USERS											*/
/* ---------------------------------------------------------------------- */


IF EXISTS (SELECT 1 FROM sysobjects WHERE type = 'U' AND name = 'USERS')
BEGIN
	 DECLARE @reftable_32 nvarchar(60), @constraintname_32 nvarchar(60)
	 DECLARE refcursor CURSOR FOR
	 select reftables.name tablename, cons.name constraintname
	  from sysobjects tables,
		   sysobjects reftables,
		   sysobjects cons,
		   sysreferences ref
	   where tables.id = ref.rkeyid
		 and cons.id = ref.constid
		 and reftables.id = ref.fkeyid
		 and tables.name = 'USERS'
	 OPEN refcursor
	 FETCH NEXT from refcursor into @reftable_32, @constraintname_32
	 while @@FETCH_STATUS = 0
	 BEGIN
	   exec ('alter table '+@reftable_32+' drop constraint '+@constraintname_32)
	   FETCH NEXT from refcursor into @reftable_32, @constraintname_32
	 END
	 CLOSE refcursor
	 DEALLOCATE refcursor
	 DROP TABLE [USERS]
END


CREATE TABLE [USERS]
(
	[USR_UID] VARCHAR(32) default '' NOT NULL,
	[USR_USERNAME] VARCHAR(100) default '' NOT NULL,
	[USR_PASSWORD] VARCHAR(32) default '' NOT NULL,
	[USR_FIRSTNAME] VARCHAR(50) default '' NOT NULL,
	[USR_LASTNAME] VARCHAR(50) default '' NOT NULL,
	[USR_EMAIL] VARCHAR(100) default '' NOT NULL,
	[USR_DUE_DATE] DATETIME default '0000-00-00' NOT NULL,
	[USR_CREATE_DATE] DATETIME default '0000-00-00 00:00:00' NOT NULL,
	[USR_UPDATE_DATE] DATETIME default '0000-00-00 00:00:00' NOT NULL,
	[USR_STATUS] INT default 1 NOT NULL,
	[USR_COUNTRY] VARCHAR(3) default '' NOT NULL,
	[USR_CITY] VARCHAR(3) default '' NOT NULL,
	[USR_LOCATION] VARCHAR(3) default '' NOT NULL,
	[USR_ADDRESS] VARCHAR(255) default '' NOT NULL,
	[USR_PHONE] VARCHAR(24) default '' NOT NULL,
	[USR_FAX] VARCHAR(24) default '' NOT NULL,
	[USR_CELLULAR] VARCHAR(24) default '' NOT NULL,
	[USR_ZIP_CODE] VARCHAR(16) default '' NOT NULL,
	[USR_DEPARTMENT] INT default 0 NOT NULL,
	[USR_POSITION] VARCHAR(100) default '' NOT NULL,
	[USR_RESUME] VARCHAR(100) default '' NOT NULL,
	[USR_BIRTHDAY] DATETIME default '0000-00-00' NOT NULL,
	[USR_ROLE] VARCHAR(32) default 'PROCESSMAKER_ADMIN' NULL,
	CONSTRAINT USERS_PK PRIMARY KEY ([USR_UID])
);
