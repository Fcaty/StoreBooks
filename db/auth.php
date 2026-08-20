<?php
    class Auth {
        private $database;

        public function __construct($database) {
            $this->database = $database;
        }

        public function register($name, $email, $password, $confirm) {
            $name = trim($name);
            $email = trim($email);

            if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
                return ['success' => false, 'message' => 'Please fill out all fields.'];
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Please enter a valid email address.'];
            }

            if (strlen($password) < 8) {
                return ['success' => false, 'message' => 'Password must be at least 8 characters.'];
            }

            if ($password !== $confirm) {
                return ['success' => false, 'message' => 'Passwords do not match.'];
            }

            $this->database->select('users', 'user_id', ['user_email' => $email]);
            if ($this->database->res->num_rows > 0) {
                return ['success' => false, 'message' => 'An account with that email already exists.'];
            }

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $this->database->insert('users', [
                'user_name' => $name,
                'user_role' => 'Customer',
                'user_email' => $email,
                'user_password' => $hashed
            ]);

            return ['success' => true, 'message' => 'Account created! You can now log in.'];
        }

        /** 
         * Verifies credentials and, if valid, starts the user's session.
         * Returns ['success' => bool, 'message'|'redirect' => string].
        */
        public function login($email, $password) {
            $email = trim($email);

            if (empty($email) || empty($password)) {
                return ['success' => false, 'message' => 'Please enter your email and password.'];
            }

            $this->database->select('users', '*', ['user_email' => $email]);
            $user = $this->database->res->fetch_assoc();

            if (!$user || !password_verify($password, $user['user_password'])) {
                return ['success' => false, 'message' => 'Incorrect email or password.'];
            }

            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['user_role'] = $user['user_role'];

            // Admins land on the main dashboard, everyone else lands in the shop
            $redirect = ($user['user_role'] === 'Admin') ? 'index.php' : 'shop.php';

            return ['success' => true, 'redirect' => $redirect];
        }
    }
?>