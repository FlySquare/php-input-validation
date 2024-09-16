<?php

use PHPInputValidation\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testValidationSuccess()
    {
        $rules = [
            'username' => 'required|min:3|max:15|alpha_num',
            'email' => 'required|email',
            'age' => 'numeric|between:18,99',
            'password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required|min:6',
            'website' => 'url',
            'json_field' => 'json',
            'date' => 'date_format:Y-m-d',
            'regex_field' => 'regex:/^([0-9]+)$/'
        ];

        $data = [
            'username' => 'user123',
            'email' => 'user@example.com',
            'age' => 25,
            'password' => 'password123',
            'confirm_password' => 'password123',
            'website' => 'https://example.com',
            'json_field' => '{"key":"value"}',
            'date' => '2021-01-01',
            'regex_field' => '123'
        ];
        $validator = new Validator($rules, $data);
        $this->assertTrue($validator->validate());
        $this->assertEmpty($validator->errors());
    }

    public function testValidationFailure()
    {
        $rules = [
            'username' => 'required|min:3|max:15|alpha_num',
            'email' => 'required|email',
            'age' => 'numeric|between:18,99',
            'password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required|min:6',
            'website' => 'url',
            'json_field' => 'json',
            'date' => 'date_format:Y-m-d',
            'regex_field' => 'regex:/^([0-9]+)$/'
        ];

        $data = [
            'username' => 'user@123', // Invalid
            'email' => 'invalid-email', // Invalid
            'age' => 17, // Invalid
            'password' => 'short', // Invalid
            'confirm_password' => 'different', // Invalid
            'website' => 'invalid-url', // Invalid
            'json_field' => '{"key":value}', // Invalid
            'date' => '01-01-2021', // Invalid
            'regex_field' => 'abc' // Invalid
        ];

        $validator = new Validator($rules, $data);
        $this->assertFalse($validator->validate());
        $this->assertNotEmpty($validator->errors());
    }
}
