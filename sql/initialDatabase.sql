drop table if exists account_type, account, customer_payment, genre, inventory, order_status, full_order, order_item_detail cascade;

create table account_type(
	account_type_ID int NOT NULL,
	account_type varchar(56) NOT NULL,
	PRIMARY KEY(account_type_ID)
)ENGINE=InnoDB;

create table account(
	account_ID int NOT NULL AUTO_INCREMENT,
	account_type_ID int NOT NULL,
	account_email varchar(128) NOT NULL,
	account_address varchar(256) NOT NULL,
	account_username varchar(128) NOT NULL,
	account_password varchar(128) NOT NULL,
	phone_num varchar(50) NOT NULL,
	PRIMARY KEY(account_ID),
	FOREIGN KEY(account_type_ID) REFERENCES account_type(account_type_ID)
)ENGINE=InnoDB;

create table customer_payment(
	card_ID int NOT NULL AUTO_INCREMENT,
	account_ID int NOT NULL,
	name_on_card varchar(255) NOT NULL,
	billing_address varchar(256) NOT NULL,
	card_number varchar(80) NOT NULL,
	expDate varchar(5) NOT NULL,
	phNum int(15) NOT NULL,
	PRIMARY KEY(card_ID),
	FOREIGN KEY(account_ID) REFERENCES account(account_ID)
)ENGINE=InnoDB;

create table genre(
	genre_ID int NOT NULL AUTO_INCREMENT,
	genre_name varchar(256) NOT NULL,
	PRIMARY KEY(genre_ID)
)ENGINE=InnoDB;

create table inventory(
	item_ID int NOT NULL AUTO_INCREMENT,
	genre_ID int NOT NULL,
	author_name varchar(256) NOT NULL,
	item_details varchar(256),
	item_price decimal(10,2) NOT NULL,
	item_name varchar(255) NOT NULL,
	item_img varchar(255) NOT NULL,
	date_added date NOT NULL,
	PRIMARY KEY(item_ID),
	FOREIGN KEY(genre_ID) REFERENCES genre(genre_ID)
)ENGINE=InnoDB;

create table order_status(
	order_status_ID int NOT NULL AUTO_INCREMENT,
	order_status varchar(56) NOT NULL,
	PRIMARY KEY(order_status_ID)
)ENGINE=InnoDB;

create table full_order(
	order_ID int NOT NULL AUTO_INCREMENT,
	account_ID int NOT NULL,
	order_status_ID int NOT NULL,
	card_ID int NOT NULL,
	order_date date NOT NULL,
	order_total decimal(10,2) NOT NULL,
	PRIMARY KEY(order_ID),
	FOREIGN KEY(account_ID) REFERENCES account(account_ID),
	FOREIGN KEY(order_status_ID) REFERENCES order_status(order_status_ID),
	FOREIGN KEY(card_ID) REFERENCES customer_payment(card_ID)
)ENGINE=InnoDB;

create table order_item_detail(
	order_detail_ID int NOT NULL AUTO_INCREMENT,
	order_ID int NOT NULL,
	item_ID int NOT NULL,
	order_item_quantity int NOT NULL,
	PRIMARY KEY(order_detail_ID),
	FOREIGN KEY(order_ID) REFERENCES `order`(order_ID),
	FOREIGN KEY(item_ID) REFERENCES inventory(item_ID)
)ENGINE=InnoDB;