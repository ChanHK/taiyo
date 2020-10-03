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

    // $sql = "CREATE TABLE EndUser (
    //     enduser_id INT(6) UNSIGNED AUTO_INCREMENT NOT NULL,
    //     user_email VARCHAR(50),
    //     user_password VARCHAR(15),
    //     username VARCHAR(30),
    //     user_description VARCHAR(255),
    //     gender VARCHAR(6),
    //     phone_number VARCHAR(15),
    //     street_1 VARCHAR(255),
    //     street_2 VARCHAR(255),
    //     city VARCHAR(120),
    //     c_state VARCHAR(15),
    //     postcode VARCHAR(5),
    //     age INT(3),
    //     profile_photo VARCHAR(255),
    //     PRIMARY KEY (enduser_id)
    //   )
    //   ";
    // gender is INT(1)
    // 1 = MALE
    // 2 = FEMALE
    // 3 = OTHERS
    // profile_photo
    // stores the direction to get the picture in string   ("pictures/dummy_profile_pic.png")

    // $sql = "CREATE TABLE Review (review_id INT(6) UNSIGNED AUTO_INCREMENT NOT NULL, review_message VARCHAR(255), reviewer_id INT(6) REFERENCES EndUser(enduser_id), reviewee_id INT(6) REFERENCES EndUser(enduser_id), PRIMARY KEY (review_id))";

    // $sql = "CREATE TABLE Product (product_id INT(6) UNSIGNED AUTO_INCREMENT NOT NULL, product_name VARCHAR(30), product_price FLOAT(7,2), product_description VARCHAR(255), product_state VARCHAR(50), category VARCHAR(50), quantity INT(2), enduser_id INT(6) REFERENCES EndUser(enduser_id), PRIMARY KEY (product_id))";

    // $sql = "CREATE TABLE Cart (cart_item_id INT(6) UNSIGNED AUTO_INCREMENT NOT NULL, quantity INT(2), product_id INT(6) REFERENCES product(product_id), enduser_id INT(6) REFERENCES EndUser(enduser_id), PRIMARY KEY (cart_item_id))";

    // $sql = "CREATE TABLE ProductImage (product_image_id INT(6) UNSIGNED AUTO_INCREMENT NOT NULL, product_id INT(6) REFERENCES product(product_id), product_image VARCHAR(255), PRIMARY KEY (product_image_id))";

    // $sql = "CREATE TABLE Wishlist (wishlist_item_id INT(6) UNSIGNED AUTO_INCREMENT NOT NULL, product_id INT(6) REFERENCES product(product_id), enduser_id INT(6) REFERENCES EndUser(enduser_id), PRIMARY KEY (wishlist_item_id))";

    // $sql = "CREATE TABLE TransactionHistory (transaction_id INT(6) UNSIGNED AUTO_INCREMENT NOT NULL, transaction_type VARCHAR(10), quantity INT(2), product_id INT(6) REFERENCES product(product_id), enduser_id INT(6) REFERENCES EndUser(enduser_id), PRIMARY KEY (transaction_id))";


    // if (mysqli_query($conn, $sql)) {
    //     echo "table created";
    // } else {
    //     echo "fail to create table";
    // }



    //$sql = "INSERT INTO cart (quantity, product_id, enduser_id) VALUES (7, 2, 113631)";

    // $sql = "INSERT INTO product (product_name, product_price, product_description, product_state, category, quantity, enduser_id) VALUES ('ps5 console', 2500.00,'This is a brand new ps5 controller','new','gaming products',1,1)";

    // $sql = "INSERT INTO productimage (product_id, product_image) values (2,'pictures/ps5.jpg')";

    //$sql = "INSERT INTO wishlist (product_id, enduser_id) VALUES (2,1)";

    // $sql = "INSERT INTO EndUser (user_email, user_password, username, gender, phone_number) VALUES ('JJ@gmail.com', '123456789', 'james', 1,'012-2345678')";

    // $sql = "DELETE FROM EndUser WHERE user_email = 'hkk@gmail.com'";



    /********************************Users***************************************/
    // $sql = "INSERT INTO EndUser (user_email, user_password, username, user_description, gender, phone_number, street_1, street_2, city, c_state, postcode, age, profile_photo) VALUES ('starLightuser1@gmail.com', 'starlightuser1','starlightuser1','Likes to buy stuff','Male','012-3456789','No. 70, Jalan A', 'Lampu Merah', 'Batu Caves', 'Terengganu', '50000',21, 'pictures/main/prfile.1.png')";
    // $sql = "INSERT INTO EndUser (user_email, user_password, username, user_description, gender, phone_number, street_1, street_2, city, c_state, postcode, age, profile_photo) VALUES ('Hatiuser2@gmail.com', 'hatiuser1','hatiuser1','Likes to scam people','Female','017-1103490','No. 701, Jalan B', 'Kearny', 'George Town', 'Pinang', '90000',36, 'pictures/main/prfile2.png')";
    // $sql = "INSERT INTO EndUser (user_email, user_password, username, user_description, gender, phone_number, street_1, street_2, city, c_state, postcode, age, profile_photo) VALUES ('Blakeuser3@gmail.com', 'blakeuser1','blakeuser1','You cant see me hehehe','Male','018-2312313','No. 34, Jalan C', 'Jupiter', 'Kuching', 'Sarawak', '54500', 28, 'pictures/main/prfile3.png')";
    /********************************Users***************************************/

    /********************************Product*************************************/
    /*****Product Category*****/
    // 1. Games
    // 2. Consoles
    // 3. Controllers
    // 4. Computers
    // 5. Accessories
    // 6. VR
    /*****Product Category*****/
    // $sql = "INSERT INTO product (product_name, product_price, product_description, product_state, category, quantity, enduser_id) VALUES ('nintendo switch', 2500.00,'The Nintendo Switch is a hybrid video game console, consisting of a console unit, a dock, and two Joy-Con controllers. Although it is a hybrid console, Nintendo classifies it as a home console that you can take with you on the go.','new','Consoles',10,2)";
    // $sql = "INSERT INTO product (product_name, product_price, product_description, product_state, category, quantity, enduser_id) VALUES ('olympic games 2020 ps4', 270.00, 'The official video game of the Olympic Games Tokyo 2020 will be released! It features more than 15 Olympic Games events and you can enjoy an authentic sports action game. Reproduces the realistic actual venues such as the Olympic Stadium. Customize your avatar freely and participate in the Olympic Games Tokyo 2020.','new','Games',60,2)";
    // $sql = "INSERT INTO product (product_name, product_price, product_description, product_state, category, quantity, enduser_id) VALUES ('ps4 console', 2000.00, 'The PlayStation 4 (PS4) is a home video game console developed by Sony Computer Entertainment. The console''s controller was also redesigned and improved over the PlayStation 3, with improved buttons and analog sticks, and an integrated touchpad among other changes.', 'second hand','Consoles',25,2)";
    // $sql = "INSERT INTO product (product_name, product_price, product_description, product_state, category, quantity, enduser_id) VALUES ('Applce MacBook Pro 2019', 10000.00, 'Apple MacBook Pro is a macOS laptop with a 13.30-inch display that has a resolution of 2560x1600 pixels. It is powered by a Core i5 processor and it comes with 12GB of RAM. The Apple MacBook Pro packs 512GB of SSD storage.', 'new','Computers',6,3)";
    /********************************Product*************************************/

    /*****************************Product Image**********************************/
    // $sql = "INSERT INTO productimage (product_id, product_image) values (1,'pictures/product/product1a.jpg')";
    // $sql = "INSERT INTO productimage (product_id, product_image) values (1,'pictures/product/product1b.jpg')";
    // $sql = "INSERT INTO productimage (product_id, product_image) values (1,'pictures/product/product1c.jpg')";
    // $sql = "INSERT INTO productimage (product_id, product_image) values (1,'pictures/product/product1d.jpg')";
    // $sql = "INSERT INTO productimage (product_id, product_image) values (1,'pictures/product/product1e.jpg')";
    // $sql = "INSERT INTO productimage (product_id, product_image) values (2,'pictures/product/product2a.jpg')";
    // $sql = "INSERT INTO productimage (product_id, product_image) values (3,'pictures/product/product3a.jpg')";
    // $sql = "INSERT INTO productimage (product_id, product_image) values (3,'pictures/product/product3b.jpg')";
    // $sql = "INSERT INTO productimage (product_id, product_image) values (4,'pictures/product/product4a.png')";
    // $sql = "INSERT INTO productimage (product_id, product_image) values (4,'pictures/product/product4b.jpg')";
    /*****************************Product Image**********************************/

    if (mysqli_query($conn, $sql)) {
        echo "insert data success";
    } else {
        echo "fail to insert data";
    }
} else {
    die("Connection failed: " . mysqli_connect_error());
}


// change user to user data