<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pemberitahuan Job Baru Dari API (QA Review)</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; color: #1f2937; }
        .card { max-width: 650px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background: #7c3aed; color: #ffffff; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 700; }
        .header p { margin: 6px 0 0; font-size: 13px; opacity: 0.9; }
        .body { padding: 24px; font-size: 14px; line-height: 1.6; }
        .info-table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px; }
        .info-table td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; font-size: 13px; }
        .info-table td.label { font-weight: 600; color: #4b5563; width: 35%; background: #f9fafb; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 11px; font-weight: 700; background: #ede9fe; color: #6d28d9; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 12px; }
        .items-table th { background: #f3f4f6; padding: 8px; text-align: left; border: 1px solid #e5e7eb; }
        .items-table td { padding: 8px; border: 1px solid #e5e7eb; }
        .footer { background: #f9fafb; padding: 16px 24px; font-size: 12px; color: #6b7280; text-align: center; border-top: 1px solid #e5e7eb; }
        .btn { display: inline-block; background: #7c3aed; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; font-size: 13px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>📋 Job Baru dari Sistem Eksternal / API</h1>
            <p>Memerlukan Verifikasi & Persetujuan Tim Quality Assurance (QA)</p>
        </div>
        <div class="body">
            <p>Halo Tim <strong>Quality Assurance</strong>,</p>
            <p>Terdapat job baru yang baru saja masuk melalui integrasi API dan saat ini berada pada tahap antrean <span class="badge">Need Review</span>.</p>

            <table class="info-table">
                <tr>
                    <td class="label">ID Job</td>
                    <td><strong>{{ $job->id_job }}</strong></td>
                </tr>
                <tr>
                    <td class="label">ID Referensi Eksternal</td>
                    <td><code>{{ $job->external_reference_id ?? '-' }}</code></td>
                </tr>
                <tr>
                    <td class="label">Sumber Data</td>
                    <td><span class="badge">API Eksternal</span></td>
                </tr>
                <tr>
                    <td class="label">Lokasi / Area</td>
                    <td>{{ $job->area->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Total Saldo / Balance</td>
                    <td><strong>{{ $job->balance }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Deskripsi Pekerjaan</td>
                    <td>{{ $job->list_job }}</td>
                </tr>
                @if($job->reason_description && $job->reason_description !== $job->list_job)
                <tr>
                    <td class="label">Alasan / Detail</td>
                    <td>{{ $job->reason_description }}</td>
                </tr>
                @endif
            </table>

            @if($job->items->isNotEmpty())
            <h4 style="margin: 15px 0 5px; font-size: 13px; color: #374151;">Daftar Item & Detail Lot:</h4>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Item</th>
                        <th>No. Lot</th>
                        <th>Kuantitas / Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($job->items as $idx => $item)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td><code>{{ $item->item_code ?? '-' }}</code></td>
                        <td>{{ $item->item_name }}</td>
                        <td><strong>{{ $item->lot_number ?? '-' }}</strong></td>
                        <td>{{ $item->qty }} {{ $item->unit ?? '' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

            <p style="text-align: center; margin-top: 25px;">
                <a href="{{ route('kanban.jobs.index') }}" class="btn" style="color: #ffffff;">Buka Kanban Board & Review Job</a>
            </p>
        </div>
        <div class="footer">
            Email otomatis dari Sistem Kanban PT SMART Tbk (SMII). Harap tidak membalas email ini secara langsung.
        </div>
    </div>
</body>
</html>
