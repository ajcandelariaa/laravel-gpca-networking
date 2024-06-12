<?php

namespace App\Http\Livewire;

use App\Mail\NewAttendee;
use App\Models\Event as Events;
use App\Models\Attendee as Attendees;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AttendeesList extends Component
{
    // FROM API
    public $registrationTypes, $members;

    public $event, $salutations, $countries;
    public $finalListOfAttendees = array(), $finalListOfAttendeesConst = array();
    public $searchTerm;

    // Attendee details
    public $addAttendeeForm;
    public $pass_type, $company_name, $registration_type, $email_address, $first_name, $last_name, $username, $job_title;
    public $emailExistingError, $usernameExistingError;

    protected $listeners = ['addAttendeeConfirmed' => 'addAttendee'];

    public function mount($eventId, $eventCategory)
    {
        $this->countries = config('app.countries');
        $this->salutations = config('app.salutations');

        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->addAttendeeForm = false;

        $attendees = Attendees::where('event_id', $eventId)->get();
        if ($attendees->isNotEmpty()) {
            foreach ($attendees as $attendee) {
                array_push($this->finalListOfAttendees, [
                    'id' => $attendee->id,
                    'badge_number' => $attendee->badge_number,
                    'name' => $attendee->salutation . ' ' . $attendee->first_name . ' ' . $attendee->middle_name . ' ' . $attendee->last_name,
                    'job_title' => $attendee->job_title,
                    'email_address' => $attendee->email_address,
                    'company_name' => $attendee->company_name,
                    'registration_type' => $attendee->registration_type,
                ]);
            }
            $this->finalListOfAttendeesConst = $this->finalListOfAttendees;
        }

        $this->members = fetchMembersData();
        $this->registrationTypes = fetchEventRegistrationTypesData($this->event->category, $this->event->year);
    }
    public function render()
    {
        return view('livewire.event.attendees.attendees-list');
    }

    public function search()
    {
        if (empty($this->searchTerm)) {
            $this->finalListOfAttendees = $this->finalListOfAttendeesConst;
        } else {
            $this->finalListOfAttendees = collect($this->finalListOfAttendeesConst)
                ->filter(function ($item) {
                    return str_contains(strtolower($item['name']), strtolower($this->searchTerm)) ||
                        str_contains(strtolower($item['job_title']), strtolower($this->searchTerm)) ||
                        str_contains(strtolower($item['company_name']), strtolower($this->searchTerm)) ||
                        str_contains(strtolower($item['email_address']), strtolower($this->searchTerm)) ||
                        str_contains(strtolower($item['registration_type']), strtolower($this->searchTerm)) ||
                        str_contains(strtolower($item['badge_number']), strtolower($this->searchTerm));
                })->all();
        }
    }

    public function showAddAttendee()
    {
        $this->addAttendeeForm = true;
    }

    public function resetAddAttendeeFields()
    {
        $this->addAttendeeForm = false;

        $this->pass_type = null;
        $this->company_name = null;

        $this->registration_type = null;
        $this->email_address = null;

        $this->first_name = null;
        $this->last_name = null;

        $this->username = null;
        $this->job_title = null;
    }

    public function addAttendeeConfirmation()
    {
        $this->validate([
            'pass_type' => 'required',
            'company_name' => 'required',

            'registration_type' => 'required',
            'email_address' => 'required|email',

            'username' => 'required',
            'job_title' => 'required',

            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        if (checkAttendeeEmailIfExistsInDatabase(null, $this->event->id, $this->email_address)) {
            $this->emailExistingError = "Email is already registered, please use another email!";
        } else {
            $this->emailExistingError = null;
        }


        if (checkAttendeeUsernameIfExistsInDatabase(null, $this->event->id, $this->username)) {
            $this->usernameExistingError = "Username is already registered, please use another username!";
        } else {
            $this->usernameExistingError = null;
        }

        if ($this->emailExistingError == null && $this->usernameExistingError == null) {
            $this->dispatchBrowserEvent('swal:confirmation', [
                'type' => 'warning',
                'message' => 'Are you sure?',
                'text' => "",
                'buttonConfirmText' => "Yes, add it!",
                'livewireEmit' => "addAttendeeConfirmed",
            ]);
        }
    }

    public function addAttendee()
    {
        $newAttendee = Attendees::create([
            'event_id' => $this->event->id,

            'badge_number' => 'temp',
            'registration_type' => $this->registration_type,
            
            'pass_type' => $this->pass_type,
            'company_name' => $this->company_name,

            'username' => $this->username,
            'password' => 'temp',

            'first_name' => $this->first_name,
            'last_name' => $this->last_name,

            'job_title' => $this->job_title,
            'email_address' => $this->email_address,

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

        $eventFormattedDate = Carbon::parse($this->event->event_start_date)->format('d') . '-' . Carbon::parse($this->event->event_end_date)->format('d M Y');
        
        $details = [
            'subject' => 'Welcome to ' . $this->event->name . ' - Your Access Details for GPCA Networking',
            'name' => $this->first_name . ' ' . $this->last_name,
            'eventName' => $this->event->name,
            'eventDate' => $eventFormattedDate,
            'eventLocation' => $this->event->location,
            'username' => $this->username,
            'password' => $randomPassword,
        ];

        //Mail::to($this->email_address)->cc(config('app.ccEmailNotif.test'))->send(new NewAttendee($details));

        array_push($this->finalListOfAttendees, [
            'id' => $newAttendee->id,
            'badge_number' => $badgeNumber,
            'name' => $this->first_name . ' ' . $this->last_name,
            'job_title' => $this->job_title,
            'email_address' => $this->email_address,
            'company_name' => $this->company_name,
            'registration_type' => $this->registration_type,
        ]);

        $this->finalListOfAttendeesConst = $this->finalListOfAttendees;

        $this->resetAddAttendeeFields();
        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Attendee added successfully!',
            'text' => ''
        ]);
    }
}
