<?php

namespace App\Enums;

// This enum represents the different statuses a report can have in the application.
enum ReportStatus: string
{
    case Pending = 'pending';
    case Reviewed = 'reviewed';
    case ActionTaken = 'action_taken';
    case Dismissed = 'dismissed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending review',
            self::Reviewed => 'Reviewed',
            self::ActionTaken => 'Action taken',
            self::Dismissed => 'Dismissed',
        };
    }
}