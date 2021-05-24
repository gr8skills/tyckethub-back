<?php

namespace Database\Seeders;

use App\Models\Artiste;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventTag;
use App\Models\Organizer;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendeeEventOrganizerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Event::truncate();

        //Fetch all the Organizers and loop through them
        Organizer::all()->each(function ($organizer) {
            //Calc randomly the number of events to be created for the this organizer
            $event_counts = random_int(1, 10);
            Event::factory($event_counts)->create([
                'user_id' => $organizer->id
            ]);
        });

        //Fetch all the events and attach them to attendees
        Event::all()->each(function ($event) {
            //Get random attendee and attach it to this event
            $attendee_id_array = Attendee::all('id')->pluck('id')->toArray();
            $rnd_index = random_int(0, (count($attendee_id_array) - 1));
            $random_attendee_id = $attendee_id_array[$rnd_index];
            unset($rnd_index);

            //Select random category and attach to the created event
            $category_id_array = EventCategory::all('id')->pluck('id')->toArray();
            $rnd_index = random_int(0, (count($category_id_array) - 1));
            $random_cat_id = $category_id_array[$rnd_index];
            unset($rnd_index);

            //Select random artiste and attach to the created event
            $artiste_id_array = Artiste::all('id')->pluck('id')->toArray();
            $rnd_index = random_int(0, (count($artiste_id_array) - 1));
            $random_artiste_id = $artiste_id_array[$rnd_index];
            unset($rnd_index);

            //Select random tag and attach to the created event
            $tag_id_array = EventTag::all('id')->pluck('id')->toArray();
            $rnd_index = random_int(0, (count($tag_id_array) - 1));
            $random_tag_id = $tag_id_array[$rnd_index];
            unset($rnd_index);

            $event->attendees()->attach($random_attendee_id);
            $event->categories()->attach($random_cat_id);
            $event->artistes()->attach($random_artiste_id);
            $event->tags()->attach($random_tag_id);
        });
    }
}
