<?php

namespace App\Enums;

enum ReportReason: string
{
    case Spam = 'spam';
    case Harassment = 'harassment';
    case HateSpeech = 'hate_speech';
    case Misinformation = 'misinformation';
    case InappropriateContent = 'inappropriate_content';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Spam => 'Spam or unsolicited content',
            self::Harassment => 'Harassment or bullying',
            self::HateSpeech => 'Hate speech or discrimination',
            self::Misinformation => 'False or misleading information',
            self::InappropriateContent => 'Sexual or violent content',
            self::Other => 'Other (please describe)',
        };
    }
}
