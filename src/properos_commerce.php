<?php

return [
    "router" => [
        "default" => [
            "options" => [
                "prefix" => "/"
            ],
            "middleware" => [
                "public" => [],
                "private" => ['auth'],
                "api/items" => ['auth', 'role:admin'],
                "api/reviews" => ['auth'],
                "api/categories" => ['auth'],
                "orders" => ['auth'],
                "api/admin" => ['auth', 'role:admin'],
                "api/collections" => ['auth', 'role:admin'],
                "admin" => ['auth', 'role:admin'],
            ]
        ]
    ]
];