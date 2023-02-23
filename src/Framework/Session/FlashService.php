<?php
namespace Framework\Session;

class FlashService
{

    private $session;

    // Nom de la clé utilisée pour stocker les messages flash en session
    private $sessionKey = 'flash';

    // Variable pour stocker les messages flash actuels
    private $message = null;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    // Méthode pour enregistrer un message flash de type "success"
    public function success(string $message)
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['success'] = $message;

        $this->session->set($this->sessionKey, $flash);
    }

    // Méthode pour enregistrer un message flash de type "error"
    public function error(string $message)
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['error'] = $message;

        $this->session->set($this->sessionKey, $flash);
    }

    // Méthode pour récupérer un message flash de type donné
    public function get(string $type): ?string
    {
        // Si les messages flash n'ont pas encore été récupérés de la session,
        // on les récupère et on supprime la clé correspondante en session
        if (is_null($this->message)) {
            $this->message = $this->session->get($this->sessionKey, []);
            $this->session->delete($this->sessionKey);
        }

        // Si un message flash de type donné existe, on le retourne
        if (array_key_exists($type, $this->message)) {
            return $this->message[$type];
        }

        // Si aucun message flash de type donné n'existe, on retourne null
        return null;
    }
}
