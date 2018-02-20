/*This is the file that is used to set up the database*/
CREATE TABLE comics(
  `id` int(10) AUTO_INCREMENT,
  `date` VARCHAR(10) NOT NULL,
  `url` VARCHAR(100) NOT NULL,
  `transcript` VARCHAR(1000) NOT NULL,
  PRIMARY KEY(id)
);
