<?php

namespace PHPInputValidation;

class Validator
{
    private $rules = [];
    private $data = [];
    private $errors = [];

    /**
     * Validator constructor.
     *
     * @param array $rules
     * @param array $data
     */
    public function __construct(array $rules, array $data)
    {
        $this->rules = $rules;
        $this->data = $data;
    }

    /**
     * Validate the data against the rules.
     *
     * @return bool
     * @throws \Exception
     */
    public function validate()
    {
        foreach ($this->rules as $field => $rules) {
            $rules = explode('|', $rules);

            foreach ($rules as $rule) {
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $ruleValue = isset($ruleParts[1]) ? $ruleParts[1] : null;

                if (!method_exists($this, $ruleName)) {
                    throw new \Exception("Validation rule $ruleName does not exist.");
                }

                $value = isset($this->data[$field]) ? $this->data[$field] : null;
                $this->{$ruleName}($field, $value, $ruleValue);
            }
        }

        return empty($this->errors);
    }

    /**
     * Check if the field is required.
     *
     * @param string $field
     * @param mixed $value
     */
    private function required($field, $value)
    {
        if (empty($value) && $value !== '0') {
            $this->errors[$field][] = 'This field is required.';
        }
    }

    /**
     * Check if the field is min length of the given value.
     *
     * @param string $field
     * @param mixed $value
     * @param string $ruleValue
     */
    private function min($field, $value, $ruleValue)
    {
        if (strlen($value) < (int)$ruleValue) {
            $this->errors[$field][] = "This field must be at least $ruleValue characters.";
        }
    }

    /**
     * Check if the field is max length of the given value.
     *
     * @param string $field
     * @param mixed $value
     * @param string $ruleValue
     */
    private function max($field, $value, $ruleValue)
    {
        if (strlen($value) > (int)$ruleValue) {
            $this->errors[$field][] = "This field must not be more than $ruleValue characters.";
        }
    }

    /**
     * Check if the field is the same as the given field.
     *
     * @param string $field
     * @param mixed $value
     * @param string $ruleValue
     */
    private function same($field, $value, $ruleValue)
    {
        if ($value !== (isset($this->data[$ruleValue]) ? $this->data[$ruleValue] : null)) {
            $this->errors[$field][] = 'This field must match the ' . $ruleValue . ' field.';
        }
    }

    /**
     * Check if the field is nullable.
     *
     * @param string $field
     * @param mixed $value
     * @param string $ruleValue
     */
    private function nullable($field, $value, $ruleValue)
    {
        // No action needed
    }

    /**
     * Check if the field is lowercase.
     *
     * @param string $field
     * @param mixed $value
     */
    private function lowercase($field, $value)
    {
        if ($value !== strtolower($value)) {
            $this->errors[$field][] = 'This field must be in lowercase.';
        }
    }

    /**
     * Check if the field is alphanumeric.
     *
     * @param string $field
     * @param mixed $value
     */
    private function alpha_num($field, $value)
    {
        if (!ctype_alnum($value)) {
            $this->errors[$field][] = 'This field must be alphanumeric.';
        }
    }

    /**
     * Check if the field is uppercase.
     *
     * @param string $field
     * @param mixed $value
     */
    private function uppercase($field, $value)
    {
        if ($value !== strtoupper($value)) {
            $this->errors[$field][] = 'This field must be in uppercase.';
        }
    }

    /**
     * Check if the field is email.
     *
     * @param string $field
     * @param mixed $value
     */
    private function email($field, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = 'This field must be a valid email address.';
        }
    }

    /**
     * Check if the field is URL.
     *
     * @param string $field
     * @param mixed $value
     */
    private function url($field, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $this->errors[$field][] = 'This field must be a valid URL.';
        }
    }

    /**
     * Check if the field is active URL.
     *
     * @param string $field
     * @param mixed $value
     */
    private function active_url($field, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL) || !@fopen($value, 'r')) {
            $this->errors[$field][] = 'This field must be an active URL.';
        }
    }

    /**
     * Check if the field is date with the given format.
     *
     * @param string $field
     * @param mixed $value
     * @param string $ruleValue
     */
    private function date_format($field, $value, $ruleValue)
    {
        $date = \DateTime::createFromFormat($ruleValue, $value);
        if (!$date || $date->format($ruleValue) !== $value) {
            $this->errors[$field][] = 'This field must be a valid date with format ' . $ruleValue . '.';
        }
    }

    /**
     * Check if the field is starts with the given value.
     *
     * @param string $field
     * @param mixed $value
     * @param string $ruleValue
     */
    private function starts_with($field, $value, $ruleValue)
    {
        if (strpos($value, $ruleValue) !== 0) {
            $this->errors[$field][] = 'The value must start with ' . $ruleValue . '.';
        }
    }

    /**
     * Check if the field is ends with the given value.
     *
     * @param string $field
     * @param mixed $value
     * @param string $ruleValue
     */
    private function ends_with($field, $value, $ruleValue)
    {
        if (substr($value, -strlen($ruleValue)) !== $ruleValue) {
            $this->errors[$field][] = 'The value must end with ' . $ruleValue . '.';
        }
    }

    /**
     * Check if the field is containing the given value.
     *
     * @param string $field
     * @param mixed $value
     * @param string $ruleValue
     */
    private function regex($field, $value, $ruleValue)
    {
        if (!preg_match($ruleValue, $value)) {
            $this->errors[$field][] = 'The value does not match the required pattern.';
        }
    }

    /**
     * Check if the field is numeric.
     *
     * @param string $field
     * @param mixed $value
     */
    private function numeric($field, $value)
    {
        if (!is_numeric($value)) {
            $this->errors[$field][] = 'This field must be a numeric value.';
        }
    }

    /**
     * Check if the field is an integer.
     *
     * @param string $field
     * @param mixed $value
     */
    private function integer($field, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_INT) !== false) {
            $this->errors[$field][] = 'This field must be an integer.';
        }
    }

    /**
     * Check if the value is in the given list.
     *
     * @param string $field
     * @param mixed $value
     */
    private function in($field, $value, $ruleValue)
    {
        $allowedValues = explode(',', $ruleValue);
        if (!in_array($value, $allowedValues, true)) {
            $this->errors[$field][] = 'This field must be one of the following values: ' . $ruleValue . '.';
        }
    }

    /**
     * Check if the values are unique in the given list.
     *
     * @param string $field
     * @param mixed $value
     */
    private function distinct($field, $value)
    {
        if (is_array($value) && count($value) !== count(array_unique($value))) {
            $this->errors[$field][] = 'This field must have distinct values.';
        }
    }

    /**
     * Check if the field is between the given values.
     *
     * @param string $field
     * @param mixed $value
     */
    private function between($field, $value, $ruleValue)
    {
        list($min, $max) = explode(',', $ruleValue);
        if ($value < $min || $value > $max) {
            $this->errors[$field][] = "This field must be between $min and $max.";
        }
    }


    /**
     * Check if the field is a valid JSON string.
     *
     * @param string $field
     * @param mixed $value
     */
    private function json($field, $value)
    {
        json_decode($value);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->errors[$field][] = 'This field must be a valid JSON string.';
        }
    }

    public function errors()
    {
        return $this->errors;
    }
}
