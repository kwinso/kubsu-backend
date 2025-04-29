ALTER TABLE submissions
  ADD COLUMN username varchar(12) not null,
  ADD COLUMN password varchar(100) not null;