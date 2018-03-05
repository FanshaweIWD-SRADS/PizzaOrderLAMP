CREATE DATABASE `group5`;

GRANT ALL PRIVILEGES ON `group5`.* TO 'lamp1user'@'localhost' identified by '!Lamp1!';

USE group5;

CREATE TABLE `customer` (
  `cusID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  PRIMARY KEY (`cusID`)
);

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL AUTO_INCREMENT,
  `cusID` int(11) NOT NULL,
  `addr` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `prov` varchar(100) NOT NULL,
  `post` varchar(100) NOT NULL,
  `appt` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`orderID`),
  FOREIGN KEY (`cusID`) references customer(`cusID`)
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

INSERT INTO customer (name, email, phone)
VALUES ("Bobby", "test1@gmail.com", "1112223333");

INSERT INTO customer (name, email, phone)
VALUES ("Mary", "wow@gmail.com", "9998887777");

INSERT INTO customer (name, email, phone)
VALUES ("Hope", "hope@msn.com", "4445556688");

INSERT INTO orders (cusID, addr, city, prov, post)
VALUES (1, "123 Baker Street", "London", "Ontario", "N6H 2G5");

INSERT INTO orders (cusID, addr, city, prov, post, appt)
VALUES ("1", "65 Blank Road", "Toronto", "Ontario", "N6H 5B5", "317");

INSERT INTO orders (cusID, addr, city, prov, post)
VALUES ("2", "12 Electric Avenue", "Austin", "Texas", "N6H 2B8");

INSERT INTO orders (cusID, addr, city, prov, post, appt)
VALUES ("2", "98 Crowd Street", "Austin", "Texas", "B5H 2B5", "820");

INSERT INTO orders (cusID, addr, city, prov, post)
VALUES ("3", "1356 Fabulous Cresent", "Montreal", "Quebec", "V3H 2B5");