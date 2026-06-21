<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class CardStageNotification extends Notification
{
    public function __construct(
        public int $cardId,
        public string $cardNumber,
        public string $messageKey,
        public string $icon = 'notifications'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'card_id'     => $this->cardId,
            'card_number' => $this->cardNumber,
            'message_key' => $this->messageKey,
            'icon'        => $this->icon,
        ];
    }
}
