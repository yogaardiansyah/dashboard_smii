<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Job Overdue Alert</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 25px; border-radius: 8px; border-top: 4px solid #ef4444; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="color: #ef4444; margin-top: 0;">⚠️ Job Overdue Alert</h2>
        <p>Job dengan ID <strong>{{ $job->id_job }}</strong> telah berada pada tahapan saat ini selama lebih dari 3 hari.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold; width: 35%;">ID Job:</td>
                <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $job->id_job }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Area:</td>
                <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ optional($job->area)->name ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Status:</td>
                <td style="padding: 8px; border-bottom: 1px solid #eee;"><span style="background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 4px; font-weight: bold;">{{ ucfirst(str_replace('_', ' ', $job->status)) }}</span></td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Pengaju:</td>
                <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ optional($job->pengaju)->name ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Deskripsi:</td>
                <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $job->list_job }}</td>
            </tr>
        </table>
        
        <p style="margin-top: 20px;">Mohon segera ditindaklanjuti pada sistem <a href="{{ route('jobs.index') }}" style="color: #2563eb; text-decoration: underline;">Marsho Job Board</a>.</p>
        <p style="color: #666; font-size: 12px; margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px;">Email ini dikirim otomatis oleh Intra SMII Operational Dashboard.</p>
    </div>
</body>
</html>
