<?php

namespace Framework\Session;

interface SessionInterface
{
    /**
     * Récupère une information en session
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Ajoute une information en session
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value): void;

    /**
     * Supprime une information en session
     * @param mixed $key
     */
    public function delete($key): void;
}
