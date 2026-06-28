<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Error')</title>

    <style>
        :root {
            --primary: #f97316;
            --primary-dark: #ea580c;
            --text: #1f2937;
            --muted: #6b7280;
            --bg: #f9fafb;
            --card: #ffffff;
            --border: #e5e7eb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background:
                radial-gradient(circle at top, rgba(249, 115, 22, 0.10), transparent 34%),
                var(--bg);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .error-card {
            width: 100%;
            max-width: 540px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 42px 30px;
            text-align: center;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
        }

        .error-code {
            font-size: 92px;
            line-height: 1;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 14px;
            letter-spacing: -3px;
        }

        .error-title {
            font-size: 26px;
            margin: 0 0 10px;
        }

        .error-text {
            color: var(--muted);
            font-size: 15px;
            line-height: 1.7;
            margin: 0 0 28px;
        }

        .error-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: .2s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-secondary {
            background: #fff;
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: #f3f4f6;
        }

        @media (max-width: 480px) {
            .error-card {
                padding: 34px 22px;
            }

            .error-code {
                font-size: 76px;
            }

            .error-title {
                font-size: 22px;
            }

            .error-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <main class="error-card">
        <div class="error-code">@yield('code')</div>

        <h1 class="error-title">@yield('heading')</h1>

        <p class="error-text">
            @yield('message')
        </p>

        <div class="error-actions">
            <a href="{{ url('/') }}" class="btn btn-primary">
                Home
            </a>

            <a href="javascript:history.back()" class="btn btn-secondary">
                Back
            </a>
        </div>
    </main>
</body>

</html>