USE sales;

CREATE TABLE `groupmembership` (
  `userId` int(11) NOT NULL,
  `groupId` int(11) NOT NULL,
  PRIMARY KEY (`userId`,`groupId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

