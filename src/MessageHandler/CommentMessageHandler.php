<?php

namespace App\MessageHandler;

use App\Message\CommentMessage;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Workflow\WorkflowInterface;


class CommentMessageHandler implements MessageHandlerInterface
{
    private $commentRepository;
    private $bus;
    private $workflow;
    private $mailer;
    private $adminEmail;
    private $logger;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, CommentRepository $commentRepository, MessageBusInterface $bus, WorkflowInterface $commentStateMachine, MailerInterface $mailer, string $adminEmail, LoggerInterface $logger = null)
    {
        $this->entityManager = $entityManager;

        $this->commentRepository = $commentRepository;
        $this->bus = $bus;
        $this->workflow = $commentStateMachine;
        $this->mailer = $mailer;
      //  $this->adminEmail = $adminEmail;
        $this->logger = $logger;
    }
    public function __invoke(CommentMessage $message)
    {
        $this->logger->notice('coucouuuu');
        $comment = $this->commentRepository->find($message->getId());

        if ($this->workflow->can($comment, 'accept')) {

            $transition = 'accept';

            $this->workflow->apply($comment, $transition);
            $this->entityManager->flush();

            $this->bus->dispatch($message);
        }elseif ($this->workflow->can($comment, 'publish') || $this->workflow->can($comment, 'publish_ham')) {

            $this->mailer->send((new TemplatedEmail())
                ->subject('New comment posted')
                ->htmlTemplate('emails/comment_notification.html.twig')
                ->from('alienmailer@example.com')
                ->to($comment->getAuthor()->getEmail())
                ->context(['comment' => $comment])
            );
        }elseif ($this->logger) {
            $this->logger->debug('Dropping comment message', ['comment' => $comment->getId(), 'state' => $comment->getState()]);
        }
    }
}