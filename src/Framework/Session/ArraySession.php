<?php

namespace Framework\Session;

/**
 *
 * Cette classe ne sert qu'a des fins de tests de sessions.
 *
 * *

class ArraySession implements SessionInterface
{

    private $session


    /**
     * Récupère une information en session
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
public function get(string $key, $default = null)
{
    if (array_key_exists($key, $this->session)) {
        return $this->session[$key];
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
    $this->session[$key] = $value;
}

    /**
     * Supprime une information en session
     * @param mixed $key
     */
public function delete($key): void
{
    unset($this->session[$key]);
}
}
