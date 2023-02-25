<?php

namespace Framework\Session;

class PHPSession implements SessionInterface, \ArrayAccess
{

    /**
     * Assure que le Session est d�marrer
     */
    private function ensureStared()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * R�cup�re une information en session
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

    /**
     * @param mixed $offset
     */
    public function offsetExists($offset): bool
    {
        $this->ensureStared();
        return array_key_exists($offset, $_SESSION);
    }

    /**
     * @param mixed $offset
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset) :void
    {
        $this->delete($offset);
    }
}
