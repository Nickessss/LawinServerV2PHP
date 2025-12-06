<?php
require_once __DIR__ . '/../vendor/autoload.php';
$configFile = __DIR__ . '/../Config/config.json';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$mysqli = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
if ($mysqli->connect_errno) {
    die("MySQL Connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");
if (!file_exists($configFile)) {
    die("config.json not found!");
}

$config = json_decode(file_get_contents($configFile), true);
$TABLE_USER     = $config['tables']['users'] ?? 'users';
$TABLE_PROFILES = $config['tables']['profiles'] ?? 'profiles';
$TABLE_FRIENDS  = $config['tables']['friends'] ?? 'friends';

$mysqli->query("
CREATE TABLE IF NOT EXISTS `$TABLE_USER` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created DATETIME NOT NULL,
    banned TINYINT(1) DEFAULT 0,
    discordId VARCHAR(255) NOT NULL UNIQUE,
    accountId VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(255) NOT NULL UNIQUE,
    username_lower VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)");

$mysqli->query("
CREATE TABLE IF NOT EXISTS `$TABLE_PROFILES` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created DATETIME NOT NULL,
    accountId VARCHAR(255) NOT NULL UNIQUE,
    profiles JSON NOT NULL
)");

$mysqli->query("
CREATE TABLE IF NOT EXISTS `$TABLE_FRIENDS` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created DATETIME NOT NULL,
    accountId VARCHAR(255) NOT NULL UNIQUE,
    list JSON NOT NULL
)");
