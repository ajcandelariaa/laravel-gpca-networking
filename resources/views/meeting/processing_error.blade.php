<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Something Went Wrong</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
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
            padding: 24px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 22px;
        }

        .muted {
            color: var(--muted);
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="card">
            <h1>Something Went Wrong</h1>
            <p class="muted">We couldn’t process your response due to a server error. Please try the link again later,
                or contact the event organizer.</p>
        </div>
    </div>
</body>

</html>
