-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: 10.0.227.55
-- Generation Time: Nov 04, 2017 at 06:33 PM
-- Server version: 5.5.29-log
-- PHP Version: 5.5.9-1ubuntu4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `131602-bookingonline`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `deposit` int(11) DEFAULT NULL,
  `fee` int(11) DEFAULT NULL,
  `max_lending_periods` int(11) DEFAULT NULL,
  `num_items` int(11) DEFAULT NULL,
  `max_lending_items` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `category`, `name`, `deposit`, `fee`, `max_lending_periods`, `num_items`, `max_lending_items`) VALUES
(1, 2, '6-i-1', 100, 0, 4, 1, 0),
(2, 2, 'Alfapet', 100, 0, 4, 1, 0),
(3, 2, 'Basketboll', 100, 0, 4, 3, 0),
(5, 4, 'Boll/Cykel-pump', 100, 0, 4, 4, 0),
(6, 4, 'Borrmaskin', 500, 0, 4, 6, 0),
(7, 4, 'Borrset', 100, 0, 4, 8, 0),
(8, 2, 'Boule', 100, 0, 4, 3, 0),
(9, 2, 'Brännbollsträ', 100, 0, 4, 4, 0),
(10, 2, 'Carcassonne', 100, 0, 4, 1, 0),
(11, 5, 'Cykel', 500, 0, 2, 8, 2),
(13, 4, 'Cykellagningssats', 100, 0, 4, 1, 0),
(16, 4, 'Cykelverktyg', 100, 0, 4, 3, 0),
(18, 2, 'Fotboll', 100, 0, 4, 2, 0),
(19, 2, 'Frisbee', 100, 0, 4, 4, 0),
(20, 4, 'Gummiklubba', 100, 0, 4, 1, 0),
(22, 4, 'Häftpistol', 100, 0, 4, 0, 0),
(23, 4, 'Hammare', 100, 0, 4, 3, 0),
(24, 4, 'Hörselskydd', 100, 0, 4, 2, 0),
(25, 4, 'Insexnyckelset', 100, 0, 4, 3, 0),
(26, 4, 'Insexnycklar (lösa)', 100, 0, 4, 17, 0),
(27, 6, 'Kastrull', 100, 0, 4, 1, 0),
(28, 2, 'Koner', 100, 0, 4, 8, 0),
(29, 2, 'Kubb', 100, 0, 4, 2, 0),
(30, 6, 'Laminator', 100, 0, 4, 1, 0),
(32, 5, 'Liggunderlag', 100, 0, 4, 6, 0),
(33, 4, 'Måttstock', 100, 0, 4, 2, 0),
(34, 2, 'Monopol', 100, 0, 4, 2, 0),
(35, 6, 'Paellapanna', 100, 0, 4, 1, 0),
(37, 2, 'Pingisnät', 100, 0, 4, 1, 0),
(38, 2, 'Pingisracket', 100, 0, 4, 6, 0),
(39, 6, 'Säckkärra (Pirra)', 100, 0, 4, 1, 0),
(40, 2, 'Pokerset', 100, 0, 4, 1, 0),
(41, 4, 'Regeldetektor', 100, 0, 4, 2, 0),
(42, 2, 'Risk', 100, 0, 4, 1, 0),
(43, 4, 'Såg', 100, 0, 4, 1, 0),
(44, 6, 'Säng', 100, 0, 4, 12, 5),
(45, 2, 'Settlers från Catan', 100, 0, 4, 1, 0),
(46, 4, 'Silikonpistol', 100, 0, 4, 1, 0),
(49, 4, 'Skruvdragare', 500, 0, 4, 2, 0),
(50, 4, 'Skruvmejsel (små)', 100, 0, 4, 3, 0),
(51, 4, 'Skruvmejsel (stora)', 100, 0, 4, 4, 0),
(52, 4, 'Skruvmejselsats', 100, 0, 4, 2, 0),
(53, 4, 'Skruvmejselset', 100, 0, 4, 1, 0),
(54, 4, 'Skyddsglasögon', 100, 0, 4, 5, 0),
(55, 4, 'Slipmaskin', 100, 0, 4, 1, 0),
(56, 2, 'Smallworld', 100, 0, 4, 1, 0),
(57, 2, 'Smallworld - Be not afraid', 100, 0, 4, 1, 0),
(58, 4, 'Spännband', 100, 0, 4, 2, 0),
(59, 4, 'Stämjärn', 100, 0, 4, 3, 0),
(60, 6, 'Strykbräda', 100, 0, 4, 3, 0),
(61, 6, 'Strykjärn', 100, 0, 4, 2, 0),
(62, 5, 'Symaskin', 500, 0, 2, 7, 1),
(64, 5, 'Tält (små)', 100, 0, 4, 1, 0),
(65, 5, 'Tält (stora)', 100, 0, 4, 2, 0),
(66, 4, 'Tång (liten)', 100, 0, 4, 3, 0),
(67, 4, 'Tång (stor)', 100, 0, 4, 1, 0),
(68, 2, 'Tennisboll 4 st', 100, 0, 4, 9, 0),
(69, 2, 'Tennisrack', 100, 0, 4, 15, 0),
(70, 2, 'Twister', 100, 0, 4, 2, 0),
(71, 6, 'Våg', 100, 0, 4, 2, 0),
(72, 4, 'Vattenpass', 100, 0, 4, 2, 0),
(73, 4, 'Verktygsset', 100, 0, 4, 2, 0),
(74, 5, 'Videokamera', 100, 0, 4, 1, 0),
(75, 2, 'Volleyboll', 100, 0, 4, 3, 0),
(77, 2, 'Badmintonrack', 100, 0, 2, 6, 2),
(78, 2, 'Super 5-KAMP', 100, 0, 1, 1, 1),
(79, 2, 'Fyra i Rad', 100, 0, 1, 1, 1),
(80, 2, 'Othello', 100, 0, 1, 1, 1),
(81, 2, 'Jenga', 100, 0, 1, 1, 1),
(82, 2, 'Cluedo', 100, 0, 1, 1, 1),
(83, 2, 'Yatzy (utomhus)', 100, 0, 1, 1, 1),
(84, 2, 'Yatzy (inomhus)', 100, 0, 4, 3, 1),
(85, 2, 'Kortlek', 100, 0, 4, 6, 2),
(86, 2, 'Trivial Pursuit', 100, 0, 1, 1, 1),
(87, 6, 'Luftmadrass', 100, 0, 2, 11, 0),
(88, 6, 'Bumperballs', 500, 500, 1, 12, 0),
(89, 6, 'Västar 7x2 gul och blå', 100, 0, 4, 1, 0),
(90, 5, 'Gopro Hero5', 1500, 0, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE IF NOT EXISTS `item_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `item_categories`
--

INSERT INTO `item_categories` (`id`, `name`) VALUES
(2, 'Sport & Spel'),
(4, 'Verktyg'),
(5, 'Fritid'),
(6, 'Övrigt');

-- --------------------------------------------------------

--
-- Table structure for table `item_categories_translations`
--

CREATE TABLE IF NOT EXISTS `item_categories_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) DEFAULT NULL,
  `language` int(11) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `item_categories_translations`
--

INSERT INTO `item_categories_translations` (`id`, `category`, `language`, `name`) VALUES
(1, 2, 2, 'Sport & Spel'),
(2, 2, 1, 'Sports & Games'),
(6, 4, 1, 'Tools'),
(7, 4, 2, 'Verktyg'),
(8, 5, 2, 'Fritid'),
(9, 5, 1, 'Leisure'),
(10, 6, 1, 'Other'),
(11, 6, 2, 'Övrigt');

-- --------------------------------------------------------

--
-- Table structure for table `item_translations`
--

CREATE TABLE IF NOT EXISTS `item_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` int(11) DEFAULT NULL,
  `language` int(11) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `description` text,
  `email_text` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=179 ;

--
-- Dumping data for table `item_translations`
--

INSERT INTO `item_translations` (`id`, `item`, `language`, `name`, `description`, `email_text`) VALUES
(1, 1, 1, '6-in-1', '', ''),
(2, 1, 2, '6-i-1', '', ''),
(3, 2, 1, 'Scrabble', '', ''),
(4, 2, 2, 'Alfapet', '', ''),
(5, 3, 1, 'Basketball', '', ''),
(6, 3, 2, 'Basketboll', '', ''),
(9, 8, 1, 'Pétanque', '', ''),
(10, 8, 2, 'Boule', '', ''),
(11, 9, 1, 'Brennball bat', '', ''),
(12, 9, 2, 'Brännbollsträ', '', ''),
(13, 10, 1, 'Carcassonne', '', ''),
(14, 10, 2, 'Carcassonne', '', ''),
(15, 18, 1, 'Soccer ball', '', ''),
(16, 18, 2, 'Fotboll', '', ''),
(17, 19, 2, 'Frisbee', '', ''),
(18, 19, 1, 'Flying disc aka Frisbee', '', ''),
(19, 28, 2, 'Koner', '', ''),
(20, 28, 1, 'Cones', '', ''),
(21, 29, 2, 'Kubb', '', ''),
(22, 29, 1, 'Kubb (Billet)', '', ''),
(23, 34, 2, 'Monopol', '', ''),
(24, 34, 1, 'Monopoly', '', ''),
(27, 37, 2, 'Pingisnät ', '', ''),
(28, 37, 1, 'Table tennis net', '', ''),
(29, 38, 2, 'Pingisracket', '2 racket. 3 pingisbollar ingår', ''),
(30, 38, 1, 'Table tennis racket', '2 rackets. 3 balls are included', ''),
(31, 40, 2, 'Pokerset', '', ''),
(32, 40, 1, 'Poker set', '', ''),
(33, 42, 2, 'Risk', '', ''),
(34, 42, 1, 'Risk', '', ''),
(35, 45, 2, 'Settlers från Catan', '', ''),
(36, 45, 1, 'Settlers of Catan', '', ''),
(37, 56, 1, 'Smallworld - Underground', '', ''),
(38, 56, 2, 'Smallworld - Underground', '', ''),
(39, 57, 1, 'Smallworld - Be not afraid', '', ''),
(40, 57, 2, 'Smallworld - Be not afraid', '', ''),
(41, 68, 2, 'Tennisboll', '', ''),
(42, 68, 1, 'Tennis ball', '', ''),
(43, 69, 2, 'Tennisrack', '', ''),
(44, 69, 1, 'Tennis racket', '', ''),
(45, 70, 2, 'Twister', '', ''),
(46, 70, 1, 'Twister', '', ''),
(47, 75, 2, 'Volleyboll', '', ''),
(48, 75, 1, 'Volleyball', '', ''),
(51, 71, 2, 'Våg', '', ''),
(52, 71, 1, 'Scale', '', ''),
(53, 61, 2, 'Strykjärn', '', ''),
(54, 61, 1, 'Smoothing Iron', '', ''),
(55, 60, 1, 'Ironing board', '', ''),
(56, 60, 2, 'Strykbräda', '', ''),
(57, 44, 2, 'Säng', 'Fällbara sängar', ''),
(58, 44, 1, 'Bed', 'Folding beds', ''),
(59, 39, 2, 'Säckkärra (Pirra)', '', ''),
(60, 39, 1, 'Hand truck', '', ''),
(61, 35, 2, 'Paellapanna', '', ''),
(62, 35, 1, 'Paella pan', '', ''),
(65, 30, 2, 'Laminator', '', ''),
(66, 30, 1, 'Laminator', '', ''),
(67, 27, 2, 'Kastrull', '', ''),
(68, 27, 1, 'Saucepan', '', ''),
(69, 74, 2, 'Videokamera', '', ''),
(70, 74, 1, 'Video camera', '', ''),
(71, 65, 2, 'Tält (stora)', '', ''),
(72, 65, 1, 'Tent (large)', '', ''),
(73, 64, 1, 'Tent (small)', '', ''),
(74, 64, 2, 'Tält (små)', '', ''),
(77, 62, 2, 'Symaskin', 'Tråd ingår om du skulle behöva det', ''),
(78, 62, 1, 'Sewing machine', 'Thread is included if needed', ''),
(79, 32, 2, 'Liggunderlag', '', ''),
(80, 32, 1, 'Sleeping pad', '', ''),
(83, 11, 2, 'Cykel', '', ''),
(84, 11, 1, 'Bicycle', '', ''),
(85, 73, 2, 'Verktygsset', '', ''),
(86, 73, 1, 'Tool set', '', ''),
(87, 5, 1, 'Ball/Bicycle pump', '', ''),
(88, 5, 2, 'Boll/Cykel-pump', '', ''),
(89, 6, 2, 'Borrmaskin', 'Med slagborr maskinen ingår borr-set!\r\n', ''),
(90, 6, 1, 'Drilling machine', 'With the Hammer-drill, you do not need to book a drilling tool set because it is included', ''),
(91, 7, 2, 'Borrset', '', ''),
(92, 7, 1, 'Drilling tool set', '', ''),
(95, 13, 2, 'Cykellagningssats', '', ''),
(96, 13, 1, 'Bicycle repair kit', '', ''),
(101, 16, 2, 'Cykelverktyg', '', ''),
(102, 16, 1, 'Bicycle tools', '', ''),
(103, 20, 2, 'Gummiklubba', '', ''),
(104, 20, 1, 'Rubber mallet', '', ''),
(107, 22, 2, 'Häftpistol', '', ''),
(108, 22, 1, 'Staple gun', '', ''),
(109, 23, 2, 'Hammare', '', ''),
(110, 23, 1, 'Hammer', '', ''),
(111, 24, 2, 'Hörselskydd', '', ''),
(112, 24, 1, 'Hearing protection', '', ''),
(113, 25, 2, 'Insexnyckelset', '', ''),
(114, 25, 1, 'Hex key set', '', ''),
(115, 26, 2, 'Insexnycklar (lösa)', '', ''),
(116, 26, 1, 'Hex keys (loose)', '', ''),
(117, 33, 2, 'Måttstock', '', ''),
(118, 33, 1, 'Yardstick', '', ''),
(119, 41, 2, 'Regeldetektor', '', ''),
(120, 41, 1, 'Stud detector', '', ''),
(121, 43, 2, 'Såg', '', ''),
(122, 43, 1, 'Saw', '', ''),
(123, 46, 2, 'Silikonpistol', '', ''),
(124, 46, 1, 'Silicon pistol', '', ''),
(129, 49, 2, 'Skruvdragare', '', ''),
(130, 49, 1, 'Electric screwdriver', '', ''),
(131, 50, 2, 'Skruvmejsel (små)', 'Löstagbar huvud', ''),
(132, 50, 1, 'Screwdriver (small)', 'Detachable head', ''),
(133, 51, 2, 'Skruvmejsel (stora)', '', ''),
(134, 51, 1, 'Screwdriver (large)', '', ''),
(135, 52, 2, 'Skruvmejselsats', '', ''),
(136, 52, 1, 'Screwdriver kit', '', ''),
(137, 53, 2, 'Skruvmejselset', '', ''),
(138, 53, 1, 'Screwdriver set', '', ''),
(139, 54, 2, 'Skyddsglasögon', '', ''),
(140, 54, 1, 'Goggles', '', ''),
(141, 55, 2, 'Slipmaskin', '', ''),
(142, 55, 1, 'Electric sander', '', ''),
(143, 58, 2, 'Spännband', '', ''),
(144, 58, 1, 'Tightening straps', '', ''),
(145, 59, 2, 'Stämjärn', '', ''),
(146, 59, 1, 'Chisel', '', ''),
(147, 66, 1, 'Pliers (small)', '', ''),
(148, 66, 2, 'Tång (liten)', '', ''),
(149, 67, 2, 'Tång (stor)', '', ''),
(150, 67, 1, 'Pliers (large)', '', ''),
(151, 72, 2, 'Vattenpass', '', ''),
(152, 72, 1, 'Spirit-level', '', ''),
(153, 77, 1, 'Badminton racket ', 'You will also get a shuttlecock.', ''),
(154, 78, 1, 'Pentathlon ', '', ''),
(155, 78, 2, 'Super 5-KAMP', '', ''),
(156, 77, 2, 'Badmintonrack', '', ''),
(157, 79, 2, 'Fyra i Rad', '', ''),
(158, 79, 1, '4 In A Row', '', ''),
(159, 80, 1, 'Reversi/Othello', '', ''),
(160, 80, 2, 'Othello', '', ''),
(161, 81, 1, 'Jenga', '', ''),
(162, 81, 2, 'Jenga', '', ''),
(163, 82, 1, 'Cluedo/Clue', '', ''),
(164, 82, 2, 'Cluedo', '', ''),
(165, 83, 2, 'Yatzy (utomhus)', '', ''),
(166, 83, 1, 'Yahtzee (outdoor)', '', ''),
(167, 84, 2, 'Yatzy (inomhus)', '', ''),
(168, 84, 1, 'Yahtzee (indoor)', '', ''),
(169, 85, 2, 'Kortlek', '', ''),
(170, 85, 1, 'Cards/Deck', '', ''),
(171, 86, 2, 'Trivial Pursuit', '', ''),
(172, 86, 1, 'Trivial Pursuit', '', ''),
(173, 87, 1, 'Air mattress', 'Self inflating air mattress', ''),
(174, 88, 1, 'Bomper balls', 'Renting for 2 days at the time\r\n(Email styrelsen@frryd.se for bokning)', ''),
(175, 89, 1, 'Vests 7x2, yellow and blue', '', ''),
(176, 89, 2, 'Västar 7x2 gul och blå', '', ''),
(177, 90, 1, 'Gopro Hero5', 'Gopro Hero5 with dfferent mounting and sd card. ', ''),
(178, 90, 2, 'Gopro Hero5', 'Gopro med tillbehör. ', '');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`) VALUES
(1, '<img src="images/eng.png" style="width:1.6em;" alt="English" />'),
(2, '<img src="images/swe.png" style="width:1.6em;" alt="Swedish" />');

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE IF NOT EXISTS `translations` (
  `name` varchar(64) DEFAULT NULL,
  `language` int(11) DEFAULT NULL,
  `value` varchar(20000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `translations`
--

INSERT INTO `translations` (`name`, `language`, `value`) VALUES
('booking_and', 1, 'and'),
('booking_between', 1, 'between'),
('booking_deposit', 1, 'Deposit'),
('booking_fee', 1, 'fee'),
('booking_instruction', 1, '<h1>FR Ryd''s Booking System</h1> \r\n\r\n<div class="booking_instruction">\r\n<p>Welcome to FR Ryd''s booking page. Here you may borrow bikes, beds, sewing machines, tents and much so more!</p>\r\n<p>\r\nThe items you book are to be picked up at our office, which can be found at Alsättersgatan 7.<br \\>\r\nNote that the opening hours in the orange box do <b>NOT</b> apply during holidays and exams, see our \r\n<a href="https://frryd.se/" target="blank" >website</a> for more information. \r\n</p>\r\n			\r\n\r\n<b>To be able to use our booking system you will need to fulfill the requirements below;</b>\r\n<li>Resident in Ryd</li>\r\n<li>Have a valid LiU-ID</li>\r\n<li>Have loose cash for the deposition (because we do not offer digital payment at the moment)</li> \r\n			\r\n<br \\>\r\n\r\n<b>Guide for a successful reservation;</b>\r\n<li>Press the Login button to login and/or create an account with your LIU credentials</li>\r\n<li>Chose an object in the list below</li>\r\n<li>Chose during which periods you want to borrow the item (you can book as many item(s) as you want before advancing to checkout)</li>\r\n<li>Press the Checkout-button</li>\r\n<li>Review the booking summary</li>\r\n<li>Fill in your user information</li>\r\n<li>Read our <a href="https://frryd.se/wp-content/uploads/2016/03/Utlåningsvillkor-engelska-2016.pdf" target="blank" >terms and conditions</a> for the lending service</li>\r\n<li>Press the Book-button</li>\r\n\r\n<br \\>\r\n<b>A couple of things that are worth noting;</b>\r\n<li>Forgotten booking;</li>\r\n<div style="margin-left:12px;">If a booking is not picked up on the day it is supposed to be, the booking will be removed!</div>\r\n\r\n<li style="margin-top:10px;">Rebooking;</li>\r\n<div style="margin-left:12px;">You need to come back to the lending service with the item(s) so we can administer a new booking, this means that adding a new period to your booking through the website it NOT considered as a rebooking.</div>\r\n\r\n<li style="margin-top:10px;">Regarding booking multiple amounts of the same item;</li>\r\n<div style="margin-left:12px;">You are not allowed to book an item over and over again to exceed the limit. <br \\>\r\nA check will be made later on to prevent you from that.<br \\>\r\nUntil then, anyone exceeding the limit will have his/hers exceeded bookings REMOVED!</div><br \\>\r\n\r\n<div style="margin-top:1px;margin-left:12px;"><strong><em><i>Example</i></em></strong>: If you see that the total number of Sewing machines is 10 but you only can chose 1 from the dropdown-box, that means  \r\nyou are only allowed to book ONE machine, and that is the limit. <br \\>\r\nThat means that you are NOT allowed to book 1 machine, checkout and then go back and book another one.</div>'),
('booking_num_items', 1, 'Number of Items'),
('book_email_sent', 1, 'A confirmation email has been sent to your email address'),
('book_thank_you', 1, 'Thank you for your reservation'),
('calendar_next_month', 1, '- next month'),
('calendar_previous_month', 1, 'previous month -'),
('cal_apr', 1, 'April'),
('cal_aug', 1, 'August'),
('cal_dec', 1, 'December'),
('cal_feb', 1, 'February'),
('cal_jan', 1, 'January'),
('cal_jul', 1, 'July'),
('cal_jun', 1, 'June'),
('cal_mar', 1, 'Mars'),
('cal_may', 1, 'May'),
('cal_nov', 1, 'November'),
('cal_oct', 1, 'October'),
('cal_sep', 1, 'September'),
('cal_short_fri', 1, 'Fr'),
('cal_short_mon', 1, 'Mo'),
('cal_short_sat', 1, 'Sa'),
('cal_short_sun', 1, 'Su'),
('cal_short_thu', 1, 'Th'),
('cal_short_tue', 1, 'Tu'),
('cal_short_wed', 1, 'We'),
('cart_checkout', 1, 'Checkout'),
('cart_items_booked', 1, 'items chosen'),
('cart_title', 1, 'Cart'),
('confirm_address', 1, 'Address'),
('confirm_back_to_booking', 1, 'Return to booking'),
('confirm_email', 1, 'Email'),
('confirm_eula', 1, 'I have read and I agree with the terms'),
('confirm_eula_name', 1, 'FR Ryd Lending Terms'),
('confirm_instructions', 1, 'Fill out your details below'),
('confirm_intro', 1, 'You want to book'),
('confirm_name', 1, 'Name'),
('confirm_personnummer', 1, 'SSN'),
('confirm_phone', 1, 'Phone'),
('confirm_sum_deposit', 1, 'Total Deposition'),
('confirm_title', 1, 'Confirm Booking'),
('error_no_items_booked', 1, 'Sorry no items could be booked'),
('error_no_items_selected', 1, 'No items selected'),
('error_num_items_not_booked', 1, 'Sorry no items could be booked'),
('item_descr_num', 1, 'items.'),
('item_descr_there_is', 1, 'There is a total of'),
('max_lending_time', 1, 'Max number of lending periods:'),
('site_title', 1, 'FR Ryd'),
('logout', 1, 'Logout'),
('email_confirm_intro', 1, 'You have successfully booked the following items:'),
('email_confirm_end', 1, 'Please visit http://www.frryd.se/ for more information.\\n\\nContact us: intendent@frryd.se'),
('email_reminder_pickup', 1, 'Greetings! This is a reminder that you have booked items through FR Ryd.'),
('email_reminder_return', 1, 'Greetings! This is a reminder that you have loaned items from FR Ryd that are to be returned shortly. '),
('email_reminder_overdue', 1, 'Hello,\r\n\r\nThis is a reminder that you have loaned items from FR Ryd that are overdue.\r\nPlease return your booking, since the items from the booking can be of use to other students.\r\n\r\n(if you feel that this email does not apply to you, then please come on down during our opening hours and inform us)\r\n\r\nRegards,\r\nFR Ryd'),
('email_unbook_instructions', 1, ''),
('unbook', 1, 'Cancel reservation'),
('unbooked', 1, 'Your reservation has been cancelled'),
('eula_text', 1, '<iframe src="https://frryd.se/wp-content/uploads/2016/03/Utlåningsvillkor-engelska-2016.pdf" width="100%" height="50%" >eula</iframe>'),
('booking_choose_period', 1, 'Show calendar'),
('booking_menu_title', 1, 'Booking'),
('calendar_instruction', 1, '<div style="font-weight:bold;">Select the period(s) you want to book the object.</div>Green is available. <br>Grey is not available. <br>Your booking is blue.'),
('booking_and', 2, 'och'),
('booking_between', 2, 'mellan'),
('booking_deposit', 2, 'Deposition'),
('booking_fee', 2, 'avgift'),
('booking_instruction', 2, '<h1>FR Ryds Bokningssystem</h1> \r\n\r\n<div class="booking_instruction">\r\n<p>Välkomen till FR Ryds bokningssida. Här kan du låna cyklar, sängar, symaskiner, tält och så mycket mer!</p>\r\n\r\n<p>\r\nDe föremål som du bokar kan hämtas upp på vårt kontor, som ligger på Alsättersgatan 7.<br \\>\r\nObservera att öppettiderna i den orange:a rutan gäller <b>INTE</b> under helgdagar, tenta-p och omtenta-p, besök vår \r\n<a href="https://frryd.se/" target="blank" >hemsida</a> för mer information.\r\n</p>\r\n			\r\n\r\n<b>För att använda vår bokningssystem måste du uppfylla kraven nedan;</b>\r\n<li>Bosatt i Ryd</li>\r\n<li>Har ett giltigt LiU-ID</li>\r\n<li>Ha lösa kontanter för depositionen (då vi tyvärr inte erbjuder digital betalning för tillfället)</li> \r\n			\r\n<br \\>\r\n<b>Guide för en lyckad reservation;</b>\r\n<li>Tryck på Login-knappen för att logga in och/eller skapa ett konto med dina LiU uppgifter</li>\r\n<li>Välj ett föremål från listan nedan</li>\r\n<li>Välj under vilka perioder du vill låna föremålen(du kan boka så många föremål som du vill innan Checkout)</li>\r\n<li>Tryck på Checkout-knappen</li>\r\n<li>Granska din boknings sammanfattning</li>\r\n<li>Fyll i dina användaruppgifter</li>\r\n<li>Läs våra <a href="https://frryd.se/wp-content/uploads/2016/03/Utlåningsvillkor-engelska-2016.pdf" target="blank" >villkor</a> för utlåningsverksamheten</li>\r\n<li>Tryck på Book-knappen</li>\r\n\r\n<br \\>\r\n<b>Ett par saker som är värda att notera;</b>\r\n<li>Glömd bokning;</li>\r\n<div style="margin-left:12px;">Om en bokning inte plockas upp samma dag som det är tänkt att vara, bokningen kommer att tas bort</div>\r\n\r\n<li style="margin-top:10px;">Ombokning;</li>\r\n<div style="margin-left:12px;">Du måste komma tillbaka till kansliet med föremålen så att vi kan administrera en ny bokning, detta innebär att lägga till en ny period till din bokning via webbplatsen är inte betraktad som en ombokning.</div>\r\n\r\n<li style="margin-top:10px;">Angående bokning av flera antal av samma föremål;</li>\r\n<div style="margin-left:12px;">Det är inte tillåtet att boka ett objekt om och om igen för att överskrida gränsvärdet.<br \\>\r\nEn kontroll kommer att göras senare för att hindra dig från det.<br \\>\r\nFram till dess kommer alla bokningar som överskrider gränsen att TAS BORT!</div><br \\>\r\n\r\n<div style="margin-top:1px;margin-left:12px;"><strong><em><i>Exempel</i></em></strong>: Om du ser att det totala antalet Symaskiner är 10 men du bara kan välja 1 från rullgardins-boxen, innebär detta att det endast är tillåtet att boka en maskin, och detta är gränsvärdet.<br \\>\r\nDet betyder att du får <b>INTE</b> boka en maskin, checka ut och sedan gå tillbaka och boka en till sådan.</div>'),
('booking_num_items', 2, 'Antal '),
('book_email_sent', 2, 'Ett bekräftelse email är nu på väg till dig'),
('book_thank_you', 2, 'Tack för din reservation'),
('calendar_next_month', 2, '- nästa månad'),
('calendar_previous_month', 2, 'föregående månad -'),
('cal_apr', 2, 'April'),
('cal_aug', 2, 'Augusti'),
('cal_dec', 2, 'December'),
('cal_feb', 2, 'Februari'),
('cal_jan', 2, 'Januari'),
('cal_jul', 2, 'Juli'),
('cal_jun', 2, 'Juni'),
('cal_mar', 2, 'Mars'),
('cal_may', 2, 'Maj'),
('cal_nov', 2, 'November'),
('cal_oct', 2, 'Oktober'),
('cal_sep', 2, 'September'),
('cal_short_fri', 2, 'fre'),
('cal_short_mon', 2, 'mon'),
('cal_short_sat', 2, 'lör'),
('cal_short_sun', 2, 'sön'),
('cal_short_thu', 2, 'tors'),
('cal_short_tue', 2, 'tis'),
('cal_short_wed', 2, 'ons'),
('cart_checkout', 2, 'Checkout'),
('cart_items_booked', 2, 'föremål valda'),
('cart_title', 2, 'Varukorg'),
('confirm_address', 2, 'Adress'),
('confirm_back_to_booking', 2, 'Tillbaka till bokning'),
('confirm_email', 2, 'Email'),
('confirm_eula', 2, 'Jag har läst och jag godkänner vilkoren'),
('confirm_eula_name', 2, 'FR Ryd Utlånings Vilkor'),
('confirm_instructions', 2, 'Fyll i dina uppgifter nedan'),
('confirm_intro', 2, 'Du vill boka'),
('confirm_name', 2, 'Namn'),
('confirm_personnummer', 2, 'Pers.nr.'),
('confirm_phone', 2, 'Mobil'),
('confirm_sum_deposit', 2, 'Total Deposition'),
('confirm_title', 2, 'Bekräfta Bokning'),
('error_no_items_booked', 2, 'Tyvärr, inga artiklar kunude bokas'),
('error_no_items_selected', 2, 'Inga föremål valda'),
('error_num_items_not_booked', 2, 'Tyvärr, inga artiklar kunude bokas'),
('item_descr_num', 2, 'föremål.'),
('item_descr_there_is', 2, 'Det finns totalt '),
('max_lending_time', 2, 'Max antal låneperiod:'),
('site_title', 2, 'FR Ryds Bokningssystem'),
('logout', 2, 'Logga Ut'),
('email_confirm_intro', 2, 'Du har bokat följande punkter:'),
('email_confirm_end', 2, 'Besök https://www.frryd.se/ för mer information.\\n\\nKontakta oss: intendent@frryd.se'),
('email_reminder_pickup', 2, 'Hej! Detta är en påminnelse om att du har bokade föremål genom FR Ryd.'),
('email_reminder_return', 2, 'Hej! Detta är en påminnelse om att du har lånat ut föremål från FR Ryd som skal returneras inom kort.'),
('email_reminder_overdue', 2, 'Hälsningar! Detta är en påminnelse om att du har lånat ut föremål från FR Ryd som du ännu inte har återvänt. Vänligen gör det eftersom de föremålen kan vara till nytta för andra studenter.'),
('email_unbook_instructions', 2, ''),
('unbook', 2, 'Avboka Bokning'),
('unbooked', 2, 'Din bokning har avbrutits'),
('eula_text', 2, '<iframe src="https://frryd.se/wp-content/uploads/2016/03/Utlåningsvillkor-svenska-2016.pdf"  width="100%" height="50%" >eula</iframe>'),
('booking_choose_period', 2, 'Visa kalender'),
('booking_menu_title', 2, 'Bokning'),
('calendar_instruction', 2, '<div style="font-weight:bold;">Välj antal låneperiod du vill boka objektet.</div>Grön finns tillgänglig.<br> Grey är inte tillgänglig.<br> Din bokning är blå.'),
('book_confirm', 1, 'Reservation Confirmation'),
('book_confirm', 2, 'Bekräftelse på reservationen'),
('you_have_booked', 1, 'You have reserved the following: '),
('you_have_booked', 2, 'Du har reserverat följande: '),
('please_note', 1, 'Please note that;'),
('please_note', 2, 'Vänligen observera att;');
