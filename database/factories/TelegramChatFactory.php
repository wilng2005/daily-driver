<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TelegramChat>
 */
class TelegramChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'data'=> [],
            'tg_chat_id'=> $this->faker->randomNumber(5, true),
            'configuration'=> [],
        ];
    }
}
