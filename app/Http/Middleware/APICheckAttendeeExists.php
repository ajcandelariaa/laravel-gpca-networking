<?php

namespace App\Http\Middleware;

use App\Models\Attendee;
use App\Traits\HttpResponses;
use Closure;
use Illuminate\Http\Request;

class APICheckAttendeeExists
{
    use HttpResponses;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $eventId = $request->route('eventId');
        $attendeeId = $request->route('attendeeId');

        $attendee = Attendee::where('id', $attendeeId)->where('event_id', $eventId)->where('is_active', true)->first();

        if ($attendee == null) {
            return $this->error(null, "Attendee doesn't exist", 404);
        } else{
            return $next($request);
        }
    }
}
