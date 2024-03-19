<?php

namespace App\Http\Requests;

use App\Rules\CanBeAuthor;
use Illuminate\Foundation\Http\FormRequest;

class TripPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required',
            'content' => 'required',
            'posted_at' => 'required|date',
            'thumbnail_id' => 'nullable|exists:media,id',
            'thumb_url' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'type' => 'nullable|string',
            'kind' => 'nullable|string',
            'status' => 'nullable|string',
            'category_id' => 'nullable|integer',
            'image_url' => 'nullable|integer',
            'author_id' => ['required', 'exists:users,id', new CanBeAuthor],
            'slug' => 'unique:posts,slug,' . (optional($this->post)->id ?: 'NULL'),
        ];
    }
}
