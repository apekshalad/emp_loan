-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 12, 2017 at 09:59 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `emp_mgmt`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `branch_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `branch_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `branch_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `entry_date` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_name`, `branch_code`, `branch_address`, `user_id`, `entry_date`) VALUES
(1, 'Sakhipur', '121', '', 1, '1495543199');

-- --------------------------------------------------------

--
-- Table structure for table `capital_account`
--

CREATE TABLE `capital_account` (
  `cap_acc_id` int(11) NOT NULL,
  `payment_in` int(11) NOT NULL,
  `payment_out` int(11) NOT NULL,
  `payment_log` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `payment_method` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'cash',
  `payment_date` int(11) NOT NULL,
  `trans_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `trans_from` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `client_ID` int(11) NOT NULL,
  `cap_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `entry_date` int(11) NOT NULL,
  `expense_type` int(11) DEFAULT NULL,
  `account_manager` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `capital_account`
--

INSERT INTO `capital_account` (`cap_acc_id`, `payment_in`, `payment_out`, `payment_log`, `payment_method`, `payment_date`, `trans_type`, `trans_from`, `client_ID`, `cap_by`, `user_id`, `entry_date`, `expense_type`, `account_manager`) VALUES
(1, 50, 0, '', 'cash', 1495543299, 'saving', '', 33, 'borrower', 1, 1495543299, NULL, NULL),
(2, 100, 0, '', 'cash', 1495477800, 'saving', '', 33, 'borrower', 1, 1495543337, NULL, NULL),
(3, 500, 0, '', 'cash', 1497897000, 'saving', '', 33, 'borrower', 1, 1497943227, NULL, NULL),
(4, 100, 0, '', 'cash', 1497897000, 'charges', 'saving', 33, 'borrower', 1, 1497943264, NULL, NULL),
(5, 0, 100, '100 Rs. Charges charged from borrower account.', 'cash', 1497897000, 'withdraw', 'charges', 33, 'borrower', 1, 1497943264, NULL, NULL),
(6, 0, 200, 'Personal given to account no. 1108121159', 'cash', 592786800, 'loan', '', 33, 'borrower', 1, 1501001149, NULL, NULL),
(7, 15, 0, 'EMI received from account no. 1108121159', 'cash', 1500921000, 'emi', '', 33, 'borrower', 1, 1501001238, NULL, NULL),
(8, 15, 0, 'EMI received from account no. 1108121159', 'cash', 1500921000, 'emi', '', 33, 'borrower', 1, 1501001525, NULL, NULL),
(9, 15, 0, 'EMI received from account no. 1108121159', 'cash', 1500921000, 'emi', '', 33, 'borrower', 1, 1501001525, NULL, NULL),
(11, 0, 1465416, ',lfkjhdgscfgvhjkl;x'';bdcdvbldfv''po', 'cash', 1501785000, 'expense', '', 0, '', 1, 1501579188, 1, '38'),
(18, 0, 625687, 'jnmgrgcuorgtuotg ', 'cash', 1503081000, 'expense', '', 0, '', 1, 1501655958, 0, '33'),
(13, 0, 125545565, 'india', 'cheque', 1502389800, 'expense', '', 0, '', 1, 1501583698, 1, '39'),
(15, 0, 54584, 'kmjhgfdfcgvhbjnkml,', 'cash', 1501871400, 'expense', '', 0, '', 1, 1501585131, 0, '39'),
(20, 0, 5000, 'Personal given to account no. 132132', 'cash', 1501538400, 'loan', '', 43, 'borrower', 1, 1504015944, NULL, NULL),
(21, 0, 5000, 'Personal given to account no. 123456', 'cash', 1501538400, 'loan', '', 46, 'borrower', 1, 1504016879, NULL, NULL),
(22, 590, 0, 'EMI received from account no. 123456', 'cash', 1503945000, 'emi', '', 46, 'borrower', 1, 1504016895, NULL, NULL),
(23, 540, 0, '', 'cash', 1503945000, 'saving', '', 46, 'borrower', 1, 1504016925, NULL, NULL),
(24, 350, 0, '', 'cash', 1503945000, 'conf_saving', '', 46, 'borrower', 1, 1504016950, NULL, NULL),
(25, 200, 0, '', 'cash', 1504031400, 'saving', '', 46, 'borrower', 1, 1504031400, NULL, NULL),
(26, 800, 0, '', 'cash', 1504031400, 'conf_saving', '', 46, 'borrower', 1, 1504031400, NULL, NULL),
(27, 100, 0, '', 'cash', 1504117800, 'saving', '', 46, 'borrower', 1, 1504117800, NULL, NULL),
(28, 0, 0, '', 'cash', 1504117800, 'conf_saving', '', 46, 'borrower', 1, 1504117800, NULL, NULL),
(29, 150, 0, '', 'cash', 1504204200, 'saving', '', 46, 'borrower', 1, 1504204200, NULL, NULL),
(30, 0, 0, '', 'cash', 1504204200, 'conf_saving', '', 46, 'borrower', 1, 1504204200, NULL, NULL),
(31, 0, 50000, 'Personal given to account no. 123456', 'cash', 1506549600, 'loan', '', 46, 'borrower', 1, 1505200055, NULL, NULL),
(32, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(33, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(34, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(35, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(36, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(37, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(38, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(39, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(40, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(41, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(42, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(43, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(44, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(45, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(46, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(47, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(48, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(49, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(50, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(51, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(52, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(53, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(54, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(55, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(56, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(57, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(58, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(59, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(60, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(61, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(62, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(63, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(64, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(65, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(66, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(67, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(68, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(69, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(70, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(71, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(72, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(73, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(74, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(75, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(76, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(77, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(78, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(79, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(80, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(81, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(82, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(83, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(84, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(85, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(86, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(87, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(88, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(89, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(90, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(91, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(92, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(93, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(94, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(95, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(96, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(97, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(98, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(99, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(100, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(101, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(102, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(103, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(104, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(105, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(106, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(107, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(108, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(109, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(110, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(111, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(112, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(113, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(114, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(115, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(116, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(117, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(118, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(119, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(120, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(121, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(122, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(123, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(124, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(125, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(126, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(127, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(128, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(129, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(130, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(131, 15, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200073, NULL, NULL),
(132, 590, 0, 'EMI received from account no. 123456', 'cash', 1505154600, 'emi', '', 46, 'borrower', 1, 1505200107, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `capital_meta`
--

CREATE TABLE `capital_meta` (
  `cm_id` int(11) NOT NULL,
  `cap_acc_id` int(11) NOT NULL,
  `meta_key` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(255) NOT NULL,
  `cat_title` varchar(255) NOT NULL,
  `cat_content` text NOT NULL,
  `parent_id` int(255) NOT NULL,
  `cat_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cat_images`
--

CREATE TABLE `cat_images` (
  `image_id` int(11) NOT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `cat_image_name` varchar(255) DEFAULT NULL,
  `cat_image_path` varchar(255) DEFAULT NULL,
  `cat_image_type` varchar(255) DEFAULT NULL,
  `cat_image_alt` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_ID` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `client_acc_no` varchar(255) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `client_father_name` varchar(255) NOT NULL,
  `client_mother_name` varchar(255) NOT NULL,
  `client_mobile` varchar(50) NOT NULL,
  `client_address` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `nominee` varchar(255) NOT NULL,
  `age_of_nominee` varchar(255) NOT NULL,
  `relation_with_nominee` varchar(255) NOT NULL,
  `entry_date` int(11) NOT NULL,
  `current_status` varchar(15) NOT NULL,
  `client_photo_proof` varchar(255) NOT NULL,
  `client_id_proof` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `account_managent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_ID`, `branch_id`, `client_acc_no`, `client_name`, `client_father_name`, `client_mother_name`, `client_mobile`, `client_address`, `age`, `nominee`, `age_of_nominee`, `relation_with_nominee`, `entry_date`, `current_status`, `client_photo_proof`, `client_id_proof`, `user_id`, `remarks`, `account_managent`) VALUES
(8, 1, '123456', 'Apeksha Lad', 'Ravi', 'maa', '7895454', '', 0, '', '', '', 1504016818, 'publish', '', '', 1, '', 45);

-- --------------------------------------------------------

--
-- Table structure for table `current_capital`
--

CREATE TABLE `current_capital` (
  `current_capital_amount` bigint(11) NOT NULL,
  `sss` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_ID` int(11) NOT NULL,
  `customer_acc_no` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_mobile` varchar(50) NOT NULL,
  `customer_address` varchar(255) NOT NULL,
  `entry_date` int(11) NOT NULL,
  `current_status` varchar(15) NOT NULL,
  `customer_photo_proof` varchar(255) NOT NULL,
  `customer_id_proof` varchar(255) NOT NULL,
  `guarantor1` int(11) NOT NULL,
  `guarantor2` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

CREATE TABLE `designation` (
  `id` int(11) NOT NULL,
  `designation_name` varchar(500) DEFAULT NULL,
  `designation_salary` varchar(200) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `entry_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`id`, `designation_name`, `designation_salary`, `user_id`, `entry_date`) VALUES
(2, 'PM', '2500', 1, '1501239722');

-- --------------------------------------------------------

--
-- Table structure for table `dyna_keys`
--

CREATE TABLE `dyna_keys` (
  `key_id` int(11) NOT NULL,
  `customer_acc_no` int(11) NOT NULL,
  `current_capital` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `expense_category`
--

CREATE TABLE `expense_category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(500) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `entry_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `expense_category`
--

INSERT INTO `expense_category` (`id`, `category_name`, `user_id`, `entry_date`) VALUES
(1, 'kkkkkkkkkk', 1, '1501570792');

-- --------------------------------------------------------

--
-- Table structure for table `guarantors`
--

CREATE TABLE `guarantors` (
  `guarantor_ID` int(11) NOT NULL,
  `guarantor_name` varchar(100) NOT NULL,
  `guarantor_mobile` varchar(50) NOT NULL,
  `guarantor_address` varchar(255) NOT NULL,
  `entry_date` int(11) NOT NULL,
  `current_status` varchar(15) NOT NULL,
  `guarantor_photo_proof` varchar(255) NOT NULL,
  `guarantor_id_proof` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `loan_id` int(11) NOT NULL,
  `loan_type` varchar(20) NOT NULL,
  `payment_terms` varchar(100) NOT NULL,
  `client_ID` int(11) NOT NULL,
  `loan_date` varchar(20) NOT NULL,
  `principal_amount` int(11) NOT NULL,
  `time_periods` int(11) NOT NULL,
  `interest_rate` int(11) NOT NULL,
  `emi_amount` int(11) NOT NULL,
  `emi_type` varchar(25) NOT NULL DEFAULT 'flat_emi',
  `net_amount` int(11) NOT NULL,
  `total_amount_deposite` int(11) NOT NULL,
  `payment_mode` varchar(50) NOT NULL DEFAULT 'cash',
  `user_id` int(11) NOT NULL,
  `entry_date` varchar(50) NOT NULL,
  `notify_install_count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`loan_id`, `loan_type`, `payment_terms`, `client_ID`, `loan_date`, `principal_amount`, `time_periods`, `interest_rate`, `emi_amount`, `emi_type`, `net_amount`, `total_amount_deposite`, `payment_mode`, `user_id`, `entry_date`, `notify_install_count`) VALUES
(3, '0', 'monthly', 46, '1501538400', 5000, 10, 18, 590, 'normal', 5900, 1180, 'cash', 1, '1504016879', 9),
(4, '0', 'monthly', 46, '1506549600', 50000, 3900, 18, 15, 'normal', 58500, 1500, 'cash', 1, '1505200055', 10);

-- --------------------------------------------------------

--
-- Table structure for table `loan_payments`
--

CREATE TABLE `loan_payments` (
  `payment_id` int(11) NOT NULL,
  `client_ID` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `emi_amount` varchar(50) NOT NULL,
  `deposit_amount` varchar(50) NOT NULL,
  `payment_date` varchar(50) NOT NULL,
  `payment_mode` varchar(50) NOT NULL DEFAULT 'cash',
  `payment_log` varchar(255) NOT NULL,
  `due_date` varchar(50) NOT NULL,
  `entry_date` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loan_payments`
--

INSERT INTO `loan_payments` (`payment_id`, `client_ID`, `loan_id`, `emi_amount`, `deposit_amount`, `payment_date`, `payment_mode`, `payment_log`, `due_date`, `entry_date`, `user_id`) VALUES
(4, 46, 3, '590', '590', '1503945000', 'cash', 'EMI received from account no. 123456', '1504204200', '1504016895', 1),
(5, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1509129000', '1505200073', 1),
(6, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1511807400', '1505200073', 1),
(7, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1514399400', '1505200073', 1),
(8, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1517077800', '1505200073', 1),
(9, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1519756200', '1505200073', 1),
(10, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1522175400', '1505200073', 1),
(11, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1524853800', '1505200073', 1),
(12, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1527445800', '1505200073', 1),
(13, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1530124200', '1505200073', 1),
(14, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1532716200', '1505200073', 1),
(15, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1535394600', '1505200073', 1),
(16, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1538073000', '1505200073', 1),
(17, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1540665000', '1505200073', 1),
(18, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1543343400', '1505200073', 1),
(19, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1545935400', '1505200073', 1),
(20, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1548613800', '1505200073', 1),
(21, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1551292200', '1505200073', 1),
(22, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1553711400', '1505200073', 1),
(23, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1556389800', '1505200073', 1),
(24, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1558981800', '1505200073', 1),
(25, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1561660200', '1505200073', 1),
(26, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1564252200', '1505200073', 1),
(27, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1566930600', '1505200073', 1),
(28, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1569609000', '1505200073', 1),
(29, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1572201000', '1505200073', 1),
(30, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1574879400', '1505200073', 1),
(31, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1577471400', '1505200073', 1),
(32, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1580149800', '1505200073', 1),
(33, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1582828200', '1505200073', 1),
(34, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1585333800', '1505200073', 1),
(35, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1588012200', '1505200073', 1),
(36, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1590604200', '1505200073', 1),
(37, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1593282600', '1505200073', 1),
(38, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1595874600', '1505200073', 1),
(39, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1598553000', '1505200073', 1),
(40, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1601231400', '1505200073', 1),
(41, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1603823400', '1505200073', 1),
(42, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1606501800', '1505200073', 1),
(43, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1609093800', '1505200073', 1),
(44, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1611772200', '1505200073', 1),
(45, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1614450600', '1505200073', 1),
(46, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1616869800', '1505200073', 1),
(47, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1619548200', '1505200073', 1),
(48, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1622140200', '1505200073', 1),
(49, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1624818600', '1505200073', 1),
(50, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1627410600', '1505200073', 1),
(51, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1630089000', '1505200073', 1),
(52, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1632767400', '1505200073', 1),
(53, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1635359400', '1505200073', 1),
(54, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1638037800', '1505200073', 1),
(55, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1640629800', '1505200073', 1),
(56, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1643308200', '1505200073', 1),
(57, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1645986600', '1505200073', 1),
(58, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1648405800', '1505200073', 1),
(59, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1651084200', '1505200073', 1),
(60, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1653676200', '1505200073', 1),
(61, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1656354600', '1505200073', 1),
(62, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1658946600', '1505200073', 1),
(63, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1661625000', '1505200073', 1),
(64, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1664303400', '1505200073', 1),
(65, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1666895400', '1505200073', 1),
(66, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1669573800', '1505200073', 1),
(67, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1672165800', '1505200073', 1),
(68, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1674844200', '1505200073', 1),
(69, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1677522600', '1505200073', 1),
(70, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1679941800', '1505200073', 1),
(71, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1682620200', '1505200073', 1),
(72, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1685212200', '1505200073', 1),
(73, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1687890600', '1505200073', 1),
(74, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1690482600', '1505200073', 1),
(75, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1693161000', '1505200073', 1),
(76, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1695839400', '1505200073', 1),
(77, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1698431400', '1505200073', 1),
(78, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1701109800', '1505200073', 1),
(79, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1703701800', '1505200073', 1),
(80, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1706380200', '1505200073', 1),
(81, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1709058600', '1505200073', 1),
(82, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1711564200', '1505200073', 1),
(83, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1714242600', '1505200073', 1),
(84, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1716834600', '1505200073', 1),
(85, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1719513000', '1505200073', 1),
(86, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1722105000', '1505200073', 1),
(87, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1724783400', '1505200073', 1),
(88, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1727461800', '1505200073', 1),
(89, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1730053800', '1505200073', 1),
(90, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1732732200', '1505200073', 1),
(91, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1735324200', '1505200073', 1),
(92, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1738002600', '1505200073', 1),
(93, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1740681000', '1505200073', 1),
(94, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1743100200', '1505200073', 1),
(95, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1745778600', '1505200073', 1),
(96, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1748370600', '1505200073', 1),
(97, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1751049000', '1505200073', 1),
(98, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1753641000', '1505200073', 1),
(99, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1756319400', '1505200073', 1),
(100, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1758997800', '1505200073', 1),
(101, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1761589800', '1505200073', 1),
(102, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1764268200', '1505200073', 1),
(103, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1766860200', '1505200073', 1),
(104, 46, 4, '15', '15', '1505154600', 'cash', 'EMI received from account no. 123456', '1769538600', '1505200073', 1),
(105, 46, 3, '590', '590', '1505154600', 'cash', 'EMI received from account no. 123456', '1509474600', '1505200107', 1);

-- --------------------------------------------------------

--
-- Table structure for table `loan_types`
--

CREATE TABLE `loan_types` (
  `loan_type_id` int(11) NOT NULL,
  `loan_title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `loan_slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `loan_types`
--

INSERT INTO `loan_types` (`loan_type_id`, `loan_title`, `loan_slug`) VALUES
(0, 'Personal', 'personal');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notify_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `notify_date` datetime NOT NULL,
  `notify_read` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `notify_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notify_id`, `client_id`, `description`, `notify_date`, `notify_read`, `notify_type`) VALUES
(1, 17, 'Dear Mostafa Kamal , Tk50 has been saved to your General Saving account.  Current Savings Tk710', '0000-00-00 00:00:00', 'N', 'Notification'),
(2, 17, 'Dear Mostafa Kamal, You EMI Tk144 has been posted successfully. Current due Tk144, Pending  EMI 1', '0000-00-00 00:00:00', 'N', 'Notification'),
(3, 30, 'Dear Mostafa Kamal , Tk10 has been saved to your Confidential Saving account.  Current Savings Tk10', '0000-00-00 00:00:00', 'N', 'Notification'),
(4, 30, 'Dear Mostafa Kamal , Tk20 has been saved to your General Saving account.  Current Savings Tk20', '0000-00-00 00:00:00', 'N', 'Notification'),
(5, 1, 'The Loan Does Not exits with this client id(1108121163)', '0000-00-00 00:00:00', 'Y', 'Warning'),
(6, 17, 'Dear Mostafa Kamal , Tk50 has been saved to your General Saving account.  Current Savings Tk760', '0000-00-00 00:00:00', 'N', 'Notification'),
(7, 17, 'Dear Mostafa Kamal, You EMI Tk144 has been posted successfully. Current due Tk0, Pending  EMI 0', '0000-00-00 00:00:00', 'N', 'Notification'),
(8, 30, 'Dear Mostafa Kamal , Tk10 has been saved to your Confidential Saving account.  Current Savings Tk20', '0000-00-00 00:00:00', 'N', 'Notification'),
(9, 30, 'Dear Mostafa Kamal , Tk20 has been saved to your General Saving account.  Current Savings Tk40', '0000-00-00 00:00:00', 'N', 'Notification'),
(10, 1, 'The Loan Does Not exits with this client id(1108121163)', '0000-00-00 00:00:00', 'Y', 'Warning'),
(11, 17, 'Dear Mostafa Kamal , Tk50 has been saved to your General Saving account.  Current Savings Tk810', '0000-00-00 00:00:00', 'N', 'Notification'),
(12, 17, 'Dear Mostafa Kamal, You EMI Tk144 has been posted successfully. Current due Tk6912, Pending  EMI 0', '0000-00-00 00:00:00', 'N', 'Notification'),
(13, 30, 'Dear Mostafa Kamal , Tk10 has been saved to your Confidential Saving account.  Current Savings Tk30', '0000-00-00 00:00:00', 'N', 'Notification'),
(14, 30, 'Dear Mostafa Kamal , Tk20 has been saved to your General Saving account.  Current Savings Tk60', '0000-00-00 00:00:00', 'N', 'Notification'),
(15, 1, 'The Loan Does Not exits with this client id(1108121163)', '0000-00-00 00:00:00', 'Y', 'Warning'),
(16, 17, 'Dear Mostafa Kamal , Tk50 has been saved to your General Saving account.  Current Savings Tk860', '0000-00-00 00:00:00', 'N', 'Notification'),
(17, 17, 'Dear Mostafa Kamal, You EMI Tk144 has been posted successfully. Current due Tk6912, Pending  EMI 0', '0000-00-00 00:00:00', 'N', 'Notification'),
(18, 30, 'Dear Mostafa Kamal , Tk10 has been saved to your Confidential Saving account.  Current Savings Tk40', '0000-00-00 00:00:00', 'N', 'Notification'),
(19, 30, 'Dear Mostafa Kamal , Tk20 has been saved to your General Saving account.  Current Savings Tk80', '0000-00-00 00:00:00', 'N', 'Notification'),
(20, 1, 'The Loan Does Not exits with this client id(1108121163)', '0000-00-00 00:00:00', 'Y', 'Warning'),
(21, 17, 'Dear Mostafa Kamal , Tk50 has been saved to your General Saving account.  Current Savings Tk910', '0000-00-00 00:00:00', 'N', 'Notification'),
(22, 17, 'Dear Mostafa Kamal, You EMI Tk144 has been posted successfully. Current due Tk6912, Pending  EMI 0', '0000-00-00 00:00:00', 'N', 'Notification'),
(23, 30, 'Dear Mostafa Kamal , Tk10 has been saved to your Confidential Saving account.  Current Savings Tk50', '0000-00-00 00:00:00', 'N', 'Notification'),
(24, 30, 'Dear Mostafa Kamal , Tk20 has been saved to your General Saving account.  Current Savings Tk100', '0000-00-00 00:00:00', 'N', 'Notification'),
(25, 17, 'Dear Mostafa Kamal , Tk50 has been saved to your General Saving account.  Current Savings Tk960', '0000-00-00 00:00:00', 'N', 'Notification'),
(26, 17, 'Dear Mostafa Kamal, You EMI Tk144 has been posted successfully. Current due Tk6912, Pending  EMI 0', '0000-00-00 00:00:00', 'N', 'Notification'),
(27, 30, 'Dear Mostafa Kamal , Tk10 has been saved to your Confidential Saving account.  Current Savings Tk60', '0000-00-00 00:00:00', 'N', 'Notification'),
(28, 30, 'Dear Mostafa Kamal , Tk20 has been saved to your General Saving account.  Current Savings Tk120', '0000-00-00 00:00:00', 'N', 'Notification'),
(29, 17, 'Dear Mostafa Kamal , Tk50 has been saved to your General Saving account.  Current Savings Tk1010', '0000-00-00 00:00:00', 'N', 'Notification'),
(30, 17, 'Dear Mostafa Kamal, You EMI Tk144 has been posted successfully. Current due Tk6912, Pending  EMI 0', '0000-00-00 00:00:00', 'N', 'Notification'),
(31, 30, 'Dear Mostafa Kamal , Tk10 has been saved to your Confidential Saving account.  Current Savings Tk70', '0000-00-00 00:00:00', 'N', 'Notification'),
(32, 30, 'Dear Mostafa Kamal , Tk20 has been saved to your General Saving account.  Current Savings Tk140', '0000-00-00 00:00:00', 'N', 'Notification'),
(33, 17, 'Dear Mostafa Kamal , Tk50 has been saved to your General Saving account.  Current Savings Tk1060', '0000-00-00 00:00:00', 'N', 'Notification'),
(34, 17, 'Dear Mostafa Kamal, You EMI Tk144 has been posted successfully. Current due Tk6912, Pending  EMI 0', '0000-00-00 00:00:00', 'N', 'Notification'),
(35, 30, 'Dear Mostafa Kamal , Tk10 has been saved to your Confidential Saving account.  Current Savings Tk80', '0000-00-00 00:00:00', 'N', 'Notification'),
(36, 30, 'Dear Mostafa Kamal , Tk20 has been saved to your General Saving account.  Current Savings Tk160', '0000-00-00 00:00:00', 'N', 'Notification'),
(37, 1, 'Dear Mostafa Kamal You have been added with http://www.bgbss.net. You account ID : 1108121159,  username: mkmonirc, Password: 123456', '0000-00-00 00:00:00', 'Y', 'Notification'),
(38, 31, 'Dear Mostafa Kamal , Tk50 has been saved to your General Saving account.  Current Savings Tk50', '0000-00-00 00:00:00', 'Y', 'Notification'),
(39, 31, 'Dear Mostafa Kamal, You EMI Tk120 has been posted successfully. Current due Tk5640, Pending  EMI 47', '0000-00-00 00:00:00', 'Y', 'Notification'),
(40, 31, 'Dear Mostafa Kamal , Tk50 has been saved to your General Saving account.  Current Savings Tk100', '0000-00-00 00:00:00', 'Y', 'Notification'),
(41, 31, 'Dear Mostafa Kamal , Tk50 has been saved to your General Saving account.  Current Savings Tk150', '0000-00-00 00:00:00', 'Y', 'Notification'),
(42, 31, 'Dear Mostafa Kamal , Tk50 has been saved to your General Saving account.  Current Savings Tk200', '0000-00-00 00:00:00', 'Y', 'Notification'),
(43, 31, 'Dear Mostafa Kamal, You EMI Tk120 has been posted successfully. Current due Tk5520, Pending  EMI 46', '0000-00-00 00:00:00', 'Y', 'Notification'),
(44, 1, 'Dear Mostafa Kamal You have been added with http://www.bgbss.net. You account ID : 1108121159,  username: mkmonirc, Password: samia123', '0000-00-00 00:00:00', 'Y', 'Notification'),
(45, 33, 'Dear Mostafa Kamal , Tk50 has been saved to your General Saving account.  Current Savings Tk50', '0000-00-00 00:00:00', 'N', 'Notification'),
(46, 33, 'Dear Mostafa Kamal , Tk100 has been saved to your General Saving account.  Current Savings Tk150', '0000-00-00 00:00:00', 'N', 'Notification'),
(47, 33, 'Dear Mostafa Kamal , Tk500 has been saved to your General Saving account.  Current Savings Tk650', '0000-00-00 00:00:00', 'N', 'Notification'),
(48, 33, 'Dear Mostafa Kamal , Tk100 has been charged from your account 1108121159, saving type Saving, for ', '0000-00-00 00:00:00', 'N', 'Notification'),
(49, 2, 'Dear 8 You have been added with https://dailytv.org/loanify2/. You account ID : 21,  username: anita, Password: 123456', '0000-00-00 00:00:00', 'N', 'Notification'),
(50, 3, 'Dear pooja You have been added with https://dailytv.org/loanify2/. You account ID : 50,  username: pooja, Password: 456123', '0000-00-00 00:00:00', 'N', 'Notification'),
(51, 4, 'Dear kajal You have been added with https://dailytv.org/loanify2/. You account ID : 44,  username: admin@gmail.com, Password: 123456789', '0000-00-00 00:00:00', 'N', 'Notification'),
(52, 5, 'Dear komal You have been added with https://dailytv.org/loanify2/. You account ID : 21551,  username: somya, Password: 88888888', '0000-00-00 00:00:00', 'N', 'Notification'),
(53, 6, 'Dear anokhi You have been added with https://dailytv.org/loanify2/. You account ID : 55,  username: shreeya, Password: 789456123', '0000-00-00 00:00:00', 'N', 'Notification'),
(54, 7, 'Dear ashok You have been added with https://dailytv.org/loanify2/. You account ID : 47,  username: rohni, Password: 456123', '0000-00-00 00:00:00', 'N', 'Notification'),
(55, 8, 'Dear Apeksha Lad You have been added with https://dailytv.org/loanify2/. You account ID : 123456,  username: apeksha, Password: 123456', '0000-00-00 00:00:00', 'N', 'Notification'),
(56, 46, 'Dear Apeksha Lad , Tk200 has been saved to your General Saving account.  Current Savings Tk200', '0000-00-00 00:00:00', 'N', 'Notification'),
(57, 46, 'Dear Apeksha Lad , Tk300 has been saved to your Confidential Saving account.  Current Savings Tk300', '0000-00-00 00:00:00', 'N', 'Notification');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `option_id` int(11) NOT NULL,
  `option_key` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `option_value` text COLLATE utf8_unicode_ci NOT NULL,
  `logo_width` int(11) NOT NULL,
  `logo_height` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`option_id`, `option_key`, `option_value`, `logo_width`, `logo_height`) VALUES
(1, 'user_name', 'codeforhost', 0, 0),
(2, 'password', 'PpOPOc4J@dbjchjd', 0, 0),
(3, 'welcome_message_sms', 'Dear [NAME] You have been added with [SITE_NAME]. You account ID : [ACCOUNT_ID],  username: [USER_NAME], Password: [PASSWORD]', 0, 0),
(4, 'addloan_message_sms', 'Dear [NAME], Your loan of Tk[AMOUNT] has been approved!  Installment count [INSTALLEMENT_COUNT], Payment term [TERM], EMI Tk[EMI_AMOUNT].', 0, 0),
(5, 'pay_emi_message_sms', 'Dear [NAME], You EMI Tk[AMOUNT] has been posted successfully. Current due Tk[DUE_AMOUNT], Pending  EMI [DUE_EMI_COUNT]', 0, 0),
(6, 'charge_message_sms', 'Dear [NAME], Tk[AMOUNT] has been charged from your account [ACCOUNT_ID], saving type [SAVING_TYPE], for [REASON]', 0, 0),
(7, 'deposit_message_sms', 'Dear [NAME], Tk[AMOUNT] has been saved to your [SAVING_TYPE] account.  Current Savings Tk[SAVING_AMOUNT]', 0, 0),
(8, 'withdraw_message_sms', 'Dear [NAME], Tk[AMOUNT] has been withdraw from your [SAVING_TYPE] account.  Current balance Tk[SAVING_AMOUNT]', 0, 0),
(9, 'getting_interest_message_sms', 'Dear [NAME], You got interest amount Tk[AMOUNT] for saving [SAVING_TYPE]. Current Balance Tk[SAVING_AMOUNT]', 0, 0),
(10, 'company_title', 'DailyTV LLC', 0, 0),
(11, 'company_address', 'Borochala\r\nDakatia', 0, 0),
(12, 'company_mob', '+8801720188883', 0, 0),
(13, 'company_tel', '+8801720188883', 0, 0),
(14, 'company_website', 'https://dailytv.org/loanify2/', 0, 0),
(15, 'company_email', 'baical749@gmail.com', 0, 0),
(25, 'website_logo', 'infosys-logo-1493900679.jpg', 0, 0),
(19, 'notify_install_count_sms', 'Dear [NAME], Client [CLIENT_NAME], account no [CLIENT_ACC_NO] has paid [INSTALLMENTS_COUNT] installment, due [DUE_INSTALLMENTS] installment.', 0, 0),
(20, 'custum_text', 'Infosys', 0, 0),
(27, 'fevicon_icon', 'infosys-logo-1493900679-1494054984.jpg', 0, 0),
(28, 'logo_width', '82px', 0, 0),
(29, 'logo_height', 'auto', 0, 0),
(30, 'sender_id', 'CodeForHost', 0, 0),
(31, 'day_off', '01/05/2017, 01/15/2017, 01/17/2017, 01/20/2017, 01/24/2017, 01/25/2017, 02/05/2017, 02/10/2017, 02/17/2017, 02/18/2017, 02/23/2017, 02/24/2017, 03/05/2017, 04/05/2017, 05/05/2017, 06/05/2017, 07/05/2017, 08/05/2017, 09/05/2017, 10/05/2017, 11/05/2017, 12/05/2017', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `other_deposits`
--

CREATE TABLE `other_deposits` (
  `otdep_id` int(11) NOT NULL,
  `cap_acc_id` int(11) NOT NULL,
  `profit_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `profit_value` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `profit_amount` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `deposit_amount` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `total_profit` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(255) NOT NULL,
  `post_title` varchar(255) NOT NULL,
  `post_content` text NOT NULL,
  `post_date` int(11) NOT NULL,
  `post_update_date` int(11) NOT NULL,
  `post_type` varchar(255) NOT NULL,
  `post_status` varchar(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `post_slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `post_images`
--

CREATE TABLE `post_images` (
  `image_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `post_image_name` varchar(255) DEFAULT NULL,
  `post_image_path` varchar(255) DEFAULT NULL,
  `post_image_type` varchar(255) DEFAULT NULL,
  `post_image_alt` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `post_meta`
--

CREATE TABLE `post_meta` (
  `meta_id` int(255) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` text NOT NULL,
  `post_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `update_file_id`
--

CREATE TABLE `update_file_id` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `upload_error`
--

CREATE TABLE `upload_error` (
  `upload_id` int(11) NOT NULL,
  `file_name` int(11) NOT NULL,
  `description` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_pass` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_type` varchar(50) NOT NULL,
  `user_registered_date` int(11) NOT NULL,
  `user_update_date` int(11) NOT NULL,
  `user_link_id` varchar(250) NOT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `user_code` varchar(50) NOT NULL,
  `user_verified` enum('Y','N') NOT NULL DEFAULT 'N',
  `user_status` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `branch_id`, `user_name`, `user_pass`, `user_email`, `user_type`, `user_registered_date`, `user_update_date`, `user_link_id`, `designation_id`, `user_code`, `user_verified`, `user_status`) VALUES
(1, 0, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@gmail.com', 'admin', 1394868509, 1481868426, '', 2, '', 'Y', 'Y'),
(44, 0, 'vivek', 'e10adc3949ba59abbe56e057f20f883e', 'vivek@gmail.com', 'admin', 1504016651, 1504016651, '', 2, '', 'Y', 'Y'),
(45, 0, 'shukla', 'e10adc3949ba59abbe56e057f20f883e', 'shukla@gmail.com', 'manager', 1504016750, 1504016750, '', 2, '', 'Y', 'Y'),
(46, 1, 'apeksha', 'e10adc3949ba59abbe56e057f20f883e', '', 'user', 1504016818, 1504016818, '', NULL, '', 'Y', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `user_meta`
--

CREATE TABLE `user_meta` (
  `meta_id` int(255) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` varchar(255) NOT NULL,
  `user_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_meta`
--

INSERT INTO `user_meta` (`meta_id`, `meta_key`, `meta_value`, `user_id`) VALUES
(309, 'first_name', 'vivek', 44),
(310, 'last_name', 'shukla', 44),
(311, 'mobile_no', '7894567854', 44),
(312, 'acc_no', '789456', 44),
(313, 'address', '', 44),
(314, 'photo_url', '', 44),
(315, 'id_url', '', 44),
(316, 'first_name', 'vivekanad', 45),
(317, 'last_name', 'shukla', 45),
(318, 'mobile_no', '546213255', 45),
(319, 'acc_no', '54632158', 45),
(320, 'address', '', 45),
(321, 'photo_url', '', 45),
(322, 'id_url', '', 45),
(323, 'first_name', 'Apeksha Lad', 46),
(324, 'mobile_no', '7895454', 46),
(325, 'address', '', 46),
(326, 'photo_url', '', 46),
(327, 'id_url', '', 46),
(328, 'client_ID', '8', 46),
(329, 'acc_no', '123456', 46),
(330, 'branch_id', '1', 46);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_info`
--

CREATE TABLE `vehicle_info` (
  `vehicle_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `registration_no` varchar(50) NOT NULL,
  `engine_no` varchar(50) NOT NULL,
  `chesis_no` varchar(50) NOT NULL,
  `modal` varchar(50) NOT NULL,
  `validity` varchar(50) NOT NULL,
  `policy_company_name` varchar(50) NOT NULL,
  `policy_no` varchar(50) NOT NULL,
  `policy_grade` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `capital_account`
--
ALTER TABLE `capital_account`
  ADD PRIMARY KEY (`cap_acc_id`);

--
-- Indexes for table `capital_meta`
--
ALTER TABLE `capital_meta`
  ADD PRIMARY KEY (`cm_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `cat_images`
--
ALTER TABLE `cat_images`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_ID`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_ID`);

--
-- Indexes for table `designation`
--
ALTER TABLE `designation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dyna_keys`
--
ALTER TABLE `dyna_keys`
  ADD PRIMARY KEY (`key_id`);

--
-- Indexes for table `expense_category`
--
ALTER TABLE `expense_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guarantors`
--
ALTER TABLE `guarantors`
  ADD PRIMARY KEY (`guarantor_ID`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `loan_types`
--
ALTER TABLE `loan_types`
  ADD PRIMARY KEY (`loan_type_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notify_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `upload_error`
--
ALTER TABLE `upload_error`
  ADD PRIMARY KEY (`upload_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_meta`
--
ALTER TABLE `user_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `capital_account`
--
ALTER TABLE `capital_account`
  MODIFY `cap_acc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;
--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `expense_category`
--
ALTER TABLE `expense_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `loan_payments`
--
ALTER TABLE `loan_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;
--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notify_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `upload_error`
--
ALTER TABLE `upload_error`
  MODIFY `upload_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `user_meta`
--
ALTER TABLE `user_meta`
  MODIFY `meta_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=331;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
