CREATE DATABASE search_engine;
USE search_engine;

CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    language VARCHAR(20)
);
