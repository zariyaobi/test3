DROP DATABASE IF EXISTS productDB;
CREATE DATABASE IF NOT EXISTS productDB;
USE productDB;

SELECT 'CREATING DATABASE STRUCTURE' AS 'INFO';

/*Products Table*/
CREATE TABLE IF NOT EXISTS products (
    productID INT NOT NULL AUTO_INCREMENT,
    productName VARCHAR(255) NOT NULL,
    productType VARCHAR(255) NOT NULL,
    productCost DECIMAL NOT NULL,
    productRating DECIMAL NOT NULL,
    productStatus BOOLEAN NOT NULL,
    PRIMARY KEY(productID)
);

/*Users Table*/
CREATE TABLE IF NOT EXISTS users (
    userID INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(userID)
);

/*Payment Method Table*/
CREATE TABLE IF NOT EXISTS paymentMethods (
    paymentmethodID INT NOT NULL AUTO_INCREMENT,
    paymentMethod VARCHAR(255) NOT NULL,
    PRIMARY KEY(paymentmethodID)
);

/*Payment Table*/
CREATE TABLE IF NOT EXISTS payment (
    paymentID INT NOT NULL AUTO_INCREMENT,
    fname VARCHAR(255) NOT NULL,
    lname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    address2 VARCHAR(255),
    district VARCHAR(255) NOT NULL,
    country VARCHAR(255) NOT NULL,
    paymentmethodID INT NOT NULL,
    nameOnCard VARCHAR(255),
    cardNum INT,
    expiration DATE,
    cvv INT,
    accountName VARCHAR(255),
    paypalEmail VARCHAR(255),
    PRIMARY KEY(paymentID),
    FOREIGN KEY(paymentmethodID) REFERENCES paymentMethods(paymentmethodID)
);

/*Invoice Table*/
CREATE TABLE IF NOT EXISTS invoice (
    invoiceID INT NOT NULL AUTO_INCREMENT,
    paymentID INT NOT NULL,
    invoiceDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    invoiceTotalnotax DECIMAL(10,2) NOT NULL,
    tax DECIMAL(10,2) NOT NULL,
    invoiceTotalwithtax DECIMAL(10,2) NOT NULL,
    note TEXT NOT NULL,
    PRIMARY KEY(invoiceID),
    FOREIGN KEY(paymentID) REFERENCES payment(paymentID)
);

/*Products inserts*/
INSERT INTO products (productName, productType, productCost, productRating, productStatus)
VALUES ('Shirt', 'Clothes', '9', '4.5', TRUE);
INSERT INTO products (productName, productType, productCost, productRating, productStatus)
VALUES ('Pants', 'Clothes', '5', '5', TRUE);
INSERT INTO products (productName, productType, productCost, productRating, productStatus)
VALUES ('Socks', 'Clothes', '4', '5', TRUE);
INSERT INTO products (productName, productType, productCost, productRating, productStatus)
VALUES ('High Heals', 'Shoes', '12', '4', TRUE);
INSERT INTO products (productName, productType, productCost, productRating, productStatus)
VALUES ('Sneakers', 'Shoes', '10', '4.5', TRUE);
INSERT INTO products (productName, productType, productCost, productRating, productStatus)
VALUES ('Flip Flops', 'Shoes', '7', '4', TRUE);
INSERT INTO products (productName, productType, productCost, productRating, productStatus)
VALUES ('Fruits and Vegetables', 'Groceries', '3', '5', TRUE);
INSERT INTO products (productName, productType, productCost, productRating, productStatus)
VALUES ('Canned Products', 'Groceries', '8', '4', TRUE);
INSERT INTO products (productName, productType, productCost, productRating, productStatus)
VALUES ('Cleaning Supplies', 'Groceries', '5', '4.5', TRUE);

/*Payment Method inserts*/
INSERT INTO paymentMethods (paymentMethod) VALUES ('Card');
INSERT INTO paymentMethods (paymentMethod) VALUES ('Paypal');