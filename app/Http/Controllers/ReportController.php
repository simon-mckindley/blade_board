<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reportable_type' => 'required|string|in:post,comment',
            'reportable_id' => 'required|integer|exists:' . $this->resolveModelTable($request->reportable_type) . ',id',
            'reason' => 'required|in:' . implode(',', array_column(ReportReason::cases(), 'value')),
            'description' => 'nullable|string|max:100',
        ]);

        try {
            $report = new Report();
            $report->user_id = Auth::id();
            $report->reportable_type = $this->resolveModelClass($validated['reportable_type']);
            $report->reportable_id = $validated['reportable_id'];
            $report->reason = $validated['reason'];
            $report->description = $validated['description'] ?? null;
            $report->status = ReportStatus::Pending->value;
            $report->save();

            return redirect()->back()->with('alert', [
                'type' => 'success',
                'message' => 'Report submitted successfully.',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'message' => 'There was an issue submitting the report: ' . $e->getMessage(),
            ]);
        }
    }


    private function resolveModelClass(string $type): string
    {
        return match (strtolower($type)) {
            'post' => \App\Models\Post::class,
            'comment' => \App\Models\Comment::class,
            default => throw new \InvalidArgumentException('Invalid reportable type.')
        };
    }

    private function resolveModelTable(string $type): string
    {
        return match (strtolower($type)) {
            'post' => 'posts',
            'comment' => 'comments',
            default => throw new \InvalidArgumentException('Invalid reportable type.')
        };
    }
}
