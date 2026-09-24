<?php

enum Type{
    case string;
    case email;
    case int;
}

function sanitizeInput($value, Type $type)
{
    $value = trim($value);

    return match ($type) {
        Type::email => filter_var($value, FILTER_SANITIZE_EMAIL),
        Type::int => filter_var($value, FILTER_SANITIZE_NUMBER_INT),
        Type::string => htmlspecialchars($value, ENT_QUOTES, "UTF-8"),
    };
}