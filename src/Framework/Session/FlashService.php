<?php
namespace Framework\Session;

class FlashService
{

    private $session;

    // Nom de la cl� utilis�e pour stocker les messages flash en session
    private $sessionKey = 'flash';

    // Variable pour stocker les messages flash actuels
    private $message = null;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    // M�thode pour enregistrer un message flash de type "success"
    public function success(string $message)
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['success'] = $message;

        $this->session->set($this->sessionKey, $flash);
    }

    // M�thode pour enregistrer un message flash de type "error"
    public function error(string $message)
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['error'] = $message;

        $this->session->set($this->sessionKey, $flash);
    }

    // M�thode pour r�cup�rer un message flash de type donn�
    public function get(string $type): ?string
    {
        // Si les messages flash n'ont pas encore �t� r�cup�r�s de la session,
        // on les r�cup�re et on supprime la cl� correspondante en session
        if (is_null($this->message)) {
            $this->message = $this->session->get($this->sessionKey, []);
            $this->session->delete($this->sessionKey);
        }

        // Si un message flash de type donn� existe, on le retourne
        if (array_key_exists($type, $this->message)) {
            return $this->message[$type];
        }

        // Si aucun message flash de type donn� n'existe, on retourne null
        return null;
    }
}
