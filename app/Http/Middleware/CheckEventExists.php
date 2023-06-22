<?php

namespace App\Http\Middleware;

use App\Models\Event;
use Closure;
use Illuminate\Http\Request;

class CheckEventExists
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

        $event = Event::where('category', $eventCategory)->where('id', $eventId)->first();

        if (!$event) {
            abort(404, 'Data not found'); 
        }

        return $next($request);
    }
}
