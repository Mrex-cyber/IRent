<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'IRent API',
    description: 'API documentation for IRent application',
    contact: new OA\Contact(
        email: 'support@irent.com'
    )
)]
#[OA\Server(
    url: 'http://localhost:8000/api',
    description: 'Local development server'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    name: 'Authorization',
    in: 'header',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
class Controller
{
    //
}
