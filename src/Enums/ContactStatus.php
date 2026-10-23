<?php

namespace Shopmade\Crm\Enums;

enum ContactStatus: string
{
    case New = 'new';
    case Working = 'working';
    case Won = 'won';
    case Lost = 'lost';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Working => 'Working',
            self::Won => 'Won',
            self::Lost => 'Lost',
            self::Archived => 'Archived',
        };
    }
}
