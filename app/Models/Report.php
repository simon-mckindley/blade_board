<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ReportStatus;
use App\Enums\ReportReason;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'description',
        'status',
        'action_text',
    ];

    public function reportable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isCompleted() : bool 
    {
        return $this->status === ReportStatus::Dismissed || $this->status === ReportStatus::ActionTaken;
    }

    protected $casts = [
        'status' => ReportStatus::class,
        'reason' => ReportReason::class,
    ];


}
