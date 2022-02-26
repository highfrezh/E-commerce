<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
        '/admin/check-current-pwd', 
        'admin/update-section-status',
        '/admin/update-category-status',//Added by me excepting this route from CSRF token 
        '/admin/append-categories-level',//Added by me excepting this route from CSRF token 
        '/admin/update-product-status',//Added by me excepting this route from CSRF token 
        '/admin/update-attribute-status',//Added by me excepting this route from CSRF token 
        '/admin/update-image-status',//Added by me excepting this route from CSRF token 
        '/admin/update-brand-status',//Added by me excepting this route from CSRF token 
        '/admin/update-banner-status',//Added by me excepting this route from CSRF token 
    ];
}
