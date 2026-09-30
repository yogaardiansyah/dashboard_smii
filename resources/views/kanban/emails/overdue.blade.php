<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kanban Job Overdue Notification</title>
    <style>
        body { margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; line-height: 1.5; color: #333333; background-color: #f9f9f9; }
        .container { max-width: 800px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1); border: 1px solid #ddd; }
        .header-main { background-color: #a48d53; color: #ffffff; padding: 25px 20px; text-align: center; font-size: 24px; font-weight: bold; }
        .header-sub { background-color: #c7b07b; color: #ffffff; padding: 15px; text-align: center; font-size: 18px; }
        .brand-tag { background-color: #e8d697; color: #4a3b18; padding: 2px 6px; border-radius: 2px; font-weight: bold; margin-right: 5px; }
        .content { padding: 30px; }
        .alert-title { color: #ef4444; font-size: 22px; font-weight: bold; margin-bottom: 20px; display: block; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .job-details-box { border-left: 5px solid #a48d53; padding-left: 15px; margin: 25px 0; }
        .detail-row { margin-bottom: 12px; }
        .detail-label { font-weight: bold; color: #555; width: 140px; display: inline-block; vertical-align: top;}
        .detail-value { display: inline-block; max-width: 75%; vertical-align: top; }
        .text-overdue { color: #ef4444; font-weight: bold; font-size: 1.1em; }
        .text-status { text-transform: uppercase; font-weight: bold; color: #333; }
        .btn { display: inline-block; background-color: #a48d53; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin-top: 10px; font-weight: bold; }
        .btn:hover { background-color: #8c7846; }
        .footer { background-color: #f9f9f9; padding: 30px 20px; text-align: center; font-size: 12px; color: #888; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-main">
            PT. Sinar Meadow International Indonesia
        </div>

        <div class="header-sub">
            <span class="brand-tag">Kanban</span> Board
        </div>
        
        <div class="content">
            <p>Hello,</p>
            <p>This is an automated notification to inform you that the following job has exceeded the <strong>3-day SLA limit</strong> in its current stage.</p>

            <div class="job-details-box">
                <div class="detail-row">
                    <span class="detail-label">Job ID:</span> 
                    <span class="detail-value" style="font-weight: bold; color: #a48d53;">{{ $job->id_job }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Current Stage:</span> 
                    <span class="detail-value text-status">{{ str_replace('_', ' ', $job->status) }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Days in Stage:</span> 
                    <span class="detail-value text-overdue">
                        {{ \Carbon\Carbon::now()->diffInDays($job->last_stage_update) }} Days (Overdue)
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Last Updated:</span> 
                    <span class="detail-value">
                        {{ $job->last_stage_update ? \Carbon\Carbon::parse($job->last_stage_update)->format('d M Y H:i') : '-' }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Requester:</span> 
                    <span class="detail-value">{{ $job->pengaju->name ?? 'Unknown' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Location/Area:</span> 
                    <span class="detail-value">{{ $job->area->name ?? '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Overall Deadline:</span> 
                    <span class="detail-value">
                        {{ $job->deadline ? \Carbon\Carbon::parse($job->deadline)->format('d M Y') : '-' }}
                    </span>
                </div>

                <div class="detail-row" style="margin-top: 15px;">
                    <span class="detail-label">Description:</span> 
                    <span class="detail-value" style="background-color: #f5f5f5; padding: 10px; border-radius: 4px; display: block; margin-top: 5px;">
                        {{ $job->list_job }}
                    </span>
                </div>
            </div>

            <p>Please take immediate action to either move this job to the next stage or forward it to another department.</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/kanban/jobs') }}" class="btn">View Job on Kanban Board</a>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated notification from Kanban Board System.</p>
            <p>&copy; {{ date('Y') }} PT. Sinar Meadow International Indonesia. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
