<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class OverrideTaskDraftRequest extends TaskDraftIdRequest
{
    /**
     * @var array<string, string>
     */
    private const FIELD_MAP = [
        'title' => 'title',
        'description' => 'summary',
        'priority' => 'priority',
        'project' => 'suggested_project',
        'team' => 'suggested_team',
        'human_notes' => 'reviewer_notes',
    ];

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['nullable', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'project' => ['nullable', 'string', 'max:255'],
            'team' => ['nullable', 'string', 'max:255'],
            'human_notes' => ['nullable', 'string'],
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            if (count($this->overrides()) === 0) {
                $v->errors()->add(
                    'fields',
                    'Request must contain at least one overridable field.'
                );
            }
        });
    }

    /**
     * Map request keys to persisted column names (non-null values only).
     *
     * @return array<string, mixed>
     */
    public function overrides(): array
    {
        $out = [];
        foreach (self::FIELD_MAP as $input => $column) {
            if ($this->has($input) && $this->input($input) !== null) {
                $out[$column] = $this->input($input);
            }
        }

        return $out;
    }
}
