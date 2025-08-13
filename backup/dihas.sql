/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 8.0.43 : Database - dihas
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`dihas` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `dihas`;

/*Table structure for table `castes` */

DROP TABLE IF EXISTS `castes`;

CREATE TABLE `castes` (
  `caste_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `caste_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`caste_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `castes` */

insert  into `castes`(`caste_id`,`caste_name`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'OBCM',NULL,NULL,NULL),
(2,'OBC-NCL',NULL,NULL,NULL),
(3,'SC',NULL,NULL,NULL),
(4,'ST',NULL,NULL,NULL),
(5,'General',NULL,NULL,NULL);

/*Table structure for table `districts` */

DROP TABLE IF EXISTS `districts`;

CREATE TABLE `districts` (
  `district_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `state_id` bigint unsigned NOT NULL,
  `district_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`district_id`),
  KEY `districts_state_id_foreign` (`state_id`),
  CONSTRAINT `districts_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`state_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `districts` */

insert  into `districts`(`district_id`,`state_id`,`district_name`,`created_at`,`updated_at`,`deleted_at`) values 
(1,1,'Bishnupur',NULL,NULL,NULL),
(2,1,'Chandel',NULL,NULL,NULL),
(3,1,'Churachandpur',NULL,NULL,NULL),
(4,1,'Imphal East',NULL,NULL,NULL),
(5,1,'Imphal West',NULL,NULL,NULL),
(6,1,'Jiribam',NULL,NULL,NULL),
(7,1,'Kakching',NULL,NULL,NULL),
(8,1,'Kamjong',NULL,NULL,NULL),
(9,1,'Kangpokpi',NULL,NULL,NULL),
(10,1,'Noney',NULL,NULL,NULL),
(11,1,'Pherzawl',NULL,NULL,NULL),
(12,1,'Senapati',NULL,NULL,NULL),
(13,1,'Tamenglong',NULL,NULL,NULL),
(14,1,'Tengnoupal',NULL,NULL,NULL),
(15,1,'Thoubal',NULL,NULL,NULL),
(16,1,'Ukhrul',NULL,NULL,NULL),
(17,2,'Aizawl',NULL,NULL,NULL),
(18,2,'Champhai',NULL,NULL,NULL),
(19,2,'Hnahthial',NULL,NULL,NULL),
(20,2,'Kolasib',NULL,NULL,NULL),
(21,2,'Lawngtlai',NULL,NULL,NULL),
(22,2,'Lunglei',NULL,NULL,NULL),
(23,2,'Mamit',NULL,NULL,NULL),
(24,2,'Saiha',NULL,NULL,NULL),
(25,2,'Serchhip',NULL,NULL,NULL),
(26,3,'Dimapur',NULL,NULL,NULL),
(27,3,'Kiphire',NULL,NULL,NULL),
(28,3,'Longleng',NULL,NULL,NULL),
(29,3,'Mokokchung',NULL,NULL,NULL),
(30,3,'Mon',NULL,NULL,NULL),
(31,3,'Peren',NULL,NULL,NULL),
(32,3,'Phek',NULL,NULL,NULL),
(33,3,'Tuensang',NULL,NULL,NULL),
(34,3,'Wokha',NULL,NULL,NULL),
(35,3,'Zunheboto',NULL,NULL,NULL);

/*Table structure for table `document_list` */

DROP TABLE IF EXISTS `document_list`;

CREATE TABLE `document_list` (
  `document_list_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_criteria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_size_kb` int NOT NULL DEFAULT '2048',
  `document_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`document_list_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `document_list` */

insert  into `document_list`(`document_list_id`,`document_name`,`document_criteria`,`max_size_kb`,`document_type`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'Death Certificate','compulsory',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL),
(2,'Termination Order of Deceased','compulsory',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL),
(3,'Age Proof Certificate (Birth Certificate or H.S.L.C)','compulsory',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL),
(4,'Education Qualification Certificate','compulsory',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL),
(5,'Additional Qualification Certificate','optional',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL),
(6,'Lists of family certificate indicating DoB/Sex from SDO','compulsory',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL),
(7,'No. of employee in the family Certificate from SDC/SDO','compulsory',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL),
(8,'Income Certificate from SOC/SDO','compulsory',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL),
(9,'NOC from wife/husband if applicant is son/daughter (in the form of Court affidavit)','compulsory',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL),
(10,'Affidavit from all eligible children if applicant is a son/daughter of deceased employee (NOC)','compulsory',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL),
(11,'Undertaking from the applicant to look after the children/dependent (Affidavit)','compulsory',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL),
(12,'Jamabandi Land Valuation Certificate','compulsory',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL),
(13,'Caste/Tribe Certificate','caste',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL),
(14,'Electoral Roll','compulsory',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL),
(15,'Police Report (if dead on duty)','dead_on_duty',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL),
(16,'Physically Handicapped Certificate (if any)','handicapped',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL),
(17,'Others','optional',2048,'pdf','2025-08-12 13:15:41','2025-08-12 13:15:41',NULL);

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `family_details` */

DROP TABLE IF EXISTS `family_details`;

CREATE TABLE `family_details` (
  `family_detail_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proforma_id` bigint unsigned NOT NULL,
  `relationship_id` bigint unsigned NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('male','female','transgender') COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`family_detail_id`),
  KEY `family_details_proforma_id_foreign` (`proforma_id`),
  KEY `family_details_relationship_id_foreign` (`relationship_id`),
  CONSTRAINT `family_details_proforma_id_foreign` FOREIGN KEY (`proforma_id`) REFERENCES `proforma` (`proforma_id`) ON DELETE CASCADE,
  CONSTRAINT `family_details_relationship_id_foreign` FOREIGN KEY (`relationship_id`) REFERENCES `relationships` (`relationship_id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `family_details` */

insert  into `family_details`(`family_detail_id`,`proforma_id`,`relationship_id`,`fullname`,`gender`,`dob`,`created_at`,`updated_at`) values 
(14,1,2,'fsdasdfas','male','2025-08-01','2025-08-13 09:46:41','2025-08-13 09:46:41');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'0001_00_01_000000__create_roles_table',1),
(2,'2014_10_12_100000_create_password_resets_table',1),
(3,'2019_08_19_000000_create_failed_jobs_table',1),
(4,'2019_12_14_000001_create_personal_access_tokens_table',1),
(5,'2025_06_04_084810_create_users_table',1),
(6,'2025_06_04_084820_create_process_table',1),
(7,'2025_06_04_084821_create_tasks_table',1),
(8,'2025_06_04_084822_create_process_tasks_mappings_table',1),
(9,'2025_06_04_084823_create_tasks_role_mapping_table',1),
(10,'2025_08_08_065547_create_relationships_table',1),
(11,'2025_08_08_065601_create_states_table',1),
(12,'2025_08_08_065617_create_districts_table',1),
(13,'2025_08_08_065632_create_subdivisions_table',1),
(14,'2025_08_08_065703_create_castes_table',1),
(15,'2025_08_08_065721_create_qualifications_table',1),
(17,'2025_08_10_123009_create_document_list_table',1),
(18,'2025_08_10_140646_create_family_details_table',1),
(19,'2025_08_11_142209_create_proforma_log_table',1),
(20,'2025_08_12_052829_create_uploaded_documents_table',1),
(22,'2025_08_08_065729_create_proforma_table',2);

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

/*Table structure for table `process` */

DROP TABLE IF EXISTS `process`;

CREATE TABLE `process` (
  `process_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `process_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `process_description` text COLLATE utf8mb4_unicode_ci,
  `process_criteria` text COLLATE utf8mb4_unicode_ci,
  `total_tasks` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`process_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `process` */

insert  into `process`(`process_id`,`process_name`,`process_description`,`process_criteria`,`total_tasks`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'MyProcess','dihas','[{\"field\":\"proforma_id\",\"operation\":\">\",\"value\":\"0\"}]',NULL,NULL,NULL,NULL);

/*Table structure for table `process_tasks_mappings` */

DROP TABLE IF EXISTS `process_tasks_mappings`;

CREATE TABLE `process_tasks_mappings` (
  `process_tasks_mapping_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `process_id` bigint unsigned NOT NULL,
  `tasks_id` bigint unsigned NOT NULL,
  `sequence` int NOT NULL,
  `allow_drop` int NOT NULL DEFAULT '0',
  `allow_reject` int NOT NULL DEFAULT '0',
  `allow_esign` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`process_tasks_mapping_id`),
  UNIQUE KEY `process_tasks_mappings_process_id_tasks_id_unique` (`process_id`,`tasks_id`),
  KEY `process_tasks_mappings_tasks_id_foreign` (`tasks_id`),
  CONSTRAINT `process_tasks_mappings_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `process` (`process_id`) ON DELETE CASCADE,
  CONSTRAINT `process_tasks_mappings_tasks_id_foreign` FOREIGN KEY (`tasks_id`) REFERENCES `tasks` (`tasks_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `process_tasks_mappings` */

insert  into `process_tasks_mappings`(`process_tasks_mapping_id`,`process_id`,`tasks_id`,`sequence`,`allow_drop`,`allow_reject`,`allow_esign`) values 
(1,1,1,1,0,0,0),
(2,1,2,2,1,1,0),
(3,1,3,3,1,0,0),
(4,1,4,4,0,0,0);

/*Table structure for table `proforma` */

DROP TABLE IF EXISTS `proforma`;

CREATE TABLE `proforma` (
  `proforma_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `deceased_ein` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deceased_emp_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deceased_field_dept_cd` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deceased_field_dept_desc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deceased_adm_dept_cd` int DEFAULT NULL,
  `deceased_adm_dept_desc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deceased_emp_desig` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deceased_emp_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deceased_doa` date NOT NULL,
  `deceased_dob` date NOT NULL,
  `expire_on_duty` tinyint(1) NOT NULL,
  `deceased_doe` date NOT NULL COMMENT 'Date of expiry',
  `deceased_causeofdeath` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'if expire on duty give reason',
  `request_dsg_srno_1` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_group_code_1` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_dsg_srno_2` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_group_code_2` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_adm_dept_cd_3` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_field_dept_cd_3` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_dsg_srno_3` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_group_code_3` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `applicant_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relationship_id` bigint unsigned NOT NULL,
  `applicant_dob` date NOT NULL,
  `applicant_mobile` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `applicant_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `applicant_sex` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caste_id` bigint unsigned NOT NULL,
  `physically_handicapped` tinyint(1) NOT NULL,
  `applicant_qualification_id` bigint unsigned NOT NULL,
  `applicant_qualification_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `applicant_current_locality` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `applicant_current_state_id` bigint unsigned NOT NULL,
  `applicant_current_district_id` bigint unsigned NOT NULL,
  `applicant_current_subdivision_id` bigint unsigned NOT NULL,
  `applicant_current_pincode` int NOT NULL,
  `applicant_permanent_locality` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `applicant_permanent_state_id` bigint unsigned NOT NULL,
  `applicant_permanent_district_id` bigint unsigned NOT NULL,
  `applicant_permanent_subdivision_id` bigint unsigned NOT NULL,
  `applicant_permanent_pincode` int NOT NULL,
  `process_id` int DEFAULT NULL,
  `proforma_status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process_sequence` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `create_by` bigint unsigned NOT NULL,
  `form_fillup_step` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`proforma_id`),
  KEY `proforma_relationship_id_foreign` (`relationship_id`),
  KEY `proforma_applicant_current_state_id_foreign` (`applicant_current_state_id`),
  KEY `proforma_applicant_permanent_state_id_foreign` (`applicant_permanent_state_id`),
  KEY `proforma_applicant_current_district_id_foreign` (`applicant_current_district_id`),
  KEY `proforma_applicant_permanent_district_id_foreign` (`applicant_permanent_district_id`),
  KEY `proforma_applicant_current_subdivision_id_foreign` (`applicant_current_subdivision_id`),
  KEY `proforma_caste_id_foreign` (`caste_id`),
  KEY `proforma_applicant_qualification_id_foreign` (`applicant_qualification_id`),
  CONSTRAINT `proforma_applicant_current_district_id_foreign` FOREIGN KEY (`applicant_current_district_id`) REFERENCES `districts` (`district_id`),
  CONSTRAINT `proforma_applicant_current_state_id_foreign` FOREIGN KEY (`applicant_current_state_id`) REFERENCES `states` (`state_id`),
  CONSTRAINT `proforma_applicant_current_subdivision_id_foreign` FOREIGN KEY (`applicant_current_subdivision_id`) REFERENCES `subdivisions` (`subdivision_id`),
  CONSTRAINT `proforma_applicant_permanent_district_id_foreign` FOREIGN KEY (`applicant_permanent_district_id`) REFERENCES `districts` (`district_id`),
  CONSTRAINT `proforma_applicant_permanent_state_id_foreign` FOREIGN KEY (`applicant_permanent_state_id`) REFERENCES `states` (`state_id`),
  CONSTRAINT `proforma_applicant_qualification_id_foreign` FOREIGN KEY (`applicant_qualification_id`) REFERENCES `qualifications` (`qualification_id`),
  CONSTRAINT `proforma_caste_id_foreign` FOREIGN KEY (`caste_id`) REFERENCES `castes` (`caste_id`),
  CONSTRAINT `proforma_relationship_id_foreign` FOREIGN KEY (`relationship_id`) REFERENCES `relationships` (`relationship_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `proforma` */

insert  into `proforma`(`proforma_id`,`deceased_ein`,`deceased_emp_name`,`deceased_field_dept_cd`,`deceased_field_dept_desc`,`deceased_adm_dept_cd`,`deceased_adm_dept_desc`,`deceased_emp_desig`,`deceased_emp_group`,`deceased_doa`,`deceased_dob`,`expire_on_duty`,`deceased_doe`,`deceased_causeofdeath`,`request_dsg_srno_1`,`request_group_code_1`,`request_dsg_srno_2`,`request_group_code_2`,`request_adm_dept_cd_3`,`request_field_dept_cd_3`,`request_dsg_srno_3`,`request_group_code_3`,`applicant_name`,`relationship_id`,`applicant_dob`,`applicant_mobile`,`applicant_email`,`applicant_sex`,`caste_id`,`physically_handicapped`,`applicant_qualification_id`,`applicant_qualification_name`,`applicant_current_locality`,`applicant_current_state_id`,`applicant_current_district_id`,`applicant_current_subdivision_id`,`applicant_current_pincode`,`applicant_permanent_locality`,`applicant_permanent_state_id`,`applicant_permanent_district_id`,`applicant_permanent_subdivision_id`,`applicant_permanent_pincode`,`process_id`,`proforma_status`,`process_sequence`,`created_at`,`updated_at`,`create_by`,`form_fillup_step`) values 
(1,'888888','Oinam ( O ) Ningthgoujam Shanti Devi','104','GAD, Secretariat',NULL,'Manipur Secretariat','Senior Secretariat Assistant','C','2012-06-08','1975-02-03',1,'2025-08-01','sfsdf sdfsdaf','8','C','13','C','13','528','13','C','sdfsdfsadf d',2,'2025-08-01','7777777777','sdfsd@sdfs.fhdh','male',1,0,3,NULL,'dfsfsfd',1,1,1,345345,'sdfsdfsdf',1,1,1,666666,1,'pending',2,'2025-08-13 09:08:33','2025-08-13 10:16:42',1,'submitted');

/*Table structure for table `proforma_log` */

DROP TABLE IF EXISTS `proforma_log`;

CREATE TABLE `proforma_log` (
  `proforma_log_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proforma_id` bigint unsigned NOT NULL,
  `action_by` bigint unsigned NOT NULL,
  `action_name` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`proforma_log_id`),
  KEY `proforma_log_proforma_id_foreign` (`proforma_id`),
  KEY `proforma_log_action_by_foreign` (`action_by`),
  CONSTRAINT `proforma_log_action_by_foreign` FOREIGN KEY (`action_by`) REFERENCES `users` (`user_id`),
  CONSTRAINT `proforma_log_proforma_id_foreign` FOREIGN KEY (`proforma_id`) REFERENCES `proforma` (`proforma_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `proforma_log` */

insert  into `proforma_log`(`proforma_log_id`,`proforma_id`,`action_by`,`action_name`,`action_remark`,`created_at`,`updated_at`) values 
(20,1,1,'Save Proforma on Draft','Step 1 completed','2025-08-13 09:08:33','2025-08-13 09:08:33'),
(21,1,1,'Update Proforma on Draft','Step 1 updated','2025-08-13 09:09:15','2025-08-13 09:09:15'),
(22,1,1,'Update Proforma on Draft','Step 1 updated','2025-08-13 09:09:41','2025-08-13 09:09:41'),
(23,1,1,'Update Proforma on Draft','Step 1 updated','2025-08-13 09:09:52','2025-08-13 09:09:52'),
(24,1,1,'Update Proforma on Draft','Step 1 updated','2025-08-13 09:18:11','2025-08-13 09:18:11'),
(25,1,1,'Family Detail save draft-step2','Step 2 completed','2025-08-13 09:45:37','2025-08-13 09:45:37'),
(26,1,1,'Family member deleted draft-step1','Step 1 completed','2025-08-13 09:46:23','2025-08-13 09:46:23'),
(27,1,1,'Family Detail save draft-step2','Step 2 completed','2025-08-13 09:46:45','2025-08-13 09:46:45'),
(28,1,1,'Proforma document save draft-step3','Step 3 completed','2025-08-13 09:54:47','2025-08-13 09:54:47'),
(29,1,1,'Update Proforma on Draft','Step 1 updated','2025-08-13 10:15:36','2025-08-13 10:15:36'),
(30,1,1,'Family Detail save draft-step2','Step 2 completed','2025-08-13 10:15:44','2025-08-13 10:15:44'),
(31,1,1,'Proforma document save draft-step3','Step 3 completed','2025-08-13 10:15:54','2025-08-13 10:15:54'),
(32,1,1,'forward','Form Submit by Applicant sdfsdfsadf d','2025-08-13 10:16:42','2025-08-13 10:16:42');

/*Table structure for table `qualifications` */

DROP TABLE IF EXISTS `qualifications`;

CREATE TABLE `qualifications` (
  `qualification_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `qualification_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`qualification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `qualifications` */

insert  into `qualifications`(`qualification_id`,`qualification_name`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'SLSS',NULL,NULL,NULL),
(2,'HSC',NULL,NULL,NULL),
(3,'Diploma',NULL,NULL,NULL),
(4,'Others',NULL,NULL,NULL);

/*Table structure for table `relationships` */

DROP TABLE IF EXISTS `relationships`;

CREATE TABLE `relationships` (
  `relationship_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `relationship_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`relationship_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `relationships` */

insert  into `relationships`(`relationship_id`,`relationship_name`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'Husband',NULL,NULL,NULL),
(2,'Father',NULL,NULL,NULL),
(3,'Mother',NULL,NULL,NULL),
(4,'Grandparents',NULL,NULL,NULL),
(5,'Unmarried Daughter',NULL,NULL,NULL),
(6,'Others',NULL,NULL,NULL);

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `role_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`role_id`,`role_name`) values 
(1,'HOD Assistant'),
(2,'HOD'),
(3,'AD Assistant'),
(4,'AD Nodal'),
(5,'DP Assistant'),
(6,'DP Nodal'),
(8,'DP Signing Authority'),
(9,'Department Signing Authority'),
(77,'Citizen'),
(999,'Superadmin');

/*Table structure for table `states` */

DROP TABLE IF EXISTS `states`;

CREATE TABLE `states` (
  `state_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `state_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`state_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `states` */

insert  into `states`(`state_id`,`state_name`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'Manipur',NULL,NULL,NULL),
(2,'Mizoram',NULL,NULL,NULL),
(3,'Nagaland',NULL,NULL,NULL),
(4,'Tripura',NULL,NULL,NULL),
(5,'Arunachal Pradesh',NULL,NULL,NULL),
(6,'Assam',NULL,NULL,NULL),
(7,'Meghalaya',NULL,NULL,NULL),
(8,'Sikkim',NULL,NULL,NULL),
(9,'West Bengal',NULL,NULL,NULL),
(10,'Bihar',NULL,NULL,NULL),
(11,'Jharkhand',NULL,NULL,NULL),
(12,'Odisha',NULL,NULL,NULL);

/*Table structure for table `subdivisions` */

DROP TABLE IF EXISTS `subdivisions`;

CREATE TABLE `subdivisions` (
  `subdivision_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `district_id` bigint unsigned NOT NULL,
  `subdivision_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`subdivision_id`),
  KEY `subdivisions_district_id_foreign` (`district_id`),
  CONSTRAINT `subdivisions_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`district_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `subdivisions` */

insert  into `subdivisions`(`subdivision_id`,`district_id`,`subdivision_name`,`created_at`,`updated_at`,`deleted_at`) values 
(1,1,'Nambl',NULL,NULL,NULL),
(2,1,'abv',NULL,NULL,NULL),
(3,2,'Derv',NULL,NULL,NULL),
(4,2,'TRyul',NULL,NULL,NULL),
(5,3,'BBq',NULL,NULL,NULL),
(6,3,'Rts',NULL,NULL,NULL),
(7,4,'df',NULL,NULL,NULL),
(8,4,'EGV',NULL,NULL,NULL);

/*Table structure for table `tasks` */

DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
  `tasks_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tasks_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tasks_description` text COLLATE utf8mb4_unicode_ci,
  `create_by` bigint unsigned DEFAULT NULL,
  `tasks_duty` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identifies the associated file/functionality name',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tasks_id`),
  KEY `tasks_create_by_foreign` (`create_by`),
  CONSTRAINT `tasks_create_by_foreign` FOREIGN KEY (`create_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tasks` */

insert  into `tasks`(`tasks_id`,`tasks_name`,`tasks_description`,`create_by`,`tasks_duty`,`deleted_at`,`created_at`,`updated_at`) values 
(1,'Proforma Form Fillup','Filling up of form',NULL,'client_form_submission',NULL,'2025-08-13 06:56:07','2025-08-13 06:56:07'),
(2,'DP form verification','test',NULL,'verify_and_forward',NULL,'2025-08-13 06:56:27','2025-08-13 06:56:27'),
(3,'HOD Document Verification','verification of documents by HOD',NULL,'verify_physical_copy',NULL,'2025-08-13 06:56:59','2025-08-13 06:56:59'),
(4,'DP Filling/Generate of UO Form','DP Filling/Generate of UO Form',NULL,'uo_form_generation',NULL,'2025-08-13 06:57:54','2025-08-13 06:57:54');

/*Table structure for table `tasks_role_mapping` */

DROP TABLE IF EXISTS `tasks_role_mapping`;

CREATE TABLE `tasks_role_mapping` (
  `tasks_role_mapping_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tasks_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`tasks_role_mapping_id`),
  UNIQUE KEY `tasks_role_mapping_tasks_id_role_id_unique` (`tasks_id`,`role_id`),
  KEY `tasks_role_mapping_role_id_foreign` (`role_id`),
  CONSTRAINT `tasks_role_mapping_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE,
  CONSTRAINT `tasks_role_mapping_tasks_id_foreign` FOREIGN KEY (`tasks_id`) REFERENCES `tasks` (`tasks_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tasks_role_mapping` */

insert  into `tasks_role_mapping`(`tasks_role_mapping_id`,`tasks_id`,`role_id`) values 
(1,1,77),
(2,1,999),
(3,2,5),
(4,2,999),
(5,3,2),
(6,3,999),
(7,4,5),
(8,4,999);

/*Table structure for table `uploaded_documents` */

DROP TABLE IF EXISTS `uploaded_documents`;

CREATE TABLE `uploaded_documents` (
  `uploaded_document_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proforma_id` bigint unsigned NOT NULL,
  `document_list_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint unsigned DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`uploaded_document_id`),
  KEY `uploaded_documents_proforma_id_foreign` (`proforma_id`),
  KEY `uploaded_documents_document_list_id_foreign` (`document_list_id`),
  CONSTRAINT `uploaded_documents_document_list_id_foreign` FOREIGN KEY (`document_list_id`) REFERENCES `document_list` (`document_list_id`) ON DELETE CASCADE,
  CONSTRAINT `uploaded_documents_proforma_id_foreign` FOREIGN KEY (`proforma_id`) REFERENCES `proforma` (`proforma_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `uploaded_documents` */

insert  into `uploaded_documents`(`uploaded_document_id`,`proforma_id`,`document_list_id`,`file_path`,`file_name`,`file_type`,`file_size`,`verified`,`remarks`,`created_at`,`updated_at`) values 
(1,1,1,'uploads/documents/37Y1MoMxMWyjpYhNr2ok5awzfYcSanj0k1QQaSpY.pdf','Death Certificate.pdf','application/pdf',128735,0,NULL,'2025-08-12 14:49:52','2025-08-12 14:49:52'),
(2,1,2,'uploads/documents/ASwsFrGCiwUwcg2PCPcIy3QUNjMuTAYd0MNf3gX5.pdf','Termination Order of Deceased.pdf','application/pdf',128735,0,NULL,'2025-08-12 16:08:22','2025-08-12 16:08:22'),
(3,1,3,'uploads/documents/JhXfpVWawvytxCsGUYgZS1jADoZ0a6AFxkrpvFHT.pdf','Age Proof Certificate (Birth Certificate or H.S.L.C).pdf','application/pdf',128735,0,NULL,'2025-08-12 16:08:30','2025-08-12 16:08:30'),
(4,1,4,'uploads/documents/0I5Q9TPwCyp4ZS1Wv2Hcd8ckUPZb9ruaewZj8ePB.pdf','Education Qualification Certificate.pdf','application/pdf',128735,0,NULL,'2025-08-12 16:08:40','2025-08-12 16:08:40'),
(5,1,5,'uploads/documents/LETqBiuKUS8zqQCehEsignCPcoujviPOzTqxKBG8.pdf','Additional Qualification Certificate.pdf','application/pdf',128735,0,NULL,'2025-08-12 16:08:45','2025-08-12 16:08:45'),
(6,2,1,'uploads/documents/iTZRY7tk6gyE8GcsA03BUUXoFEzPs2fsIbwdfWgE.pdf','Death Certificate.pdf','application/pdf',128735,0,NULL,'2025-08-13 06:35:10','2025-08-13 06:35:10'),
(7,2,2,'uploads/documents/8HLpiB3jCqSGpLDeQNLWVN83Pq3RJD0vcz6ofkcA.pdf','Termination Order of Deceased.pdf','application/pdf',128735,0,NULL,'2025-08-13 06:35:15','2025-08-13 06:35:15'),
(8,2,3,'uploads/documents/bPj1hfrDENwCaOKWDGVqYa36aWpEU6bsEUzhc5Hg.pdf','Age Proof Certificate (Birth Certificate or H.S.L.C).pdf','application/pdf',128735,0,NULL,'2025-08-13 06:35:22','2025-08-13 06:35:22'),
(9,2,4,'uploads/documents/RHRjpUg7h3LDVAD5fCT7fzbm4tVBa6tjEHgCh3Sk.pdf','Education Qualification Certificate.pdf','application/pdf',128735,0,NULL,'2025-08-13 06:35:29','2025-08-13 06:35:29'),
(10,2,5,'uploads/documents/ma4GRX2DqnRepHG93LsrkfWtQyFjXRYOde69xuQu.pdf','Additional Qualification Certificate.pdf','application/pdf',128735,0,NULL,'2025-08-13 06:35:40','2025-08-13 06:35:40'),
(11,2,6,'uploads/documents/UVH6FoUuT3Lm9ZSv9NAmJvmeT7Nivd5fKWLsJqzT.pdf','Lists of family certificate indicating DoB/Sex from SDO.pdf','application/pdf',128735,0,NULL,'2025-08-13 06:35:49','2025-08-13 06:35:49'),
(12,2,7,'uploads/documents/QIcmBXkoBzCRHDFqgbsBiUfG1pUf6qVZwYGfXxIC.pdf','No. of employee in the family Certificate from SDC/SDO.pdf','application/pdf',128735,0,NULL,'2025-08-13 06:36:03','2025-08-13 06:36:03'),
(13,2,8,'uploads/documents/5e4M7l2jPiNE2Ejz2RLhJu54nZfPMjZQIKO0IJ1S.pdf','Income Certificate from SOC/SDO.pdf','application/pdf',128735,0,NULL,'2025-08-13 06:36:09','2025-08-13 06:36:09'),
(14,2,9,'uploads/documents/FvkJHNf0VwA0RDtucxEPc3T1RjfIbKg3wNmjrZvJ.pdf','NOC from wife/husband if applicant is son/daughter (in the form of Court affidavit).pdf','application/pdf',128735,0,NULL,'2025-08-13 06:36:15','2025-08-13 06:36:15'),
(15,2,10,'uploads/documents/lTaiZKNpuo1XRoWalKTHAxHpdBvzr0pMAXlSsx8p.pdf','Affidavit from all eligible children if applicant is a son/daughter of deceased employee (NOC).pdf','application/pdf',128735,0,NULL,'2025-08-13 06:36:21','2025-08-13 06:36:21'),
(16,2,12,'uploads/documents/61yrxzFMQy8I1H8h7NukSiFUsHbmDzzKW0kIXXRv.pdf','Jamabandi Land Valuation Certificate.pdf','application/pdf',128735,0,NULL,'2025-08-13 06:36:27','2025-08-13 06:36:27'),
(17,2,13,'uploads/documents/9THrOsnndgQs7E6YXI0heRV7MI1zvrU3bcXvhO0y.pdf','Caste/Tribe Certificate.pdf','application/pdf',128735,0,NULL,'2025-08-13 06:36:34','2025-08-13 06:36:34'),
(18,2,14,'uploads/documents/Rep4GEqd1Shs8jOyEv2dlVAdTh9i4pllf9rtGM9h.pdf','Electoral Roll.pdf','application/pdf',128735,0,NULL,'2025-08-13 06:36:39','2025-08-13 06:36:39'),
(19,2,16,'uploads/documents/Udobg1SyaI6CZlOkwjiSlraTil4M7rUG4Oo6NuVV.pdf','Physically Handicapped Certificate (if any).pdf','application/pdf',128735,0,NULL,'2025-08-13 06:36:53','2025-08-13 06:36:53'),
(20,2,11,'uploads/documents/9s9GuCbMZeF3uFJ2rbZ6QroMJDxm0YnBbD8Pe7uz.pdf','Undertaking from the applicant to look after the children/dependent (Affidavit).pdf','application/pdf',128735,0,NULL,'2025-08-13 06:37:08','2025-08-13 06:37:08'),
(21,1,6,'uploads/documents/e8qPWoGRrN2ydGf963lTR9mg3JBqUrDNYJ08Vjva.pdf','Lists of family certificate indicating DoB/Sex from SDO.pdf','application/pdf',128735,0,NULL,'2025-08-13 09:46:55','2025-08-13 09:46:55'),
(22,1,7,'uploads/documents/cgLyFJY971xRtbiyhhPs1cWOvri0kViayxez8Yor.pdf','No. of employee in the family Certificate from SDC/SDO.pdf','application/pdf',128735,0,NULL,'2025-08-13 09:47:00','2025-08-13 09:47:00'),
(23,1,8,'uploads/documents/G9JwQLNN4EaWL26wdCXXlZiKACW6tKirdnZO9tn9.pdf','Income Certificate from SOC/SDO.pdf','application/pdf',128735,0,NULL,'2025-08-13 09:47:05','2025-08-13 09:47:05'),
(24,1,9,'uploads/documents/ofvq1SCmTZ6qoMTgwI1GeffX7BktZugheoRQC0Tu.pdf','NOC from wife/husband if applicant is son/daughter (in the form of Court affidavit).pdf','application/pdf',128735,0,NULL,'2025-08-13 09:47:09','2025-08-13 09:47:09'),
(25,1,10,'uploads/documents/rgM6jPQgHHbpKe91yKF0A5cDIQ1byjZXOooKKkpp.pdf','Affidavit from all eligible children if applicant is a son/daughter of deceased employee (NOC).pdf','application/pdf',128735,0,NULL,'2025-08-13 09:47:17','2025-08-13 09:47:17'),
(26,1,11,'uploads/documents/obd2uJQFYmf9Xt4x2bUHKIwWZXG6gyZya9JBOSUC.pdf','Undertaking from the applicant to look after the children/dependent (Affidavit).pdf','application/pdf',128735,0,NULL,'2025-08-13 09:47:22','2025-08-13 09:47:22'),
(27,1,12,'uploads/documents/0EFdNtxU2vmnBj3zgpGfXuoMH7iZ9yUKVzXOq3bk.pdf','Jamabandi Land Valuation Certificate.pdf','application/pdf',128735,0,NULL,'2025-08-13 09:47:27','2025-08-13 09:47:27'),
(28,1,13,'uploads/documents/bERzfSJ8fjiLDpGmp35ij0rwjxrOCtvwpUf0d0U7.pdf','Caste/Tribe Certificate.pdf','application/pdf',128735,0,NULL,'2025-08-13 09:47:32','2025-08-13 09:47:32'),
(29,1,14,'uploads/documents/EzLuNeL34L63fTWhYAoGpjstlOIjrTgKLAfIONJc.pdf','Electoral Roll.pdf','application/pdf',128735,0,NULL,'2025-08-13 09:47:39','2025-08-13 09:47:39'),
(30,1,15,'uploads/documents/LQWuRON8uvrIEMFfOS6ChTIzkF8XRSY4Rsi8VVjy.pdf','Police Report (if dead on duty).pdf','application/pdf',128735,0,NULL,'2025-08-13 09:47:45','2025-08-13 09:47:45');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `dsg_serial_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_dept_cd` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attempts` int DEFAULT NULL,
  `last_attempt_date` date DEFAULT NULL,
  `active_status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`user_id`,`fullname`,`mobile`,`email`,`password`,`role_id`,`dsg_serial_no`,`field_dept_cd`,`email_verified_at`,`remember_token`,`attempts`,`last_attempt_date`,`active_status`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'Administrator','9999999999','lkonsam@gmail.com','$2y$10$cVctVm7BbHk26yVm8nQGJ.X4tcvdRkj8BTekSe9YxT7Wm2NPXJen6',999,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL),
(2,'Tomba Singh','9111111111','leecba@gmail.com','$2y$10$Z2CtFIRxhxJcLzzwg2tHJeaABigCw1tm0hS.HhR1iiw8hvaH8nGAK',77,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
