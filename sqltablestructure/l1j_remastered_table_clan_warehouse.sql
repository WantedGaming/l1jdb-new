
-- --------------------------------------------------------

--
-- Table structure for table `clan_warehouse`
--

CREATE TABLE `clan_warehouse` (
  `id` int(11) NOT NULL,
  `clan_name` varchar(45) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `is_equipped` int(11) DEFAULT NULL,
  `enchantlvl` int(11) DEFAULT NULL,
  `is_id` int(11) DEFAULT NULL,
  `durability` int(11) DEFAULT NULL,
  `charge_count` int(11) DEFAULT NULL,
  `remaining_time` int(11) DEFAULT NULL,
  `last_used` datetime DEFAULT NULL,
  `attr_enchantlvl` int(11) DEFAULT NULL,
  `special_enchant` int(11) DEFAULT NULL,
  `doll_ablity` int(4) DEFAULT 0,
  `package` tinyint(3) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=euckr COLLATE=euckr_korean_ci;

--
-- RELATIONSHIPS FOR TABLE `clan_warehouse`:
--
