<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Guava\Calendar\Widgets\CalendarWidget;
use Guava\Calendar\ValueObjects\Event;
use App\Models\ProgramKerja;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;
use Livewire\Livewire;

class Calendar extends CalendarWidget
{
    protected string $calendarView = 'dayGridMonth';
    // protected bool $eventClickEnabled = true; // ✅ Enable clicking events

    public function getEvents(array $fetchInfo = []): Collection | array
    {
        return ProgramKerja::whereNotNull('date')
            ->get()
            ->map(
                fn ($event) => Event::make()
                    ->title($event->name)
                    ->start($event->date)
                    ->end($event->date)
                    // ->extendedProps(['id' => $event->id]) // ✅ Store event ID inside `extendedProps`
                    ->styles([
                        'color' => 'black', // Text color
                        'background-color' => match ($event->approval?->getStatus() ?? 'pending') {
                            'approved' => '#81C784',   // Green ✅
                            'pending' => '#FFD54F',    // Yellow ⏳
                            'rejected' => '#FF8A65',   // Red ❌
                            default => 'gray',         // Gray (No Status)
                        },
                    ])
            );
    }
}
