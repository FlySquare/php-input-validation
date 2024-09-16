
# PHP Input Validation Library

This is a simple PHP library that validates user input based on predefined rules. It supports multiple validation methods such as required fields, string lengths, matching fields, email format, numeric values, and many others.

## Installation

You can install this library via [Composer](https://getcomposer.org/).

### Install via Composer

```bash
composer require flysquare/php-input-validation
```

### Manual Installation

1. Clone this repository or download the `Validator.php` file.
2. Include the `Validator.php` file in your project.

```php
require_once 'Validator.php';
```

## Usage

### Initial Setup

Create an instance of the `Validator` class by passing the rules and data to be validated:

```php
use PHPInputValidation\Validator;

$rules = [
    'name' => 'required|min:3|max:20',
    'email' => 'required|email',
    'password' => 'required|min:6',
    'confirm_password' => 'same:password'
];

$data = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'password123',
    'confirm_password' => 'password123'
];

$validator = new Validator($rules, $data);
if ($validator->validate()) {
    // Validation passed
} else {
    // Validation failed
    print_r($validator->errors());
}
```

### Supported Validation Rules

- `required`: The field must not be empty.
- `min:n`: The field must have at least `n` characters.
- `max:n`: The field must have at most `n` characters.
- `same:field`: The field must match the given `field`.
- `email`: The field must be a valid email address.
- `url`: The field must be a valid URL.
- `numeric`: The field must be numeric.
- `integer`: The field must be an integer.
- `between:min,max`: The field must be between the given minimum and maximum values.
- `alpha_num`: The field must be alphanumeric.
- `starts_with:value`: The field must start with the given value.
- `ends_with:value`: The field must end with the given value.
- `distinct`: The field must contain distinct values (for arrays).
- `json`: The field must be a valid JSON string.

### Error Handling

The `errors()` method returns an array of validation errors:

```php
$errors = $validator->errors();
print_r($errors);
```

Each field's errors will be stored under the corresponding field name in the errors array.

## License

This project is licensed under the MIT License. See the LICENSE file for more details.
