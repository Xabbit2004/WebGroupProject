Create DATABASE Library;
USE Library;

CREATE TABLE BOOKS(
	ISBN varchar(17) NOT NULL,
    TITLE TINYTEXT,
    AUTHOR varchar(100),
    PUBDATE DATE,
    GENRE varchar(25),
    AVAILABILITY BOOLEAN,
    PRIMARY KEY(ISBN)
);

CREATE TABLE RATINGS(
	ISBN varchar(17) NOT NULL,
    RATING FLOAT,
    REVIEW TINYTEXT
);

CREATE TABLE TAGS(
	ISBN varchar(17),
    TAG varchar(25)
);

INSERT INTO books (ISBN, TITLE, AUTHOR, PUBDATE, GENRE, AVAILABILITY) VALUES
('978-0-394-52340-1', 'The Clockmaker’s Secret', 'Julian Everett', '2019-03-22', 'Mystery', TRUE),
('978-1-86197-876-9', 'Beneath Crimson Skies', 'Alyssa Harrington', '2020-07-10', 'Historical Fiction', FALSE),
('978-0-7432-7356-5', 'Digital Shadows', 'Colton Reyes', '2022-11-01', 'Thriller', TRUE),
('978-0-06-112241-5', 'The Last Ember', 'Isla Vance', '2018-01-30', 'Adventure', TRUE),
('978-1-5011-8756-6', 'Through Broken Glass', 'Nina Falkner', '2023-06-18', 'Drama', FALSE),
('978-0-452-28423-4', 'Algorithm of the Heart', 'Jasper Nguyen', '2020-10-25', 'Romance', TRUE),
('978-0-06-085052-4', 'Echo Chamber', 'Riley Tanaka', '2017-12-12', 'Science Fiction', FALSE),
('978-1-4165-3455-6', 'The Fifth Bell', 'Carmen Dorsey', '2021-04-05', 'Fantasy', TRUE),
('978-0-330-37369-6', 'Songs from a Distant Star', 'Leo Whitaker', '2019-09-17', 'Literary Fiction', TRUE),
('978-0-345-39180-3', 'Harvest of Ashes', 'Selena Myles', '2024-02-28', 'Dystopian', FALSE);
