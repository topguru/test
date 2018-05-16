DROP TABLE IF EXISTS `#__symbol_pricing_usergroup`;
DROP TABLE IF EXISTS `#__symbol_pricing_breakdown`;
DROP TABLE IF EXISTS `#__symbol_pricing_details`;
DROP TABLE IF EXISTS `#__ap_winners_actual`;
DROP TABLE IF EXISTS `#__ap_potential_winner`;
DROP TABLE IF EXISTS `#__ap_winner_returned`;
DROP TABLE IF EXISTS `#__ap_winner_symbol`;
DROP TABLE IF EXISTS `#__ap_winners_user`;
DROP TABLE IF EXISTS `#__ap_prize_claim`;
DROP TABLE IF EXISTS `#__ap_winners`;
DROP TABLE IF EXISTS `#__symbol_group_module`;
DROP TABLE IF EXISTS `#__ap_donation_details`;
DROP TABLE IF EXISTS `#__ap_donation_transactions`;
DROP TABLE IF EXISTS `#__ap_useraccounts`;
DROP TABLE IF EXISTS `#__ap_categories`;
DROP TABLE IF EXISTS `#__ap_usergroup`;
DROP TABLE IF EXISTS `#__ap_donation_variables`;
DROP TABLE IF EXISTS `#__ap_award_schedule`;
DROP TABLE IF EXISTS `#__ap_user_packages`;
DROP TABLE IF EXISTS `#__ap_non_user_packages`;
DROP TABLE IF EXISTS `#__ap_award_archive`;
DROP TABLE IF EXISTS `#__ap_awardpackages`;
DROP TABLE IF EXISTS `#__funding_revenue`;
DROP TABLE IF EXISTS `#__funding`;
DROP TABLE IF EXISTS `#__funding_user`;
DROP TABLE IF EXISTS `#__withdraw_user`;
DROP TABLE IF EXISTS `#__funding_history`;
DROP TABLE IF EXISTS `#__donation_history`;
DROP TABLE IF EXISTS `#__funding_presentations`;
DROP TABLE IF EXISTS `#__gc_recieve_user`;
DROP TABLE IF EXISTS `#__giftcode_giftcode`;
DROP TABLE IF EXISTS `#__giftcode_category`;
DROP TABLE IF EXISTS `#__giftcode_collection`;
DROP TABLE IF EXISTS `#__giftcode_collection_setting`;
DROP TABLE IF EXISTS `#__giftcode_queue`;
DROP TABLE IF EXISTS `#__giftcode_renew_schedule`;
DROP TABLE IF EXISTS `#__giftcode_schedule_created`;
DROP TABLE IF EXISTS `#__ap_award_symbol_progress`;
DROP TABLE IF EXISTS `#__ap_winner_setting`;
DROP TABLE IF EXISTS `#__ap_potential_winner_module`;
DROP TABLE IF EXISTS `#__symbol_symbol_prize`;
DROP TABLE IF EXISTS `#__symbol_pricing`;
DROP TABLE IF EXISTS `#__symbol_presentation`;
DROP TABLE IF EXISTS `#__symbol_prize`;
DROP TABLE IF EXISTS `#__symbol_queue`;
DROP TABLE IF EXISTS `#__symbol_queue_detail`;
DROP TABLE IF EXISTS `#__symbol_symbol_pieces`;
DROP TABLE IF EXISTS `#__symbol_symbol`;
DROP TABLE IF EXISTS `#__symbol_user`;
DROP TABLE IF EXISTS `#__giftcode_user`;
DROP TABLE IF EXISTS `#__refund_record_details`;
DROP TABLE IF EXISTS `#__refund_record`;
DROP TABLE IF EXISTS `#__refund_schedule_day`;
DROP TABLE IF EXISTS `#__refund_distribution_schedule`;
DROP TABLE IF EXISTS `#__refund_usergroup`;
DROP TABLE IF EXISTS `#__refund_recepient_group_module`;
DROP TABLE IF EXISTS `#__refund_calculator`;
DROP TABLE IF EXISTS `#__refund_qualifying_event`;
DROP TABLE IF EXISTS `#__refund_refundable_activity`;
DROP TABLE IF EXISTS `#__refund_quota`;
DROP TABLE IF EXISTS `#__refund_package`;
DROP TABLE IF EXISTS `#__refund_package_list`;
DROP TABLE IF EXISTS `#__refund_distribution_list`;
DROP TABLE IF EXISTS `#__refund_claim`;
DROP TABLE IF EXISTS `#__refund_schedule_calendar`;
DROP TABLE IF EXISTS `#__symbol_order`;
DROP TABLE IF EXISTS `#__jvotesystem_deposit`;
DROP TABLE IF EXISTS `#__jvotesystem_transaction`;
DROP TABLE IF EXISTS `#__jvotesystem_transaction_detail`;
DROP TABLE IF EXISTS `#__shopping_claim`;
DROP TABLE IF EXISTS `#__shopping_calculator`;
DROP TABLE IF EXISTS `#__shopping_record`;
DROP TABLE IF EXISTS `#__shopping_credit_distribution_list`;
DROP TABLE IF EXISTS `#__shopping_recepient_group_module`;
DROP TABLE IF EXISTS `#__shopping_usergroup`;
DROP TABLE IF EXISTS `#__shopping_schedule_calendar`;
DROP TABLE IF EXISTS `#__shopping_schedule_day`;
DROP TABLE IF EXISTS `#__shopping_distribution_schedule`;
DROP TABLE IF EXISTS `#__shopping_credit_config`;
DROP TABLE IF EXISTS `#__shopping_credit_package`;
DROP TABLE IF EXISTS `#__shopping_credit_package_list`;
DROP TABLE IF EXISTS `#__ap_payment_options`;
DROP TABLE IF EXISTS `#__paypal_config`;

DROP TABLE IF EXISTS `#__process_presentation`;

DROP TABLE IF EXISTS `#__survey`;
DROP TABLE IF EXISTS `#__survey_answers`;
DROP TABLE IF EXISTS `#__survey_contactgroups`;
DROP TABLE IF EXISTS `#__survey_contact_group_map`;
DROP TABLE IF EXISTS `#__survey_contacts`;
DROP TABLE IF EXISTS `#__survey_countries`;
DROP TABLE IF EXISTS `#__survey_keys`;
DROP TABLE IF EXISTS `#__survey_options`;
DROP TABLE IF EXISTS `#__survey_pages`;
DROP TABLE IF EXISTS `#__survey_question_types`;
DROP TABLE IF EXISTS `#__survey_questions`;
DROP TABLE IF EXISTS `#__survey_response_details`;
DROP TABLE IF EXISTS `#__survey_response_types`;
DROP TABLE IF EXISTS `#__survey_responses`;
DROP TABLE IF EXISTS `#__survey_rules`;
DROP TABLE IF EXISTS `#__survey_question_giftcode`;

DROP TABLE IF EXISTS `#__quiz_quizzes`;
DROP TABLE IF EXISTS `#__quiz_questions`;
DROP TABLE IF EXISTS `#__quiz_answers`;
DROP TABLE IF EXISTS `#__quiz_categories`;
DROP TABLE IF EXISTS `#__quiz_pages`;
DROP TABLE IF EXISTS `#__quiz_countries`;
DROP TABLE IF EXISTS `#__quiz_responses`;
DROP TABLE IF EXISTS `#__quiz_response_details`;
DROP TABLE IF EXISTS `#__corejoomla_rating`;
DROP TABLE IF EXISTS `#__corejoomla_rating_details`;
DROP TABLE IF EXISTS `#__corejoomla_assets`;
DROP TABLE IF EXISTS `#__quiz_tags`;
DROP TABLE IF EXISTS `#__quiz_tagmap`;
DROP TABLE IF EXISTS `#__quiz_tags_stats`;
DROP TABLE IF EXISTS `#__quiz_users`;
DROP TABLE IF EXISTS `#__quiz_subscribes`;
DROP TABLE IF EXISTS `#__quiz_question_giftcode`;

DROP TABLE IF EXISTS `#__fund_prize_plan`;
DROP TABLE IF EXISTS `#__award_fund_plan`;
DROP TABLE IF EXISTS `#__symbol_queue_group`;


DROP TABLE IF EXISTS `#__shopping_credit_category`;
DROP TABLE IF EXISTS `#__shopping_credit_plan`;
DROP TABLE IF EXISTS `#__contribution_range`;
DROP TABLE IF EXISTS `#__progress_check`;
DROP TABLE IF EXISTS `#__shopping_credit_from_donation`;
DROP TABLE IF EXISTS `#__shopping_credit_from_purchase_award_symbol`;
DROP TABLE IF EXISTS `#__shopping_credit_giftcode`;
DROP TABLE IF EXISTS `#__shopping_credit_plan_detail`;

DROP TABLE IF EXISTS `#__usergroup_presentation`;
DROP TABLE IF EXISTS `#__selected_presentation`;
DROP TABLE IF EXISTS `#__presentation_usergroup`;
DROP TABLE IF EXISTS `#__presentation_category`;
DROP TABLE IF EXISTS `#__ap_groups`;
DROP TABLE IF EXISTS `#__survey_categories`;
DROP TABLE IF EXISTS `#__start_fund_prize_plan`;

CREATE TABLE IF NOT EXISTS `#__survey_categories` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`title` varchar(255) NOT NULL,
`alias` varchar(255) NOT NULL,
`parent_id` int(10) unsigned NOT NULL,
`nleft` int(10) unsigned NOT NULL,
`nright` int(10) unsigned NOT NULL,
`nlevel` int(10) unsigned NOT NULL DEFAULT '0',
`norder` int(10) unsigned NOT NULL DEFAULT '0',
`surveys` int(10) unsigned NOT NULL DEFAULT '0',
`package_id` int(10),
PRIMARY KEY (`id`),
KEY `jos_survey_categories_nleft` (`nleft`),
KEY `jos_survey_categories_parent_id` (`parent_id`),
KEY `jos_survey_categories_nright` (`nright`),
KEY `jos_survey_categories_nlevel` (`nlevel`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__start_fund_prize_plan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `selected_presentation` VARCHAR(200) NOT NULL,
  `value_from` int(11) NOT NULL,
  `value_to` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  `date_created` DATETIME NOT NULL,  
  `package_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11;


CREATE TABLE IF NOT EXISTS `#__ap_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `package_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_on` timestamp NULL,
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `updated_by` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;


CREATE TABLE IF NOT EXISTS `#__symbol_presentation` (
 `presentation_id` int(11) NOT NULL AUTO_INCREMENT,
  `presentation_create` datetime NOT NULL,
  `presentation_modify` datetime DEFAULT NULL,
  `presentation_publish` tinyint(4) NOT NULL DEFAULT '0',
  `package_id` bigint(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `is_update` int(11) NOT NULL DEFAULT '0',
  `selected` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`presentation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `#__ap_award_archive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `awardpackage` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_archived` datetime NOT NULL,
  `number_of_user` int(11) NOT NULL,
  `number_of_prize` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

DROP TABLE IF EXISTS `#__ap_award_schedule`;

CREATE TABLE IF NOT EXISTS `#__ap_award_schedule` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` tinyint(2) NOT NULL,
  `package_id` bigint(20) unsigned NOT NULL,
  `sunday` tinyint(1) DEFAULT NULL,
  `monday` tinyint(1) DEFAULT NULL,
  `tuesday` tinyint(1) DEFAULT NULL,
  `wednesday` tinyint(1) DEFAULT NULL,
  `thursday` tinyint(1) DEFAULT NULL,
  `friday` tinyint(1) DEFAULT NULL,
  `saturday` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__ap_awardpackages`;

CREATE TABLE IF NOT EXISTS `#__ap_awardpackages` (
  `package_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_name` varchar(30) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `start_date` DATETIME NOT NULL,
  `end_date` DATETIME NOT NULL,
  `published` tinyint(1) NOT NULL,
  `is_archive` tinyint(1) DEFAULT NULL,
  UNIQUE KEY `ap_id` (`package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

DROP TABLE IF EXISTS `#__ap_categories`;

CREATE TABLE IF NOT EXISTS `#__ap_categories` (
  `setting_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint(20) unsigned NOT NULL,
  `category_id` tinyint(2) NOT NULL,
  `colour_code` varchar(10) NOT NULL,
  `category_name` varchar(10) NOT NULL,
  `donation_amount` float NOT NULL,
  `poll_price` float NOT NULL,
  `survey_price` float NOT NULL,
  `quiz_price` float NOT NULL,
  `user_survey_price` float,
  `user_quiz_price` float,
  `giftcode_quantity` float NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `unlocked` int(1) DEFAULT '0',
  UNIQUE KEY `setting_id` (`setting_id`),
  KEY `#__ap_categories_ibfk_1` (`package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

DROP TABLE IF EXISTS `#__ap_donation_details`;

CREATE TABLE IF NOT EXISTS `#__ap_donation_details` (
  `transaction_id` bigint(20) unsigned NOT NULL,
  `category_id` tinyint(3) NOT NULL,
  `donation_amount` float NOT NULL,
  `quantity` int(11) NOT NULL,
  KEY `#__donation_details_ibfk_1` (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__ap_donation_transactions`;

CREATE TABLE IF NOT EXISTS `#__ap_donation_transactions` (
  `package_id` bigint(20),
  `transaction_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11),
  `payment_gateway` varchar(30),
  `dated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `transaction` varchar(300),
  `debit` float,
  `credit` float,
  `status` varchar(30),
  `log` text,
  UNIQUE KEY `transaction_id` (`transaction_id`),
  KEY `#__donation_transactions_ibfk_1` (`package_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__ap_donation_variables`;

CREATE TABLE IF NOT EXISTS `#__ap_donation_variables` (
  `name` varchar(30) NOT NULL,
  `value` varchar(100) NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__ap_non_user_package`;

CREATE TABLE IF NOT EXISTS `#__ap_non_user_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `package_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1 is sent, 0 not send',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84 ;

DROP TABLE IF EXISTS `#__ap_user_packages`;

CREATE TABLE IF NOT EXISTS `#__ap_user_packages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `population` tinyint(3) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `from_age` tinyint(3) NOT NULL,
  `to_age` tinyint(3) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `street` varchar(150) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(50) NOT NULL,
  `post_code` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `field` varchar(30) NOT NULL,
  `group_name` varchar(250),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__ap_useraccounts`;

CREATE TABLE IF NOT EXISTS `#__ap_useraccounts` (
  `ap_account_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `firstname` varchar(30),
  `lastname` varchar(30),
  `birthday` date,
  `gender` varchar(10),
  `street` varchar(300),
  `city` varchar(30),
  `state` varchar(30),
  `post_code` varchar(10),
  `country` varchar(40),
  `phone` varchar(30),
  `paypal_account` varchar(100),
  `package_id` int(11),
  `email` varchar(100),
  `is_active` int(1), 
  `is_presentation` tinyint(1),
  `category_id` INT(11),
  PRIMARY KEY (`ap_account_id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

DROP TABLE IF EXISTS `#__ap_usergroup`;

CREATE TABLE IF NOT EXISTS `#__ap_usergroup` (
  `criteria_id` int(20) NOT NULL AUTO_INCREMENT,
  `package_id` int(20) NOT NULL DEFAULT '0',
  `population` tinyint(3) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `birthday` date,
  `email` varchar(100) NOT NULL,
  `from_age` tinyint(3) NOT NULL,
  `to_age` tinyint(3) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `street` varchar(150) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(50) NOT NULL,
  `post_code` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `field` varchar(30) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '1', 
  `status` int(11) NOT NULL DEFAULT '1',
  `group_name` varchar(250),
  `is_presentation` tinyint(1),
  `parent_usergroup` bigint(20),
  `usergroup_id` int(11) NOT NULL,
  `useraccount_id` int(11) NOT NULL,
  `filter` varchar(255) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`criteria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__fund_receiver`;

CREATE TABLE IF NOT EXISTS `#__fund_receiver` (
  `criteria_id` int(20) NOT NULL AUTO_INCREMENT,
  `package_id` int(20) NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL,
  `receiver` int(20) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `birthday` date,
  `email` varchar(100) NOT NULL,
  `from_day` int(20) NOT NULL,
  `to_day` int(20) NOT NULL,
  `from_month` int(20) NOT NULL,
  `to_month` int(20) NOT NULL,
  `from_year` int(20) NOT NULL,
  `to_year` int(20) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `state` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `street` varchar(30) NOT NULL,
  `postcode` varchar(30) NOT NULL,
  `country` varchar(30) NOT NULL,  
  `field` varchar(30) NOT NULL,
  `randoma` int(20) NOT NULL, 
  `randomb` int(20) NOT NULL, 
  `randomc` int(20) NOT NULL,     
  `randomd` int(20) NOT NULL, 
  `randome` int(20) NOT NULL, 
  `randomf` int(20) NOT NULL,    
  `status` int(11) NOT NULL DEFAULT '1',
  `group_name` varchar(250),
  `is_presentation` tinyint(1),
  `parent_usergroup` bigint(20),
  `usergroup_id` int(11) NOT NULL,
  `useraccount_id` int(11) NOT NULL,
  `filter` varchar(255) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`criteria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__fund_receiver_list`;

CREATE TABLE IF NOT EXISTS `#__fund_receiver_list` (
  `criteria_id` int(20) NOT NULL AUTO_INCREMENT,
  `package_id` int(20) NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL,
  `randoma` int(20) NOT NULL, 
  `randomb` int(20) NOT NULL, 
  `randomc` int(20) NOT NULL,     
  `randomd` int(20) NOT NULL, 
  `randome` int(20) NOT NULL, 
  `randomf` int(20) NOT NULL,     
  `receiver` int(20) NOT NULL,   
  `status` int(11) NOT NULL DEFAULT '1',
  `group_name` varchar(250),
  `is_presentation` tinyint(1),
  `parent_usergroup` bigint(20),
  `usergroup_id` int(11) NOT NULL,
  `useraccount_id` int(11) NOT NULL,
  `filter` varchar(255) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`criteria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__funding`;

CREATE TABLE IF NOT EXISTS `#__funding` (
  `funding_id` int(25) NOT NULL AUTO_INCREMENT,
  `funding_session` text NOT NULL,
  `funding_desc` text NOT NULL,
  `funding_published` int(25) NOT NULL,
  `funding_created` DATETIME NOT NULL,
  `funding_modify` DATETIME NOT NULL,
  `funding_1st` int(25) NOT NULL DEFAULT '0',
  `package_id` int(25) NOT NULL,
  `presentation_id` int(25) NOT NULL,
  `selected_presentation` int(11),
  PRIMARY KEY (`funding_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__funding_user`;

CREATE TABLE IF NOT EXISTS `#__funding_user` (
  `funding_id` int(25) NOT NULL AUTO_INCREMENT,  
  `funding_last_update` datetime,  
  `package_id` int(25),
  `user_id` int(25),  
  PRIMARY KEY (`funding_id`)	
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__withdraw_user`;

CREATE TABLE IF NOT EXISTS `#__withdraw_user` (
  `withdraw_id` int(25) NOT NULL AUTO_INCREMENT,    
  `withdraw_last_update` DATETIME,
  `package_id` int(25),
  `user_id` int(25),
  PRIMARY KEY (`withdraw_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__funding_history`;

CREATE TABLE IF NOT EXISTS `#__funding_history` (
  `funding_history_id` int(25) NOT NULL AUTO_INCREMENT,  
  `funding_id` int(25),
  `description` varchar(250),
  `credit` float,
  `debit` float, 
  `total_pending` float,
  `total_plus` float, 
  `grand_total` float, 
  `created_date` datetime,   
  `method` varchar(100),
  `transaction_type` varchar(100),
  `status` varchar(50),
  PRIMARY KEY (`funding_history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__donation_history` (
  `donation_history_id` int(25) NOT NULL AUTO_INCREMENT,  
  `funding_id` int(25),
  `description` varchar(250),
  `credit` float,
  `debit` float, 
  `created_date` DATETIME,   
  `method` varchar(100),
  `transaction_type` varchar(100),
  `status` varchar(50),
  PRIMARY KEY (`donation_history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__funding_presentations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prize_funding_session_id` int(11) NOT NULL DEFAULT '0',
  `prize_id` int(11) NOT NULL DEFAULT '0',
  `value` decimal(9,2) DEFAULT NULL,
  `funding` decimal(9,2) DEFAULT NULL,
  `shortfall` decimal(9,2) DEFAULT NULL,
  `pct_funded` decimal(6,2) DEFAULT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `unlocked_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `published` tinyint(3) DEFAULT NULL,
  `donation_id` int(11) DEFAULT NULL,
  `revenue_id` int(11) DEFAULT NULL,
  `queue` varchar(100),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

CREATE TABLE IF NOT EXISTS `#__funding_revenue` (
  `revenue_id` int(25) NOT NULL AUTO_INCREMENT,
  `funding_id` int(25) NOT NULL,
  `revenue_percentage` int(25) NOT NULL DEFAULT '0',
  `revenue_fromprize` int(25) NOT NULL DEFAULT '0',
  `revenue_toprize` int(25) NOT NULL DEFAULT '0',
  `revenue_strategy` int(25) NOT NULL DEFAULT '0',
  `locked` int(25) NOT NULL DEFAULT '1',
  PRIMARY KEY (`revenue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__gc_recieve_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gcid` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  `status` char(5) NOT NULL,
  `giftcode_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'if 1 is for buy giftcod,0 for free',
  PRIMARY KEY (`id`),
  KEY `gcid` (`gcid`),
  KEY `lt5g1_gc_recieve_user_ibfk_1` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=74 ;

CREATE TABLE IF NOT EXISTS `#__giftcode_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `image` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `published` tinyint(1) DEFAULT NULL,
  `symbol_pieces_award` varchar(45) DEFAULT NULL,
  `created_date_time` DATETIME DEFAULT NULL,
  `color_code` varchar(11) DEFAULT NULL,
  `locked` tinyint(1) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

CREATE TABLE IF NOT EXISTS `#__giftcode_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color_id` tinyint(1) DEFAULT NULL,
  `total_giftcodes` int(11) DEFAULT NULL,
  `created_date_time` DATETIME DEFAULT NULL,
  `modified_date_time` DATETIME DEFAULT NULL,
  `published` tinyint(1) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `renew_status` tinyint(1) DEFAULT NULL,
  `package_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `#__giftcode_collection_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_length` int(11) DEFAULT NULL,
  `allow_to_repeat` tinyint(1) DEFAULT NULL,
  `alphabet_composition` varchar(10) DEFAULT NULL,
  `number_composition` varchar(10) DEFAULT NULL,
  `max_number_of_code` int(11) DEFAULT NULL,
  `min_number_of_code` int(11) DEFAULT NULL,
  `comment` varchar(45) DEFAULT NULL,
  `giftcode_collection_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `#__giftcode_giftcode` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `giftcode_category_id` int(11) DEFAULT NULL,
  `giftcode_setting_id` int(11) DEFAULT NULL,
  `giftcode` varchar(45) DEFAULT NULL,
  `created_date_time` DATETIME DEFAULT NULL,
  `published` tinyint(1) DEFAULT NULL,
  `giftcode_queue_id` int(11) DEFAULT NULL,
  `redeemed` varchar(45) DEFAULT NULL,
  `giftcode_collection_id` int(11) DEFAULT NULL,
  `renew_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `giftcode_category_id` (`giftcode_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=901 ;

CREATE TABLE IF NOT EXISTS `#__giftcode_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_date_time` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__giftcode_renew_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `giftcode_color_id` varchar(45) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `sunday` tinyint(1) DEFAULT NULL,
  `monday` tinyint(1) DEFAULT NULL,
  `tuesday` tinyint(1) DEFAULT NULL,
  `wednesday` tinyint(1) DEFAULT NULL,
  `thursday` tinyint(1) DEFAULT NULL,
  `friday` tinyint(1) DEFAULT NULL,
  `saturday` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__giftcode_schedule_created` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_date` varchar(20) DEFAULT NULL,
  `color_id` varchar(2) DEFAULT NULL,
  `giftcode_collection_id` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__symbol_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_created` DATETIME NOT NULL,
  `prize_name` varchar(50) NOT NULL,
  `prize_value` varchar(30) NOT NULL,
  `prize_image` varchar(255) NOT NULL,
  `created_by` varchar(20) NOT NULL,
  `desc` text NOT NULL,
  `package_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `unlocked_status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `#__symbol_queue` (
  `queue_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `piece` int(11) DEFAULT NULL,
  `shufle` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `groupId` int(11) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `selected_presentation` int(11),
  PRIMARY KEY (`queue_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__symbol_queue_detail` (
  `queuedetail_id` int(11) NOT NULL AUTO_INCREMENT,
  `queue_id` int(11) NOT NULL,
  `symbol_pieces_id` int(11) NOT NULL,
  `status` enum('0','1') DEFAULT '0',
  `symbol_prize_id` int(11) DEFAULT NULL,
  `presentation_id` int(11) DEFAULT NULL,
  `selected_presentation` int(11),
  `userid` int(11),
  `gcid` int(11),
  `category_id` int(11),
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,  
  `date_end` timestamp NULL ,  
  PRIMARY KEY (`queuedetail_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

CREATE TABLE IF NOT EXISTS `#__symbol_symbol` (
  `symbol_id` int(11) NOT NULL AUTO_INCREMENT,
  `date_created` DATETIME DEFAULT NULL,
  `symbol_name` varchar(30) DEFAULT NULL,
  `symbol_image` varchar(50) DEFAULT NULL,
  `pieces` int(11) DEFAULT NULL,
  `rows` int(11) DEFAULT NULL,
  `cols` int(11) DEFAULT NULL,
  `package_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`symbol_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `#__symbol_symbol_pieces` (
   `symbol_pieces_id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol_id` int(11) NOT NULL,
  `symbol_pieces_image` varchar(50) NOT NULL,
  `is_lock` tinyint(4) NOT NULL,
  PRIMARY KEY (`symbol_pieces_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `#__symbol_symbol_prize` (
  `symbol_prize_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL COMMENT 'Prize ID',
  `symbol_id` int(11) NOT NULL,
  `presentation_id` int(11) DEFAULT NULL,
  `symbol_queue` int(11) DEFAULT NULL,
  PRIMARY KEY (`symbol_prize_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__symbol_user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `passkey` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__giftcode_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `#__ap_award_symbol_progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol_id` int(11) NOT NULL,
  `added_by` varchar(200) NOT NULL,
  `created_date` datetime NOT NULL,
  `is_publish` tinyint(4) NOT NULL,
  `package_id` tinyint(4) NOT NULL,
  `symbol_presentation` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `symbol_presentation` (`symbol_presentation`),
  KEY `symbol_id` (`symbol_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

CREATE TABLE IF NOT EXISTS `#__refund_package_list` (
  `refund_package_list_id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `state` tinyint(4) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`refund_package_list_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

CREATE TABLE IF NOT EXISTS `#__refund_package` (
  `refund_package_id` int(11) NOT NULL AUTO_INCREMENT,
  `refund_package_list_id` int(11) NOT NULL,
  PRIMARY KEY (`refund_package_id`),
  KEY `refund_package_list_id` (`refund_package_list_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

CREATE TABLE IF NOT EXISTS `#__refund_distribution_schedule` (
  `schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `refund_quota_id` int(11) DEFAULT NULL,
  `refund_package_id` int(11) NOT NULL,
  `start_date` DATETIME NOT NULL,
  `end_date` DATETIME NOT NULL,
  PRIMARY KEY (`schedule_id`),
  KEY `refund_package_id` (`refund_package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `#__refund_schedule_day` (
  `schedule_day_id` int(11) NOT NULL AUTO_INCREMENT,
  `distribution_schedule_id` int(11) NOT NULL,
  `refund_package_id` int(11) DEFAULT NULL,
  `sunday` tinyint(4) DEFAULT NULL,
  `monday` tinyint(4) DEFAULT NULL,
  `tuesday` tinyint(4) DEFAULT NULL,
  `wednesday` tinyint(4) DEFAULT NULL,
  `thursday` tinyint(4) DEFAULT NULL,
  `friday` tinyint(4) DEFAULT NULL,
  `saturday` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`schedule_day_id`),
  KEY `distribution_schedule_id` (`distribution_schedule_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `#__refund_recepient_group_module` (
  `recepient_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `refund_package_id` int(11) NOT NULL,
  `first_name` varchar(200) DEFAULT NULL,
  `last_name` varchar(200) DEFAULT NULL,
  `age` varchar(10) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `state_province` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`recepient_group_id`),
  KEY `#__refund_recepient_group_module_ibfk_1` (`refund_package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `#__refund_usergroup` (
  `criteria_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint(20) unsigned NOT NULL,
  `population` tinyint(3) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `from_age` tinyint(3) NOT NULL,
  `to_age` tinyint(3) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `street` varchar(150) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(50) NOT NULL,
  `post_code` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `field` varchar(30) NOT NULL,
  `package_list_id` int(11) NOT NULL,
  UNIQUE KEY `criteria_id` (`criteria_id`),
  KEY `lrws3_ap_usergroup_ibfk_1` (`package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

CREATE TABLE IF NOT EXISTS `#__refund_calculator` (
  `refund_calculator_id` int(11) NOT NULL AUTO_INCREMENT,
  `refund_package_id` int(11) NOT NULL,
  `total_refund_receipents` varchar(45) DEFAULT NULL,
  `total_refund_quota_used` varchar(45) DEFAULT NULL,
  `refund_quota_retain_duration` varchar(45) DEFAULT NULL,
  `bank_interest_rate` varchar(45) DEFAULT NULL,
  `bank_interest_income` varchar(45) DEFAULT NULL,
  `refund_package_list_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`refund_calculator_id`),
  KEY `refund_package_id` (`refund_package_id`),
  KEY `refund_package_list_id` (`refund_package_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__refund_quota` (
  `refund_quota_id` int(11) NOT NULL AUTO_INCREMENT,
  `refund_quota` varchar(45) DEFAULT NULL,
  `refund_package_id` int(11) DEFAULT NULL,
  `qualifying_events` varchar(200) DEFAULT NULL,
  `unlock_activity` varchar(200) DEFAULT NULL,
  `special_activity` varchar(200) DEFAULT NULL,
  `to_expire` float DEFAULT NULL,
  `ready_to_use` float DEFAULT NULL,
  `package_list_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`refund_quota_id`),
  KEY `refund_package_id` (`refund_package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

CREATE TABLE IF NOT EXISTS `#__refund_qualifying_event` (
  `qualifying_event_id` int(11) NOT NULL AUTO_INCREMENT,
  `refund_quota_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`qualifying_event_id`),
  KEY `refund_quota_id` (`refund_quota_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__refund_refundable_activity` (
  `refundable_activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `refund_quota_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`refundable_activity_id`),
  KEY `refund_quota_id` (`refund_quota_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__refund_record` (
  `refund_record_id` int(11) NOT NULL AUTO_INCREMENT,
  `refund_package_list_id` int(11) NOT NULL,
  `date_recived` DATETIME DEFAULT NULL,
  `description` text,
  `amount` float DEFAULT NULL,
  `unlocked` float DEFAULT NULL,
  `unlocked_date` DATETIME DEFAULT NULL,
  `unlocked_status` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `distribution_id` bigint(11) DEFAULT NULL,
  `claimed_status` tinyint(4) DEFAULT NULL,
  `is_blocked` tinyint(4) DEFAULT NULL,
  `refund_status` int(11) NOT NULL,
  PRIMARY KEY (`refund_record_id`),
  KEY `refund_package_list_id` (`refund_package_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__refund_record_details` (
  `record_details_id` int(11) NOT NULL AUTO_INCREMENT,
  `date_time` datetime DEFAULT NULL,
  `description` text,
  `amount` varchar(45) DEFAULT NULL,
  `refund_record_id` int(11) NOT NULL,
  PRIMARY KEY (`record_details_id`),
  KEY `refund_record_id` (`refund_record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__refund_distribution_list` (
  `distribution_list_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `schedule_id` int(11) NOT NULL,
  `start_date` DATETIME NOT NULL,
  `end_date` DATETIME NOT NULL,
  `refund_amount` float NOT NULL,
  `distribute_date` DATETIME NOT NULL,
  `expire_date` DATETIME NOT NULL,
  `ready_for_use` DATETIME NOT NULL,
  `duration` float NOT NULL,
  `refund_package_id` int(11) NOT NULL,
  `is_weekdays` tinyint(4) NOT NULL,
  `is_calendar` tinyint(4) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`distribution_list_id`),
  KEY `shopping_credit_id` (`refund_package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=864 ;

CREATE TABLE IF NOT EXISTS `#__refund_claim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_claimed` DATETIME DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `record_id` (`record_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

CREATE TABLE IF NOT EXISTS `#__refund_schedule_calendar` (
  `schedule_calendar_id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_distribution_id` int(11) NOT NULL,
  `refund_package_id` int(11) NOT NULL,
  `date` DATETIME NOT NULL,
  PRIMARY KEY (`schedule_calendar_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

CREATE TABLE IF NOT EXISTS `#__symbol_pricing` (
  `symbol_pricing_id` int(11) NOT NULL AUTO_INCREMENT,
  `is_all_user` tinyint(4) NOT NULL,
  `presentation_id` int(11) DEFAULT NULL,
  `is_publish` tinyint(4) DEFAULT NULL,
  `selected_presentation` int(11),
  PRIMARY KEY (`symbol_pricing_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

CREATE TABLE IF NOT EXISTS `#__symbol_pricing_details` (
  `details_id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol_pricing_id` int(11) NOT NULL,
  `prize_id` tinyint(4) DEFAULT NULL,
  `symbol_id` tinyint(4) DEFAULT NULL,
  `price_from` float DEFAULT NULL,
  `price_to` float DEFAULT NULL,
  `virtual_price` float DEFAULT NULL,
  PRIMARY KEY (`details_id`),
  KEY `symbol_pricing_id` (`symbol_pricing_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

CREATE TABLE IF NOT EXISTS `#__symbol_pricing_breakdown` (
  `breakdownid` int(11) NOT NULL AUTO_INCREMENT,
  `detailsid` int(11) NOT NULL,
  `price_from` float DEFAULT NULL,
  `price_to` float DEFAULT NULL,
  `virtual_price_breakdown` float DEFAULT NULL,
  `symbol_pieces_id` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`breakdownid`),
  KEY `detailsid` (`detailsid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

CREATE TABLE IF NOT EXISTS `#__symbol_pricing_usergroup` (
  `criteria_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint(20) unsigned NOT NULL,
  `population` tinyint(3) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `from_age` tinyint(3) NOT NULL,
  `to_age` tinyint(3) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `street` varchar(150) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(50) NOT NULL,
  `post_code` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `field` varchar(30) NOT NULL,
  `symbol_pricing_id` int(11) NOT NULL,
  UNIQUE KEY `criteria_id` (`criteria_id`),
  KEY `lrws3_ap_usergroup_ibfk_1` (`package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

CREATE TABLE IF NOT EXISTS `#__symbol_order` (
  `order_number_id` int(11) NOT NULL,
  `order_date` DATETIME NOT NULL,
  `prize_id` tinyint(4) NOT NULL,
  `symbol_pieces` tinyint(4) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `order_total` float DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__symbol_group_module` (
  `symbol_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol_pricing_id` int(11) NOT NULL,
  `first_name` varchar(200) DEFAULT NULL,
  `last_name` varchar(200) DEFAULT NULL,
  `age` varchar(10) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `state_province` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`symbol_group_id`),
  KEY `#__symbol_group_module_ibfk_1` (`symbol_pricing_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

CREATE TABLE IF NOT EXISTS `#__jvotesystem_deposit` (
   `id_deposit` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `dated` datetime NOT NULL,
  `payment_gateway` varchar(200) DEFAULT NULL,
  `deposit_amount` float DEFAULT NULL,
  `deposit_number` int(11) DEFAULT NULL,
  `top_amount` double NOT NULL,
  `top_every` float NOT NULL,
  `currency_code` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_deposit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__jvotesystem_transaction` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

CREATE TABLE IF NOT EXISTS `#__jvotesystem_transaction_detail` (
  `transaction_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text,
  `amount` float DEFAULT NULL,
  `deposit_id` int(11) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`transaction_detail_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=132 ;

CREATE TABLE IF NOT EXISTS `#__shopping_credit_package_list` (
  `shopping_id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `published` int(11) NOT NULL,
  PRIMARY KEY (`shopping_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `#__shopping_credit_package` (
  `shopping_credit_id` int(11) NOT NULL AUTO_INCREMENT,
  `shopping_package_list_id` int(11) NOT NULL,
  PRIMARY KEY (`shopping_credit_id`),
  KEY `shopping_package_list_id` (`shopping_package_list_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

CREATE TABLE IF NOT EXISTS `#__shopping_credit_config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `shopping_package_list_id` int(11) DEFAULT NULL,
  `shopping_credit_id` int(11) DEFAULT NULL,
  `shopping_credit_amount` float NOT NULL,
  `qualifying_events` varchar(200) DEFAULT NULL,
  `unlock_activity` varchar(200) DEFAULT NULL,
  `special_activity` varchar(200) DEFAULT NULL,
  `to_expire` int(11) DEFAULT NULL,
  `ready_to_use` int(11) DEFAULT NULL,
  PRIMARY KEY (`config_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `#__shopping_distribution_schedule` (
  `schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `shopping_credit_id` int(11) NOT NULL,
  `shopping_credit_package_id` int(11) NOT NULL,
  `start_date` DATETIME NOT NULL,
  `end_date` DATETIME NOT NULL,
  PRIMARY KEY (`schedule_id`),
  KEY `shopping_credit_id` (`shopping_credit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

CREATE TABLE IF NOT EXISTS `#__shopping_schedule_day` (
  `schedule_day_id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_distribution_id` int(11) NOT NULL,
  `shopping_package_id` int(11) NOT NULL,
  `sunday` tinyint(4) DEFAULT NULL,
  `monday` tinyint(4) DEFAULT NULL,
  `tuesday` tinyint(4) DEFAULT NULL,
  `wednesday` tinyint(4) DEFAULT NULL,
  `thursday` tinyint(4) DEFAULT NULL,
  `friday` tinyint(4) DEFAULT NULL,
  `saturday` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`schedule_day_id`),
  KEY `schedule_distribution_id` (`schedule_distribution_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

CREATE TABLE IF NOT EXISTS `#__shopping_schedule_calendar` (
  `schedule_calendar_id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_distribution_id` int(11) NOT NULL,
  `shopping_package_id` int(11) NOT NULL,
  `date` DATETIME NOT NULL,
  PRIMARY KEY (`schedule_calendar_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

CREATE TABLE IF NOT EXISTS `#__shopping_usergroup` (
  `criteria_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint(20) unsigned NOT NULL,
  `population` tinyint(3) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `from_age` tinyint(3) NOT NULL,
  `to_age` tinyint(3) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `street` varchar(150) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(50) NOT NULL,
  `post_code` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `field` varchar(30) NOT NULL,
  `package_list_id` int(11) NOT NULL,
  UNIQUE KEY `criteria_id` (`criteria_id`),
  KEY `lrws3_ap_usergroup_ibfk_1` (`package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `#__shopping_recepient_group_module` (
  `recepient_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `shopping_package_id` int(11) NOT NULL,
  `first_name` varchar(200) DEFAULT NULL,
  `last_name` varchar(200) DEFAULT NULL,
  `age` varchar(10) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `state_province` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`recepient_group_id`),
  KEY `#__refund_recepient_group_module_ibfk_1` (`shopping_package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `#__shopping_credit_distribution_list` (
  `distribution_list_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `schedule_id` int(11) NOT NULL,
  `start_date` DATETIME NOT NULL,
  `end_date` DATETIME NOT NULL,
  `shopping_credit_amount` float NOT NULL,
  `distribute_date` DATETIME NOT NULL,
  `expire_date` DATETIME NOT NULL,
  `ready_for_use` DATETIME NOT NULL,
  `duration` float NOT NULL,
  `shopping_credit_id` int(11) NOT NULL,
  `is_weekdays` tinyint(4) NOT NULL,
  `is_calendar` tinyint(4) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`distribution_list_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

CREATE TABLE IF NOT EXISTS `#__shopping_record` (
`shopping_record_id` int(11) NOT NULL AUTO_INCREMENT,
  `shopping_credit_package_list_id` int(11) DEFAULT NULL,
  `date_recived` DATETIME DEFAULT NULL,
  `description` text,
  `amount` float DEFAULT NULL,
  `unlocked` float NOT NULL,
  `unlocked_date` DATETIME NOT NULL,
  `unlocked_status` tinyint(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `distribution_id` int(11) NOT NULL,
  `claimed_status` tinyint(4) NOT NULL,
  `is_blocked` tinyint(4) NOT NULL,
  PRIMARY KEY (`shopping_record_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__shopping_claim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_claimed` DATETIME DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `record_id` (`record_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

CREATE TABLE IF NOT EXISTS `#__shopping_calculator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopping_credit_id` int(11) NOT NULL,
  `total_recipients` float NOT NULL,
  `total_distribute` float NOT NULL,
  `received_date` DATETIME NOT NULL,
  `expire_date` DATETIME NOT NULL,
  `ready_for_use` DATETIME NOT NULL,
  `retained_duration` float NOT NULL,
  `total_credit_unlocked` float NOT NULL,
  `total_claimed` float NOT NULL,
  `total_spent` float NOT NULL,
  `bank_interest` float NOT NULL,
  `bank_interest_income` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `shopping_credit_id` (`shopping_credit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `#__ap_winners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `start_unlocked_prize` int(11) NOT NULL,
  `end_unlocked_prize` int(11) NOT NULL,
  `total_no_of_unlocked_prizes` int(11) NOT NULL,
  `total_to_award` int(11) NOT NULL,
  `total_winners` int(11) NOT NULL,
  `presentation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ap_potential_winner` (
  `criteria_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint(20) unsigned NOT NULL,
  `presentation_id` int(11) NOT NULL,
  `population` tinyint(3) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `from_age` tinyint(3) NOT NULL,
  `to_age` tinyint(3) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `street` varchar(150) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(50) NOT NULL,
  `post_code` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `field` varchar(30) NOT NULL,
  UNIQUE KEY `criteria_id` (`criteria_id`),
  KEY `#__ap_usergroup_ibfk_1` (`package_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ap_potential_winner_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `presentation_id` int(11) NOT NULL,
  `first_name` varchar(200) DEFAULT NULL,
  `last_name` varchar(200) DEFAULT NULL,
  `age` varchar(10) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `state_province` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `#__ap_potential_winner_module_ibfk_1` (`presentation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ap_winners_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ap_winner_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prize_id` int(11) NOT NULL,
  `awarded_date` datetime NOT NULL,
  `prize` float NOT NULL,
  `prize_value` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `prize_id` (`prize_id`),
  KEY `ap_winner_id` (`ap_winner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `#__ap_winner_symbol` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_winner_id` int(11) NOT NULL,
  `pieces_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_winner_id` (`user_winner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `#__ap_winner_returned` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_winner_id` int(11) NOT NULL,
  `pieces_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_winner_id` (`user_winner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

CREATE TABLE IF NOT EXISTS `#__ap_winners_actual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ap_winner_id` int(11) NOT NULL,
  `select_winner_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prize_id` int(11) NOT NULL,
  `awarded_date` datetime NOT NULL,
  `prize` float NOT NULL,
  `prize_value` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ap_winner_id` (`ap_winner_id`),
  KEY `prize_id` (`prize_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

CREATE TABLE IF NOT EXISTS `#__ap_winner_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `presentation_id` int(11) NOT NULL,
  `is_same_person` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `#__ap_winner_setting_ibfk_1` (`presentation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ap_prize_claim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `winner_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `prize_value` float NOT NULL,
  `prize_name` varchar(100) NOT NULL,
  `claimed_date` DATETIME NOT NULL,
  `claimed_status` tinyint(4) NOT NULL,
  `send_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

CREATE TABLE IF NOT EXISTS `#__ap_currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(10) NOT NULL,
  `currency` varchar(100) NOT NULL,
  `package_id` int(11),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `#__ap_payment_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option` varchar(100) NOT NULL,
  `date_created` DATETIME NOT NULL,
  `package_id` int(11),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;


CREATE TABLE IF NOT EXISTS `#__paypal_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business` varchar(100),
  `currency_code` varchar(10),
  `lc` varchar(10),  
  `is_active` INT(2),
  `package_id` int(11),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `#__ap_symbol_process` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prize_value_from` float NOT NULL,
  `prize_value_to` float NOT NULL,
  `extra_from` float NOT NULL,
  `extra_to` float NOT NULL,
  `clone_from` float NOT NULL,
  `clone_to` float NOT NULL,
  `shuffle_from` float NOT NULL,
  `shuffle_to` float NOT NULL,
  `presentation_id` int(11) NOT NULL,
  `selected_presentation` int(11),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `#__ap_symbol_process_clone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pieces_id` int(11) NOT NULL,
  `clone_id` int(11) NOT NULL,
  `is_lock` tinyint(4) NOT NULL,
  `is_lock_priced_rpc` tinyint(4),
  `is_lock_free_rpc` tinyint(4),
  `price` int(11),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `#__ap_symbol_process_extract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `extract_id` int(11) NOT NULL,
  `pieces_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

CREATE TABLE IF NOT EXISTS `#__ap_symbol_process_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `process_id` int(11) NOT NULL,
  `prize_id` int(11) NOT NULL,
  `symbol_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `#__ap_symbol_process_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `process_id` int(11) NOT NULL,
  `prize_id` int(11) NOT NULL,
  `symbol_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `#__ap_symbol_process_prize_extracted` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `process_id` int(11) NOT NULL,
  `prize_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `#__ap_symbol_process_process_clone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `process_id` int(11) DEFAULT NULL,
  `prize_id` int(11) DEFAULT NULL,
  `symbol_id` int(11) NOT NULL,
  `percent_pricing` int(11),
  `percent_of_priced_rpc` int(11),
  `percent_of_free_rpc` int(11),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=126 ;

CREATE TABLE IF NOT EXISTS `#__ap_paypal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paypal_account` varchar(200) NOT NULL,
  `sandbox_account` varchar(200) NOT NULL,
  `return_url` varchar(200) NOT NULL,
  `notify_url` varchar(200) NOT NULL,
  `is_test` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

ALTER TABLE `#__ap_potential_winner_module`
  ADD CONSTRAINT `#__ap_potential_winner_module_ibfk_1` FOREIGN KEY (`presentation_id`) REFERENCES `#__symbol_presentation` (`presentation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__symbol_symbol_prize`
  ADD CONSTRAINT `#__symbol_symbol_prize_ibfk_1` FOREIGN KEY (`presentation_id`) REFERENCES `#__symbol_presentation` (`presentation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__ap_winner_setting`
  ADD CONSTRAINT `#__ap_winner_setting_ibfk_1` FOREIGN KEY (`presentation_id`) REFERENCES `#__symbol_presentation` (`presentation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__ap_winners_actual`
  ADD CONSTRAINT `#__ap_winners_actual_ibfk_1` FOREIGN KEY (`ap_winner_id`) REFERENCES `#__ap_winners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `#__ap_winners_actual_ibfk_2` FOREIGN KEY (`prize_id`) REFERENCES `#__symbol_prize` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__ap_winner_returned`
  ADD CONSTRAINT `#__ap_winner_returned_ibfk_1` FOREIGN KEY (`user_winner_id`) REFERENCES `#__ap_winners_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__ap_winner_symbol`
  ADD CONSTRAINT `#__ap_winner_symbol_ibfk_1` FOREIGN KEY (`user_winner_id`) REFERENCES `#__ap_winners_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__ap_winners_user`
  ADD CONSTRAINT `#__ap_winners_user_ibfk_1` FOREIGN KEY (`prize_id`) REFERENCES `#__symbol_prize` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `#__ap_winners_user_ibfk_2` FOREIGN KEY (`ap_winner_id`) REFERENCES `#__ap_winners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__shopping_calculator`
  ADD CONSTRAINT `#__shopping_calculator_ibfk_1` FOREIGN KEY (`shopping_credit_id`) REFERENCES `#__shopping_credit_package` (`shopping_credit_id`) ON DELETE CASCADE;

ALTER TABLE `#__shopping_claim`
  ADD CONSTRAINT `#__shopping_claim_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `#__shopping_record` (`shopping_record_id`) ON DELETE CASCADE;

ALTER TABLE `#__shopping_distribution_schedule`
  ADD CONSTRAINT `#__shopping_distribution_schedule_ibfk_1` FOREIGN KEY (`shopping_credit_id`) REFERENCES `#__shopping_credit_package` (`shopping_credit_id`) ON DELETE CASCADE;

ALTER TABLE `#__shopping_credit_package`
  ADD CONSTRAINT `#__shopping_credit_package_ibfk_1` FOREIGN KEY (`shopping_package_list_id`) REFERENCES `#__shopping_credit_package_list` (`shopping_id`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `#__ap_payment_options` (`id`, `option`, `date_created`) VALUES
(1, 'paypal', '0000-00-00');

INSERT INTO `#__ap_donation_variables` (`name`, `value`) VALUES
('business', 'seller_1315922650_biz@gmail.com'),
('currency_code', 'USD'),
('currency_unit', '1'),
('payment_gateway', 'paypal'),
('sandbox', 'seller_1315922650_biz@gmail.com'),
('test_mode', '1');

ALTER TABLE `#__symbol_group_module`
  ADD CONSTRAINT `l#__symbol_group_module_ibfk_1` FOREIGN KEY (`symbol_pricing_id`) REFERENCES `#__symbol_pricing` (`symbol_pricing_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__giftcode_giftcode`
  ADD CONSTRAINT `#__giftcode_giftcode_ibfk_1` FOREIGN KEY (`giftcode_category_id`) REFERENCES `#__giftcode_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__ap_categories`
  ADD CONSTRAINT `#__ap_categories_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `#__ap_awardpackages` (`package_id`) ON DELETE CASCADE;

ALTER TABLE `#__refund_record`
  ADD CONSTRAINT `#__refund_record_ibfk_2` FOREIGN KEY (`refund_package_list_id`) REFERENCES `#__refund_package` (`refund_package_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__refund_record_details`
  ADD CONSTRAINT `#__refund_record_details_ibfk_1` FOREIGN KEY (`refund_record_id`) REFERENCES `#__refund_record` (`refund_record_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__refund_package`
  ADD CONSTRAINT `#__refund_package_ibfk_1` FOREIGN KEY (`refund_package_list_id`) REFERENCES `#__refund_package_list` (`refund_package_list_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__refund_distribution_schedule`
  ADD CONSTRAINT `#__refund_distribution_schedule_ibfk_1` FOREIGN KEY (`refund_package_id`) REFERENCES `#__refund_package` (`refund_package_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__refund_recepient_group_module`
  ADD CONSTRAINT `#__refund_recepient_group_module_ibfk_1` FOREIGN KEY (`refund_package_id`) REFERENCES `#__refund_package_list` (`refund_package_list_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__refund_calculator`
  ADD CONSTRAINT `#__refund_calculator_ibfk_1` FOREIGN KEY (`refund_package_id`) REFERENCES `#__refund_package` (`refund_package_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `#__refund_calculator_ibfk_2` FOREIGN KEY (`refund_package_list_id`) REFERENCES `#__refund_package_list` (`refund_package_list_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__refund_quota`
  ADD CONSTRAINT `#__refund_quota_ibfk_1` FOREIGN KEY (`refund_package_id`) REFERENCES `#__refund_package` (`refund_package_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__refund_qualifying_event`
  ADD CONSTRAINT `#__refund_qualifying_event_ibfk_1` FOREIGN KEY (`refund_quota_id`) REFERENCES `#__refund_quota` (`refund_quota_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__refund_refundable_activity`
  ADD CONSTRAINT `#__refund_refundable_activity_ibfk_1` FOREIGN KEY (`refund_quota_id`) REFERENCES `#__refund_quota` (`refund_quota_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__symbol_pricing_details`
  ADD CONSTRAINT `#__symbol_pricing_details_ibfk_1` FOREIGN KEY (`symbol_pricing_id`) REFERENCES `#__symbol_pricing` (`symbol_pricing_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__symbol_pricing_breakdown`
  ADD CONSTRAINT `#__symbol_pricing_breakdown_ibfk_1` FOREIGN KEY (`detailsid`) REFERENCES `#__symbol_pricing_details` (`details_id`) ON DELETE CASCADE;

ALTER TABLE `#__symbol_pricing`
  ADD CONSTRAINT `#__symbol_pricing_ibfk_1` FOREIGN KEY (`presentation_id`) REFERENCES `#__symbol_presentation` (`presentation_id`) ON DELETE CASCADE ON UPDATE CASCADE;
  
CREATE TABLE IF NOT EXISTS `#__survey_question_giftcode` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`package_id` int(11),
	`survey_id` int(11),		
	`page_number` int(11),
	`question_id` varchar(10),
	`uniq_key` varchar(100),
	`complete_giftcode` varchar(10),
	`complete_giftcode_quantity` int(11),
	`complete_giftcode_cost_response` int(11),
	`incomplete_giftcode` varchar(10),
	`incomplete_giftcode_quantity` int(11),
	`incomplete_giftcode_cost_response` int(11),
	PRIMARY KEY (`id`)	
) DEFAULT CHARSET=utf8;
  
CREATE TABLE IF NOT EXISTS `#__survey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `introtext` text,
  `endtext` text,
  `created_by` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `publish_up` datetime NOT NULL,
  `publish_down` datetime NOT NULL,
  `responses` int(11) NOT NULL DEFAULT '0',
  `private_survey` tinyint(1) DEFAULT '0',
  `max_responses` int(10) unsigned NOT NULL DEFAULT '0',
  `anonymous` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `custom_header` text,
  `public_permissions` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `published` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `survey_key` varchar(32) NOT NULL,
  `redirect_url` VARCHAR(999) DEFAULT NULL,
  `display_template` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `skip_intro` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `ip_address` VARCHAR(39) DEFAULT NULL,
  `backward_navigation` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `display_notice` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `display_progress` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `notification` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `restriction` VARCHAR(32) NOT NULL DEFAULT 'cookie',
  `package_id` INT(11),
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__survey_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_type` varchar(10) DEFAULT NULL,
  `answer_label` mediumtext,
  `sort_order` int(3) unsigned NOT NULL DEFAULT '1',
  `image` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_SURVEY_OPTIONS_SURVEY_ID` (`survey_id`),
  KEY `FK_SURVEY_QUESTIONS_QUESTION_ID` (`question_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__survey_contactgroups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `contacts` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__survey_contact_group_map` (
  `contact_id` INTEGER UNSIGNED NOT NULL,
  `group_id` INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY (`contact_id`, `group_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__survey_contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `created_by` INTEGER UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uniq_survey_contacts_email` (`email`, `created_by`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__survey_countries` (
  `country_code` varchar(3) NOT NULL,
  `country_name` varchar(64) NOT NULL,
  PRIMARY KEY (`country_code`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__survey_keys` (
  `key_name` varchar(255) NOT NULL,
  `survey_id` int(10) unsigned NOT NULL,
  `response_id` int(10) unsigned NOT NULL,
  `response_status` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `contact_id` int(10) unsigned NOT NULL DEFAULT 0
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__survey_options` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_type` varchar(3) NOT NULL,
  `responses` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_SURVEY_OPTIONS_SURVEY_ID` (`survey_id`),
  KEY `FK_SURVEY_QUESTIONS_QUESTION_ID` (`question_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__survey_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) unsigned NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT '1',
  `title` VARCHAR(255),
  `uniq_key` VARCHAR(100),
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__survey_question_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(24) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__survey_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` MEDIUMTEXT DEFAULT NULL,
  `survey_id` int(11) NOT NULL,
  `question_type` int(10) unsigned NOT NULL DEFAULT '0',
  `page_number` int(11) NOT NULL DEFAULT '1',
  `responses` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `custom_choice` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `orientation` VARCHAR(2) NOT NULL DEFAULT 'H',
  `min_selections` int(3) UNSIGNED NOT NULL DEFAULT '0',
  `max_selections` int(3) UNSIGNED NOT NULL DEFAULT '0',
  `uniq_key` varchar(100),
  `question_id` varchar(10),
  PRIMARY KEY (`id`),
  KEY `FK_SURVEY_QUESTIONS_SURVEY_ID` (`survey_id`),
  KEY `FK_SURVEY_QUESTIONS_RESPONSE_TYPE` (`question_type`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__survey_response_details` (
  `response_id` int(11) NOT NULL DEFAULT '0',
  `question_id` int(11) NOT NULL DEFAULT '0',
  `answer_id` int(11) NOT NULL DEFAULT '0',
  `column_id` int(11) DEFAULT '0',
  `free_text` text,
  KEY `FK_SURVEY_RESPONSE_DETAILS_RESPONSE_ID` (`response_id`),
  KEY `FK_SURVEY_RESPONSE_DETAILS_QUESTION_ID` (`question_id`),
  KEY `FK_SURVEY_RESPONSE_DETAILS_OPTION_ID` (`answer_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__survey_response_types` (
  `id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__survey_responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `ip_address` varchar(39) DEFAULT NULL,
  `survey_key` varchar(64) DEFAULT NULL,
  `completed` datetime DEFAULT '0000-00-00 00:00:00',
  `country` varchar(3) DEFAULT NULL,
  `city` VARCHAR(128),
  `browser` varchar(32) DEFAULT NULL,
  `os` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_SURVEY_RESPONSES_SURVEY_ID` (`survey_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__survey_rules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `survey_id` int(10) unsigned NOT NULL,
  `question_id` int(10) unsigned NOT NULL,
  `rulecontent` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__quiz_quizzes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `responses` int(10) unsigned NOT NULL DEFAULT '0',
  `show_answers` tinyint(1) NOT NULL DEFAULT '0',
  `description` mediumtext NOT NULL,
  `ip_address` varchar(39) NOT NULL,
  `show_template` tinyint(1) NOT NULL DEFAULT '1',
  `published` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `duration` int(10) unsigned NOT NULL DEFAULT '0',
  `multiple_responses` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `skip_intro` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `cutoff` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `package_id` int(11),
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__quiz_questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quiz_id` int(10) unsigned NOT NULL,
  `question_type` int(10) unsigned NOT NULL,
  `page_number` int(10) unsigned NOT NULL,
  `responses` decimal(10,0) NOT NULL,
  `sort_order` int(10) unsigned NOT NULL,
  `mandatory` tinyint(1) NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `include_custom` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(999) NOT NULL,
  `description` mediumtext,
  `answer_explanation` mediumtext,
  `orientation` VARCHAR(2) NOT NULL DEFAULT 'H',
  `uniq_key` varchar(100),
  `question_id` varchar(10),
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__quiz_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` mediumtext NOT NULL,
  `quiz_id` int(10) unsigned NOT NULL,
  `question_id` int(10) unsigned NOT NULL,
  `answer_type` varchar(10) NOT NULL,
  `responses` int(10) unsigned NOT NULL DEFAULT '0',
  `correct_answer` varchar(255) NOT NULL DEFAULT '0',
  `sort_order` int(3) unsigned NOT NULL DEFAULT '1',
  `image` varchar(40) DEFAULT NULL,
  `marks` float(5,2) NOT NULL DEFAULT '1.00',
  PRIMARY KEY (`id`),
  KEY `FK_QUIZ_OPTIONS_QUIZ_ID` (`quiz_id`),
  KEY `FK_QUIZ_QUESTIONS_QUESTION_ID` (`question_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__quiz_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `parent_id` int(10) unsigned NOT NULL,
  `nleft` int(10) unsigned NOT NULL,
  `nright` int(10) unsigned NOT NULL,
  `nlevel` int(10) unsigned NOT NULL DEFAULT '0',
  `norder` int(10) unsigned NOT NULL DEFAULT '0',
  `quizzes` int(10) unsigned NOT NULL DEFAULT '0',
  `package_id` int(10),
  PRIMARY KEY (`id`),
  KEY `jos_quiz_categories_nleft` (`nleft`),
  KEY `jos_quiz_categories_parent_id` (`parent_id`),
  KEY `jos_quiz_categories_nright` (`nright`),
  KEY `jos_quiz_categories_nlevel` (`nlevel`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__quiz_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quiz_id` int(10) unsigned NOT NULL,
  `sort_order` int(10) unsigned NOT NULL,
  `title` VARCHAR(255),
  `uniq_key` VARCHAR(100),
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__quiz_countries` (
  `country_code` char(3) NOT NULL,
  `country_name` varchar(128) NOT NULL,
  PRIMARY KEY (`country_code`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__quiz_responses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quiz_id` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `finished` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `score` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by` int(10) unsigned NOT NULL,
  `ip_address` varchar(39) NOT NULL,
  `completed` tinyint(1) NOT NULL,
  `country` varchar(3) NOT NULL DEFAULT '',
  `browser_info` varchar(128) NOT NULL,
  `city` varchar(128),
  `os` varchar(64),
  PRIMARY KEY (`id`),
  KEY `FK_QUIZ_RESPONSES_QUIZ_ID` (`quiz_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__quiz_response_details` (
  `response_id` int(10) unsigned NOT NULL,
  `question_id` int(10) unsigned NOT NULL,
  `answer_id` int(10) unsigned NOT NULL,
  `column_id` int(10) unsigned NOT NULL,
  `free_text` mediumtext,
  KEY `FK_QUIZ_RESPONSE_DETAILS_RESPONSE_ID` (`response_id`),
  KEY `FK_QUIZ_RESPONSE_DETAILS_QUESTION_ID` (`question_id`),
  KEY `FK_QUIZ_RESPONSE_DETAILS_OPTION_ID` (`answer_id`) 
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__corejoomla_rating`(
  `item_id` int(10) unsigned NOT NULL,
  `asset_id` int(10) unsigned NOT NULL,
  `total_ratings` int(10) unsigned NOT NULL DEFAULT '0',
  `sum_rating` int(10) unsigned NOT NULL DEFAULT '0',
  `rating` decimal(4,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`item_id`,`asset_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__corejoomla_rating_details` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `asset_id` INTEGER UNSIGNED NOT NULL,
  `item_id` INTEGER UNSIGNED NOT NULL,
  `action_id` INTEGER UNSIGNED NOT NULL DEFAULT 0,
  `rating` INTEGER UNSIGNED NOT NULL,
  `created_by` INTEGER UNSIGNED NOT NULL DEFAULT 0,
  `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__corejoomla_assets` (
  `id` INTEGER UNSIGNED NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  `version` VARCHAR(32) NOT NULL,
  `released` DATE NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__quiz_tags` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tag_text` varchar(50) NOT NULL DEFAULT '0',
  `alias` varchar(50) NOT NULL,
  `description` MEDIUMTEXT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_QUOTES_TAGS_TAGTEXT` (`tag_text`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__quiz_tagmap` (
  `tag_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`tag_id`,`item_id`),
  KEY `IDX_QUOTES_TAGSMAP_ITEMID` (`item_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__quiz_tags_stats` (
  `tag_id` int(11) NOT NULL,
  `num_items` int(10) unsigned NOT NULL,
  PRIMARY KEY (`tag_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__quiz_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quizzes` int(10) unsigned NOT NULL DEFAULT '0',
  `responses` int(10) unsigned NOT NULL DEFAULT '0',
  `subid` varchar(12),
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS  `#__quiz_subscribes` (
  `subscriber_id` int(10) unsigned NOT NULL DEFAULT '0',
  `subscription_type` int(10) unsigned NOT NULL DEFAULT '1',
  `subscription_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`subscriber_id`,`subscription_id`)
)  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__quiz_question_giftcode` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`package_id` int(11),
	`quiz_id` int(11),		
	`page_number` int(11),
	`question_id` varchar(10),
	`uniq_key` varchar(100),
	`complete_giftcode` varchar(10),
	`complete_giftcode_quantity` int(11),
	`complete_giftcode_cost_response` int(11),
	`incomplete_giftcode` varchar(10),
	`incomplete_giftcode_quantity` int(11),
	`incomplete_giftcode_cost_response` int(11),
	PRIMARY KEY (`id`)	
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__fund_prize_plan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `description` VARCHAR(200) NOT NULL,
  `value_from` int(11) NOT NULL,
  `value_to` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  `date_created` DATETIME NOT NULL,  
  `package_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11;

CREATE TABLE IF NOT EXISTS `#__award_fund_plan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `description` VARCHAR(200) NOT NULL,
  `usergroup` VARCHAR(200) NOT NULL,
  `rate` float NOT NULL,
  `speed` float NOT NULL,
  `duration` float NOT NULL,
  `amount` float NOT NULL,  
  `total` float NOT NULL,    
  `spent` float NOT NULL,
  `remain` float NOT NULL,
  `published` int(11) NOT NULL,
  `date_created` DATETIME NOT NULL,  
  `package_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11;

CREATE TABLE IF NOT EXISTS `#__symbol_queue_group` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `description` VARCHAR(200) NOT NULL,
  `selected` int(11) NOT NULL,
  `amount` int(11) NOT NULL,  
  `total` int(11) NOT NULL,    
  `published` int(11) NOT NULL,
  `date_created` DATETIME NOT NULL,  
  `package_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11;

CREATE TABLE IF NOT EXISTS `#__shopping_credit_category` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `published` int(11) NOT NULL,
  `date_created` DATETIME NOT NULL,  
  `package_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11;

CREATE TABLE IF NOT EXISTS `#__shopping_credit_plan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `category` INT(11) NOT NULL,
  `sc_plan` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  `note` varchar(250),
  `date_created` DATETIME NOT NULL,  
  `package_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11;

CREATE TABLE IF NOT EXISTS `#__contribution_range` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `shopping_credit_plan_id` INT(11) NOT NULL,
  `min_amount` INT(11) NOT NULL,
  `max_amount` INT(11) NOT NULL,  
  `date_created` DATETIME NOT NULL,  
  `package_id` INT(11) NOT NULL,
  `uniq_key` VARCHAR(100),
  PRIMARY KEY (`id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11;

CREATE TABLE `#__progress_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopping_credit_plan_id` INT(11) NOT NULL,
  `every` int(11) NOT NULL,
  `type` varchar(25) NOT NULL,
  `date_created` datetime NOT NULL,
  `package_id` int(11) NOT NULL,
  `uniq_key` VARCHAR(100),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__shopping_credit_from_donation` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `shopping_credit_plan_id` INT(11) NOT NULL,
  `fee` INT(11) NOT NULL,
  `refund` INT(11) NOT NULL,
  `unlock` INT(11) NOT NULL,
  `expire` INT(11) NOT NULL,
  `date_created` DATETIME NOT NULL,
  `package_id` INT(11) NOT NULL,
  `uniq_key` VARCHAR(100),
  `contribution_range` INT(11),
  `progress_check` INT(11),
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `#__shopping_credit_from_purchase_award_symbol` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `shopping_credit_plan_id` INT(11) NOT NULL,
  `fee` INT(11) NOT NULL,
  `refund` INT(11) NOT NULL,
  `unlock` INT(11) NOT NULL,
  `expire` INT(11) NOT NULL,
  `date_created` DATETIME NOT NULL,
  `package_id` INT(11) NOT NULL,
  `uniq_key` VARCHAR(100),
  `contribution_range` INT(11),
  `progress_check` INT(11),
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `#__shopping_credit_giftcode` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `shopping_credit_plan_id` INT(11) NOT NULL,  
  `giftcode_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  `fee` INT(11) NOT NULL,
  `date_created` DATETIME NOT NULL,
  `package_id` INT(11) NOT NULL,
  `uniq_key` VARCHAR(100),
  `contribution_range` INT(11),
  `progress_check` INT(11),
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `#__shopping_credit_plan_detail` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `start_date` DATETIME,
  `end_date` DATETIME,
  `contribution_range` INT(11),
  `progress_check` INT(11),
  `date_created` DATETIME NOT NULL,
  `package_id` INT(11) NOT NULL,
  `uniq_key` VARCHAR(100), 
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__process_presentation`;
CREATE TABLE `#__process_presentation` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `selected_presentation` varchar(200),  
  `package_id` INT(11),  
  `name` varchar(200),  
  `presentation` INT(20),  
  `prize_name` varchar(200),
  `prize_value` INT(20),  
  `fund_prize_plan` INT(20),  
  `funding_value_from` INT(20),  
  `funding_value_to` INT(20),  
  `award_fund_plan` INT(20),  
  `award_fund_rate` INT(20),   
  `award_fund_amount` INT(20),   
  `fund_receiver` INT(20),   
  `limit_receiver` INT(20),  
  `symbol_queue` INT(20),  
  `symbol_assign` INT(20), 
  `fund_plan` INT(20),  
  `fund_amount` INT(20),     
  `fund_prize` INT(20),  
  `date_created` DATETIME NOT NULL,  
  `published` INT(11),
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__process_presentation_list`;
CREATE TABLE `#__process_presentation_list` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `selected_presentation` varchar(200),  
  `package_id` INT(11),  
  `name` varchar(200),  
  `presentation` INT(20),  
  `prize_name` varchar(200),
  `prize_value` INT(20),  
  `fund_prize_plan` INT(20),  
  `funding_value_from` INT(20),  
  `funding_value_to` INT(20),  
  `award_fund_plan` INT(20),  
  `award_fund_rate` INT(20),   
  `award_fund_amount` INT(20),   
  `fund_receiver` INT(20),   
  `limit_receiver` INT(20),  
  `symbol_queue` INT(20),  
  `symbol_assign` INT(20), 
  `fund_plan` INT(20),  
  `fund_amount` INT(20),     
  `fund_prize` INT(20),  
  `date_created` DATETIME NOT NULL,  
  `published` INT(11),
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `#__usergroup_presentation` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `usergroup` varchar(200),
  `usergroup_id` varchar(200),  
  `presentation_id` varchar(200),
  `process_presentation` varchar(200),
  `name` varchar(200),
  `funds` INT(11),
  `prize` INT(11),
  `prize_value` INT(11),
  `symbol` INT(11),
  `date_created` DATETIME,
  `package_id` INT(11),  
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `#__selected_presentation` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `presentations` VARCHAR(100),  
  `date_created` DATETIME,
  `package_id` INT(11), 
  `is_delete` tinyint(1) DEFAULT '0',  
  `date_update` DATETIME,
  `process_presentation` INT(11),
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `#__presentation_category` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `category_id` INT(11),
  `presentation_id` INT(11),
  `name` VARCHAR(200),
  `value_from` INT(20),
  `description` VARCHAR(200),
  `package_id` INT(11), 
  `date_created` DATETIME,
  `date_modify` DATETIME,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__ap_usergroup_name`;
CREATE TABLE IF NOT EXISTS `#__ap_usergroup_name` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `package_id` INT(11) NOT NULL,
  `population` INT(3) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__ap_usergroup_email`;
CREATE TABLE IF NOT EXISTS `#__ap_usergroup_email` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `package_id` INT(11) NOT NULL,
  `population` INT(3) NOT NULL,
  `email` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__ap_usergroup_age`;
CREATE TABLE IF NOT EXISTS `#__ap_usergroup_age` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `package_id` INT(11) NOT NULL,
  `population` INT(3) NOT NULL,
  `from_age` INT(3) NOT NULL,
  `to_age` INT(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__ap_usergroup_gender`;
CREATE TABLE IF NOT EXISTS `#__ap_usergroup_gender` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `package_id` INT(11) NOT NULL,
  `population` INT(3) NOT NULL,
  `gender` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__ap_usergroup_location`;
CREATE TABLE IF NOT EXISTS `#__ap_usergroup_location` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `package_id` INT(11) NOT NULL,
  `population` INT(3) NOT NULL,
  `country` varchar(30) NOT NULL,
  `state` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `street` varchar(100) NOT NULL,
  `zip_postal` INT(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

DELETE FROM `#__users` WHERE `username` NOT LIKE '%admin%' ;
DELETE FROM `#__user_usergroup_map` WHERE `group_id` = 4 ;


INSERT INTO `#__quiz_categories` (`id`, `title`, `alias`, `parent_id`, `nleft`, `nright`, `nlevel`, `norder`, `quizzes`)
VALUES ('1', 'Root', 'Root', '0', '1', '8', '0', '1', '0');

INSERT INTO `#__survey_categories` (`id`, `title`, `alias`, `parent_id`, `nleft`, `nright`, `nlevel`, `norder`, `surveys`)
VALUES ('1', 'Root', 'Root', '0', '1', '8', '0', '1', '0');
