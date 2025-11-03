<?php
declare(strict_types=1);

namespace NetVOD\src\Exception;

class InvalidArgumentException extends \Exception
{

    /**
     * @param string|null $message message d'erreur
     */
    public function __construct(?string $message) {
        parent::__construct($message);
    }

}