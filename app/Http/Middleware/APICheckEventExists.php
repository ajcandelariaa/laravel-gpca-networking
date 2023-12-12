<?php

namespace App\Http\Middleware;

use App\Models\Event;
use Closure;
use Illuminate\Http\Request;

class APICheckEventExists
{
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
        $eventCategory = $request->route('eventCategory');

        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();

        if ($event == null) {
            return response()->json([
                'status' => 404,
                'message' => "Event not found",
            ], 404);
        } else{
            return $next($request);
        }
    }
}
