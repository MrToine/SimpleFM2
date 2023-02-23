<?php
namespace Framework;

use Framework\Validator\ValidationError;

class Validator
{

    private $params;

    /**
     * Summary of $errors
     * @var string[]
     */
    private $errors = [];

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * V�rifie que les champs sont pr�sent dans le tableau
     * @param string[] $keys
     * @return Validator
     */
    public function required(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value)) {
                $this->addError($key, 'required');
            }
        }

        return $this;
    }

    /**
     * V�rifie que le champs n'est pas vide
     * @param string[] $keys
     * @return Validator
     */
    public function notEmpty(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value) || empty($value)) {
                $this->addError($key, 'empty');
            }
        }

        return $this;
    }

    public function length(string $key, ?int $min, ?int $max = null): self
    {
        $value = $this->getValue($key);
        $length = mb_strlen($value);

        if (!is_null($min) &&
            !is_null($max) &&
            ($length < $min || $length > $max)
            ) {
            $this->addError($key, 'betweenLength', [$min, $max]);
            return $this;
        }

        if (!is_null($min) &&
            $length < $min
            ) {
            $this->addError($key, 'minLength', [$min]);
            return $this;
        }

        if (!is_null($max) &&
            $length > $max
            ) {
            $this->addError($key, 'maxLength', [$max]);
            return $this;
        }

        return $this;
    }

    /**
     * V�rifie que l'�l�ment est bien un slug
     * @param string $key
     * @return Validator
     */
    public function slug(string $key): self
    {
        $value = $this->getValue($key);
        if (is_null($value)) {
            /**
             * On r�cup�re la valeur et si elle  est nulle, alors on retourne quand m�me la r�gle pour �viter une erreur au cas o� le slug n'existe pas.
             * */
            return $this;
        }

        $pattern = '/^[a-z0-9]+(-[a-z0-9]+)*$/';
        if (!preg_match($pattern, $this->params[$key])) {
            $this->addError($key, 'slug');
        }

        return $this;
    }

    /**
     * V�rifie que l'�l�ment est bien une date
     * @param string $key
     * @return Validator
     */
    public function dateTime(string $key, string $format = "'Y-m-d H:i:s'"): self
    {
        $value = $this->getValue($key);
        $datetime = \DateTime::createFromFormat($format, $value);
        $errors = \DateTime::getLastErrors();
        if ($errors['error_count'] > 0 || $errors['warning_count'] > 0 || $datetime === false) {
            $this->addError($key, 'datetime', [$format]);
        }

        return $this;
    }

    /**
     * V�rifie que le formulaire soumis est valide au niveau des r�gles de validations
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * R�cup�re les erreurs
     * @return ValidationError[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Ajoute une erreur
     * @param string $key
     * @param string $rule
     * @param array $attributes
     */
    private function addError(string $key, string $rule, array $attributes = [])
    {
        $this->errors[$key] = new ValidationError($key, $rule, $attributes);
    }

    /**
     * retourne la valeur d'une cl�
     * @param string $key
     * @return mixed
     */
    private function getValue(string $key)
    {
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }

        return null;
    }
}
