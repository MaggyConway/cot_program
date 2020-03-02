create table if not exists b_aelita_test_group(
	ID int(18) not null auto_increment,
	XML_ID varchar(255) null,
	GROUP_ID varchar(255) null references b_aelita_test_group(ID),
	NAME varchar(255) not null,
	ACTIVE char(1) not null default 'Y',
	PICTURE int(18) null,
  ALT varchar(255) null,
	DESCRIPTION text null,
	DESCRIPTION_TYPE char(4) not null default 'text',
	SORT int(18) not null default 500,
	CODE varchar(255) null,
	ACCESS_ALL char(1) not null default 'Y',
	primary key (ID)
);

create table if not exists b_aelita_test_test(
	ID int(18) not null auto_increment,
	XML_ID varchar(255) null,
	GROUP_ID int(18) null references b_aelita_test_group(ID),
	NAME varchar(255) not null,
  TO_TITLE text null,
  TO_TITLE_TYPE char(4) not null default 'text',
	ACTIVE char(1) not null default 'Y',
	PICTURE int(18) null,
	ALT varchar(255) null,
	DESCRIPTION text null,
	DESCRIPTION_TYPE char(4) not null default 'text',
	SORT int(18) not null default 500,
	CODE varchar(255) null,
	ACCESS_ALL char(1) not null default 'Y',
	ACCESS_GROUP char(1) not null default 'Y',
	DATE_FROM timestamp null,
	DATE_TO timestamp null,
	NUMBER_ATTEMPTS int(18) not null default 0,
	PERIOD_ATTEMPTS int(18) not null default 0,
	TEST_TIME int(18) not null default 0,
	SHOW_ANSWERS char(1) not null default 'N',
	MIX_QUESTION char(1) not null default 'N',
  AUTO_START_OVER char(1) not null default 'N',
  MULTIPLE_QUESTION char(4) not null default 'none',#none,anum,gnum,allq,clst
  MULTIPLE_QUESTION_COUNT int(18) not null default 0,
	primary key (ID),
	SHOW_COMMENTS char(1) not null default 'N',
	TYPE_RESULT char(4) not null default 'summ',#summ,aver,suer
	USE_CORRECT char(1) not null default 'N',
  SPONSOR_NAME varchar(255) null,
  SPONSOR_PICTURE int(18) null,
  SPONSOR_ALT varchar(255) null,
  SPONSOR_LINK text null,
  SPONSOR_DESCRIPTION text null,
	SPONSOR_DESCRIPTION_TYPE char(4) not null default 'text',
  COUNT_USER_AUTOR char(1) not null default 'N',
	index ix_test_group (GROUP_ID)
);

create table if not exists b_aelita_test_question_group(
	ID int(18) not null auto_increment,
	TEST_ID int(18) not null references b_aelita_test_test(ID),
	XML_ID varchar(255) null,
	NAME varchar(255) not null,
	ACTIVE char(1) not null default 'Y',
	PICTURE int(18) null,
	ALT varchar(255) null,
	DESCRIPTION text null,
	DESCRIPTION_TYPE char(4) not null default 'text',
	SORT int(18) not null default 500,
	CODE varchar(255) null,
	COUNT int(18) null default 0,
	MULTIPLE_QUESTION_COUNT int(18) not null default 0,
	primary key (ID),
	index ix_question_test (TEST_ID)
);

create table if not exists b_aelita_test_question(
	ID int(18) not null auto_increment,
	XML_ID varchar(255) null,
	TEST_ID int(18) not null references b_aelita_test_test(ID),
	TEST_GROUP_ID int(18) null references b_aelita_test_question_group(ID),
	NAME varchar(255) not null,
	ACTIVE char(1) not null default 'Y',
	PICTURE int(18) null,
	ALT varchar(255) null,
	DESCRIPTION text null,
	DESCRIPTION_TYPE char(4) not null default 'text',
	SORT int(18) not null default 500,
	TEST_TYPE char(5) not null default 'radio',#check,radio,input
	CORRECT_ANSWER varchar(255) null,
	SCORES int(18) not null default 0,
	SHOW_COMMENTS char(1) not null default 'N',
	primary key (ID),
	index ix_question_test (TEST_ID),
	index ix_question_test_group (TEST_GROUP_ID)
);

create table if not exists b_aelita_test_answer(
	ID int(18) not null auto_increment,
	XML_ID varchar(255) null,
	QUESTION_ID int(18) not null references b_aelita_test_question(ID),
	NAME varchar(500) not null,
	ACTIVE char(1) not null default 'Y',
  CORRECT char(1) not null default 'N',
	PICTURE int(18) null,
	ALT varchar(255) null,
	DESCRIPTION text null,
	DESCRIPTION_TYPE char(4) not null default 'text',
	SORT int(18) not null default 500,
	SCORES int(18) not null default 0,
  CORRECT_DESCRIPTION text null,
	CORRECT_DESCRIPTION_TYPE char(4) not null default 'text',
  ERROR_DESCRIPTION text null,
	ERROR_DESCRIPTION_TYPE char(4) not null default 'text',
	primary key (ID),
	index ix_answer_question (QUESTION_ID)
);

create table if not exists b_aelita_test_result(
	ID int(18) not null auto_increment,
	XML_ID varchar(255) null,
	TEST_ID int(18) not null references b_aelita_test_test(ID),
	NAME varchar(255) not null,
	ACTIVE char(1) not null default 'Y',
	PICTURE int(18) null,
	ALT varchar(255) null,
	DESCRIPTION text null,
	DESCRIPTION_TYPE char(4) not null default 'text',
	SORT int(18) not null default 500,
	MIN_SCORES int(18) not null default 0,
	MAX_SCORES int(18) not null default 0,
	primary key (ID),
	index ix_result_test (TEST_ID)
);

create table if not exists b_aelita_test_profile(
	ID int(18) not null auto_increment,
	SESS_ID varchar(255) null,
	USER_ID int(18) null,
	DATE_CREATE timestamp not null default now(),
	primary key (ID),
	index ix_result_profile_sess (SESS_ID),
	index ix_result_profile_user (USER_ID)
);

create table if not exists b_aelita_test_questioning(
	ID int(18) not null auto_increment,
	PROFILE_ID  int(18) not null references b_aelita_test_profile(ID),
	TEST_ID int(18) not null references b_aelita_test_test(ID),
	RESULT_ID int(18) null references b_aelita_test_result(ID),
	CLOSED char(1) not null default 'N',
	FINAL char(1) not null default 'N',
	DURATION int(18) not null,
	DATE_START timestamp not null default now(),
	DATE_STOP timestamp null,
	GLASSES_ID int(18) not null default 0,
  STEP_MULTIPLE char(4) not null default 'none',#none,step
	primary key (ID),
	index ix_result_questioning_profile (PROFILE_ID),
	index ix_result_questioning_test (TEST_ID)
);

create table if not exists b_aelita_test_step(
	ID int(18) not null auto_increment,
	QUESTIONING_ID  int(18) not null references b_aelita_test_questioning(ID),
  OTV char(1) not null default 'N',
	primary key (ID),
	index ix_result_step_questioning (QUESTIONING_ID)
);

create table if not exists b_aelita_test_glasses(
	ID int(18) not null auto_increment,
	QUESTIONING_ID  int(18) not null references b_aelita_test_questioning(ID),
	QUESTION_ID int(18) not null references b_aelita_test_question(ID),
	SCORES int(18) not null default 0,
	OTV char(1) not null default 'N',
	SERIALIZED_RESULT text null,
	SERIALIZED_RESULT_TEXT text null,
	COMMENTS text null,
  STEP int(18) null references b_aelita_test_step(ID),
	primary key (ID),
	index ix_result_glasses_questioning (QUESTIONING_ID),
	index ix_result_glasses_question (QUESTION_ID)
);

create table if not exists b_aelita_test_access_group(
	ID int(18) not null auto_increment,
	GROUP_ID int(18) null references b_aelita_test_group(ID),
	USER_GROUP_ID int(18) null,
	primary key (ID),
	index ix_access_group (GROUP_ID)
);

create table if not exists b_aelita_test_access_test(
	ID int(18) not null auto_increment,
	TEST_ID int(18) not null references b_aelita_test_test(ID),
	USER_GROUP_ID int(18) null,
	primary key (ID),
	index ix_access_test (TEST_ID)
);

create table if not exists b_aelita_test_responsible(
	ID int(18) not null auto_increment,
	TEST_ID int(18) not null references b_aelita_test_test(ID),
	USER_ID int(18) not null,
	primary key (ID),
	index ix_access_test (TEST_ID)
);


