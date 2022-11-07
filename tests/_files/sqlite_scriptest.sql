CREATE TABLE `test` (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    topic VARCHAR(25) NOT NULL,
    msg VARCHAR(50) NOT NULL
);
INSERT INTO test(`topic`, `msg`)
VALUES
    ("testing", "First test msg"),
    ("testing", "Second test msg");
