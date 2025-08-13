<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Thank You</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --primary: #1a73e8;
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #111827;
            --muted: #6b7280;
            --border: #e5e7eb;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Inter, Arial;
            background: var(--bg);
            color: var(--text);
        }

        .wrap {
            max-width: 640px;
            margin: 40px auto;
            padding: 0 16px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 28px;
            text-align: center;
        }

        h1 {
            margin: 0 0 10px;
            font-size: 24px;
        }

        .muted {
            color: var(--muted);
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-weight: 700;
            margin: 10px 0 0;
        }

        .accepted {
            background: #ecfdf5;
            color: #065f46;
        }

        .declined {
            background: #fef2f2;
            color: #991b1b;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="card">
            <h1>Thanks! Your response has been recorded.</h1>
            @php
                $accepted = $status === \App\Enums\MeetingStatus::ACCEPTED->value;
            @endphp
            <div class="badge {{ $accepted ? 'accepted' : 'declined' }}">
                {{ $accepted ? 'Accepted' : 'Declined' }}
            </div>

            <p class="muted" style="margin-top:14px;">
                You can now close this page. The requester will be notified by
                email{{ $accepted ? ' and in the app' : '' }}.
            </p>
        </div>
    </div>
</body>

</html>
