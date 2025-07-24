@extends('layouts.default')

@section('title', 'Reports Admin')

@section('add-link')
    <a class="link" href="{{ route('admin.dashboard') }}">Admin</a>
@endsection

@section('pagetitle', 'Reports Admin')

@section('maincontent')
    <form class="auth-form report-filter-form" action="{{ route('admin.users.index') }}" method="">
        <div class="input-cont">
            <select name="status" id="status" required>
                <option value="">No filter</option>
                @foreach (\App\Enums\ReportStatus::cases() as $status)
                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </select>
            <label for="reason">Filter by status</label>
        </div>
    </form>   
<dd class="fillable"></dd>here
    @if ($reports->isEmpty())
        <p>No reports found!</p>
    @else
        <p>Reports -> &lpar;<span id="report-count">{{ $reports->count() }}</span>&rpar;</p>

        <div class="report-card-cont">
        @foreach ($reports as $report)
            <div class="report-card" data-status="{{ $report->status }}">
                <div class="report-header">
                    <div>{{ display_time($report->created_at) }}</div>
                    <button type="button" class="btn" onclick="openDialog({{ $report->id }})">View</button>
                </div>
                <div class="report-content">
                    <div>{{ $report->reason->label() }}</div>
                    <div class="report-status {{ $report->status }}">{{ $report->status->label() }}</div>
                </div>
            </div>
        @endforeach
        </div>
    @endif

    <dialog id="report-dialog" class="admin-report-dialog">
        <h3>Report details</h3>
        <dl>
            <div class="data-cont"><dt>Created</dt><dd class="fillable date">Text</dd></div>
            <div class="data-cont"><dt>Reason</dt><dd class="fillable reason"></dd></div>
            <div class="data-cont"><dt>Description</dt><dd class="fillable description"></dd></div>
            <div class="data-cont"><dt>Reported document</dt><a class="type-link" href=""><dd class="fillable type"></dd></a></div>
            <div class="data-cont"><dt>Reporting user</dt><a class="user-link" href=""><dd class="fillable user"></dd></a></div>
            <div class="data-cont"><dt>Actions</dt><dd class="fillable actions"></dd></div>
            <div class="data-cont"><dt>Current status</dt><dd class="fillable status"></dd></div>
        </dl>
        <form action="{{ route('reports.store') }}" method="POST" class="report-form auth-form">
            @csrf
            <input type="hidden" name="reportable_type">
            <input type="hidden" name="reportable_id">

            <div class="input-cont">
                <select name="status" id="status" required>
                    @foreach (\App\Enums\ReportStatus::cases() as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
                <label for="status">Status</label>
            </div>

            <div class="input-cont">
                <textarea name="action_text" id="action_text" rows="4" placeholder="What actions were taken..."></textarea>
                <label for="action_text">Action details</label>
            </div>

            <div class="dialog-actions">
                <button type="button" class="btn" onclick="closeDialog(this.closest('dialog'))">Close</button>
                <button type="submit" class="btn submit-btn">Update Report</button>
            </div>
        </form>
    </dialog>
@endsection

@section('scripts')
<script>
function openDialog(reportId) {
    const dialog = document.getElementById('report-dialog');
    dialog.showModal();
    loadReport(reportId, dialog);
}

function closeDialog(dialog) {
    dialog.querySelectorAll('dd').forEach(el => {
        el.textContent = '';
    });

    dialog.querySelectorAll('a').forEach(el => {
        el.href = '';
    });

    dialog.close();
}

async function loadReport(reportId, dialog) {
    try {
        const url = `../admin/reports/${reportId}/json`;
        const response = await fetch(url);

        if (!response.ok) throw new Error('Failed to fetch report');

        const data = await response.json();

        // Fill static fillables
        dialog.querySelector('.fillable.date').textContent = new Date(data.created_at).toLocaleString('AU');
        dialog.querySelector('.fillable.reason').textContent = data.reason;
        dialog.querySelector('.fillable.description').textContent = data.description;
        dialog.querySelector('.fillable.type').textContent = data.reportable_type;
        dialog.querySelector('.fillable.user').textContent = data.reporter?.name || 'Unknown';
        dialog.querySelector('.fillable.actions').textContent = data.action_text || 'None';
        dialog.querySelector('.fillable.status').textContent = data.status;

        // Set links
        dialog.querySelector('.type-link').href = `/${data.reportable_type.toLowerCase()}s/${data.reportable_id}`;
        dialog.querySelector('.user-link').href = `/users/${data.reporter?.id}`;

        // Fill form hidden fields
        dialog.querySelector('input[name="reportable_type"]').value = data.reportable_type;
        dialog.querySelector('input[name="reportable_id"]').value = data.reportable_id;

        // Pre-select current status in the select dropdown
        const statusSelect = dialog.querySelector('select[name="status"]');
        if (statusSelect) {
            [...statusSelect.options].forEach(option => {
                option.selected = (option.value === data.status);
            });
        }

        // Clear actions text area
        dialog.querySelector('textarea[name="action_text"]').value = '';

    } catch (error) {
        console.error('Error loading report:', error);
        dialog.close();
        alert('Could not load report details.');
    }
}
</script>
@endsection