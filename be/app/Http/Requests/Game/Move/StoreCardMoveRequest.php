<?php

namespace App\Http\Requests\Game\Move;

use App\Dtos\Game\Move\CardMoveRequestDto;
use App\Eloquent\Actions\Game\GetActiveGameByUser;
use Illuminate\Foundation\Http\FormRequest;

class StoreCardMoveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $game = GetActiveGameByUser::execute($this->user('sanctum'));

        if (! $game) {
            return false;
        }

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
            'id' => ['required', 'integer'],
        ];
    }

    public function getDto(): CardMoveRequestDto
    {
        return new CardMoveRequestDto(
            deckCardId: $this->validated('id')
        );
    }
}
