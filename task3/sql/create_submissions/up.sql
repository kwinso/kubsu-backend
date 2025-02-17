CREATE IF NOT EXISTS languages (
  `id` int PRIMARY KEY,
  `name` varchar(20) not null
);

CREATE IF NOT EXISTS submission_languages (
  `id` int PRIMARY KEY,
  `submission_id` foreign key references submissions(id),
  `language_id`  foreign key references languages(id)
);

CREATE TABLE IF NOT EXISTS submissions (
  `id` varchar(500) PRIMARY KEY,
  `name` varchar(500) not null,
  `phone` varchar(500) not null,
  `email` varchar(500) not null,
  `birth_date` date not null,
  `bio` text not null,
  `sex` TINYINT(1)
  `created_at` timestamp not null default current_timestamp
);
