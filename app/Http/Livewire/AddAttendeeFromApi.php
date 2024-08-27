<?php

namespace App\Http\Livewire;

use App\Models\Attendee as Attendees;
use App\Models\Event as Events;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Support\Str;

class AddAttendeeFromApi extends Component
{
    public $event, $attendeesFromApi;
    public $activeSelectedIndex;

    protected $listeners = ['addAttendeeConfirmed' => 'addAttendee'];

    public function mount($eventId, $eventCategory)
    {
        $this->attendeesFromApi = array();
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();

        $url = env('API_ENDPOINT') . '/event/' . $eventCategory . '/' . $this->event->year .'/attendees';
        $response = Http::get($url)->json();

        if ($response['status'] == '200') {
            foreach($response['data'] as $apiAttendee){
                $attendee = Attendees::where('event_id', $eventId)->where('email_address', $apiAttendee['delegateEmailAddress'])->first();
                
                $is_added = false;
                if($attendee){
                    $is_added = true;
                }

                array_push($this->attendeesFromApi, [
                    'delegateTransactionId' => $apiAttendee['delegateTransactionId'],
                    'delegateInvoiceNumber' => $apiAttendee['delegateInvoiceNumber'],
                    'delegatePassType' => $apiAttendee['delegatePassType'],
                    'delegateCompany' => $apiAttendee['delegateCompany'],
                    'delegateJobTitle' => $apiAttendee['delegateJobTitle'],
                    'delegateSalutation' => $apiAttendee['delegateSalutation'],
                    'delegateFName' => $apiAttendee['delegateFName'],
                    'delegateMName' => $apiAttendee['delegateMName'],
                    'delegateLName' => $apiAttendee['delegateLName'],
                    'delegateEmailAddress' => $apiAttendee['delegateEmailAddress'],
                    'delegateBadgeType' => $apiAttendee['delegateBadgeType'],
                    'delegateIsAdded' => $is_added,
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.event.attendees.add-attendee-from-api');
    }

    public function addAttendeeConfirmation($index){
        $this->activeSelectedIndex = $index;
        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addAttendeeConfirmed",
        ]);
    }

    public function addAttendee()
    {
        $selectedAttendee = $this->attendeesFromApi[$this->activeSelectedIndex];
        $newAttendee = Attendees::create([
            'event_id' => $this->event->id,

            'badge_number' => 'temp',
            'registration_type' => $selectedAttendee['delegateBadgeType'],
            
            'pass_type' => $selectedAttendee['delegatePassType'],
            'company_name' => $selectedAttendee['delegateCompany'],

            'username' => $selectedAttendee['delegateFName'],
            'password' => 'temp',

            'salutation' => $selectedAttendee['delegateSalutation'],
            'first_name' => $selectedAttendee['delegateFName'],
            'middle_name' => $selectedAttendee['delegateMName'],
            'last_name' => $selectedAttendee['delegateLName'],

            'job_title' => $selectedAttendee['delegateJobTitle'],
            'email_address' => $selectedAttendee['delegateEmailAddress'],

            'joined_date_time' => Carbon::now(),
        ]);


        foreach (config('app.eventCategories') as $eventCategoryC => $code) {
            if ($this->event->category == $eventCategoryC) {
                $getEventcode = $code;
            }
        }

        $lastDigit = 1000 + intval($newAttendee->id);
        $badgeNumber = $this->event->year . "$getEventcode" . "$lastDigit";

        $currentDate = Carbon::now();
        $day = $currentDate->format('d');
        $month = $currentDate->format('m');
        $year = $currentDate->format('y');
        $randomString = Str::random(4);

        $randomPassword = $this->event->category . '@' . $newAttendee->id . $randomString . $day . $month . $year;
        $hashRandomPass = Hash::make($randomPassword);

        Attendees::find($newAttendee->id)->fill(
            [
                'password' => $hashRandomPass,
                'badge_number' => $badgeNumber,
            ],
        )->save();

        // $eventFormattedDate = Carbon::parse($this->event->event_start_date)->format('d') . '-' . Carbon::parse($this->event->event_end_date)->format('d M Y');
        
        // $details = [
        //     'subject' => 'Welcome to ' . $this->event->full_name . ' - Your Access Details for GPCA Networking',
        //     'eventCategory' => $this->event->category,
        //     'eventYear' => $this->event->year,

        //     'name' => $this->first_name . ' ' . $this->last_name,
        //     'eventName' => $this->event->full_name,
        //     'eventDate' => $eventFormattedDate,
        //     'eventLocation' => $this->event->location,
        //     'username' => $this->username,
        //     'password' => $randomPassword,
        // ];

        // Mail::to($this->email_address)->send(new NewAttendee($details));

        $this->attendeesFromApi[$this->activeSelectedIndex]['delegateIsAdded'] = true;
        $this->activeSelectedIndex = null;
        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Attendee added successfully!',
            'text' => ''
        ]);
    }
}
