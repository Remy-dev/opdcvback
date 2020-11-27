<?php

namespace App\Message;

class CommentMessage
{
    private $id;
    private $context;

    public function __construct(int $id)
    {
        $this->id = $id;

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}