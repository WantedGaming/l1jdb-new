
-- --------------------------------------------------------

--
-- Table structure for table `shop_npc`
--

CREATE TABLE `shop_npc` (
  `npc_id` int(10) NOT NULL,
  `id` int(10) NOT NULL DEFAULT 1,
  `item_id` int(10) NOT NULL DEFAULT 0,
  `memo` text DEFAULT NULL,
  `count` int(10) NOT NULL DEFAULT 1,
  `enchant` int(10) NOT NULL DEFAULT 0,
  `selling_price` int(10) NOT NULL DEFAULT -1,
  `purchasing_price` int(10) NOT NULL DEFAULT -1
) ENGINE=MyISAM DEFAULT CHARSET=euckr COLLATE=euckr_korean_ci;

--
-- RELATIONSHIPS FOR TABLE `shop_npc`:
--
