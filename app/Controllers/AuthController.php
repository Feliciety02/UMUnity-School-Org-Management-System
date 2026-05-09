<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }

        $this->render('auth/login', [
            'title' => 'Login',
            'error' => app()->session()->consumeFlash('error'),
            'success' => app()->session()->consumeFlash('success'),
        ]);
    }

    public function login(): void
    {
        $email = trim((string)($_POST['email'] ?? ''));
        $password = trim((string)($_POST['password'] ?? ''));
        $db = app()->db();

        $stmt = $db->prepare("
            SELECT users.user_id, users.full_name, users.password, roles.name AS role
            FROM users
            LEFT JOIN roles ON users.role_id = roles.role_id
            WHERE users.email = ?
            LIMIT 1
        ");

        if (!$stmt) {
            app()->session()->flash('error', 'Database error. Please try again.');
            $this->redirect('/login');
        }

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();

        if (!$user || !verify_user_password($password, (string)$user['password'])) {
            app()->session()->flash('error', 'Invalid email or password.');
            $this->redirect('/login');
        }

        if (!password_verify($password, (string)$user['password'])) {
            $hashedPassword = hash_user_password($password);
            $update = $db->prepare('UPDATE users SET password = ? WHERE user_id = ?');
            if ($update) {
                $update->bind_param('si', $hashedPassword, $user['user_id']);
                $update->execute();
                $update->close();
            }
        }

        Auth::login($user);

        if (strtolower((string)$user['role']) === 'admin') {
            logActivity((int)$user['user_id'], 'Admin Login', "{$user['full_name']} logged into the system");
        }

        $this->redirect('/dashboard');
    }

    public function showRegister(): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }

        $this->render('auth/register', [
            'title' => 'Register',
            'error' => app()->session()->consumeFlash('error'),
            'success' => app()->session()->consumeFlash('success'),
        ]);
    }

    public function register(): void
    {
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $role = (string)($_POST['role'] ?? 'student');
        $roleId = $role === 'leader' ? 2 : 3;
        $db = app()->db();

        if ($name === '' || $email === '' || $password === '') {
            app()->session()->flash('error', 'All fields are required.');
            $this->redirect('/register');
        }

        $check = $db->prepare('SELECT user_id FROM users WHERE email = ? LIMIT 1');
        $check->bind_param('s', $email);
        $check->execute();
        $existing = $check->get_result()->fetch_assoc();
        $check->close();

        if ($existing) {
            app()->session()->flash('error', 'Email already exists.');
            $this->redirect('/register');
        }

        $hashedPassword = hash_user_password($password);
        $stmt = $db->prepare('INSERT INTO users (full_name, email, password, role_id, status) VALUES (?, ?, ?, ?, ?)');
        $status = 'active';
        $stmt->bind_param('sssis', $name, $email, $hashedPassword, $roleId, $status);

        if (!$stmt->execute()) {
            $stmt->close();
            app()->session()->flash('error', 'Registration failed. Please try again.');
            $this->redirect('/register');
        }

        $stmt->close();
        app()->session()->flash('success', 'Registration successful. You can sign in now.');
        $this->redirect('/login');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/login');
    }
}
