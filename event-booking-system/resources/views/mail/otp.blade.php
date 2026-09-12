<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ config('app.name') }} verification code</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #3f3f46;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f5; margin: 0; padding: 0; width: 100%;">
        <tr>
            <td align="center" style="padding: 32px 16px;">
                <table role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width: 560px; width: 100%;">
                    <tr>
                        <td style="padding: 0 0 20px 0; text-align: center; font-size: 18px; font-weight: 700; letter-spacing: 0.04em; color: #18181b;">
                            {{ config('app.name') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #ffffff; border: 1px solid #e4e4e7; border-radius: 12px; overflow: hidden;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="height: 6px; background-color: #18181b; font-size: 0; line-height: 0;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="padding: 36px 32px 32px 32px;">
                                        <p style="margin: 0 0 8px 0; font-size: 12px; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase; color: #71717a;">
                                            Email verification
                                        </p>
                                        <h1 style="margin: 0 0 16px 0; font-size: 24px; line-height: 1.3; font-weight: 700; color: #18181b;">
                                            Your verification code
                                        </h1>
                                        <p style="margin: 0 0 28px 0; font-size: 16px; line-height: 1.6; color: #52525b;">
                                            Use this one-time password to finish creating your {{ config('app.name') }} account. It expires in <strong style="color: #18181b;">10 minutes</strong>.
                                        </p>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="center" style="background-color: #fafafa; border: 1px dashed #d4d4d8; border-radius: 10px; padding: 22px 16px;">
                                                    <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: #71717a;">
                                                        One-time password
                                                    </p>
                                                    <p style="margin: 0; font-family: 'Courier New', Courier, ui-monospace, monospace; font-size: 36px; font-weight: 700; letter-spacing: 0.28em; color: #18181b; line-height: 1.2;">
                                                        {{ $otp }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                        <p style="margin: 28px 0 0 0; font-size: 14px; line-height: 1.6; color: #71717a;">
                                            Keep this code private. {{ config('app.name') }} will never ask for it by phone, email, or chat.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 24px 8px 0 8px; text-align: center; font-size: 13px; line-height: 1.6; color: #a1a1aa;">
                            If you did not create an account, you can ignore this message.<br>
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
