<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pemberitahuan Job API Selesai (Completed) - Tim QA</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; color: #1f2937; }
        .card { max-width: 650px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background: #059669; color: #ffffff; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 700; }
        .header p { margin: 6px 0 0; font-size: 13px; opacity: 0.95; }
        .body { padding: 24px; font-size: 14px; line-height: 1.6; }
        .info-table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px; }
        .info-table td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; font-size: 13px; }
        .info-table td.label { font-weight: 600; color: #4b5563; width: 35%; background: #f9fafb; }
        .badge-success { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 11px; font-weight: 700; background: #d1fae5; color: #065f46; }
        .badge-api { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 11px; font-weight: 700; background: #ede9fe; color: #6d28d9; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 12px; }
        .items-table th { background: #f3f4f6; padding: 8px; text-align: left; border: 1px solid #e5e7eb; }
        .items-table td { padding: 8px; border: 1px solid #e5e7eb; }
        .footer { background: #f9fafb; padding: 16px 24px; font-size: 12px; color: #6b7280; text-align: center; border-top: 1px solid #e5e7eb; }
        .lot-badge { font-family: monospace; font-weight: bold; background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 4px; border: 1px solid #fde68a; }
        .btn { display: inline-block; background: #059669; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; font-size: 13px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>✅ Pekerjaan API Selesai (Completed)</h1>
            <p>Pemberitahuan Hasil Pekerjaan untuk Tim Quality Assurance (QA)</p>
        </div>
        <div class="body">
            <p>Halo Tim <strong>Quality Assurance (QA)</strong>,</p>
            <p>Pekerjaan Kanban yang berasal dari integrasi sistem eksternal / API telah <span class="badge-success">Selesai (Completed)</span> dikerjakan di lapangan.</p>

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
                    <td class="label">Sumber Pekerjaan</td>
                    <td><span class="badge-api">Integrasi API</span></td>
                </tr>
                <tr>
                    <td class="label">Status Saat Ini</td>
                    <td><span class="badge-success">COMPLETED</span></td>
                </tr>
                <tr>
                    <td class="label">Tanggal Selesai</td>
                    <td>{{ $job->tanggal_job_selesai ? $job->tanggal_job_selesai->format('d M Y H:i') : date('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="label">Area Lokasi</td>
                    <td>{{ $job->area->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Total Saldo / Balance</td>
                    <td><strong>{{ $job->balance }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Ringkasan Pekerjaan</td>
                    <td>{{ $job->list_job }}</td>
                </tr>
                @php
                    $latestNote = $job->notes()->latest('id')->first()?->note;
                @endphp
                @if($latestNote)
                <tr>
                    <td class="label">Catatan Penyelesaian</td>
                    <td>{{ $latestNote }}</td>
                </tr>
                @endif
            </table>

            @if($job->items->isNotEmpty())
            <h4 style="margin: 15px 0 5px; font-size: 13px; color: #374151;">Daftar Item & Saldo per Lot:</h4>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Item</th>
                        <th>Nama Item</th>
                        <th>No. Lot</th>
                        <th>Kuantitas / Saldo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($job->items as $idx => $item)
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td><code>{{ $item->item_code ?? '-' }}</code></td>
                        <td><strong>{{ $item->item_name }}</strong></td>
                        <td>
                            @if($item->lot_number)
                                <span class="lot-badge">{{ $item->lot_number }}</span>
                            @else
                                <span style="color: #9ca3af; font-style: italic;">-</span>
                            @endif
                        </td>
                        <td><strong>{{ $item->qty }}</strong> {{ $item->unit ?? 'PCS' }}</td>
                        <td>
                            @if($item->is_completed)
                                <span style="color: #059669; font-weight: bold;">✓ Selesai</span>
                            @else
                                <span style="color: #6b7280;">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

            <p style="margin-top: 20px; font-size: 13px; color: #4b5563;">
                Tiket ini kini siap untuk ditutup dan diarsipkan (Closed) oleh pihak terkait sesuai prosedur Kanban SMII.
            </p>

            <div style="text-align: center; margin-top: 25px;">
                <a href="{{ url('/kanban/jobs') }}" class="btn">Buka Kanban Board</a>
            </div>
        </div>
        <div class="footer">
            <p style="margin: 0;">Email ini dikirim secara otomatis oleh <strong>Sistem Kanban SMII</strong>.</p>
        </div>
    </div>
</body>
</html>
