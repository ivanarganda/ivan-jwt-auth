<?php 

class AuthService
{
    public $user = null;
    public function __construct($user)
    {
        $this->user = $user;
    }

    public static function authenticate($email, $password)
    {
        if ($this->user->getEmail() === $email && password_verify($password, $this->user->getPassword())) {
            return true;
        }
        return false;
    }

    public static function generateToken()
    {
        $payload = [
            'sub' => $this->user->getId(),
            'email' => $this->user->getEmail(),
            'iat' => time(),
            'exp' => time() + (60 * 60) // Token expires in 1 hour
        ];

        return JWT::encode($payload, 'your_secret_key');
    }

    public static function verifyToken($token)
    {
        try {
            $decoded = JWT::decode($token, 'your_secret_key', ['HS256']);
            return $decoded;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function refreshToken($token)
    {
        $decoded = $this->verifyToken($token);
        if ($decoded) {
            return $this->generateToken();
        }
        return null;
    }

    public static function revokeToken($token)
    {
        // In a real application, you would store revoked tokens in a database or cache
        // and check against that list when verifying tokens.
        return true;
    }

    public static function isAuthenticated($token)
    {
        return $this->verifyToken($token) !== null;
    }
}

?>