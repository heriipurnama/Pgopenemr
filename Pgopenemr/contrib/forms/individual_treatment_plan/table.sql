CREATE TABLE IF NOT EXISTS form_individual_treatment_plan (
id bigint(20) NOT NULL auto_increment,
date datetime default NULL,
pid bigint(20) default NULL,
user varchar(255) default NULL,
groupname varchar(255) default NULL,
authorized tinyint(4) default NULL,
activity tinyint(4) default NULL,
date_of_referal date default NULL,
dcn varchar(20) default NULL,
icd9 varchar(5) default NULL,
prognosis varchar(20) default NULL,
diagnosis_description longtext,
presenting_problem longtext,
frequency varchar(10) default NULL,
duration varchar(10) default NULL,
scope varchar(10) default NULL,
short_term_goals_1 varchar(40) default NULL,
time_frame_1 varchar(15) default NULL,
short_term_goals_2 varchar(40) default NULL,
time_frame_2 varchar(15) default NULL,
short_term_goals_3 varchar(40) default NULL,
time_frame_3 varchar(15) default NULL,
long_term_goals longtext,
discharge_criteria longtext,
individual_family_therapy varchar(3) NOT NULL default 'N/A',
substance_abuse varchar(3) NOT NULL default 'N/A',
group_therapy varchar(3) NOT NULL default 'N/A',
parenting varchar(3) NOT NULL default 'N/A',
action_steps_by_supports longtext,
other_supports_name_1 varchar(35) default NULL,
other_supports_name_2 varchar(35) default NULL,
other_supports_contact_1 varchar(35) default NULL,
other_supports_contact_2 varchar(35) default NULL,
medications_1 varchar(40) default NULL,
medications_2 varchar(40) default NULL,
referrals_1 varchar(40) default NULL,
referrals_2 varchar(40) default NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB;
