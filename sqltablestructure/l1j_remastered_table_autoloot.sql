
-- --------------------------------------------------------

--
-- Table structure for table `autoloot`
--

CREATE TABLE `autoloot` (
  `item_id` int(11) NOT NULL DEFAULT 0,
  `note` varchar(50) DEFAULT NULL,
  `desc_kr` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=euckr COLLATE=euckr_korean_ci;

--
-- RELATIONSHIPS FOR TABLE `autoloot`:
--
