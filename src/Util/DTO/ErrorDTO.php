<?php

declare(strict_types=1);

namespace App\Util\DTO;

class ErrorDTO
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $url;

    public function __construct(string $message, string $url)
    {
        $this->message = $message;
        $this->url = $url;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getUrl(): string {
        return $this->url;
    }
}
