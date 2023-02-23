<?php
namespace Framework\Validator;

class ValidationError
{
    private $key;
    private $rule;

    private $attributes;

    private $message = [
        'required' => 'Le champ %s est requis',
        'empty' => 'Le champs %s ne dois pes être vide',
        'slug' => 'Le champs %s n\'est pas un slug valide',
        'minLength' => 'Le champs %s doit contenir plus de %d caractères',
        'maxLength' => 'Le champs %s doit contenir moins de %d caractères',
        'betweenLength' => 'Le champs %s doit contenir entre %d et %d caractères',
        'datetime' => 'Le champ %s doit être une date valide (%s)',
        'exists' => 'Le champs %s n\'existe pas dans la table %s'
    ];

    public function __construct(string $key, string $rule, array $attributes = [])
    {
        $this->key = $key;
        $this->rule = $rule;
        $this->attributes = $attributes;
    }

    public function __toString()
    {
        $params = array_merge([$this->message[$this->rule], $this->key], $this->attributes);
        return (string)call_user_func_array('sprintf', $params);
    }
}
