SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `payments`
--

-- --------------------------------------------------------

--
-- Table structure for table `payment_modules_settings`
--

CREATE TABLE IF NOT EXISTS `payment_modules_settings` (
  `settingid` int(11) NOT NULL AUTO_INCREMENT,
  `module_option` char(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`settingid`),
  UNIQUE KEY `option` (`module_option`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `payment_modules_settings`
--

INSERT INTO `payment_modules_settings` (`settingid`, `module_option`, `value`) VALUES
(1, 'AuthorizeNet_x_login', 'b6g5bWZ83uzFjCrX++ksavc8oDM5430FXwoDaQanYBo='),
(2, 'AuthorizeNet_x_tran_key', 'hB9kKeg/z/hS9ucP9/tf4disr+H58F+1ON5cHXqXeDo=');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
