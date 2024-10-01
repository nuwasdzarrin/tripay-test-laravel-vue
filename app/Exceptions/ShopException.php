<?php

namespace App\Exceptions;

use Exception;

class ShopException extends Exception
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function render()
    {
        return response()->json([
            'success' => false,
            'message' => $this->message,
        ], 400);
    }
}
