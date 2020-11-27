<?php

namespace App\EntityListener;

use App\Entity\Comment;
use Doctrine\ORM\Event\LifecycleEventArgs;

use Symfony\Component\Messenger\MessageBusInterface;

class CommentEntityListener
{
    private $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function postPersist(Comment $comment, LifecycleEventArgs $event)
    {
        $comment->show($this->bus);
    }


}