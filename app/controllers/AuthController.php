<?php
class AuthController extends Controller
{
    public function login(): void
    {
        if ($this->currentUser()) {
            redirect(url());
        }
        $errors = [];
        $username = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            $username = clean($_POST['username'] ?? '');
            $password = (string) ($_POST['password'] ?? '');
            $user = (new UserModel())->findByUsername($username);
            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $user['id'];
                flash('success', 'Welcome back, ' . $user['full_name'] . '!');
                redirect(url());
            }
            $_SESSION['auth_login_error'] = 'Invalid username or password.';
            $_SESSION['auth_login_username'] = $username;
            redirect(url('auth', 'login'));
        }

        if (!empty($_SESSION['auth_login_error'])) {
            $errors[] = $_SESSION['auth_login_error'];
            $username = $_SESSION['auth_login_username'] ?? '';
            unset($_SESSION['auth_login_error'], $_SESSION['auth_login_username']);
        }

        $this->view('auth/login', ['errors' => $errors, 'username' => $username, 'title' => 'Login', 'layout' => 'auth']);
    }

    public function register(): void
    {
        if ($this->currentUser()) {
            redirect(url());
        }
        $errors = [];
        $old = ['username' => '', 'full_name' => ''];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            $users = new UserModel();
            $old['username'] = clean($_POST['username'] ?? '');
            $old['full_name'] = clean($_POST['full_name'] ?? '');
            $password = (string) ($_POST['password'] ?? '');
            $confirm = (string) ($_POST['confirm'] ?? '');

            if (!preg_match('/^[A-Za-z0-9_]{3,20}$/', $old['username'])) {
                $errors[] = 'Username must be 3-20 characters: letters, numbers, underscore.';
            } elseif ($users->findByUsername($old['username'])) {
                $errors[] = 'That username is already taken.';
            }
            if ($old['full_name'] === '' || mb_strlen($old['full_name']) > 100) {
                $errors[] = 'Full name is required (max 100 characters).';
            }
            if (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters.';
            }
            if ($password !== $confirm) {
                $errors[] = 'Passwords do not match.';
            }

            $image = null;
            if (!$errors) {
                $error = null;
                $image = upload_image($_FILES['profile_image'] ?? [], $error);
                if ($error) {
                    $errors[] = $error;
                }
            }

            if (!$errors) {
                $id = $users->create($old['username'], password_hash($password, PASSWORD_DEFAULT), $old['full_name'], $image);
                session_regenerate_id(true);
                $_SESSION['user_id'] = $id;
                flash('success', 'Account created. Welcome to OmniSphere!');
                redirect(url());
            }

            $_SESSION['auth_reg_errors'] = $errors;
            $_SESSION['auth_reg_old'] = $old;
            redirect(url('auth', 'register'));
        }

        if (!empty($_SESSION['auth_reg_errors'])) {
            $errors = $_SESSION['auth_reg_errors'];
            $old = $_SESSION['auth_reg_old'] ?? $old;
            unset($_SESSION['auth_reg_errors'], $_SESSION['auth_reg_old']);
        }

        $this->view('auth/register', ['errors' => $errors, 'old' => $old, 'title' => 'Register', 'layout' => 'auth']);
    }

    public function logout(): void
    {
        $this->requirePost();
        $_SESSION = [];
        session_destroy();
        session_start();
        flash('info', 'You have been logged out.');
        redirect(url('auth', 'login'));
    }
}
