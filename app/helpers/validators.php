<?php

function required(string $value): bool
{
    return trim($value) !== '';
}

function validate_email(string $value): bool
{
    return (bool) filter_var($value, FILTER_VALIDATE_EMAIL);
}
