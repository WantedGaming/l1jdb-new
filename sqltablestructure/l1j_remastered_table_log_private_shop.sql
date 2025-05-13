
-- --------------------------------------------------------

--
-- Table structure for table `log_private_shop`
--

CREATE TABLE `log_private_shop` (
  `id` int(10) NOT NULL,
  `time` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `type` varchar(45) NOT NULL,
  `shop_account` varchar(45) DEFAULT NULL,
  `shop_id` int(10) DEFAULT NULL,
  `shop_name` varchar(45) DEFAULT NULL,
  `user_account` varchar(45) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `user_name` varchar(45) DEFAULT NULL,
  `item_id` int(10) DEFAULT NULL,
  `item_name` varchar(45) DEFAULT NULL,
  `item_enchantlvl` int(10) DEFAULT NULL,
  `price` int(12) DEFAULT NULL,
  `item_count` int(10) DEFAULT NULL,
  `total_price` int(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=euckr COLLATE=euckr_korean_ci;

--
-- RELATIONSHIPS FOR TABLE `log_private_shop`:
--
