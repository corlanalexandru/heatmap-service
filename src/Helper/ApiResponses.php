<?php


namespace App\Helper;


class ApiResponses
{
    public CONST VALIDATION_FAILED = array(
        'CODE' => 400,
        'MESSAGE' => 'Validation Failed!',
    );

    public CONST RESOURCE_NOT_FOUND = array(
        'CODE' => 404,
        'MESSAGE' => 'Resource not found!',
    );

    public CONST RESOURCE_CREATED = array(
        'CODE' => 201,
        'MESSAGE' => 'Resource created!',
    );

    public CONST REQUEST_OK = array(
        'CODE' => 200,
        'MESSAGE' => 'The request has succeeded!',
    );
}