create database digital_mandir;
USE digital_mandir;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
	phone VARCHAR(10),
    password VARCHAR(100)
)ENGINE=InnoDB;

CREATE TABLE temples (
    temple_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    location VARCHAR(100),
    description TEXT
)ENGINE=InnoDB;
CREATE TABLE services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    temple_id INT,
    service_name VARCHAR(100),
    price INT,
    FOREIGN KEY (temple_id)
REFERENCES temples(temple_id)
ON UPDATE CASCADE
ON DELETE CASCADE
)ENGINE=InnoDB;


CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    service_id INT,
    booking_date DATE,
    status VARCHAR(50),
    
FOREIGN KEY (user_id)
REFERENCES users(user_id)
ON DELETE CASCADE
ON UPDATE CASCADE,

FOREIGN KEY (service_id)
REFERENCES services(service_id)
ON DELETE CASCADE
ON UPDATE CASCADE
)ENGINE=InnoDB;


INSERT INTO temples VALUES
(1, 'Pashupatinath', 'Kathmandu', 'Famous Shiva Temple');
select * from digital_mandir.temples;

select*from digital_mandir1.admin;
DELETE FROM admin WHERE admin_id = 2;
TRUNCATE TABLE admin;
ALTER TABLE admin 
MODIFY password VARCHAR(255) NOT NULL;

select * from digital_mandir1.users;


















