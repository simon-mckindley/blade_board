@extends('layouts.default')

@section('title', 'Reports Admin')

@section('add-link')
    <a class="link" href="{{ route('admin.dashboard') }}">Admin</a>
@endsection

@section('pagetitle', 'Reports Admin')

@section('maincontent')
    <form class="auth-form report-filter-form" action="{{ route('admin.users.index') }}" method="">
        <div class="input-cont">
            <select name="status-filter" id="status-filter" required>
                <option value="">No filter</option>
                @foreach (\App\Enums\ReportStatus::cases() as $status)
                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </select>
            <label for="status-filter">Filter by status</label>
        </div>
    </form>   

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
            <div class="data-cont"><dt>Created</dt><dd class="fillable date"></dd></div>
            <div class="data-cont"><dt>Reporting user</dt><a class="user-link" href=""><dd class="fillable user"></dd></a></div>
            <div class="data-cont"><dt>Reason</dt><dd class="fillable reason"></dd></div>
            <div class="data-cont"><dt>Description</dt><dd class="fillable description"></dd></div>
            <div class="data-cont"><dt>Reported document</dt><a class="type-link"><dd class="fillable type"></dd></a></div>
            <div class="data-cont"><dt>Document author</dt><a class="author-link"><dd class="fillable author"></dd></a></div>
            <div class="data-cont"><dt>Actions</dt><dd class="fillable actions"></dd></div>
            <div class="data-cont"><dt>Current status</dt><dd class="fillable status"></dd></div>
        </dl>
        
        <form action="{{ route('admin.reports.update') }}" method="POST" class="report-form auth-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="report">

            <div class="input-cont">
                <select name="status" id="status" required>
                    <option value="">Select</option>
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
    // Clear data
    dialog.querySelectorAll('dd').forEach(el => {
        el.textContent = '';
    });

    // Clear links
    dialog.querySelectorAll('a').forEach(el => {
        el.removeAttribute('href');
    });

    // Un-select current status in the select dropdown
    const statusSelect = dialog.querySelector('select[name="status"]');
    if (statusSelect) {
        [...statusSelect.options].forEach(option => option.selected = false);
    }

    dialog.close();
}

async function loadReport(reportId, dialog) {
    try {
        const url = `reports/${reportId}/json`;
        const response = await fetch(url);

        if (!response.ok) throw new Error('Failed to fetch report');

        const data = await response.json();

        // Fill static fillables
        dialog.querySelector('.fillable.date').textContent = new Date(data.created_at).toLocaleString('en-AU');
        dialog.querySelector('.fillable.user').textContent = data.reporter?.name || 'Unknown';
        dialog.querySelector('.fillable.reason').textContent = data.reason_label;
        dialog.querySelector('.fillable.description').textContent = data.description || 'None';
        dialog.querySelector('.fillable.type').textContent = data.reportable_type;
        dialog.querySelector('.fillable.author').textContent = data.reportable_author?.name || 'Unknown';
        dialog.querySelector('.fillable.actions').textContent = data.action_text || 'None';
        dialog.querySelector('.fillable.status').textContent = data.status_label;

        // Set links
        dialog.querySelector('.type-link').href = `../posts/${data.post_id}`;
        dialog.querySelector('.user-link').href = `user/${data.reporter?.id}`;
        dialog.querySelector('.author-link').href = `user/${data.reportable_author?.id}`;

        // Fill form hidden field
        dialog.querySelector('input[name="report"]').value = data.id;

        // Pre-select current status in the select dropdown
        const statusSelect = dialog.querySelector('select[name="status"]');
        if (statusSelect) {
            [...statusSelect.options].forEach(option => {
                option.selected = (option.value === data.status);
            });
        }

    } catch (error) {
        console.error('Error loading report:', error);
        dialog.close();
        alert('Could not load report details.');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Card filtering
    const statusSelect = document.getElementById('status-filter');
    const reportCards = document.querySelectorAll('.report-card');
    const reportCount = document.getElementById('report-count');

    statusSelect.addEventListener('change', function () {
        const selectedStatus = this.value;
        let visibleCount = 0;
        
        reportCards.forEach(card => {
            card.style.display = 'none';
            const cardStatus = card.dataset.status;

            setTimeout(() => {
                if (!selectedStatus || cardStatus === selectedStatus) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }

                reportCount.textContent = visibleCount;
            }, 300);
        });
    });

    // Close dialog on link click
    document.querySelectorAll('dialog a').forEach(link => {
        link.addEventListener('click', function () {
            setTimeout(() => {
                closeDialog(this.closest('dialog'));
            }, 300);
        });
    })
});

</script>
@endsection