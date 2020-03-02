ALTER TABLE b_aelita_test_group ADD CODE varchar(255) null;
ALTER TABLE b_aelita_test_test ADD CODE varchar(255) null;

ALTER TABLE b_aelita_test_group ADD ACCESS_ALL char(1) not null default 'Y';
ALTER TABLE b_aelita_test_test ADD ACCESS_ALL char(1) not null default 'Y';
ALTER TABLE b_aelita_test_test ADD ACCESS_GROUP char(1) not null default 'Y';

ALTER TABLE b_aelita_test_questioning ADD DATE_START timestamp not null default now();
ALTER TABLE b_aelita_test_questioning ADD DATE_STOP timestamp null;
ALTER TABLE b_aelita_test_questioning ADD FINAL char(1) not null default 'N';
ALTER TABLE b_aelita_test_questioning ADD DURATION int(18) not null;

ALTER TABLE b_aelita_test_test ADD DATE_FROM timestamp null;
ALTER TABLE b_aelita_test_test ADD DATE_TO timestamp null;
ALTER TABLE b_aelita_test_test ADD NUMBER_ATTEMPTS int(18) not null default 0;

ALTER TABLE b_aelita_test_test ADD SHOW_ANSWERS char(1) not null default 'N';

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

/********************************************/

create table if not exists b_aelita_test_question_group(
	ID int(18) not null auto_increment,
	TEST_ID int(18) not null references b_aelita_test_test(ID),
	XML_ID varchar(255) null,
	NAME varchar(255) not null,
	ACTIVE char(1) not null default 'Y',
	PICTURE int(18) null,
	DESCRIPTION text null,
	DESCRIPTION_TYPE char(4) not null default 'text',
	SORT int(18) not null default 500,
	CODE varchar(255) null,
	COUNT int(18) null default 0,
	primary key (ID),
	index ix_question_test (TEST_ID)
);

ALTER TABLE b_aelita_test_question ADD TEST_GROUP_ID int(18) null references b_aelita_test_question_group(ID);
CREATE INDEX ix_question_test_group ON b_aelita_test_question (TEST_GROUP_ID);

ALTER TABLE b_aelita_test_glasses ADD OTV char(1) not null default 'N';
ALTER TABLE b_aelita_test_questioning ADD GLASSES_ID int(18) not null default 0;
UPDATE b_aelita_test_questioning SET CLOSED='Y'  WHERE CLOSED='N';

create table if not exists b_aelita_test_responsible(
	ID int(18) not null auto_increment,
	TEST_ID int(18) not null references b_aelita_test_test(ID),
	USER_ID int(18) not null,
	primary key (ID),
	index ix_access_test (TEST_ID)
);

ALTER TABLE b_aelita_test_test ADD PERIOD_ATTEMPTS int(18) not null default 0;

/********************************************/

ALTER TABLE b_aelita_test_test ADD TEST_TIME int(18) not null default 0;

/********************************************/

ALTER TABLE b_aelita_test_test ADD MIX_QUESTION char(1) not null default 'N';

/********************************************/

ALTER TABLE b_aelita_test_test ADD SHOW_COMMENTS char(1) not null default 'N';
ALTER TABLE b_aelita_test_test ADD TYPE_RESULT char(4) not null default 'summ';
ALTER TABLE b_aelita_test_glasses ADD COMMENTS text null;

/********************************************/

ALTER TABLE b_aelita_test_question ADD SHOW_COMMENTS char(1) not null default 'N';

/*****************29.08.2015*****************/

ALTER TABLE b_aelita_test_test ADD AUTO_START_OVER char(1) not null default 'N';
ALTER TABLE b_aelita_test_test ADD MULTIPLE_QUESTION char(4) not null default 'none';
ALTER TABLE b_aelita_test_test ADD MULTIPLE_QUESTION_COUNT int(18) not null default 0;
create table if not exists b_aelita_test_step(
	ID int(18) not null auto_increment,
	QUESTIONING_ID  int(18) not null references b_aelita_test_questioning(ID),
	primary key (ID),
	index ix_result_step_questioning (QUESTIONING_ID)
);
ALTER TABLE b_aelita_test_glasses ADD STEP int(18) null references b_aelita_test_step(ID);
ALTER TABLE b_aelita_test_questioning ADD STEP_MULTIPLE char(4) not null default 'none';
ALTER TABLE b_aelita_test_step ADD OTV char(1) not null default 'N';
ALTER TABLE b_aelita_test_question_group ADD MULTIPLE_QUESTION_COUNT int(18) not null default 0;

/*****************02.10.2015*****************/

ALTER TABLE b_aelita_test_answer ADD CORRECT char(1) not null default 'N';
ALTER TABLE b_aelita_test_test ADD TO_TITLE text null;
ALTER TABLE b_aelita_test_test ADD TO_TITLE_TYPE char(4) not null default 'text';
ALTER TABLE b_aelita_test_test ADD USE_CORRECT char(1) not null default 'N';
ALTER TABLE b_aelita_test_answer ADD CORRECT_DESCRIPTION text null;
ALTER TABLE b_aelita_test_answer ADD CORRECT_DESCRIPTION_TYPE char(4) not null default 'text';
ALTER TABLE b_aelita_test_answer ADD ERROR_DESCRIPTION text null;
ALTER TABLE b_aelita_test_answer ADD ERROR_DESCRIPTION_TYPE char(4) not null default 'text';
ALTER TABLE b_aelita_test_test ADD SPONSOR_NAME varchar(255) null;
ALTER TABLE b_aelita_test_test ADD SPONSOR_PICTURE int(18) null;
ALTER TABLE b_aelita_test_test ADD SPONSOR_LINK text null;
ALTER TABLE b_aelita_test_test ADD SPONSOR_DESCRIPTION text null;
ALTER TABLE b_aelita_test_test ADD SPONSOR_DESCRIPTION_TYPE char(4) not null default 'text';

/*****************15.12.2015*****************/

ALTER TABLE b_aelita_test_test ADD ALT varchar(255) null;
ALTER TABLE b_aelita_test_test ADD SPONSOR_ALT varchar(255) null;
ALTER TABLE b_aelita_test_question ADD ALT varchar(255) null;
ALTER TABLE b_aelita_test_group ADD ALT varchar(255) null;
ALTER TABLE b_aelita_test_answer ADD ALT varchar(255) null;
ALTER TABLE b_aelita_test_result ADD ALT varchar(255) null;
ALTER TABLE b_aelita_test_question_group ADD ALT varchar(255) null;

/*****************03.04.2016*****************/

ALTER TABLE b_aelita_test_test ADD COUNT_USER_AUTOR char(1) not null default 'N';

/*****************21.11.2016*****************/

ALTER TABLE b_aelita_test_glasses ADD SERIALIZED_RESULT_TEXT text null;

/*****************03.12.2016*****************/

ALTER TABLE b_aelita_test_answer MODIFY NAME varchar(500) not null;

/*****************16.03.2019*****************/

ALTER TABLE b_aelita_test_group ADD GROUP_ID varchar(255) null references b_aelita_test_group(ID);

