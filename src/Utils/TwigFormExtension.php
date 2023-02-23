<?php
namespace Utils;

use Twig\TwigFunction;

class TwigFormExtension extends \Twig\Extension\AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('field', [$this, 'field'], [
            'is_safe' => ['html'],
            'needs_context' => true,
            ])
        ];
    }

    public function field(array $context, string $key, $value, string $label = null, array $options = []): string
    {
        $type = $options['type'] ?? 'text';
        $error = $this->getErrorHtml($context, $key);
        $class = 'form-group';
        $value = $this->convertValue($value);
        $inputClass = [];
        $attributes = [
            'class' => trim('form-control ' . ($options['class'] ?? '')),
            'name' => $key,
            'id' => $key,
        ];

        if ($error) {
            $class .= ' has-validation';
            $attributes['class'] .= ' is-invalid';
        }

        if ($type === 'textarea') {
            $input = $this->textarea($value, $attributes);
        } else {
            $input = $this->input($value, $attributes);
        }

        return "<div class=\"" . $class . "\">
                <label for=\"name\">{$label}</label>
                {$input}
                {$error}
            </div>";
    }

    private function convertValue($value): string
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i:s');
        }
        return (string)$value;
    }

    private function getErrorHtml($context, $key)
    {
        $error = $context['errors'][$key] ?? false;

        if ($error) {
            return "<small class=\"form-text invalid-feedback\">{$error}</small>";
        }

        return "";
    }

    private function input(?string $value, array $attributes): string
    {
        return "<input type=\"text\" " . $this->getHtmlFromArray($attributes) . " value=\"{$value}\">";
    }

    private function textarea(?string $value, array $attributes): string
    {
        return "<textarea " . $this->getHtmlFromArray($attributes) . " rows=\"10\">$value</textarea>";
    }

    private function getHtmlFromArray(array $attributes)
    {
        return implode(' ', array_map(function ($key, $value) {
            return "$key=\"$value\"";
        }, array_keys($attributes), $attributes));
    }
}
