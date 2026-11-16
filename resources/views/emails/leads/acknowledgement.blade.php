<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>Thank you for contacting Santorini Residences</title>
</head>
<body style="margin:0;padding:0;background:#f2f0eb;color:#292d31;font-family:Arial,Helvetica,sans-serif;-webkit-font-smoothing:antialiased;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f2f0eb;padding:36px 14px;">
        <tr><td align="center">
            <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;background:#fff;border:1px solid #e8e3da;">
                <tr><td style="height:5px;background:#b99762;font-size:0;line-height:0;">&nbsp;</td></tr>
                <tr><td style="padding:34px 42px 22px;background:#182b3a;color:#fff;">
                    <p style="margin:0;color:#d5bc91;font-size:10px;letter-spacing:3px;text-transform:uppercase;">Santorini Residences</p>
                    <p style="margin:18px 0 0;color:#fff;font-family:Georgia,'Times New Roman',serif;font-size:30px;line-height:1.2;">Thank you, {{ $lead->name }}.</p>
                </td></tr>
                <tr><td style="padding:32px 42px 12px;font-size:15px;line-height:1.75;color:#4b5155;">
                    <p style="margin:0 0 16px;">Thank you for getting in touch with us. We have received your {{ strtolower($requestLabel) }} and a member of our team will be in touch shortly.</p>
                    <p style="margin:0;">We appreciate your interest in Santorini Residences and look forward to speaking with you.</p>
                </td></tr>
                @if ($lead->preferred_date || $lead->preferred_time || $lead->residence_type)
                    <tr><td style="padding:14px 42px 28px;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f7f5f1;border-left:3px solid #b99762;">
                            <tr><td style="padding:16px 18px;font-size:13px;line-height:1.8;color:#4b5155;">
                                <strong style="color:#182b3a;">Your request</strong><br>
                                {{ $requestLabel }}
                                @if ($lead->preferred_date)<br>Preferred date: {{ $lead->preferred_date->format('j F Y') }}@endif
                                @if ($lead->preferred_time)<br>Preferred time: {{ $lead->preferred_time }}@endif
                                @if ($lead->residence_type)<br>Residence: {{ \App\Support\LeadFormTypes::RESIDENCES[$lead->residence_type] ?? $lead->residence_type }}@endif
                            </td></tr>
                        </table>
                    </td></tr>
                @endif
                <tr><td style="padding:4px 42px 34px;font-size:14px;line-height:1.8;color:#4b5155;">
                    <p style="margin:0;">If you need to add anything, reply to this email or reach us at <a href="mailto:hello@santoriniresidences.com" style="color:#182b3a;text-decoration:underline;">hello@santoriniresidences.com</a>.</p>
                    <p style="margin:22px 0 0;color:#182b3a;font-family:Georgia,'Times New Roman',serif;font-size:17px;">Warm regards,<br>The Santorini Residences Team</p>
                </td></tr>
                <tr><td style="padding:20px 42px;background:#182b3a;color:#d4d1ca;font-size:11px;line-height:1.7;">
                    Lantana Road, Westlands, Nairobi<br>
                    <a href="https://santoriniresidences.com" style="color:#d5bc91;text-decoration:none;">santoriniresidences.com</a>
                </td></tr>
            </table>
            <p style="margin:18px 0 0;color:#89877f;font-size:10px;line-height:1.6;">This is an acknowledgement of your enquiry, not a booking confirmation.</p>
        </td></tr>
    </table>
</body>
</html>
