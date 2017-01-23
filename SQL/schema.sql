-- INFO
-- #####################################
-- Table names must be prefixed with "cleo_"
-- Table names must use camelCase
-- Column names must be prefixed with the abbreviation of the table name (all lowercase) followed by an underscore
-- Column names must use camelCase

-- Delete tables in reverse order of creation
-- STILL NEED TO PUT THESE IN THE CORRECT ORDER
DROP TABLE IF EXISTS cleo_rooms, cleo_timeSlots, cleo_users, cleo_bookings, cleo_confirmationPins;

-- TABLES
-- #####################################
-- cleo_rooms
-- cleo_timeSlots
-- cleo_users
-- cleo_bookings
-- cleo_confirmationPins


-- Define a table to store the rooms
CREATE TABLE cleo_rooms (
	r_id INT AUTO_INCREMENT,
	r_roomNumber VARCHAR(16) NOT NUll,
	PRIMARY KEY (r_id)
);

-- Define a table to store the time-slots
CREATE TABLE cleo_timeSlots (
	ts_id INT AUTO_INCREMENT,
	ts_time VARCHAR(16) NOT NULL,
	PRIMARY KEY (ts_id)
);

-- Define the table to store users
CREATE TABLE cleo_users (
	u_id INT AUTO_INCREMENT,
	u_facebookId INT NOT NULL,
	u_username VARCHAR(16) NOT NULL,
	u_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (u_id)
);

-- Define the table to store room bookings
CREATE TABLE cleo_bookings (
	b_id INT NOT NULL,
	b_userId INT,
	b_roomId INT,
	b_timeSlot1Id INT,
	b_timeSlot2Id INT,
	b_bookingDate DATE NOT NULL,
	b_status ENUM('pending-confirm', 'confirmed', 'pending-cancel', 'cancelled') NOT NULL DEFAULT 'pending-confirm',
	b_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (b_id)
);

-- Define the table to store pin numbers to confirm actions
CREATE TABLE cleo_confirmationPins (
	cp_id INT AUTO_INCREMENT,
	cp_bookingId INT,
	cp_pinNumber VARCHAR(16) NOT NULL,
	PRIMARY KEY (cp_id)
);

-- #####################################
-- Add the foreign keys

ALTER TABLE cleo_bookings
ADD CONSTRAINT b_uid
FOREIGN KEY (b_userId)
REFERENCES cleo_users (u_id);

ALTER TABLE cleo_bookings
ADD CONSTRAINT b_rid
FOREIGN KEY (b_roomId)
REFERENCES cleo_rooms (r_id);

ALTER TABLE cleo_bookings
ADD CONSTRAINT b_ts1
FOREIGN KEY (b_timeSlot1Id)
REFERENCES cleo_timeSlots (ts_id);

ALTER TABLE cleo_bookings
ADD CONSTRAINT b_ts2
FOREIGN KEY (b_timeSlot2Id)
REFERENCES cleo_timeSlots (ts_id);

ALTER TABLE cleo_confirmationPins
ADD CONSTRAINT cp_bid
FOREIGN KEY (cp_bookingId)
REFERENCES cleo_bookings (b_id);