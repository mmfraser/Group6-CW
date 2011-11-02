USE sales;

CREATE TABLE `store` (
  `storeId` smallint(6) NOT NULL AUTO_INCREMENT,
  `storeName` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  PRIMARY KEY (`storeId`),
  FOREIGN KEY (`manager`)
  	REFERENCES user(`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
