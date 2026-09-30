<?php

namespace App\Http\Requests\Kanban;

use Illuminate\Foundation\Http\FormRequest;

class StoreExternalJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'external_id' => 'required|string|max:100|unique:mysql_kanban.kanban_jobs,external_reference_id',
            'area_id' => 'required|exists:mysql_kanban.kanban_areas,id',
            'department_id' => 'required|exists:mysql_kanban.kanban_departments,id',
            'list_job' => 'nullable|string',
            'reason_description' => 'required|string',
            'remark' => 'nullable|string',
            'balance' => 'nullable|integer|min:0',
            'items' => 'nullable|array',
            'items.*.item_code' => 'nullable|string',
            'items.*.item_name' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'external_id.unique' => 'Job dengan ID eksternal ini sudah pernah diterima sebelumnya.',
            'area_id.exists' => 'Area yang dipilih tidak valid.',
            'department_id.exists' => 'Departemen yang dipilih tidak valid.',
        ];
    }
}
