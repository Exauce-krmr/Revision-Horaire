<?php

enum Type{
    case string;
    case email;
    case int;

}

function sanitizeInput($value, Type $type)
{
    $value = trim($value);
    $value = htmlspecialchars($value);

    return match ($type) {
        Type::email => filter_var($value, FILTER_SANITIZE_EMAIL),
        Type::int => filter_var($value, FILTER_SANITIZE_NUMBER_INT),
        Type::string => filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS)
    };
}