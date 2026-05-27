<header class="header-glassmorphism p-4 sm:p-6">
    <div class="flex items-center justify-between">
        <button id="mobileMenuBtn" class="sm:hidden text-gray-700 hover:text-gray-900 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <div class="flex-1 lg:flex-none ml-4 lg:ml-0">
            <h1 class="text-xl sm:text-2xl font-bold text-dark">Privacy Requests (DSAR)</h1>
            <p class="text-sm sm:text-base text-gray">UK GDPR Art. 15-22 — 1-month SLA per Art. 12</p>
        </div>
        <div class="flex items-center space-x-2 sm:space-x-4">
            <button id="pdRefresh" class="btn-hover px-3 sm:px-4 py-2 bg-white text-[#24A556] border border-[#24A556] font-medium rounded-xl hover:bg-[#24A556]/10 transition-all duration-200 shadow-sm" title="Refresh">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
        </div>
    </div>
</header>

<div class="flex-1 relative">
    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6 my-6 sm:my-8">
        <div class="card-glassmorphism rounded-2xl p-4 sm:p-6 shadow-lg">
            <p class="text-gray text-xs sm:text-sm font-medium">Total requests</p>
            <p id="pd-stat-total" class="text-2xl sm:text-3xl font-bold text-dark mt-1 sm:mt-2">0</p>
        </div>
        <div class="card-glassmorphism rounded-2xl p-4 sm:p-6 shadow-lg">
            <p class="text-gray text-xs sm:text-sm font-medium">Open</p>
            <p id="pd-stat-open" class="text-2xl sm:text-3xl font-bold text-blue-600 mt-1 sm:mt-2">0</p>
        </div>
        <div class="card-glassmorphism rounded-2xl p-4 sm:p-6 shadow-lg">
            <p class="text-gray text-xs sm:text-sm font-medium">In progress</p>
            <p id="pd-stat-inprogress" class="text-2xl sm:text-3xl font-bold text-yellow-600 mt-1 sm:mt-2">0</p>
        </div>
        <div class="card-glassmorphism rounded-2xl p-4 sm:p-6 shadow-lg">
            <p class="text-gray text-xs sm:text-sm font-medium">Due soon (&le;7d)</p>
            <p id="pd-stat-duesoon" class="text-2xl sm:text-3xl font-bold text-orange-600 mt-1 sm:mt-2">0</p>
        </div>
        <div class="card-glassmorphism rounded-2xl p-4 sm:p-6 shadow-lg">
            <p class="text-gray text-xs sm:text-sm font-medium">Overdue</p>
            <p id="pd-stat-overdue" class="text-2xl sm:text-3xl font-bold text-red-600 mt-1 sm:mt-2">0</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-2 mb-4">
        <?php
        $filters = [
            'all' => 'All',
            'open' => 'Open',
            'in_progress' => 'In progress',
            'extended' => 'Extended',
            'completed' => 'Completed',
            'rejected' => 'Rejected',
        ];
        foreach ($filters as $val => $label) :
        ?>
        <button data-filter="<?= $val ?>" class="pd-filter-btn px-4 py-2 rounded-full border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50">
            <?= htmlspecialchars($label) ?>
        </button>
        <?php endforeach; ?>
    </div>

    <!-- Table -->
    <div class="relative content-glassmorphism rounded-2xl shadow-lg">
        <div class="p-4 sm:p-6 border-b border-light flex items-center justify-between">
            <div>
                <h3 class="text-lg sm:text-xl font-semibold text-dark">Incoming requests</h3>
                <p class="text-gray mt-1 text-sm sm:text-base">Click a row to view details and respond.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold">Reference</th>
                        <th class="px-4 py-2 text-left font-semibold">Type</th>
                        <th class="px-4 py-2 text-left font-semibold">Email</th>
                        <th class="px-4 py-2 text-left font-semibold">Country</th>
                        <th class="px-4 py-2 text-left font-semibold">Received</th>
                        <th class="px-4 py-2 text-left font-semibold">Deadline</th>
                        <th class="px-4 py-2 text-left font-semibold">SLA</th>
                        <th class="px-4 py-2 text-left font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody id="pd-dsar-tbody" class="divide-y divide-slate-100">
                    <tr><td colspan="8" class="px-4 py-8 text-center text-slate-500">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Detail modal -->
<div id="pd-dsar-modal" class="fixed inset-0 z-[10000] hidden bg-black/40 backdrop-blur-sm items-center justify-center p-4" style="align-items: center; justify-content: center;">
    <div class="bg-white text-slate-900 w-full max-w-2xl rounded-2xl shadow-2xl max-h-[90vh] flex flex-col">
        <div class="px-6 pt-6 pb-4 border-b border-slate-200 flex items-start justify-between">
            <div>
                <h2 class="font-bold text-xl" id="pd-d-title">DSAR detail</h2>
                <div id="pd-d-ref" class="text-sm font-mono text-slate-500 mt-1"></div>
            </div>
            <button id="pd-d-close" class="text-slate-400 hover:text-slate-700 text-2xl leading-none">&times;</button>
        </div>
        <div class="px-6 py-4 overflow-y-auto flex-1 space-y-4">
            <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                <div><span class="text-slate-500">Type:</span> <span id="pd-d-type" class="font-medium"></span></div>
                <div><span class="text-slate-500">Status:</span> <span id="pd-d-status" class="font-medium"></span></div>
                <div><span class="text-slate-500">Email:</span> <span id="pd-d-email" class="font-medium break-all"></span></div>
                <div><span class="text-slate-500">Name:</span> <span id="pd-d-name" class="font-medium"></span></div>
                <div><span class="text-slate-500">Country:</span> <span id="pd-d-country" class="font-medium"></span></div>
                <div><span class="text-slate-500">Capacity:</span> <span id="pd-d-capacity" class="font-medium"></span></div>
                <div><span class="text-slate-500">Matched user:</span> <span id="pd-d-matched" class="font-medium"></span></div>
                <div><span class="text-slate-500">Received:</span> <span id="pd-d-received" class="font-medium"></span></div>
                <div><span class="text-slate-500">Deadline:</span> <span id="pd-d-deadline" class="font-medium"></span></div>
                <div><span class="text-slate-500">Completed:</span> <span id="pd-d-completed" class="font-medium"></span></div>
            </div>

            <div>
                <div class="text-slate-500 text-xs uppercase tracking-wide font-semibold mb-1">Details from requester</div>
                <div id="pd-d-details" class="whitespace-pre-wrap text-sm bg-slate-50 border-l-4 border-[#24A556] p-3 rounded"></div>
            </div>

            <div>
                <div class="text-slate-500 text-xs uppercase tracking-wide font-semibold mb-1">Staff notes (audit log)</div>
                <div id="pd-d-notes" class="whitespace-pre-wrap text-sm bg-slate-50 border border-slate-200 p-3 rounded max-h-48 overflow-y-auto font-mono text-xs"></div>
            </div>

            <div class="border-t border-slate-200 pt-4 space-y-3">
                <div>
                    <label class="block text-sm font-semibold mb-1">Add a note (audit-logged)</label>
                    <textarea id="pd-d-newnote" rows="3" maxlength="2000" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600" placeholder="e.g. 'Sent verification email to user'"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Change status (optional)</label>
                    <select id="pd-d-newstatus" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600">
                        <option value="">(no change)</option>
                        <option value="open">Open</option>
                        <option value="in_progress">In progress</option>
                        <option value="extended">Extended (notify user of +2 month extension)</option>
                        <option value="completed">Completed</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div id="pd-d-err" class="hidden bg-red-50 border border-red-200 text-red-800 text-sm rounded p-2"></div>
                <button id="pd-d-save" class="w-full rounded-lg border border-emerald-700 bg-emerald-600 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">Save</button>
            </div>

            <div class="text-xs text-slate-400 pt-2 border-t border-slate-100">
                <div>IP: <span id="pd-d-ip" class="font-mono"></span></div>
                <div>UA: <span id="pd-d-ua" class="font-mono break-all"></span></div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';
    var currentFilter = 'open';
    var openId = null;

    var TYPE_LABELS = {
        access: 'Access',
        rectification: 'Rectification',
        erasure: 'Erasure',
        restrict: 'Restrict processing',
        portability: 'Portability',
        object: 'Object',
        no_automated: 'No automated decisions',
        withdraw_consent: 'Withdraw consent'
    };
    var STATUS_BADGE = {
        open: 'bg-blue-100 text-blue-800',
        in_progress: 'bg-yellow-100 text-yellow-800',
        completed: 'bg-emerald-100 text-emerald-800',
        rejected: 'bg-slate-200 text-slate-700',
        extended: 'bg-purple-100 text-purple-800'
    };
    var STATUS_LABEL = {
        open: 'Open',
        in_progress: 'In progress',
        completed: 'Completed',
        rejected: 'Rejected',
        extended: 'Extended'
    };

    function $(id) { return document.getElementById(id); }
    function esc(s) {
        if (s == null) return '';
        return String(s).replace(/[&<>"']/g, function (c) {
            return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[c];
        });
    }
    function fmtDate(s) {
        if (!s) return '—';
        var d = new Date(s.replace(' ', 'T') + 'Z');
        if (isNaN(d.getTime())) return s;
        return d.toLocaleString();
    }
    function slaCell(row) {
        if (row.status === 'completed' || row.status === 'rejected') return '—';
        var hrs = parseInt(row.hours_remaining, 10);
        var days = Math.round(hrs / 24);
        var cls = 'bg-emerald-100 text-emerald-800';
        var label = days + 'd left';
        if (hrs < 0) { cls = 'bg-red-100 text-red-800'; label = Math.abs(days) + 'd OVERDUE'; }
        else if (days <= 7) { cls = 'bg-orange-100 text-orange-800'; label = days + 'd left'; }
        return '<span class="px-2 py-1 rounded-full text-xs font-semibold ' + cls + '">' + label + '</span>';
    }

    function renderList(payload) {
        var c = payload.counts || {};
        $('pd-stat-total').textContent = c.total || 0;
        $('pd-stat-open').textContent = c.open_count || 0;
        $('pd-stat-inprogress').textContent = c.in_progress_count || 0;
        $('pd-stat-duesoon').textContent = c.due_soon_count || 0;
        $('pd-stat-overdue').textContent = c.overdue_count || 0;

        var rows = payload.list || [];
        if (!rows.length) {
            $('pd-dsar-tbody').innerHTML = '<tr><td colspan="8" class="px-4 py-8 text-center text-slate-500">No requests match this filter.</td></tr>';
            return;
        }
        var html = rows.map(function (r) {
            var badgeCls = STATUS_BADGE[r.status] || 'bg-slate-100 text-slate-700';
            var badge = '<span class="px-2 py-1 rounded-full text-xs font-semibold ' + badgeCls + '">' + esc(STATUS_LABEL[r.status] || r.status) + '</span>';
            return '<tr data-id="' + r.id + '" class="hover:bg-slate-50 cursor-pointer pd-row">'
                + '<td class="px-4 py-3 font-mono text-xs">' + esc(r.reference) + '</td>'
                + '<td class="px-4 py-3">' + esc(TYPE_LABELS[r.request_type] || r.request_type) + '</td>'
                + '<td class="px-4 py-3 text-xs">' + esc(r.email) + '</td>'
                + '<td class="px-4 py-3">' + esc(r.country || '—') + '</td>'
                + '<td class="px-4 py-3 text-xs">' + fmtDate(r.received_at) + '</td>'
                + '<td class="px-4 py-3 text-xs">' + fmtDate(r.deadline_at) + '</td>'
                + '<td class="px-4 py-3">' + slaCell(r) + '</td>'
                + '<td class="px-4 py-3">' + badge + '</td>'
                + '</tr>';
        }).join('');
        $('pd-dsar-tbody').innerHTML = html;
        Array.prototype.forEach.call(document.querySelectorAll('.pd-row'), function (tr) {
            tr.addEventListener('click', function () { openDetail(parseInt(tr.getAttribute('data-id'), 10)); });
        });
    }

    function load() {
        $('pd-dsar-tbody').innerHTML = '<tr><td colspan="8" class="px-4 py-8 text-center text-slate-500">Loading...</td></tr>';
        fetch('/super/admin/api/dsar/getlist?status=' + encodeURIComponent(currentFilter), { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(renderList)
            .catch(function (e) {
                $('pd-dsar-tbody').innerHTML = '<tr><td colspan="8" class="px-4 py-8 text-center text-red-600">Failed to load: ' + esc(String(e)) + '</td></tr>';
            });
    }

    function openDetail(id) {
        openId = id;
        $('pd-d-err').classList.add('hidden');
        $('pd-d-newnote').value = '';
        $('pd-d-newstatus').value = '';
        $('pd-dsar-modal').classList.remove('hidden');
        $('pd-dsar-modal').style.display = 'flex';
        fetch('/super/admin/api/dsar/get?id=' + encodeURIComponent(id), { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (r) {
                if (r.error) { $('pd-d-err').textContent = r.error; $('pd-d-err').classList.remove('hidden'); return; }
                var d = r.data;
                $('pd-d-title').textContent = 'DSAR ' + (TYPE_LABELS[d.request_type] || d.request_type);
                $('pd-d-ref').textContent = d.reference;
                $('pd-d-type').textContent = TYPE_LABELS[d.request_type] || d.request_type;
                $('pd-d-status').innerHTML = '<span class="px-2 py-1 rounded-full text-xs font-semibold ' + (STATUS_BADGE[d.status] || '') + '">' + esc(STATUS_LABEL[d.status] || d.status) + '</span>';
                $('pd-d-email').textContent = d.email;
                $('pd-d-name').textContent = d.name || '—';
                $('pd-d-country').textContent = d.country || '—';
                $('pd-d-capacity').textContent = d.capacity === 'representative' ? 'Authorised representative' : 'Data subject (self)';
                $('pd-d-matched').textContent = d.matched_user_id ? ('users.id = ' + d.matched_user_id) : '(no account)';
                $('pd-d-received').textContent = fmtDate(d.received_at);
                $('pd-d-deadline').textContent = fmtDate(d.deadline_at);
                $('pd-d-completed').textContent = d.completed_at ? fmtDate(d.completed_at) : '—';
                $('pd-d-details').textContent = d.details || '(none provided)';
                $('pd-d-notes').textContent = d.staff_notes || '(no notes yet)';
                $('pd-d-ip').textContent = d.ip_address || '—';
                $('pd-d-ua').textContent = d.user_agent || '—';
            });
    }

    function closeDetail() {
        $('pd-dsar-modal').classList.add('hidden');
        $('pd-dsar-modal').style.display = 'none';
        openId = null;
    }

    function save() {
        var newStatus = $('pd-d-newstatus').value;
        var note = $('pd-d-newnote').value.trim();
        if (!newStatus && !note) {
            $('pd-d-err').textContent = 'Add a note or pick a new status.';
            $('pd-d-err').classList.remove('hidden');
            return;
        }
        var fd = new FormData();
        fd.append('id', openId);
        if (newStatus) fd.append('new_status', newStatus);
        if (note) fd.append('note', note);
        $('pd-d-save').disabled = true;
        $('pd-d-save').textContent = 'Saving...';
        fetch('/super/admin/api/dsar/update', { method: 'POST', credentials: 'same-origin', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (r) {
                $('pd-d-save').disabled = false;
                $('pd-d-save').textContent = 'Save';
                if (r.error) {
                    $('pd-d-err').textContent = r.error;
                    $('pd-d-err').classList.remove('hidden');
                    return;
                }
                closeDetail();
                load();
            })
            .catch(function (e) {
                $('pd-d-save').disabled = false;
                $('pd-d-save').textContent = 'Save';
                $('pd-d-err').textContent = 'Network error: ' + e;
                $('pd-d-err').classList.remove('hidden');
            });
    }

    function setFilter(f) {
        currentFilter = f;
        Array.prototype.forEach.call(document.querySelectorAll('.pd-filter-btn'), function (b) {
            if (b.getAttribute('data-filter') === f) {
                b.classList.remove('bg-white', 'text-slate-700', 'border-slate-300');
                b.classList.add('bg-[#24A556]', 'text-white', 'border-[#24A556]');
            } else {
                b.classList.add('bg-white', 'text-slate-700', 'border-slate-300');
                b.classList.remove('bg-[#24A556]', 'text-white', 'border-[#24A556]');
            }
        });
        load();
    }

    document.addEventListener('DOMContentLoaded', function () {
        Array.prototype.forEach.call(document.querySelectorAll('.pd-filter-btn'), function (b) {
            b.addEventListener('click', function () { setFilter(b.getAttribute('data-filter')); });
        });
        $('pd-d-close').addEventListener('click', closeDetail);
        $('pd-d-save').addEventListener('click', save);
        $('pdRefresh').addEventListener('click', load);
        // Modal: close on backdrop click only (not when clicking inside content)
        $('pd-dsar-modal').addEventListener('click', function (e) {
            if (e.target.id === 'pd-dsar-modal') closeDetail();
        });
        setFilter('open');
    });
})();
</script>
