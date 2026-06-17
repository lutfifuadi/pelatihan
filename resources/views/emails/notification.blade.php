<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title }}</title>
    <style type="text/css">
        /* Base styles */
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #18181b;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        /* Container */
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px 10px;
        }

        /* Header */
        .email-header {
            background-color: #1e40af;
            border-radius: 8px 8px 0 0;
            padding: 24px 32px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            color: #ffffff;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Body */
        .email-body {
            background-color: #ffffff;
            padding: 32px;
            border-left: 1px solid #e4e4e7;
            border-right: 1px solid #e4e4e7;
        }

        .email-body p {
            margin: 0 0 16px 0;
            font-size: 15px;
            color: #18181b;
        }

        .email-body p:last-child {
            margin-bottom: 0;
        }

        /* Footer */
        .email-footer {
            background-color: #fafafa;
            border: 1px solid #e4e4e7;
            border-top: none;
            border-radius: 0 0 8px 8px;
            padding: 16px 32px;
            text-align: center;
        }

        .email-footer p {
            margin: 0;
            font-size: 12px;
            color: #71717a;
        }

        /* Responsive */
        @@media only screen and (max-width: 600px) {
            .email-wrapper {
                padding: 10px 5px;
            }

            .email-header {
                padding: 20px 16px;
            }

            .email-body {
                padding: 24px 16px;
            }

            .email-footer {
                padding: 12px 16px;
            }
        }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center">
                <div class="email-wrapper">
                    <!-- Header -->
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <td class="email-header">
                                <h1>{{ config('app.name') }}</h1>
                            </td>
                        </tr>
                    </table>

                    <!-- Body -->
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <td class="email-body">
                                {!! nl2br(e($body)) !!}
                            </td>
                        </tr>
                    </table>

                    <!-- Footer -->
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <td class="email-footer">
                                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
