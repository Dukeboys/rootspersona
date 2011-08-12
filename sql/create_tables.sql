SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS rp_address;
CREATE TABLE IF NOT EXISTS rp_address (
  id int(11) NOT null AUTO_INCREMENT,
  line1 varchar(60) DEFAULT null,
  line2 varchar(60) DEFAULT null,
  line3 varchar(60) DEFAULT null,
  city varchar(60) DEFAULT null,
  ctry_subentity varchar(60) DEFAULT null,
  ctry varchar(60) DEFAULT null,
  postal_code varchar(10) DEFAULT null,
  phone1 varchar(25) DEFAULT null,
  phone2 varchar(25) DEFAULT null,
  phone3 varchar(25) DEFAULT null,
  email1 varchar(120) DEFAULT null,
  email2 varchar(120) DEFAULT null,
  email3 varchar(120) DEFAULT null,
  www1 varchar(120) DEFAULT null,
  www2 varchar(120) DEFAULT null,
  www3 varchar(120) DEFAULT null,
  fax1 varchar(60) DEFAULT null,
  fax2 varchar(60) DEFAULT null,
  fax3 varchar(60) DEFAULT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS rp_event_cite;
CREATE TABLE IF NOT EXISTS rp_event_cite (
  event_id int(11) NOT null,
  cite_id int(11) NOT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (event_id,cite_id),
  KEY cite_id (cite_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS rp_event_detail;
CREATE TABLE IF NOT EXISTS rp_event_detail (
  id int(11) NOT null AUTO_INCREMENT,
  event_type varchar(22) NOT null,
  classification varchar(90) DEFAULT null,
  event_date varchar(35) DEFAULT null,
  place varchar(120) DEFAULT null,
  addr_id int(11) DEFAULT null,
  resp_agency varchar(120) DEFAULT null,
  religious_aff varchar(90) DEFAULT null,
  cause varchar(90) DEFAULT null,
  restriction_notice varchar(7) DEFAULT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1550 ;

DROP TABLE IF EXISTS rp_event_note;
CREATE TABLE IF NOT EXISTS rp_event_note (
  id int(11) NOT null AUTO_INCREMENT,
  event_id int(11) NOT null,
  note_rec_id varchar(22),
  note longtext NOT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (id),
  KEY event_id (event_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS rp_fam;
CREATE TABLE IF NOT EXISTS rp_fam (
  id varchar(22) NOT null DEFAULT '',
  batch_id tinyint(4) NOT null DEFAULT '1',
  restriction_notice varchar(7) DEFAULT null,
  spouse1 varchar(22) DEFAULT null,
  indi_batch_id_1 tinyint(4) DEFAULT null,
  spouse2 varchar(22) DEFAULT null,
  indi_batch_id_2 tinyint(4) DEFAULT null,
  auto_rec_id varchar(12) DEFAULT null,
  ged_change_date varchar(11) DEFAULT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (id,batch_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS rp_fam_child;
CREATE TABLE IF NOT EXISTS rp_fam_child (
  fam_id varchar(22) NOT null,
  fam_batch_id tinyint(4) NOT null DEFAULT '1',
  child_id varchar(22) NOT null,
  indi_batch_id tinyint(4) NOT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (fam_id,fam_batch_id,child_id,indi_batch_id),
  KEY `child_id` (`child_id`,`indi_batch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS rp_fam_cite;
CREATE TABLE IF NOT EXISTS rp_fam_cite (
  fam_id varchar(22) NOT null,
  fam_batch_id tinyint(4) NOT null,
  cite_id int(11) NOT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (fam_id,fam_batch_id,cite_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS rp_fam_event;
CREATE TABLE IF NOT EXISTS rp_fam_event (
  fam_id varchar(22) NOT null DEFAULT '',
  fam_batch_id tinyint(4) NOT null DEFAULT '0',
  event_id int(11) NOT null DEFAULT '0',
  update_datetime datetime NOT null,
  PRIMARY KEY (fam_id,fam_batch_id,event_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS rp_fam_note;
CREATE TABLE IF NOT EXISTS rp_fam_note (
  id int(11) NOT null AUTO_INCREMENT,
  fam_id varchar(22) NOT null,
  fam_batch_id tinyint(4) NOT null,
  note_rec_id varchar(22),
  note longtext NOT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (id),
  KEY fam_id (fam_id,fam_batch_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS rp_header;
CREATE TABLE IF NOT EXISTS rp_header (
  id int(11) NOT null AUTO_INCREMENT,
  batch_id tinyint(4) NOT null DEFAULT '1',
  src_system_id varchar(20) DEFAULT null,
  src_system_version varchar(15) DEFAULT null,
  product_name varchar(90) DEFAULT null,
  corp_name varchar(90) DEFAULT null,
  corp_addr_id int(11) DEFAULT null,
  src_data_name varchar(90) DEFAULT null,
  publication_date varchar(11) DEFAULT null,
  copyright_src_data varchar(90) DEFAULT null,
  receiving_sys_name varchar(90) DEFAULT null,
  transmission_date varchar(11) DEFAULT null,
  transmission_time varchar(12) DEFAULT null,
  submitter_id varchar(22) DEFAULT null,
  submitter_batch_id tinyint(4) DEFAULT null,
  submission_id varchar(22) DEFAULT null,
  submission_batch_id tinyint(4) DEFAULT null,
  file_name varchar(120) DEFAULT null,
  copyright_ged_file varchar(90) DEFAULT null,
  lang varchar(15) DEFAULT null,
  gedc_version varchar(15) DEFAULT null,
  gedc_form varchar(20) DEFAULT null,
  char_set varchar(8) DEFAULT null,
  char_set_version varchar(15) DEFAULT null,
  place_hierarchy varchar(120) DEFAULT null,
  ged_content_description longtext,
  update_datetime datetime NOT null,
  PRIMARY KEY (id,batch_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

DROP TABLE IF EXISTS rp_indi;
CREATE TABLE IF NOT EXISTS rp_indi (
  id varchar(22) NOT null,
  batch_id tinyint(4) NOT null DEFAULT '1',
  wp_page_id int(11) DEFAULT null,
  restriction_notice varchar(7) DEFAULT null,
  gender char(1) DEFAULT null,
  perm_rec_file_nbr varchar(90) DEFAULT null,
  anc_rec_file_nbr varchar(12) DEFAULT null,
  auto_rec_id varchar(12) DEFAULT null,
  ged_change_date varchar(11) DEFAULT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (id,batch_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS rp_indi_option;
CREATE TABLE IF NOT EXISTS rp_indi_option (
  indi_id varchar(22) NOT null,
  indi_batch_id tinyint(4) NOT null,
  privacy_code char(3) NOT null,
  excluded_name varchar(120),
  update_datetime datetime NOT null,
  PRIMARY KEY (indi_id,indi_batch_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS rp_indi_cite;
CREATE TABLE IF NOT EXISTS rp_indi_cite (
  indi_id varchar(22) NOT null,
  indi_batch_id tinyint(4) NOT null,
  cite_id int(11) NOT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (indi_id,indi_batch_id,cite_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS rp_indi_event;
CREATE TABLE IF NOT EXISTS rp_indi_event (
  indi_id varchar(22) NOT null,
  indi_batch_id tinyint(4) NOT null,
  event_id int(11) NOT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (indi_id,indi_batch_id,event_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS rp_indi_fam;
CREATE TABLE IF NOT EXISTS rp_indi_fam (
  indi_id varchar(22) NOT null,
  indi_batch_id tinyint(4) NOT null,
  fam_id varchar(22) NOT null,
  fam_batch_id tinyint(4) NOT null,
  link_type char(1) NOT null,
  link_status varchar(15) DEFAULT null,
  pedigree varchar(7) DEFAULT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (indi_id,indi_batch_id,fam_id,fam_batch_id,link_type),
  KEY indi_id (indi_id,indi_batch_id,link_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS rp_indi_name;
CREATE TABLE IF NOT EXISTS rp_indi_name (
  indi_id varchar(22) NOT null,
  indi_batch_id tinyint(4) NOT null,
  name_id int(11) NOT null,
  seq_nbr int(11) NOT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (indi_id,indi_batch_id,name_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS rp_indi_note;
CREATE TABLE IF NOT EXISTS rp_indi_note (
  id int(11) NOT null AUTO_INCREMENT,
  indi_id varchar(22) NOT null,
  indi_batch_id tinyint(4) NOT null,
  note_rec_id varchar(22),
  note longtext NOT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (id),
  KEY indi_id (indi_id,indi_batch_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS rp_name_cite;
CREATE TABLE IF NOT EXISTS rp_name_cite (
  name_id int(11) NOT null,
  cite_id int(11) NOT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (name_id,cite_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS rp_name_name;
CREATE TABLE IF NOT EXISTS rp_name_name (
  name_id int(11) NOT null,
  assoc_name_id int(11) NOT null,
  assoc_name_type varchar(12) NOT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (name_id,assoc_name_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS rp_name_note;
CREATE TABLE IF NOT EXISTS rp_name_note (
  id int(11) NOT null AUTO_INCREMENT,
  name_id int(11) NOT null,
  note_rec_id int(11),
  note longtext NOT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (id),
  KEY name_id (name_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS rp_name_personal;
CREATE TABLE IF NOT EXISTS rp_name_personal (
  id int(11) NOT null AUTO_INCREMENT,
  personal_name varchar(120) DEFAULT null,
  name_type varchar(30) DEFAULT null,
  prefix varchar(30) DEFAULT null,
  given varchar(120) DEFAULT null,
  nickname varchar(30) DEFAULT null,
  surname_prefix varchar(30) DEFAULT null,
  surname varchar(120) DEFAULT null,
  suffix varchar(30) DEFAULT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=918 ;

DROP TABLE IF EXISTS rp_note;
CREATE TABLE IF NOT EXISTS rp_note (
  id varchar(22), NOT null AUTO_INCREMENT,
  batch_id tinyint(4) NOT null,
  cite_id int(11) DEFAULT null,
  auto_rec_id varchar(12) DEFAULT null,
  ged_change_date varchar(11) DEFAULT null,
  update_datetime datetime NOT null,
  submitter_text longtext NOT null,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS rp_repo;
CREATE TABLE IF NOT EXISTS rp_repo (
  id varchar(22) NOT null,
  batch_id tinyint(4) NOT null DEFAULT '1',
  repo_name varchar(90) DEFAULT null,
  addr_id int(11) DEFAULT null,
  auto_rec_id varchar(12) DEFAULT null,
  ged_change_date varchar(11) DEFAULT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (id,batch_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS rp_repo_note;
CREATE TABLE IF NOT EXISTS rp_repo_note (
  id int(11) NOT null AUTO_INCREMENT,
  repo_id varchar(22) NOT null,
  repo_batch_id tinyint(4) NOT null,
  note_rec_id varchar(22),
  note longtext NOT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (id),
  KEY repo_id (repo_id,repo_batch_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS rp_source;
CREATE TABLE IF NOT EXISTS rp_source (
  id varchar(22) NOT null,
  batch_id tinyint(4) NOT null DEFAULT '1',
  wp_page_id int(11) DEFAULT null,
  originator longtext,
  source_title longtext,
  abbr varchar(60) DEFAULT null,
  publication_facts longtext,
  `text` longtext,
  auto_rec_id varchar(12) DEFAULT null,
  ged_change_date varchar(11) DEFAULT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (id,batch_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS rp_source_cite;
CREATE TABLE IF NOT EXISTS rp_source_cite (
  id int(11) NOT null AUTO_INCREMENT,
  source_id varchar(22) NOT null,
  source_batch_id tinyint(4) NOT null,
  source_page varchar(248) NOT null,
  event_type varchar(15) DEFAULT null,
  event_role varchar(15) DEFAULT null,
  quay char(1) DEFAULT null,
  source_description longtext,
  update_datetime datetime NOT null,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=337 ;

DROP TABLE IF EXISTS rp_source_note;
CREATE TABLE IF NOT EXISTS rp_source_note (
  id int(11) NOT null AUTO_INCREMENT,
  source_id varchar(22) NOT null,
  source_batch_id tinyint(4) NOT null,
  note_rec_id varchar(22),
  note longtext NOT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (id),
  KEY source_id (source_id,source_batch_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS rp_submitter;
CREATE TABLE IF NOT EXISTS rp_submitter (
  id varchar(22) NOT null,
  batch_id tinyint(4) NOT null DEFAULT '1',
  submitter_name varchar(60) DEFAULT null,
  addr_id int(11) DEFAULT null,
  lang1 varchar(90) DEFAULT null,
  lang2 varchar(90) DEFAULT null,
  lang3 varchar(90) DEFAULT null,
  registered_rfn varchar(30) DEFAULT null,
  auto_rec_id varchar(12) DEFAULT null,
  ged_change_date varchar(11) DEFAULT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (id,batch_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS rp_submitter_note;
CREATE TABLE IF NOT EXISTS rp_submitter_note (
  id int(11) NOT null AUTO_INCREMENT,
  submitter_id varchar(22) NOT null,
  submitter_batch_id tinyint(4) NOT null,
  note_rec_id varchar(22),
  note longtext NOT null,
  update_datetime datetime NOT null,
  PRIMARY KEY (id),
  KEY submitter_id (submitter_id,submitter_batch_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE rp_fam ADD UNIQUE( `id`, `batch_id`, `spouse1`, `indi_batch_id_1`, `spouse2`, `indi_batch_id_2`);
