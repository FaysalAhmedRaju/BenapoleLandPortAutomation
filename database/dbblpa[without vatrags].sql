/*
SQLyog Ultimate v11.5 (64 bit)
MySQL - 5.5.44-MariaDB : Database - dbblpa
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`dbblpa` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `dbblpa`;

/*Table structure for table `acc_head` */

CREATE TABLE `acc_head` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acc_head` varchar(150) DEFAULT NULL,
  `in_ex_status` int(5) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

/*Table structure for table `acc_sub_head` */

CREATE TABLE `acc_sub_head` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `head_id` int(11) DEFAULT NULL,
  `acc_sub_head` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=221 DEFAULT CHARSET=latin1;

/*Table structure for table `assesment_details` */

CREATE TABLE `assesment_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manif_id` int(11) DEFAULT NULL,
  `sub_head_id` int(11) DEFAULT NULL,
  `unit` int(11) DEFAULT NULL,
  `other_unit` decimal(12,2) DEFAULT NULL,
  `charge_per_unit` decimal(14,2) DEFAULT NULL,
  `tcharge` decimal(14,2) DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `verified` tinyint(4) DEFAULT '0',
  `verify_comm` varchar(100) DEFAULT NULL,
  `verified_by` varchar(50) DEFAULT NULL,
  `verify_dt` datetime DEFAULT NULL,
  `approved` tinyint(4) DEFAULT '0',
  `approve_comment` varchar(100) DEFAULT NULL,
  `approved_by` varchar(50) DEFAULT NULL,
  `approved_dt` datetime DEFAULT NULL,
  `done_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

/*Table structure for table `bank_details` */

CREATE TABLE `bank_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_and_address` varchar(200) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `create_by` varchar(100) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

/*Table structure for table `bonus` */

CREATE TABLE `bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `amount` decimal(14,2) DEFAULT '0.00',
  `type` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `creator` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

/*Table structure for table `cargo_details` */

CREATE TABLE `cargo_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cargo_name` varchar(100) DEFAULT NULL,
  `cargo_description` varchar(250) DEFAULT NULL,
  `category` varchar(20) DEFAULT NULL,
  `Charges_type` varchar(50) DEFAULT NULL,
  `Shed_first_slab` decimal(14,2) DEFAULT NULL,
  `Shed_second_slab` decimal(14,2) DEFAULT NULL,
  `Shed_third_slab` decimal(14,2) DEFAULT NULL,
  `yard_first_slab` decimal(14,2) DEFAULT NULL,
  `yard_second_slab` decimal(14,2) DEFAULT NULL,
  `yard_third_slab` decimal(14,2) DEFAULT NULL,
  `tariff_year` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=207 DEFAULT CHARSET=latin1;

/*Table structure for table `challan_details` */

CREATE TABLE `challan_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manf_id` varchar(50) DEFAULT NULL,
  `challan_no` varchar(10) DEFAULT NULL,
  `challan_dt` date DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

/*Table structure for table `charge_receive_banks` */

CREATE TABLE `charge_receive_banks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manif_id` int(11) DEFAULT NULL,
  `T_charge` decimal(14,2) DEFAULT NULL,
  `vat` decimal(12,2) DEFAULT NULL,
  `paymode` varchar(15) DEFAULT NULL,
  `challan_no` varchar(11) DEFAULT NULL,
  `payment_details` varchar(100) DEFAULT NULL,
  `recived_by` varchar(15) DEFAULT NULL,
  `receive_dt` datetime DEFAULT NULL,
  `comment` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

/*Table structure for table `customs_dutys` */

CREATE TABLE `customs_dutys` (
  `id` int(11) DEFAULT NULL,
  `manf_id` int(11) DEFAULT NULL,
  `be_no` varchar(15) DEFAULT NULL,
  `be_date` date DEFAULT NULL,
  `release_order` varchar(15) DEFAULT NULL,
  `release_date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `deductions` */

CREATE TABLE `deductions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(20) DEFAULT NULL,
  `house_rent` decimal(14,2) DEFAULT '0.00',
  `water` decimal(14,2) DEFAULT '0.00',
  `generator` decimal(14,2) DEFAULT '0.00',
  `electricity` decimal(14,2) DEFAULT '0.00',
  `previous_due` decimal(14,2) DEFAULT '0.00',
  `month_year` date DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `creator` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Table structure for table `delivery_export` */

CREATE TABLE `delivery_export` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `truck_no` varchar(50) DEFAULT NULL,
  `truck_type` tinyint(1) DEFAULT NULL,
  `driver_name` varchar(100) DEFAULT NULL,
  `entry_datetime` date DEFAULT NULL,
  `entry_by` varchar(100) DEFAULT NULL,
  `exit_datetime` datetime DEFAULT NULL,
  `exit_by` varchar(100) DEFAULT NULL,
  `haltage_day` int(3) DEFAULT NULL,
  `entrance_fee` decimal(14,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=latin1;

/*Table structure for table `delivery_export_bus` */

CREATE TABLE `delivery_export_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_no` varchar(50) DEFAULT NULL,
  `entry_datetime` date DEFAULT NULL,
  `haltage_day` int(3) DEFAULT NULL,
  `entrance_fee` decimal(14,2) DEFAULT NULL,
  `entry_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

/*Table structure for table `delivery_export_challan` */

CREATE TABLE `delivery_export_challan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `export_challan_no` varchar(50) DEFAULT NULL,
  `delivery_export_id` varchar(50) DEFAULT NULL,
  `miscellaneous_name` varchar(100) DEFAULT NULL,
  `miscellaneous_charge` decimal(11,2) DEFAULT '0.00',
  `total_amount` decimal(14,2) DEFAULT '0.00',
  `create_by` varchar(50) DEFAULT NULL,
  `create_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

/*Table structure for table `delivery_export_challan_bus` */

CREATE TABLE `delivery_export_challan_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `export_challan_no` varchar(50) DEFAULT NULL,
  `delivery_export_bus_id` varchar(50) DEFAULT NULL,
  `miscellaneous_name` varchar(100) DEFAULT NULL,
  `miscellaneous_charge` decimal(11,2) DEFAULT '0.00',
  `total_amount` decimal(14,2) DEFAULT '0.00',
  `create_by` varchar(50) DEFAULT NULL,
  `create_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

/*Table structure for table `designations` */

CREATE TABLE `designations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(100) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Table structure for table `employee_designations` */

CREATE TABLE `employee_designations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `desig_id` int(11) DEFAULT NULL,
  `basic` decimal(14,2) DEFAULT '0.00',
  `scale_year` year(4) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `created_by` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

/*Table structure for table `employees` */

CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(7) DEFAULT NULL,
  `name` varchar(155) DEFAULT NULL,
  `father_name` varchar(155) DEFAULT NULL,
  `mother_name` varchar(155) DEFAULT NULL,
  `mobile` varchar(12) DEFAULT NULL,
  `telephone` varchar(12) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `national_id` varchar(21) DEFAULT NULL,
  `national_id_photo` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `date_join` datetime DEFAULT NULL,
  `present_address` varchar(255) DEFAULT NULL,
  `permanent_address` varchar(255) DEFAULT NULL,
  `children` int(5) DEFAULT '0',
  `photo` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `create_dt` datetime DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;

/*Table structure for table `expenditures` */

CREATE TABLE `expenditures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vouchar_id` int(11) DEFAULT NULL,
  `sub_head_id` int(11) DEFAULT NULL,
  `amount` decimal(14,2) DEFAULT NULL,
  `ex_date` date DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

/*Table structure for table `fdr_accounts` */

CREATE TABLE `fdr_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_detail_id` int(11) DEFAULT NULL,
  `sl_no` varchar(20) DEFAULT NULL,
  `fdr_no` varchar(40) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `create_by` varchar(50) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fdr_accounts_has_bank_details_id` (`bank_detail_id`),
  CONSTRAINT `fdr_accounts_has_bank_details_id` FOREIGN KEY (`bank_detail_id`) REFERENCES `bank_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=latin1;

/*Table structure for table `fdr_actions` */

CREATE TABLE `fdr_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fdr_account_id` int(11) DEFAULT NULL,
  `sl_no` varchar(20) DEFAULT NULL,
  `main_amount` decimal(14,2) DEFAULT '0.00',
  `opening_date` date DEFAULT NULL,
  `duration` int(11) DEFAULT '0',
  `expire_date` date DEFAULT NULL,
  `interest_rate` decimal(10,2) DEFAULT '0.00',
  `total_interest` decimal(14,2) DEFAULT '0.00',
  `income_tax` decimal(14,2) DEFAULT '0.00',
  `excavator_tariff` decimal(14,2) DEFAULT '0.00',
  `net_interest` decimal(14,2) DEFAULT '0.00',
  `bank_charge` decimal(14,2) DEFAULT '0.00',
  `vat` decimal(10,2) DEFAULT '0.00',
  `total_balance` decimal(14,2) DEFAULT '0.00',
  `comments` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `create_by` varchar(50) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fdr_actions_has_fdr_accounts` (`fdr_account_id`),
  CONSTRAINT `fdr_actions_has_fdr_accounts` FOREIGN KEY (`fdr_account_id`) REFERENCES `fdr_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

/*Table structure for table `fdr_closings` */

CREATE TABLE `fdr_closings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fdr_account_id` int(11) DEFAULT NULL,
  `bank_detail_id` int(11) DEFAULT NULL,
  `payorder_cheque_payslip_no` varchar(50) DEFAULT NULL,
  `transaction_acc_no` varchar(50) DEFAULT NULL,
  `official_order_no` varchar(50) DEFAULT NULL,
  `bank_charge` decimal(14,2) DEFAULT '0.00',
  `vat` decimal(10,2) DEFAULT '0.00',
  `total_closing_ammount` decimal(14,2) DEFAULT '0.00',
  `create_by` varchar(50) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fdr_closings_has_fdr_accounts` (`fdr_account_id`),
  KEY `fdr_closings_has_bank_details` (`bank_detail_id`),
  CONSTRAINT `fdr_closings_has_bank_details` FOREIGN KEY (`bank_detail_id`) REFERENCES `bank_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fdr_closings_has_fdr_accounts` FOREIGN KEY (`fdr_account_id`) REFERENCES `fdr_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `fdr_details` */

CREATE TABLE `fdr_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sl_no` varchar(10) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `fdr_no` varchar(50) DEFAULT NULL,
  `main_amount` decimal(14,2) DEFAULT '0.00',
  `opening_dt` date DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `expire_dt` date DEFAULT NULL,
  `interest_rate` decimal(10,2) DEFAULT '0.00',
  `total_interest` decimal(14,2) DEFAULT '0.00',
  `income_tax` decimal(14,2) DEFAULT '0.00',
  `excavator_tariff` decimal(14,2) DEFAULT '0.00',
  `net_interest` decimal(14,2) DEFAULT '0.00',
  `total_with_interest` decimal(14,2) DEFAULT '0.00',
  `comments` varchar(100) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `fixed_facilities_and_deductions` */

CREATE TABLE `fixed_facilities_and_deductions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_rent` decimal(14,2) DEFAULT '0.00',
  `education` decimal(14,2) DEFAULT '0.00',
  `medical` decimal(14,2) DEFAULT '0.00',
  `tiffin` decimal(14,2) DEFAULT '0.00',
  `gpf` decimal(14,2) DEFAULT '0.00',
  `revenue` decimal(14,2) DEFAULT '0.00',
  `scale_year` year(4) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `creator` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `handling_and_othercharges` */

CREATE TABLE `handling_and_othercharges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_of_charge` varchar(50) DEFAULT NULL,
  `name_of_charge` varchar(150) DEFAULT NULL,
  `description_of_charge` varchar(100) DEFAULT NULL,
  `rate_of_charges` decimal(14,2) DEFAULT NULL,
  `charges_year` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

/*Table structure for table `holidays` */

CREATE TABLE `holidays` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hday` date NOT NULL,
  `day_name` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

/*Table structure for table `holydays` */

CREATE TABLE `holydays` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hday` date NOT NULL,
  `day_name` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=233 DEFAULT CHARSET=utf8;

/*Table structure for table `increments` */

CREATE TABLE `increments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(20) DEFAULT NULL,
  `type` varchar(60) DEFAULT NULL,
  `amount` decimal(14,2) DEFAULT '0.00',
  `date` date DEFAULT NULL,
  `create_dt` date DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

/*Table structure for table `item_codes` */

CREATE TABLE `item_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(6) DEFAULT NULL,
  `Description` varchar(75) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=latin1;

/*Table structure for table `item_details` */

CREATE TABLE `item_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manf_id` int(11) DEFAULT NULL,
  `item_Code_id` tinyint(6) DEFAULT NULL,
  `item_type` varchar(1) DEFAULT NULL,
  `item_quantity` int(11) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `dangerous` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

/*Table structure for table `manifests` */

CREATE TABLE `manifests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `port_id` int(11) DEFAULT NULL,
  `manifest` varchar(20) DEFAULT NULL,
  `manifest_date` date DEFAULT NULL,
  `marks_no` varchar(50) DEFAULT NULL,
  `goods_id` varchar(50) DEFAULT NULL,
  `gweight` decimal(14,2) DEFAULT NULL,
  `nweight` decimal(14,2) DEFAULT NULL,
  `package_no` decimal(14,2) DEFAULT NULL,
  `package_type` varchar(15) DEFAULT NULL,
  `cnf_value` decimal(18,2) DEFAULT NULL,
  `exporter_name_addr` varchar(255) DEFAULT NULL,
  `vat_id` varchar(15) DEFAULT NULL,
  `lc_no` varchar(25) DEFAULT NULL,
  `lc_date` date DEFAULT NULL,
  `ind_be_no` varchar(15) DEFAULT NULL,
  `ind_be_date` date DEFAULT NULL,
  `be_no` varchar(15) DEFAULT NULL,
  `be_date` date DEFAULT NULL,
  `paid_tax` float DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `custom_approved_by` varchar(50) DEFAULT NULL,
  `custom_approved_date` datetime DEFAULT NULL,
  `no_del_truck` int(5) DEFAULT NULL,
  `manifest_posted_by` int(11) DEFAULT NULL,
  `manifest_created_time` datetime DEFAULT NULL,
  `carpenter_packages` int(5) DEFAULT NULL,
  `carpenter_repair_packages` int(5) DEFAULT NULL,
  `carpenter_charge_id` int(2) DEFAULT NULL,
  `carpenter_repair_id` int(2) DEFAULT NULL,
  `ain_no` varchar(50) DEFAULT NULL,
  `cnf_name` varchar(100) DEFAULT NULL,
  `posting_remark` varchar(100) DEFAULT NULL,
  `posted_yard_shed` int(11) DEFAULT NULL,
  `gate_pass_no` varchar(50) DEFAULT NULL,
  `custom_release_order_no` varchar(50) DEFAULT NULL,
  `custom_release_order_date` varchar(50) DEFAULT NULL,
  `approximate_delivery_date` date DEFAULT NULL,
  `approximate_delivery_type` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=515 DEFAULT CHARSET=latin1;

/*Table structure for table `multi_entrys` */

CREATE TABLE `multi_entrys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `passport_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `entry_exit_status` varchar(50) DEFAULT NULL,
  `entry_reasons` varchar(100) DEFAULT NULL,
  `comment` varchar(100) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=130 DEFAULT CHARSET=latin1;

/*Table structure for table `org_types` */

CREATE TABLE `org_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `org_type` varchar(50) DEFAULT NULL,
  `type_description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Unq_Key_Org_Type` (`org_type`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Table structure for table `organization_employes` */

CREATE TABLE `organization_employes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `org_id` int(11) DEFAULT NULL,
  `emp_id` varchar(20) DEFAULT NULL,
  `emp_name` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `national_id` varchar(21) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `designation` varchar(20) DEFAULT NULL,
  `phone_no` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `photo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=74 DEFAULT CHARSET=latin1;

/*Table structure for table `organizations` */

CREATE TABLE `organizations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `port_id` int(5) DEFAULT NULL,
  `org_type_id` int(11) DEFAULT NULL,
  `org_name` varchar(150) DEFAULT NULL,
  `add1` varchar(150) DEFAULT NULL,
  `add2` varchar(150) DEFAULT NULL,
  `propriter_name` varchar(100) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `created_by` varchar(15) DEFAULT NULL,
  `create_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

/*Table structure for table `passports` */

CREATE TABLE `passports` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `passport_no` varchar(50) DEFAULT NULL,
  `country_code` varchar(50) DEFAULT NULL,
  `sur_name` varchar(100) DEFAULT NULL,
  `given_name` varchar(100) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `sex` int(5) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `place_of_birth` varchar(100) DEFAULT NULL,
  `place_of_issue` varchar(100) DEFAULT NULL,
  `date_of_issue` date DEFAULT NULL,
  `date_of_expired` date DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

/*Table structure for table `ports` */

CREATE TABLE `ports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `port_name` varchar(100) DEFAULT NULL,
  `port_add1` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Table structure for table `roles` */

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `salarys` */

CREATE TABLE `salarys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `emp_id` varchar(10) DEFAULT NULL,
  `emp_name` varchar(100) DEFAULT NULL,
  `emp_designation` varchar(50) DEFAULT NULL,
  `new_salary` decimal(14,2) DEFAULT '0.00',
  `old_salary` decimal(14,2) DEFAULT '0.00',
  `house_rent` decimal(14,2) DEFAULT '0.00',
  `edu_allowance` decimal(14,2) DEFAULT '0.00',
  `medi_allowance` decimal(14,2) DEFAULT '0.00',
  `due_edu_allowance` decimal(14,2) DEFAULT '0.00',
  `tiffin` decimal(14,2) DEFAULT '0.00',
  `total_in` decimal(14,2) DEFAULT '0.00',
  `gpf` decimal(14,2) DEFAULT '0.00',
  `house_rent_deduction` decimal(14,2) DEFAULT '0.00',
  `water` decimal(14,2) DEFAULT '0.00',
  `generator` decimal(14,2) DEFAULT '0.00',
  `previous_due` decimal(14,2) DEFAULT '0.00',
  `electricity` decimal(14,2) DEFAULT '0.00',
  `revenue` decimal(14,2) DEFAULT '0.00',
  `total_deduction` decimal(14,2) DEFAULT '0.00',
  `total_payable` decimal(14,2) DEFAULT '0.00',
  `payable_month_year` date DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=173 DEFAULT CHARSET=latin1;

/*Table structure for table `some_restrictions` */

CREATE TABLE `some_restrictions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restriction_name` varchar(50) DEFAULT NULL,
  `restriction_code` varchar(3) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `created_by` varchar(20) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(20) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Table structure for table `tariff_schedule` */

CREATE TABLE `tariff_schedule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) DEFAULT NULL,
  `Shed_first_slab` decimal(14,2) DEFAULT NULL,
  `Shed_second_slab` decimal(14,2) DEFAULT NULL,
  `Shed_third_slab` decimal(14,2) DEFAULT NULL,
  `yard_first_slab` decimal(14,2) DEFAULT NULL,
  `yard_second_slab` decimal(14,2) DEFAULT NULL,
  `yard_third_slab` decimal(14,2) DEFAULT NULL,
  `tariff_year` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Table structure for table `transaction` */

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manif_id` int(11) DEFAULT NULL,
  `sub_head_id` int(11) DEFAULT NULL,
  `challan_details_id` int(11) DEFAULT NULL,
  `particulars` varchar(120) DEFAULT NULL,
  `debit` decimal(12,2) DEFAULT '0.00',
  `credit` decimal(12,2) DEFAULT '0.00',
  `comments` varchar(120) DEFAULT NULL,
  `userid` varchar(20) DEFAULT NULL,
  `trans_dt` datetime DEFAULT NULL,
  `entry_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=271 DEFAULT CHARSET=latin1;

/*Table structure for table `truck_deliverys` */

CREATE TABLE `truck_deliverys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manf_id` int(11) DEFAULT NULL,
  `truck_no` varchar(50) DEFAULT NULL,
  `driver_name` varchar(100) DEFAULT NULL,
  `gweight` decimal(12,2) DEFAULT NULL,
  `package` varchar(15) DEFAULT NULL,
  `delivery_req_dt` datetime DEFAULT NULL,
  `delivery_req_by` varchar(50) DEFAULT NULL,
  `delivery_dt` datetime DEFAULT NULL,
  `approve_by` varchar(15) DEFAULT NULL,
  `weightment_flag` tinyint(1) DEFAULT NULL,
  `approve_dt` datetime DEFAULT NULL,
  `loading_flag` int(5) DEFAULT NULL,
  `loading_unit` float DEFAULT NULL,
  `labor_load` float DEFAULT '0',
  `labor_package` varchar(50) DEFAULT NULL,
  `equip_load` float DEFAULT '0',
  `equip_name` varchar(20) DEFAULT NULL,
  `equipment_package` varchar(50) DEFAULT NULL,
  `loading_manual` int(5) DEFAULT NULL,
  `loading_equipment` int(5) DEFAULT NULL,
  `entry_dt` datetime DEFAULT NULL,
  `entry_comment` varchar(50) DEFAULT NULL,
  `entry_by` varchar(25) DEFAULT NULL,
  `exit_dt` datetime DEFAULT NULL,
  `exit_by` varchar(25) DEFAULT NULL,
  `exit_comment` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=388 DEFAULT CHARSET=latin1;

/*Table structure for table `truck_entry_regs` */

CREATE TABLE `truck_entry_regs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manf_id` int(11) DEFAULT NULL,
  `truck_type` varchar(15) DEFAULT NULL,
  `truck_no` varchar(10) DEFAULT NULL,
  `goods_id` varchar(50) DEFAULT NULL,
  `gweight` decimal(14,2) DEFAULT NULL,
  `nweight` decimal(14,2) DEFAULT NULL,
  `driver_card` varchar(10) DEFAULT NULL,
  `driver_name` varchar(50) DEFAULT NULL,
  `truckentry_datetime` datetime DEFAULT NULL,
  `created_by` varchar(20) DEFAULT NULL,
  `shifting_flag` tinyint(1) DEFAULT NULL,
  `weightment_flag` tinyint(1) DEFAULT NULL,
  `gweight_wbridge` decimal(12,2) DEFAULT NULL,
  `wbridg_user1` varchar(15) DEFAULT NULL,
  `wbrdge_time1` datetime DEFAULT NULL,
  `tweight_wbridge` decimal(12,2) DEFAULT NULL,
  `tr_weight` decimal(12,2) DEFAULT NULL,
  `wbridg_user2` varchar(15) DEFAULT NULL,
  `wbrdge_time2` datetime DEFAULT NULL,
  `posted_yard_shed` varchar(15) DEFAULT NULL,
  `posted_by` int(11) DEFAULT NULL,
  `posted_time` datetime DEFAULT NULL,
  `receive_weight` decimal(12,2) DEFAULT NULL,
  `receive_package` varchar(15) DEFAULT NULL,
  `recive_comment` varchar(100) DEFAULT NULL,
  `receive_datetime` datetime DEFAULT NULL,
  `receive_by` varchar(15) DEFAULT NULL,
  `labor_unload` float DEFAULT NULL,
  `offloading_manual` int(11) DEFAULT NULL,
  `offloading_equipment` int(11) DEFAULT NULL,
  `labor_package` varchar(50) DEFAULT NULL,
  `offloading_flag` float DEFAULT NULL,
  `equip_unload` float DEFAULT NULL,
  `equip_name` varchar(20) DEFAULT NULL,
  `equipment_package` varchar(50) DEFAULT NULL,
  `carpenter` int(5) DEFAULT NULL,
  `out_date` datetime DEFAULT NULL,
  `out_by` varchar(15) DEFAULT NULL,
  `out_comment` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1147 DEFAULT CHARSET=latin1;

/*Table structure for table `users` */

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `port_id` int(11) NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `org_id` int(5) DEFAULT NULL,
  `org_type_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `father_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mother_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(13) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(13) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `designation` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `present_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `permanent_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `photo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nid_no` varchar(21) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nid_photo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `visa_details` */

CREATE TABLE `visa_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `passport_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `numbers_of_entries` int(11) DEFAULT NULL,
  `duration_of_stay` int(11) DEFAULT NULL,
  `remarks` varchar(50) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

/*Table structure for table `vouchers` */

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vouchar_no` varchar(20) DEFAULT NULL,
  `vouchar_date` date DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;

/*Table structure for table `yard_details` */

CREATE TABLE `yard_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yard_shed` varchar(20) DEFAULT NULL,
  `square_feet` decimal(14,2) DEFAULT NULL,
  `capacity_ton` decimal(14,2) DEFAULT NULL,
  `yard_shed_name` varchar(50) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=latin1;

/*Table structure for table `yard_graphs` */

CREATE TABLE `yard_graphs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `truck_id` int(11) DEFAULT NULL,
  `yard_id` int(11) DEFAULT NULL,
  `row` varchar(10) DEFAULT NULL,
  `column` int(10) DEFAULT NULL,
  `weight` decimal(11,2) DEFAULT NULL,
  `capacity` decimal(11,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=88 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
