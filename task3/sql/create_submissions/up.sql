CREATE TABLE IF NOT EXISTS languages (
  `id` int PRIMARY KEY,
  `name` varchar(20) not null
);

CREATE TABLE IF NOT EXISTS submissions (
  `id` int PRIMARY KEY,
  `name` varchar(500) not null,
  `phone` varchar(500) not null,
  `email` varchar(500) not null,
  `birth_date` date not null,
  `bio` text not null,
  `sex` TINYINT(1),
  `created_at` timestamp not null default current_timestamp
);

CREATE TABLE IF NOT EXISTS submission_languages (
  `submission_id` int,
  `language_id` int,
  PRIMARY KEY (submission_id, language_id),
  foreign key (submission_id) references submissions(id),
  foreign key (language_id) references languages(id)
);