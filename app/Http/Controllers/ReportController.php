<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Show a list of the resources
     */
    public function index()
    {
        $reports = Report::with(['user', 'reportable'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reports', compact('reports'));
    }

    /**
     * Create the resource
     */
    public function store(Request $request)
    {
        if (! Auth::check()) {
            return back()->withErrors('alert', [
                'type' => 'error',
                'message' => 'You must be logged in to submit a report',
            ]);
        }

        $validated = $request->validate([
            'reportable_type' => 'required|string|in:post,comment',
            'reportable_id' => 'required|integer|exists:' . $this->resolveModelTable($request->reportable_type) . ',id',
            'reason' => 'required|in:' . implode(',', array_column(ReportReason::cases(), 'value')),
            'description' => 'nullable|string|max:100',
        ]);

        try {
            $report = Report::create([
                'user_id' => Auth::id(),
                'reportable_type' => $this->resolveModelClass($validated['reportable_type']),
                'reportable_id' => $validated['reportable_id'],
                'reason' => $validated['reason'],
                'description' => $validated['description'],
                'status' => ReportStatus::Pending->value,
            ]);

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

    /**
     * Return the resource
     */
    public function show($id)
    {
        $report = Report::with(['user', 'reportable'])
            ->findOrFail($id);

        return view('admin.reports', compact('report'));
    }

    /**
     * Return the resource as JSON data
     */
    public function showJson($id)
    {
        $report = Report::with(['user:id,name', 'reportable'])
            ->findOrFail($id);

        // Get reporter info
        $reporter = $report->user ? [
            'id' => $report->user->id,
            'name' => $report->user->name,
        ] : null;

        // Get reportable's author info, if possible
        $reportableAuthor = null;
        if ($report->reportable && method_exists($report->reportable, 'user')) {
            $reportableUser = $report->reportable->user;
            if ($reportableUser) {
                $reportableAuthor = [
                    'id' => $reportableUser->id,
                    'name' => $reportableUser->name,
                ];
            }
        }

        return response()->json([
            'id' => $report->id,
            'reportable_type' => $report->reportable_type,
            'reportable_id' => $report->reportable_id,
            'reason' => $report->reason,
            'description' => $report->description,
            'status' => $report->status,
            'action_text' => $report->action_text,
            'reporter' => $reporter,
            'reportable_author' => $reportableAuthor,
            'created_at' => $report->created_at,
        ]);
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
