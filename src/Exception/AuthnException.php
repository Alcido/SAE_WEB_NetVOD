<?php

namespace NetVOD\src\Exception;

class AuthnException extends \Exception {

    public function __construct(?string $message) {
        parent::__construct($message);
    }

}