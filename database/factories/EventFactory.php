<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition()
    {
        $status_id = random_int(1, EventStatus::all()->count());
        $start_day_offset = random_int(1, 5);
        $end_day_offset = random_int(5, 10);
        $start_time_offset = random_int(0, 10);
        $end_time_offset = random_int(10, 15);

        return [
            'event_status_id' => $status_id,
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'start_date' => now()->addDays($start_day_offset),
            'end_date' => now()->addDays($end_day_offset),
            'start_time' => now()->addHours($start_time_offset)->toTimeString('minute'),
            'end_time' => now()->addHours($end_time_offset)->toTimeString('minute'),
        ];
    }
}
