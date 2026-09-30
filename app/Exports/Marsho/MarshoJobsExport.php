<?php

namespace App\Exports\Marsho;

use App\Models\Marsho\JobMarsho;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MarshoJobsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * Mengambil data dari database secara efisien menggunakan query builder.
    * Relasi yang dibutuhkan di-load menggunakan with() untuk mencegah N+1 problem.
    */
    public function query()
    {
        return JobMarsho::query()->with([
            'pengaju', 
            'penutup', 
            'area', 
            'latestRoute.toDepartment'
        ]);
    }

    /**
    * Mendefinisikan baris header (judul kolom) pada file Excel.
    */
    public function headings(): array
    {
        return [
            'ID Job',
            'Status',
            'Area',
            'List Pekerjaan',
            'Departemen Terakhir',
            'Diajukan Oleh',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Ditutup Oleh',
            'Tanggal Ditutup',
        ];
    }

    /**
    * Memetakan setiap baris data dari model JobMarsho ke dalam format array.
    */
    public function map($job): array
    {
        return [
            $job->id_job,
            ucfirst(str_replace('_', ' ', $job->status)),
            $job->area->name ?? 'N/A',
            $job->list_job,
            $job->latestRoute->toDepartment->department_name ?? 'N/A',
            $job->pengaju->name ?? 'N/A',
            $job->tanggal_job_mulai ? $job->tanggal_job_mulai->format('d-m-Y H:i') : '',
            $job->tanggal_job_selesai ? $job->tanggal_job_selesai->format('d-m-Y H:i') : '',
            $job->penutup->name ?? 'N/A',
            $job->closed_at ? $job->closed_at->format('d-m-Y H:i') : '',
        ];
    }
}
