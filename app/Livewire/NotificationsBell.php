<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationsBell extends Component
{
    public int $unread = 0;

    public function markRead($id)
    {
        $n = auth()->user()->notifications()->find($id);

        if ($n) {
            $n->markAsRead();
            $code = $n->data['card_number'] ?? null;
            if ($code) {
                return redirect()->route('maintenance.index', ['search' => $code]);
            }
        }
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $user = auth()->user();
        $this->unread = $user->unreadNotifications()->count();

        return view('livewire.notifications-bell', [
            'items' => $user->notifications()->latest()->take(12)->get(),
        ]);
    }
}
