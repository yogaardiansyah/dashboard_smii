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
            'area_id' => 'nullable|exists:mysql_kanban.kanban_areas,id',
            'department_id' => 'nullable|exists:mysql_kanban.kanban_departments,id',
            'department_name' => 'nullable|string|max:255',
            'area_name' => 'nullable|string|max:255',
            'list_job' => 'nullable|string',
            'reason_description' => 'required|string',
            'remark' => 'nullable|string',
            'balance' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:need_review,to_be_scheduled,scheduled,on_going,completed,closed,cancelled,on_hold,preparation',
            'items' => 'nullable|array',
            'items.*.item_code' => 'nullable|string|max:255',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.lot_number' => 'nullable|string|max:100',
            'items.*.lot' => 'nullable|string|max:100',
            'items.*.qty' => 'nullable|integer|min:1',
            'items.*.unit' => 'nullable|string|max:50',
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
