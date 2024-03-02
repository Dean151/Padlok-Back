<?php

declare(strict_types=1);

namespace App\Handler\Feedback;

use App\ApiPlatform\Dto\Feedback\Feedback;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

#[AutoconfigureTag(name: FeedbackHandlerInterface::TAG)]
final class MailFeedbackHandler implements FeedbackHandlerInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
    ) {
    }

    public function handle(Feedback $feedback): void
    {
        $from = new Address('bot@illumineering.fr', 'Illumineering Bot (legacy)');
        $to = 'padlok@illumineering.fr';

        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject('Feedback received!')
            ->text($feedback->__toString());

        if ($sender = $feedback->email) {
            $email->replyTo($sender);
        }
        $this->mailer->send($email);
    }
}