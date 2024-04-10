CREATE TABLE IF NOT EXISTS up_schedule_subject
(
    ID INT NOT NULL AUTO_INCREMENT,
    TITLE VARCHAR(255) NOT NULL,
    AUDIENCE_TYPE_ID INT NOT NULL,
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_schedule_audience
(
    ID INT NOT NULL AUTO_INCREMENT,
    NUMBER VARCHAR(10) NOT NULL,
    AUDIENCE_TYPE_ID INT NOT NULL,
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_schedule_audience_type
(
    ID INT NOT NULL AUTO_INCREMENT,
    TITLE VARCHAR(100) NOT NULL,
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_schedule_group
(
    ID INT NOT NULL AUTO_INCREMENT,
    TITLE varchar(255) NOT NULL,
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_schedule_group_subject
(
    SUBJECT_ID INT NOT NULL,
    GROUP_ID INT NOT NULL,
    HOURS_NUMBER INT NOT NULL,
    PRIMARY KEY (SUBJECT_ID, GROUP_ID)
);

CREATE TABLE IF NOT EXISTS up_schedule_subject_teacher
(
    SUBJECT_ID INT NOT NULL,
    TEACHER_ID INT NOT NULL,
    PRIMARY KEY(SUBJECT_ID, TEACHER_ID)
);

CREATE TABLE IF NOT EXISTS up_schedule_role
(
    ID INT NOT NULL AUTO_INCREMENT,
    TITLE VARCHAR(100) NOT NULL,
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_schedule_couple
(
    GROUP_ID INT NOT NULL,
    SUBJECT_ID INT NOT NULL,
    TEACHER_ID INT NOT NULL,
    AUDIENCE_ID INT,
    WEEK_DAY INT NOT NULL,
    COUPLE_NUMBER_IN_DAY INT NOT NULL,
    WEEK_TYPE VARCHAR(10)
);

CREATE TABLE IF NOT EXISTS up_schedule_user_role
(
    USER_ID INT NOT NULL,
    ROLE_ID INT NOT NULL
);

CREATE TABLE IF NOT EXISTS up_schedule_user_group
(
    USER_ID INT NOT NULL,
    GROUP_ID INT NOT NULL
);