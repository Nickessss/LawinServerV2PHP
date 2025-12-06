<?php
require_once __DIR__ . '/../structs/db.php';

class User {
    public static function findByDiscordId($discordId) {
        global $mysqli;
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE discordId = ?");
        $stmt->bind_param("s", $discordId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function create($data) {
        global $mysqli;
        $stmt = $mysqli->prepare("
            INSERT INTO users (created, banned, discordId, accountId, username, username_lower, email, password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $banned = 0;
        $stmt->bind_param(
            "sissssss",
            $data['created'],
            $banned,
            $data['discordId'],
            $data['accountId'],
            $data['username'],
            $data['username_lower'],
            $data['email'],
            $data['password']
        );
        return $stmt->execute();
    }
}
