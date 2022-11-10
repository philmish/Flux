CREATE TABLE `test` (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    testStr VARCHAR(25),
    testNum int NOT NULL
);
INSERT INTO test(`testStr`, `testNum`)
VALUES
    ("testing", 12),
    ("test", 34);
