<?php

namespace App\Transformers;

use App\Models\Event;
use League\Fractal\TransformerAbstract;

class EventTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Event $event)
    {
        return [
            'id' => (int) $event->id,
            'userId' => $event->user_id,
            'title' => $event->name,
            'statusId' => $event->event_status_id,
            'imageUrl' => $event->images->first(),
            'description' => $event->description,
            'startDate' => $event->start_date,
            'endDate' => $event->end_date,
            'startTime' => $event->start_time,
            'endTime' => $event->end_time,
            'ageRestriction' => $event->age_restriction,
            'displayStartTime' => $event->display_start_time === Event::DISPLAY_TIME_ON
                                ? 'ON'
                                : 'OFF',
            'displayEndTime' => $event->display_end_time === Event::DISPLAY_TIME_ON
                                ? 'ON'
                                : 'OFF',
            'createAt' => $event->created_at
        ];
    }
}
