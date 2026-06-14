<?php
require_once __DIR__ . '/../models/User.php';

class AuthController
{
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($email === '' || $password === '') {
                $error = 'Veuillez renseigner votre email et votre mot de passe.';
                require_once __DIR__ . '/../views/layout/login.php';
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Format d\'email invalide.';
                require_once __DIR__ . '/../views/layout/login.php';
                return;
            }

            $result = User::authenticate($email, $password);

            if ($result) {
                $_SESSION['user_id'] = $result->getId();
                $_SESSION['user_role'] = $result->getRole();
                $_SESSION['user_prenom'] = $result->getPrenom();
                $_SESSION['user_nom'] = $result->getNom();

                if ($result->isTechnicien()) {
                    header('Location: index.php?page=technicien_dashboard');
                } else {
                    header('Location: index.php?page=user_dashboard');
                }
                exit;
            }

            $error = 'Identifiants incorrects.';
            require_once __DIR__ . '/../views/layout/login.php';
            return;
        }

        require_once __DIR__ . '/../views/layout/login.php';
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
