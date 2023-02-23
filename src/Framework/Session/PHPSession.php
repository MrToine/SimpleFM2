<?php

namespace Framework\Session;

class PHPSession implements SessionInterface
{

    /**
     * Assure que le Session est démarrer
     */
    private function ensureStared()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Récupère une information en session
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $this->ensureStared();
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }

        return $default;
    }

    /**
     * Ajoute une information en session
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value): void
    {
        $this->ensureStared();
        $_SESSION[$key] = $value;
    }

    /**
     * Supprime une information en session
     * @param mixed $key
     */
    public function delete($key): void
    {
        $this->ensureStared();
        unset($_SESSION[$key]);
    }
}
