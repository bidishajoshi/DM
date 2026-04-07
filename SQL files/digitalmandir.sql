CREATE DATABASE digital_mandir1;
USE digital_mandir1;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(10),
    password VARCHAR(100)
);

CREATE TABLE temples (
    temple_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    location VARCHAR(100),
    description TEXT
);

CREATE TABLE services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    temple_id INT,
    service_name VARCHAR(100),
    price INT
);
ALTER TABLE services
ADD CONSTRAINT fk_temple_service
FOREIGN KEY (temple_id)
REFERENCES temples(temple_id)
ON DELETE CASCADE;


CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    service_id INT,
    booking_date DATE,
    status VARCHAR(50)
);
ALTER TABLE bookings
ADD CONSTRAINT fk_user_booking
FOREIGN KEY (user_id)
REFERENCES users(user_id)
ON DELETE CASCADE;
ALTER TABLE bookings
ADD CONSTRAINT fk_service_booking
FOREIGN KEY (service_id)
REFERENCES services(service_id) temples
ON DELETE CASCADE;


CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(50)
);

INSERT INTO admin VALUES (1,'admin','admin123');


ALTER TABLE temples
ADD image VARCHAR(255);

select * from digital_mandir1.temples;