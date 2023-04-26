-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2023 at 07:14 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `newmvc-sahil-solanki`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(20) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `name`, `email`, `password`, `status`, `created_at`, `updated_at`) VALUES
(10, '1', 's@sas.sa', '122121', 1, '2023-04-24 15:22:23', '2023-04-24 15:23:08');

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `brand_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`brand_id`, `name`, `description`, `image`, `created_at`, `updated_at`) VALUES
(4, 'sas', '1212', '', '2023-04-18 16:05:29', '2023-04-19 14:29:24');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `shipping_method_id` int(11) NOT NULL,
  `shipping_amount` decimal(10,2) NOT NULL,
  `tax_percent` int(2) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `cart_item_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `parent_id`, `path`, `name`, `status`, `description`, `created_at`, `updated_at`) VALUES
(1, 0, '1', 'Root', 1, '', '2023-04-03 00:00:00', NULL),
(162, 1, '1-162', '', 2, '', '2023-04-25 11:04:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category_varchar`
--

CREATE TABLE `category_varchar` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` tinyint(4) NOT NULL,
  `mobile` bigint(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `shipping_address_id` int(11) DEFAULT NULL,
  `billing_address_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `first_name`, `last_name`, `email`, `gender`, `mobile`, `status`, `shipping_address_id`, `billing_address_id`, `created_at`, `updated_at`) VALUES
(53, 'Customer2', 'asas', 'asa@asasa', 1, 2121212, 2, 19, 20, '2023-04-11 17:28:38', '2023-04-20 14:45:55'),
(70, 'customer1', 'sas', 'sasas@sas', 0, 0, 2, 45, 44, '2023-04-12 14:54:37', '2023-04-20 14:45:49');

-- --------------------------------------------------------

--
-- Table structure for table `customer_address`
--

CREATE TABLE `customer_address` (
  `address_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `zip_code` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer_address`
--

INSERT INTO `customer_address` (`address_id`, `customer_id`, `address`, `city`, `state`, `country`, `zip_code`, `created_at`, `updated_at`) VALUES
(19, 53, '2', '2', '2', '2', 2, '2023-04-20 14:45:56', '0000-00-00 00:00:00'),
(20, 53, '1', '1', '1', '1', 1, '2023-04-20 14:45:56', '0000-00-00 00:00:00'),
(44, 70, '1', '1', '11', '66666', 1, '2023-04-20 14:45:49', '0000-00-00 00:00:00'),
(45, 70, '1', '1', '11', '55555', 1, '2023-04-20 14:45:49', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `eav_attribute`
--

CREATE TABLE `eav_attribute` (
  `attribute_id` int(11) NOT NULL,
  `entity_type_id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `backend_type` varchar(20) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `backend_model` varchar(255) NOT NULL,
  `input_type` varchar(20) NOT NULL,
  `source_model` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `eav_attribute`
--

INSERT INTO `eav_attribute` (`attribute_id`, `entity_type_id`, `code`, `backend_type`, `name`, `status`, `backend_model`, `input_type`, `source_model`) VALUES
(3, 2, 'style', 'int', 'Style', 1, '', 'select', ''),
(4, 2, 'short_desc', 'text', 'Short description', 1, '', 'textarea', ''),
(5, 2, 'gender', 'int', 'Gender', 1, '', 'radio', ''),
(17, 6, 'name', 'varchar', 'Name', 1, '', 'text', 'Eav_Attribute_Option_Source'),
(18, 7, 'color', 'int', 'Color', 1, '', 'select', 'Eav_Attribute_Option_Source'),
(19, 7, 'style', 'varchar', 'Style', 1, '', 'multiselect', ''),
(20, 7, 'gender', 'int', 'Gender', 1, '', 'radio', ''),
(21, 7, 'name', 'varchar', 'Name', 1, '', 'text', ''),
(22, 7, 'price', 'decimal', 'Price', 1, '', 'text', ''),
(23, 7, 'cost', 'decimal', 'Cost', 1, '', 'text', ''),
(24, 7, 'description', 'text', 'Description', 1, '', 'textarea', ''),
(25, 2, 'n', 'varchar', 'N', 1, '', 'text', ''),
(26, 7, 'checkbox', 'varchar', 'Checkbox', 1, '', 'checkbox', ''),
(27, 8, 'name', 'varchar', 'Name', 1, '', 'text', ''),
(28, 9, 'name', 'varchar', 'Name', 1, '', 'text', '');

-- --------------------------------------------------------

--
-- Table structure for table `eav_attribute_option`
--

CREATE TABLE `eav_attribute_option` (
  `option_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `position` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `eav_attribute_option`
--

INSERT INTO `eav_attribute_option` (`option_id`, `name`, `attribute_id`, `position`) VALUES
(50, 'Male', 5, 1),
(55, 'Female', 5, 2),
(73, '1', 3, 1),
(86, 'Green', 18, 2),
(87, 'Red', 18, 1),
(88, 'Blue', 18, 3),
(89, 'Traditional', 19, 3),
(90, 'Electric', 19, 2),
(91, 'Contemprory', 19, 1),
(92, 'Male', 20, 1),
(93, 'Female', 20, 2),
(94, 'checkbox2', 26, 2),
(95, 'checkbox1', 26, 1);

-- --------------------------------------------------------

--
-- Table structure for table `entity_type`
--

CREATE TABLE `entity_type` (
  `entity_type_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `entity_model` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `entity_type`
--

INSERT INTO `entity_type` (`entity_type_id`, `name`, `entity_model`) VALUES
(2, 'product', ''),
(3, 'customer', ''),
(4, 'vendor', ''),
(5, 'salesman', ''),
(6, 'category', ''),
(7, 'item', ''),
(8, 'shipping', ''),
(9, 'payment', '');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `entity_id` int(11) NOT NULL,
  `entity_type_id` int(11) NOT NULL DEFAULT 7,
  `sku` varchar(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`entity_id`, `entity_type_id`, `sku`, `status`, `created_at`, `updated_at`) VALUES
(26, 7, '22', 1, '2023-04-17 23:50:50', '2023-04-25 14:34:32');

-- --------------------------------------------------------

--
-- Table structure for table `item_decimal`
--

CREATE TABLE `item_decimal` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_decimal`
--

INSERT INTO `item_decimal` (`value_id`, `entity_id`, `attribute_id`, `value`) VALUES
(38, 26, 22, 233.00),
(39, 26, 23, 50.00);

-- --------------------------------------------------------

--
-- Table structure for table `item_int`
--

CREATE TABLE `item_int` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_int`
--

INSERT INTO `item_int` (`value_id`, `entity_id`, `attribute_id`, `value`) VALUES
(97, 26, 18, 86),
(98, 26, 20, 93);

-- --------------------------------------------------------

--
-- Table structure for table `item_text`
--

CREATE TABLE `item_text` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_text`
--

INSERT INTO `item_text` (`value_id`, `entity_id`, `attribute_id`, `value`) VALUES
(46, 26, 24, '');

-- --------------------------------------------------------

--
-- Table structure for table `item_varchar`
--

CREATE TABLE `item_varchar` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_varchar`
--

INSERT INTO `item_varchar` (`value_id`, `entity_id`, `attribute_id`, `value`) VALUES
(112, 26, 17, '13'),
(118, 26, 19, '90,91'),
(119, 26, 21, 'item 3');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_mobile_no` int(10) NOT NULL,
  `order_total` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `shipping_method_id` int(11) NOT NULL,
  `shipping_amount` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_decimal`
--

CREATE TABLE `payment_decimal` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_int`
--

CREATE TABLE `payment_int` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `payment_method_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`payment_method_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(8, 'Credit/debit card', 1, '2023-04-25 13:24:58', NULL),
(9, 'Upi', 1, '2023-04-25 13:25:08', NULL),
(10, 'cash', 2, '2023-04-25 13:25:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_text`
--

CREATE TABLE `payment_text` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_varchar`
--

CREATE TABLE `payment_varchar` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_varchar`
--

INSERT INTO `payment_varchar` (`value_id`, `entity_id`, `attribute_id`, `value`) VALUES
(10, 8, 28, ''),
(11, 9, 28, ''),
(12, 10, 28, '');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(5) NOT NULL DEFAULT 100,
  `description` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 2,
  `color` tinyint(4) NOT NULL,
  `material` tinyint(4) NOT NULL,
  `small_id` int(11) DEFAULT NULL,
  `thumb_id` int(11) DEFAULT NULL,
  `base_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `name`, `sku`, `cost`, `price`, `quantity`, `description`, `status`, `color`, `material`, `small_id`, `thumb_id`, `base_id`, `created_at`, `updated_at`) VALUES
(4, '111', 'sa', 21.00, 212.00, 12, '121', 1, 1, 1, 16, 11, 16, '2023-04-12 12:23:17', '2023-04-25 22:27:07'),
(11, 'sahil', '', 0.00, 0.00, 0, '', 1, 2, 2, 0, 0, 0, '2023-04-24 10:59:49', '2023-04-24 11:00:07'),
(13, 'asdas', '', 0.00, 0.00, 0, '', 1, 1, 1, 0, 0, 0, '2023-04-25 18:50:31', '2023-04-25 22:27:01'),
(14, 'sasas', '', 0.00, 0.00, 0, '', 1, 1, 1, 0, 0, 0, '2023-04-25 22:26:30', '2023-04-25 22:26:56');

-- --------------------------------------------------------

--
-- Table structure for table `product_decimal`
--

CREATE TABLE `product_decimal` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_int`
--

CREATE TABLE `product_int` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product_int`
--

INSERT INTO `product_int` (`value_id`, `entity_id`, `attribute_id`, `value`) VALUES
(9, 4, 3, 73),
(10, 4, 5, 50),
(11, 11, 3, 73),
(14, 13, 3, 73),
(15, 14, 3, 73);

-- --------------------------------------------------------

--
-- Table structure for table `product_media`
--

CREATE TABLE `product_media` (
  `media_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 2,
  `gallery` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product_media`
--

INSERT INTO `product_media` (`media_id`, `product_id`, `name`, `image`, `status`, `gallery`, `created_at`) VALUES
(11, 4, 'saas', '11.jpg', 2, 0, '2023-04-12 12:23:38'),
(16, 4, 'pic 2', '16.jpg', 1, 1, '2023-04-14 23:58:57');

-- --------------------------------------------------------

--
-- Table structure for table `product_text`
--

CREATE TABLE `product_text` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product_text`
--

INSERT INTO `product_text` (`value_id`, `entity_id`, `attribute_id`, `value`) VALUES
(2, 4, 4, '12'),
(3, 11, 4, ''),
(6, 13, 4, ''),
(7, 14, 4, '');

-- --------------------------------------------------------

--
-- Table structure for table `product_varchar`
--

CREATE TABLE `product_varchar` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product_varchar`
--

INSERT INTO `product_varchar` (`value_id`, `entity_id`, `attribute_id`, `value`) VALUES
(4, 4, 25, '21'),
(5, 11, 25, ''),
(8, 13, 25, ''),
(9, 14, 25, '');

-- --------------------------------------------------------

--
-- Table structure for table `quote`
--

CREATE TABLE `quote` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_total` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `shipping_method_id` int(11) NOT NULL,
  `shipping_amount` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quote`
--

INSERT INTO `quote` (`order_id`, `customer_id`, `order_total`, `status`, `payment_method_id`, `shipping_method_id`, `shipping_amount`, `created_at`, `updated_at`) VALUES
(2, 70, 0.00, 0, 9, 15, 50.00, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 53, 0.00, 0, 2, 5, 1222.00, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 0, 0.00, 0, 0, 5, 1222.00, '0000-00-00 00:00:00', NULL),
(5, 0, 0.00, 0, 0, 5, 1222.00, '0000-00-00 00:00:00', NULL),
(6, 0, 0.00, 0, 8, 0, 0.00, '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quote_address`
--

CREATE TABLE `quote_address` (
  `address_id` int(11) NOT NULL,
  `customer_address_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `zip_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quote_address`
--

INSERT INTO `quote_address` (`address_id`, `customer_address_id`, `address`, `city`, `state`, `country`, `zip_code`) VALUES
(30, 44, '1', '1', '11', '66666', 1),
(31, 45, '1', '1', '11', '55555', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quote_item`
--

CREATE TABLE `quote_item` (
  `item_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(3) NOT NULL,
  `discount` int(3) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quote_item`
--

INSERT INTO `quote_item` (`item_id`, `product_id`, `quantity`, `discount`, `price`) VALUES
(22, 4, 10, 100, 212.00),
(23, 9, 12, 0, 20.00);

-- --------------------------------------------------------

--
-- Table structure for table `salesman`
--

CREATE TABLE `salesman` (
  `salesman_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` tinyint(4) NOT NULL,
  `mobile` bigint(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `company` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `salesman`
--

INSERT INTO `salesman` (`salesman_id`, `first_name`, `last_name`, `email`, `gender`, `mobile`, `status`, `company`, `created_at`, `updated_at`) VALUES
(1, 'raju', 'patel', 'r@gmail.com', 1, 9043782388, 1, 'raju enterprice', '2023-03-27 12:33:27', NULL),
(2, 'hina', 'khan', 'h@gmail.com', 2, 9058376809, 1, 'india express', '2023-03-27 12:34:27', '2023-03-28 11:38:57');

-- --------------------------------------------------------

--
-- Table structure for table `salesman_address`
--

CREATE TABLE `salesman_address` (
  `address_id` int(11) NOT NULL,
  `salesman_id` int(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `zip_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `salesman_address`
--

INSERT INTO `salesman_address` (`address_id`, `salesman_id`, `address`, `city`, `state`, `country`, `zip_code`) VALUES
(1, 1, 'vadaj', 'ahmedabad', 'gujarat', 'india', 380667),
(2, 2, 'india', 'ahmedabad', 'gujarat', 'india', 383909);

-- --------------------------------------------------------

--
-- Table structure for table `salesman_price`
--

CREATE TABLE `salesman_price` (
  `entity_id` int(11) NOT NULL,
  `salesman_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `salesman_price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `salesman_price`
--

INSERT INTO `salesman_price` (`entity_id`, `salesman_id`, `product_id`, `salesman_price`) VALUES
(25, 2, 4, 12);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_decimal`
--

CREATE TABLE `shipping_decimal` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_int`
--

CREATE TABLE `shipping_int` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_method`
--

CREATE TABLE `shipping_method` (
  `shipping_method_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `shipping_method`
--

INSERT INTO `shipping_method` (`shipping_method_id`, `name`, `amount`, `status`, `created_at`, `updated_at`) VALUES
(14, 'Normal', 0.00, 1, '2023-04-25 13:23:54', NULL),
(15, 'Gold', 50.00, 1, '2023-04-25 13:24:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_text`
--

CREATE TABLE `shipping_text` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_varchar`
--

CREATE TABLE `shipping_varchar` (
  `value_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_varchar`
--

INSERT INTO `shipping_varchar` (`value_id`, `entity_id`, `attribute_id`, `value`) VALUES
(18, 14, 27, ''),
(19, 15, 27, '');

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `vendor_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` tinyint(4) NOT NULL,
  `mobile` bigint(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `company` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`vendor_id`, `first_name`, `last_name`, `email`, `gender`, `mobile`, `status`, `company`, `created_at`, `updated_at`) VALUES
(1, 'vijay', 'thakor', 'v@gmail.com', 1, 7858346799, 1, 'vijay sales', '2023-03-27 12:36:41', '2023-04-10 19:58:45');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_address`
--

CREATE TABLE `vendor_address` (
  `address_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `zip_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `vendor_address`
--

INSERT INTO `vendor_address` (`address_id`, `vendor_id`, `address`, `city`, `state`, `country`, `zip_code`) VALUES
(1, 1, 'parasmani flat', 'vijay nagar', 'gujarat', 'india', 332111);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `shipping_method_id` (`shipping_method_id`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `category_varchar`
--
ALTER TABLE `category_varchar`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `customer_ibfk_1` (`shipping_address_id`),
  ADD KEY `customer_ibfk_2` (`billing_address_id`);

--
-- Indexes for table `customer_address`
--
ALTER TABLE `customer_address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `eav_attribute`
--
ALTER TABLE `eav_attribute`
  ADD PRIMARY KEY (`attribute_id`),
  ADD KEY `entity_type_id` (`entity_type_id`);

--
-- Indexes for table `eav_attribute_option`
--
ALTER TABLE `eav_attribute_option`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `entity_type`
--
ALTER TABLE `entity_type`
  ADD PRIMARY KEY (`entity_type_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`entity_id`);

--
-- Indexes for table `item_decimal`
--
ALTER TABLE `item_decimal`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `item_int`
--
ALTER TABLE `item_int`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `item_text`
--
ALTER TABLE `item_text`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `item_varchar`
--
ALTER TABLE `item_varchar`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `payment_decimal`
--
ALTER TABLE `payment_decimal`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `payment_int`
--
ALTER TABLE `payment_int`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`payment_method_id`);

--
-- Indexes for table `payment_text`
--
ALTER TABLE `payment_text`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `payment_varchar`
--
ALTER TABLE `payment_varchar`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_decimal`
--
ALTER TABLE `product_decimal`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `product_int`
--
ALTER TABLE `product_int`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `product_media`
--
ALTER TABLE `product_media`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_text`
--
ALTER TABLE `product_text`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `product_varchar`
--
ALTER TABLE `product_varchar`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `quote`
--
ALTER TABLE `quote`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `quote_address`
--
ALTER TABLE `quote_address`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `quote_item`
--
ALTER TABLE `quote_item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `salesman`
--
ALTER TABLE `salesman`
  ADD PRIMARY KEY (`salesman_id`);

--
-- Indexes for table `salesman_address`
--
ALTER TABLE `salesman_address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `salesman_id` (`salesman_id`);

--
-- Indexes for table `salesman_price`
--
ALTER TABLE `salesman_price`
  ADD PRIMARY KEY (`entity_id`),
  ADD KEY `salesman_id` (`salesman_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `shipping_decimal`
--
ALTER TABLE `shipping_decimal`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `shipping_int`
--
ALTER TABLE `shipping_int`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `shipping_method`
--
ALTER TABLE `shipping_method`
  ADD PRIMARY KEY (`shipping_method_id`);

--
-- Indexes for table `shipping_text`
--
ALTER TABLE `shipping_text`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `shipping_varchar`
--
ALTER TABLE `shipping_varchar`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `entity_id` (`entity_id`,`attribute_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`vendor_id`);

--
-- Indexes for table `vendor_address`
--
ALTER TABLE `vendor_address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `category_varchar`
--
ALTER TABLE `category_varchar`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `customer_address`
--
ALTER TABLE `customer_address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `eav_attribute`
--
ALTER TABLE `eav_attribute`
  MODIFY `attribute_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `eav_attribute_option`
--
ALTER TABLE `eav_attribute_option`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `entity_type`
--
ALTER TABLE `entity_type`
  MODIFY `entity_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `entity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `item_decimal`
--
ALTER TABLE `item_decimal`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `item_int`
--
ALTER TABLE `item_int`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `item_text`
--
ALTER TABLE `item_text`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `item_varchar`
--
ALTER TABLE `item_varchar`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `payment_decimal`
--
ALTER TABLE `payment_decimal`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_int`
--
ALTER TABLE `payment_int`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `payment_method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payment_text`
--
ALTER TABLE `payment_text`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_varchar`
--
ALTER TABLE `payment_varchar`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product_decimal`
--
ALTER TABLE `product_decimal`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_int`
--
ALTER TABLE `product_int`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `product_media`
--
ALTER TABLE `product_media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product_text`
--
ALTER TABLE `product_text`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_varchar`
--
ALTER TABLE `product_varchar`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `quote`
--
ALTER TABLE `quote`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `quote_address`
--
ALTER TABLE `quote_address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `quote_item`
--
ALTER TABLE `quote_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `salesman`
--
ALTER TABLE `salesman`
  MODIFY `salesman_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `salesman_address`
--
ALTER TABLE `salesman_address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `salesman_price`
--
ALTER TABLE `salesman_price`
  MODIFY `entity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `shipping_decimal`
--
ALTER TABLE `shipping_decimal`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_int`
--
ALTER TABLE `shipping_int`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_method`
--
ALTER TABLE `shipping_method`
  MODIFY `shipping_method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `shipping_text`
--
ALTER TABLE `shipping_text`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_varchar`
--
ALTER TABLE `shipping_varchar`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vendor_address`
--
ALTER TABLE `vendor_address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category_varchar`
--
ALTER TABLE `category_varchar`
  ADD CONSTRAINT `category_varchar_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `category_varchar_ibfk_2` FOREIGN KEY (`entity_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`shipping_address_id`) REFERENCES `customer_address` (`address_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `customer_ibfk_2` FOREIGN KEY (`billing_address_id`) REFERENCES `customer_address` (`address_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `customer_address`
--
ALTER TABLE `customer_address`
  ADD CONSTRAINT `customer_address_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `eav_attribute`
--
ALTER TABLE `eav_attribute`
  ADD CONSTRAINT `eav_attribute_ibfk_1` FOREIGN KEY (`entity_type_id`) REFERENCES `entity_type` (`entity_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `eav_attribute_option`
--
ALTER TABLE `eav_attribute_option`
  ADD CONSTRAINT `eav_attribute_option_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `item_decimal`
--
ALTER TABLE `item_decimal`
  ADD CONSTRAINT `item_decimal_ibfk_1` FOREIGN KEY (`entity_id`) REFERENCES `item` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_decimal_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `item_int`
--
ALTER TABLE `item_int`
  ADD CONSTRAINT `item_int_ibfk_1` FOREIGN KEY (`entity_id`) REFERENCES `item` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_int_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `item_text`
--
ALTER TABLE `item_text`
  ADD CONSTRAINT `item_text_ibfk_1` FOREIGN KEY (`entity_id`) REFERENCES `item` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_text_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `item_varchar`
--
ALTER TABLE `item_varchar`
  ADD CONSTRAINT `item_varchar_ibfk_1` FOREIGN KEY (`entity_id`) REFERENCES `item` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_varchar_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_decimal`
--
ALTER TABLE `payment_decimal`
  ADD CONSTRAINT `payment_decimal_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_decimal_ibfk_2` FOREIGN KEY (`entity_id`) REFERENCES `payment_method` (`payment_method_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_int`
--
ALTER TABLE `payment_int`
  ADD CONSTRAINT `payment_int_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_int_ibfk_2` FOREIGN KEY (`entity_id`) REFERENCES `payment_method` (`payment_method_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_text`
--
ALTER TABLE `payment_text`
  ADD CONSTRAINT `payment_text_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_text_ibfk_2` FOREIGN KEY (`entity_id`) REFERENCES `payment_method` (`payment_method_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_varchar`
--
ALTER TABLE `payment_varchar`
  ADD CONSTRAINT `payment_varchar_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_varchar_ibfk_2` FOREIGN KEY (`entity_id`) REFERENCES `payment_method` (`payment_method_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_decimal`
--
ALTER TABLE `product_decimal`
  ADD CONSTRAINT `product_decimal_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_decimal_ibfk_2` FOREIGN KEY (`entity_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_int`
--
ALTER TABLE `product_int`
  ADD CONSTRAINT `product_int_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_int_ibfk_2` FOREIGN KEY (`entity_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_media`
--
ALTER TABLE `product_media`
  ADD CONSTRAINT `product_media_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_text`
--
ALTER TABLE `product_text`
  ADD CONSTRAINT `product_text_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_text_ibfk_2` FOREIGN KEY (`entity_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_varchar`
--
ALTER TABLE `product_varchar`
  ADD CONSTRAINT `product_varchar_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_varchar_ibfk_2` FOREIGN KEY (`entity_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `salesman_address`
--
ALTER TABLE `salesman_address`
  ADD CONSTRAINT `salesman_address_ibfk_1` FOREIGN KEY (`salesman_id`) REFERENCES `salesman` (`salesman_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `salesman_price`
--
ALTER TABLE `salesman_price`
  ADD CONSTRAINT `salesman_price_ibfk_2` FOREIGN KEY (`salesman_id`) REFERENCES `salesman` (`salesman_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `salesman_price_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shipping_decimal`
--
ALTER TABLE `shipping_decimal`
  ADD CONSTRAINT `shipping_decimal_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shipping_decimal_ibfk_2` FOREIGN KEY (`entity_id`) REFERENCES `shipping_method` (`shipping_method_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shipping_int`
--
ALTER TABLE `shipping_int`
  ADD CONSTRAINT `shipping_int_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shipping_int_ibfk_2` FOREIGN KEY (`entity_id`) REFERENCES `shipping_method` (`shipping_method_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shipping_text`
--
ALTER TABLE `shipping_text`
  ADD CONSTRAINT `shipping_text_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shipping_text_ibfk_2` FOREIGN KEY (`entity_id`) REFERENCES `shipping_method` (`shipping_method_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shipping_varchar`
--
ALTER TABLE `shipping_varchar`
  ADD CONSTRAINT `shipping_varchar_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `eav_attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shipping_varchar_ibfk_2` FOREIGN KEY (`entity_id`) REFERENCES `shipping_method` (`shipping_method_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vendor_address`
--
ALTER TABLE `vendor_address`
  ADD CONSTRAINT `vendor_address_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`vendor_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
