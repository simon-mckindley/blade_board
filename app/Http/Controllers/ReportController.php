<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Comment;
use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            return back()->with('alert', [
                'type' => 'error',
                'message' => 'You must be logged in to submit a report',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'reportable_type' => 'required|string|in:post,comment',
            'reportable_id' => 'required|integer|exists:' . $this->resolveModelTable($request->reportable_type) . ',id',
            'reason' => 'required|in:' . implode(',', array_column(ReportReason::cases(), 'value')),
            'description' => 'nullable|string|max:100',
            'action_text' => 'nullable|string|max:100',
        ]);

        // Manual validation messaging
        if ($validator->fails()) {
            $allErrors = $validator->errors()->all(); // array of all messages
            $message = implode(' ', $allErrors);

            return back()->with('alert', [
                'type' => 'error',
                'message' => 'Report submition error! ' . $message,
            ])->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        try {
            Report::create([
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
     * Update the resource
     */
    public function update(Request $request)
    {
        $request->validate([
            'report' => 'required|exists:reports,id',
            'status' => ['required', new Enum(ReportStatus::class)],
            'action_text' => ['nullable', 'string'],
        ], [
            'status.required' => 'The status is required.',
            'status.enum' => 'The status provided is invalid.',
            'action_text.string' => 'The action text must be a string.',
        ]);

        try {
            $report = Report::findOrFail($request->input('report'));

            // Append action_text if provided
            if ($request->filled('action_text')) {
                $newActionText = trim($report->action_text . ' ' . $request->input('action_text'));
                $report->action_text = $newActionText;
            }

            $report->status = $request->input('status');
            $report->save();

            return redirect()
                ->back()
                ->with('alert', [
                    'type' => 'success',
                    'message' => 'Report updated!',
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'There was a problem updating the report: ' . $e->getMessage(),
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

        // Find the post ID if the reportable is a comment
        $postId = $report->reportable_id;
        if ($report->reportable_type === Comment::class && $report->reportable) {
            $postId = $report->reportable->post_id;
        }

        return response()->json([
            'id' => $report->id,
            'reportable_type' => class_basename($report->reportable_type),
            'reportable_id' => $report->reportable_id,
            'post_id' => $postId,
            'reason' => $report->reason,
            'reason_label' => $report->reason->label(),
            'description' => $report->description,
            'status' => $report->status,
            'status_label' => $report->status->label(),
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
