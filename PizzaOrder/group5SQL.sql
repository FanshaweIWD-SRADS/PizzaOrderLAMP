DROP DATABASE if exists group5;
CREATE DATABASE if not exists group5;

GRANT ALL PRIVILEGES ON group5.* TO 'lamp1user'@'localhost' identified by '!Lamp1!';

USE group5;

CREATE TABLE `customer` (
  `cusID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`cusID`)
);

CREATE TABLE `address` (
  `addrID` int(11) NOT NULL AUTO_INCREMENT,
  `cusID` int(11) NOT NULL,
  `addr` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `prov` varchar(100) NOT NULL,
  `post` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `appt` varchar(100) DEFAULT NULL,  
  PRIMARY KEY (`addrID`),
  FOREIGN KEY (`cusID`) references customer(`cusID`)
);

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL AUTO_INCREMENT,
  `cusID` int(11) NOT NULL,
  `addrID` int(11) NOT NULL,
  PRIMARY KEY (`orderID`),
  FOREIGN KEY (`cusID`) references customer(`cusID`),
  FOREIGN KEY (`addrID`) references address(`addrID`)
);

CREATE TABLE `pizza` (
  `pizzaID` int(11) NOT NULL AUTO_INCREMENT,
  `orderID` int(11) NOT NULL,
  `size` varchar(100) NOT NULL,
  `dough` varchar(100) NOT NULL,
  `sauce` varchar(100) NOT NULL,
  `cheese` varchar(100) NOT NULL,
  `toppings` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`pizzaID`),
  FOREIGN KEY (`orderID`) references orders(`orderID`)
);

INSERT INTO customer (name, email)
VALUES ("Bobby", "test1@gmail.com");

INSERT INTO customer (name, email)
VALUES ("Mary", "wow@gmail.com");

INSERT INTO customer (name, email)
VALUES ("Hope", "hope@msn.com");

INSERT INTO address (cusID, addr, city, prov, post, phone)
VALUES (1, "123 Baker Street", "London", "Ontario", "N6H 2G5", "1112223333");

INSERT INTO address (cusID, addr, city, prov, post, phone, appt)
VALUES (1, "65 Blank Road", "Toronto", "Ontario", "N6H 5B5", "1112223333", "317");

INSERT INTO address (cusID, addr, city, prov, post, phone)
VALUES (2, "12 Electric Avenue", "Austin", "Texas", "N6H 2B8", "9998887777");

INSERT INTO address (cusID, addr, city, prov, post, phone, appt)
VALUES (2, "98 Crowd Street", "Austin", "Texas", "B5H 2B5", "9998887777", "820");

INSERT INTO address (cusID, addr, city, prov, post, phone)
VALUES (3, "1356 Fabulous Cresent", "Montreal", "Quebec", "V3H 2B5", "4445556688");

INSERT INTO orders (cusID, addrID)
VALUES (1, 1);

INSERT INTO orders (cusID, addrID)
VALUES (1, 2);

INSERT INTO orders (cusID, addrID)
VALUES (3, 5);

INSERT INTO orders (cusID, addrID)
VALUES (2, 3);