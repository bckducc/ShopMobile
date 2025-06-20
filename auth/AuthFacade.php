<?php
// auth/AuthFacade.php
require_once __DIR__ . '/../db/Database.php';

class AuthService {
    public function login($username, $password) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND password = MD5(?)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            $_SESSION["user"] = $user;
            return true;
        }
        return false;
    }

    public function logout() {
        session_destroy();
    }

    public function currentUser() {
        return $_SESSION["user"] ?? null;
    }
}
