<?php
// $conn = mysqli_connect("localhost", "root", "");
$conn = mysqli_connect("localhost", "root", "", "taiyodb");

if ($conn) {
    // $sql = "CREATE DATABASE taiyoDB";

    // if(mysqli_query($conn, $sql)) {
    //     echo "database created";
    // }
    // else {
    //     echo "database creation fail";
    // } 

    //$sql = "CREATE TABLE User (user_id INT(6) UNSIGNED AUTO_INCREMENT NOT NULL, user_email VARCHAR(50), user_password VARCHAR(15), username VARCHAR(30), user_description VARCHAR(255), gender INT(1), phone_number VARCHAR(15), user_address VARCHAR(255), age INT(3), profile_photo VARCHAR(255), PRIMARY KEY (user_id))";
    // gender is INT(1)
    // 1 = MALE
    // 2 = FEMALE
    // 3 = OTHERS
    // profile_photo
    // stores the direction to get the picture in string   ("pictures/dummy_profile_pic.png")

    // $sql = "CREATE TABLE Review (review_id INT(6) UNSIGNED AUTO_INCREMENT NOT NULL, review_message VARCHAR(255), reviewer_id INT(6) REFERENCES user(user_id), reviewee_id INT(6) REFERENCES user(user_id), PRIMARY KEY (review_id))";

    // $sql = "CREATE TABLE Product (product_id INT(6) UNSIGNED AUTO_INCREMENT NOT NULL, product_name VARCHAR(30), product_price FLOAT(7,2), product_description VARCHAR(255), product_state VARCHAR(50), category VARCHAR(50), quantity INT(2), user_id INT(6) REFERENCES user(user_id), PRIMARY KEY (product_id))";

    // $sql = "CREATE TABLE Cart (cart_item_id INT(6) UNSIGNED AUTO_INCREMENT NOT NULL, quantity INT(2), product_id INT(6) REFERENCES product(product_id), user_id INT(6) REFERENCES user(user_id), PRIMARY KEY (cart_item_id))";

    // $sql = "CREATE TABLE ProductImage (product_image_id INT(6) UNSIGNED AUTO_INCREMENT NOT NULL, product_id INT(6) REFERENCES product(product_id), product_image VARCHAR(255), PRIMARY KEY (product_image_id))";

    // $sql = "CREATE TABLE Wishlist (wishlist_item_id INT(6) UNSIGNED AUTO_INCREMENT NOT NULL, product_id INT(6) REFERENCES product(product_id), user_id INT(6) REFERENCES user(user_id), PRIMARY KEY (wishlist_item_id))";

    // $sql = "CREATE TABLE TransactionHistory (transaction_id INT(6) UNSIGNED AUTO_INCREMENT NOT NULL, transaction_type VARCHAR(10), quantity INT(2), product_id INT(6) REFERENCES product(product_id), user_id INT(6) REFERENCES user(user_id), PRIMARY KEY (transaction_id))";


    // if(mysqli_query($conn, $sql)) {
    //     echo "table created";
    // }
    // else {
    //     echo "fail to create table";
    // } 


    // $sql = "INSERT INTO user (user_email, user_password, username, user_description, gender, phone_number, user_address, age) VALUES ('hk@gmail.com', 'abc123','CHANHK','this is user description',1,'012-3456789','this is user address',21)";

    $sql = "INSERT INTO cart (quantity, product_id, user_id) VALUES (7, 2, 1)";

    // $sql = "INSERT INTO product (product_name, product_price, product_description, product_state, category, quantity, user_id) VALUES ('ps5 console', 2500.00,'This is a brand new ps5 controller','new','gaming products',1,1)";

    // $sql = "INSERT INTO productimage (product_id, product_image) values (2,'pictures/ps5.jpg')";

    if(mysqli_query($conn, $sql)) {
        echo "insert data success";
    }
    else {
        echo "fail to insert data";
    } 


} else {
    die("Connection failed: " . mysqli_connect_error());
}
