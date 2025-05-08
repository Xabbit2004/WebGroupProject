Create DATABASE library;
USE library;

CREATE TABLE BOOKS(
  id INT AUTO_INCREMENT PRIMARY KEY,
	ISBN varchar(17) NOT NULL,
  TITLE varchar(255),
  AUTHOR varchar(255),
  PUBDATE DATE,
  STATUS enum('Available','Unavailable') NOT NULL,
  RATING int(10) UNSIGNED NOT NULL,
  EMAIL varchar(255),
  CHECKDATE DATE,  
  EXPDATE DATE
);

CREATE TABLE USERS (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  USERNAME varchar(255) NOT NULL,
  EMAIL varchar(255) NOT NULL UNIQUE,
  PASSWORD varchar(255) NOT NULL,
  ROLE enum('user','admin') NOT NULL
)

INSERT INTO books (ISBN, TITLE, AUTHOR, PUBDATE, STATUS) VALUES
('978-0-394-52340-1', 'The Clockmaker’s Secret', 'Julian Everett', '2019-03-22','Available'),
('978-1-86197-876-9', 'Beneath Crimson Skies', 'Alyssa Harrington', '2020-07-10','Available'),
('978-0-7432-7356-5', 'Digital Shadows', 'Colton Reyes', '2022-11-01','Available'),
('978-0-06-112241-5', 'The Last Ember', 'Isla Vance', '2018-01-30','Available'),
('978-1-5011-8756-6', 'Through Broken Glass', 'Nina Falkner', '2023-06-18','Available'),
('978-0-452-28423-4', 'Algorithm of the Heart', 'Jasper Nguyen', '2020-10-25','Available'),
('978-0-06-085052-4', 'Echo Chamber', 'Riley Tanaka', '2017-12-12','Available'),
('978-1-4165-3455-6', 'The Fifth Bell', 'Carmen Dorsey', '2021-04-05','Available'),
('978-0-330-37369-6', 'Songs from a Distant Star', 'Leo Whitaker', '2019-09-17','Available'),
('978-0-345-39180-3', 'Harvest of Ashes', 'Selena Myles', '2024-02-28','Available');