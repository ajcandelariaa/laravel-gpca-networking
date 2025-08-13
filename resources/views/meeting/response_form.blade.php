<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Respond to Meeting Request</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    @vite('resources/css/app.css')

    <style>
        :root {
            --primary: #1a73e8;
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #111827;
            --muted: #6b7280;
            --border: #e5e7eb;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Inter, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji";
            background: var(--bg);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wrap {
            width: 100%;
            max-width: 640px;
            padding: 16px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .04);
        }

        h1 {
            margin: 0 0 8px;
            font-size: 22px;
        }

        h2 {
            margin: 18px 0 8px;
            font-size: 16px;
            color: var(--muted);
            font-weight: 600;
            letter-spacing: .3px;
            text-transform: uppercase;
        }

        .muted {
            color: var(--muted);
            font-size: 14px;
        }

        .field {
            margin: 12px 0;
        }

        .field label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .radio-row {
            display: flex;
            gap: 16px;
        }

        textarea {
            width: 100%;
            min-height: 100px;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 10px;
            resize: vertical;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }

        button {
            appearance: none;
            border: 0;
            border-radius: 10px;
            padding: 10px 14px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-outline {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
        }

        .error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 12px;
        }

        .kv {
            display: flex;
            gap: 8px;
            font-size: 14px;
            margin: 3px 0;
        }

        .kv .k {
            width: 120px;
            color: var(--muted);
        }

        .footer {
            margin-top: 18px;
            font-size: 12px;
            color: var(--muted);
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="card">
            <h1>Respond to Meeting Request</h1>
            <div class="muted">Please accept or decline this request, and include a short message.</div>

            <h2>Meeting Details</h2>
            @php
                use Illuminate\Support\Carbon;
                $date = Carbon::parse($meeting->meeting_date)->format('F d, Y');
                $start = Carbon::parse($meeting->meeting_start_time)->format('g:i A');
                $end = Carbon::parse($meeting->meeting_end_time)->format('g:i A');
            @endphp
            <div class="kv">
                <div class="k">Title</div>
                <div class="v">{{ $meeting->meeting_title }}</div>
            </div>
            <div class="kv">
                <div class="k">Date</div>
                <div class="v">{{ $date }}</div>
            </div>
            <div class="kv">
                <div class="k">Time</div>
                <div class="v">{{ $start }} – {{ $end }}</div>
            </div>
            <div class="kv">
                <div class="k">Location</div>
                <div class="v">{{ $meeting->meeting_location }}</div>
            </div>
            @if (!empty($meeting->meeting_notes))
                <div class="kv">
                    <div class="k">Notes</div>
                    <div class="v">{{ $meeting->meeting_notes }}</div>
                </div>
            @endif
            <div class="kv">
                <div class="k">Requested by</div>
                <div class="v">{{ optional($meeting->requester)->first_name ?? 'Attendee' }}</div>
            </div>

            @if ($errors->any())
                <div class="error">
                    <strong>There was a problem:</strong>
                    <ul style="margin:6px 0 0 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="respondForm" method="POST"
                action="{{ route('meeting.respond.submit', ['eventCategory' => $eventCategory, 'eventId' => $eventId, 'meetingId' => $meeting->id]) }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="field">
                    <label>Response</label>
                    <div class="radio-row">
                        <label><input type="radio" name="action" value="accept"
                                {{ old('action') === 'accept' ? 'checked' : '' }} required> Accept</label>
                        <label><input type="radio" name="action" value="decline"
                                {{ old('action') === 'decline' ? 'checked' : '' }} required> Decline</label>
                    </div>
                </div>

                <div class="field">
                    <label>Message / Reason <span class="pill"
                            style="background:#eef2ff;color:#3730a3;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;">Required</span></label>
                    <textarea name="message" maxlength="500" required placeholder="Write a short message...">{{ old('message') }}</textarea>
                    <div class="muted">Max 500 characters.</div>
                </div>

                <div class="actions">
                    <button id="submitBtn" type="submit" class="btn-primary">Submit Response</button>
                </div>
            </form>

            <div class="footer">
                This link is single-use and expires on
                {{ \Illuminate\Support\Carbon::parse($meeting->respond_token_expires_at)->format('F d, Y g:i A') }}.
            </div>
        </div>
    </div>

    <div id="loadingOverlay"
        class="hidden fixed inset-0 z-50 bg-black/30 backdrop-blur-sm flex items-center justify-center">
        <div class="h-12 w-12 rounded-full border-4 border-white/70 border-t-transparent animate-spin"></div>
    </div>

    <script>
        (function() {
            const form = document.getElementById('respondForm');
            const submitBtn = document.getElementById('submitBtn');
            const overlay = document.getElementById('loadingOverlay');

            function showOverlay() {
                overlay.classList.remove('hidden');
                document.body.style.pointerEvents = 'none';
            }

            function hideOverlay() {
                overlay.classList.add('hidden');
                document.body.style.pointerEvents = '';
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!form.reportValidity()) return;

                const actionEl = form.querySelector('input[name="action"]:checked');
                const action = actionEl ? actionEl.value : null;

                const title = action === 'accept' ? 'Accept this meeting?' : 'Decline this meeting?';
                const text = action === 'accept' ?
                    'We will notify the requester and record your message.' :
                    'We will notify the requester with your reason.';

                swal({
                    title: title,
                    text: text,
                    icon: 'warning',
                    buttons: {
                        cancel: 'Cancel',
                        confirm: action === 'accept' ? 'Yes, accept' : 'Yes, decline'
                    },
                    dangerMode: action === 'decline'
                }).then(function(confirmed) {
                    if (!confirmed) return;

                    submitBtn.disabled = true;
                    showOverlay();

                    form.submit();
                });
            });

            @if ($errors->any())
                submitBtn.disabled = false;
                hideOverlay();
            @endif
        })();
    </script>
</body>

</html>
