<?php
// src/Service/AuthService.php
namespace Src\Service;

use Src\DAO\UserDAO;
use Src\Helper\SecurityHelper;

final class AuthService
{
    public function __construct(private UserDAO $users) {}

    public function login(string $email, string $password): array
    {
        $user = $this->users->findByEmail($email);
        if (!$user) {
            return ['ok' => false, 'error' => 'Invalid credentials.'];
        }
        if (!SecurityHelper::verifyPassword($password, $user->getPassword())) {
            return ['ok' => false, 'error' => 'Invalid credentials.'];
        }

        SecurityHelper::login($user);
        return ['ok' => true, 'user' => $user];
    }

    public function logout(): void
    {
        SecurityHelper::logout();
    }

    public function currentUser(): ?array
    {
        return SecurityHelper::currentUser();
    }
}
