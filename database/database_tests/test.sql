USE court_tracking_system;

-- CREATE: Insert new records
INSERT INTO defendant (Name, Date_of_Birth, Address, Ethnicity, Phone_Number, Email) VALUES ('Test User', '1995-12-31', '999 Test Lane', 'Test Ethnicity', '0400000000', 'test.user@example.com');
INSERT INTO caserecord (defendant_ID) VALUES (LAST_INSERT_ID());
INSERT INTO charge (case_ID, Description, Status) VALUES (4, 'Test Charge Description', 'Pending');
INSERT INTO lawyer (Name, Email, Phone_Number, Firm) VALUES ('Test Lawyer', 'test.lawyer@example.com', '0499999999', 'Test Firm');
INSERT INTO case_lawyer (case_ID, lawyer_ID) VALUES (4, 4);
INSERT INTO court_event (case_ID, Location, Description, Date) VALUES (4, 'Test Court', 'Test Event Description', '2025-08-01');

-- READ: Select from each table
SELECT * FROM defendant; SELECT * FROM caserecord; SELECT * FROM charge; SELECT * FROM lawyer; SELECT * FROM case_lawyer; SELECT * FROM court_event;

-- UPDATE: Modify inserted test data
UPDATE defendant SET Name = 'Updated User' WHERE Email = 'test.user@example.com';
UPDATE charge SET Status = 'Closed' WHERE case_ID = 4;
UPDATE lawyer SET Firm = 'Updated Firm' WHERE lawyer_ID = 4;
UPDATE court_event SET Location = 'Updated Court' WHERE case_ID = 4;

-- DELETE: Remove inserted test data
DELETE FROM case_lawyer WHERE case_ID = 4 AND lawyer_ID = 4;
DELETE FROM court_event WHERE case_ID = 4;
DELETE FROM charge WHERE case_ID = 4;
DELETE FROM caserecord WHERE case_ID = 4;
DELETE FROM lawyer WHERE lawyer_ID = 4;
DELETE FROM defendant WHERE Email = 'test.user@example.com';