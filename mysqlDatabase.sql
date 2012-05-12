CREATE TABLE IF NOT EXISTS `payment_modules_settings` (
  `settingid` int(11) NOT NULL AUTO_INCREMENT,
  `module_option` char(50) NOT NULL,
  `value` char(255) NOT NULL,
  PRIMARY KEY (`settingid`),
  UNIQUE KEY `option` (`module_option`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;


INSERT INTO `payment_modules_settings` (`settingid`, `module_option`, `value`) VALUES
(1, 'AuthorizeNet_x_login', 'b6g5bWZ83uzFjCrX++ksavc8oDM5430FXwoDaQanYBo='),
(2, 'AuthorizeNet_x_tran_key', 'hB9kKeg/z/hS9ucP9/tf4disr+H58F+1ON5cHXqXeDo='),
(3, 'GoogleCheckout_merchantId', '247275622417170'),
(4, 'GoogleCheckout_merchantKey', 'gWPMiKTL3jlqDdpA14DzGA'),
(5, 'PayPal_business', 'tyler_1316489459_biz@freelancerpanel.com'),
(6, 'PayPal_ipnUrl', 'payment/paypal'),
(7, 'PayPalPro_apiUsername', 'iO4WWHDNIP3NL57bJsawNLqFTffETS4OCAuzKbO6Nkrxfbo9rpnz+4VSpt30TfinogaEOt6tupll70dje6MXVg=='),
(8, 'PayPalPro_apiPassword', 'IO1bJMvB/+fghkQXZTwvpmYyCcPr+9s9oA8mXVKC+Xo='),
(9, 'PayPalPro_apiSignature', 'AFcWxV21C7fd0v3bYYYRCpSSRl31AVfbDmfa93.sdHXWloOrXupym2zE'),
(10, 'TwoCo_vendorId', '1595373'),
(11, 'TwoCo_secretWord', 'tango');