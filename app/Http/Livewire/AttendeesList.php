<?php

namespace App\Http\Livewire;

use App\Mail\NewAttendee;
use App\Models\Event as Events;
use App\Models\Attendee as Attendees;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AttendeesList extends Component
{
    public $event, $salutations, $countries;

    public $finalListOfAttendees = array(), $finalListOfAttendeesConst = array();

    public $searchTerm;
    public $addAttendeeForm;

    public $registrationTypes, $members;

    // Attendee details
    public $registration_type, $username, $email_address, $pass_type, $company_name, $job_title, $salutation, $first_name, $middle_name, $last_name, $mobile_number, $landline_number, $country;

    public $badge_number, $password;

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
                    'name' => $attendee->salutation . ' ' . $attendee->first_name . ' ' . $attendee->middle_name . ' ' . $attendee->last_name,
                    'job_title' => $attendee->job_title,
                    'company_name' => $attendee->company_name,
                    'email_address' => $attendee->email_address,
                    'country' => $attendee->country,
                    'registration_type' => $attendee->registration_type,
                    'badge_number' => $attendee->badge_number,
                ]);
            }
            $this->finalListOfAttendeesConst = $this->finalListOfAttendees;
        }

        $this->fetchMembersData();
        $this->fetchEventRegistrationTypesData($this->event->category, $this->event->year);
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
                        str_contains(strtolower($item['country']), strtolower($this->searchTerm)) ||
                        str_contains(strtolower($item['registration_type']), strtolower($this->searchTerm)) ||
                        str_contains(strtolower($item['badge_number']), strtolower($this->searchTerm));
                })->all();
        }
    }

    public function showAddAttendee()
    {
        $this->addAttendeeForm = true;
    }

    public function cancelAddAttendee()
    {
        $this->addAttendeeForm = false;
    }

    public function resetAddAttendeeFields()
    {
        $this->addAttendeeForm = false;
        $this->registration_type = null;
        $this->username = null;
        $this->email_address = null;
        $this->pass_type = null;
        $this->company_name = null;
        $this->job_title = null;
        $this->salutation = null;
        $this->first_name = null;
        $this->middle_name = null;
        $this->last_name = null;
        $this->mobile_number = null;
        $this->landline_number = null;
        $this->country = null;
    }

    public function addAttendeeConfirmation()
    {
        $this->validate([
            'registration_type' => 'required',
            'username' => 'required',
            'email_address' => 'required|email',
            'pass_type' => 'required',
            'company_name' => 'required',
            'job_title' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile_number' => 'required',
            'country' => 'required',
        ]);

        if ($this->checkEmailIfExistsInDatabase($this->email_address)) {
            $this->emailExistingError = "Email is already registered, please use another email!";
        } else {
            $this->emailExistingError = null;
        }


        if ($this->checkUsernameIfExistsInDatabase($this->username)) {
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

            'username' => $this->username,
            'password' => 'temp',

            'salutation' => $this->salutation,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email_address' => $this->email_address,
            'mobile_number' => $this->mobile_number,
            'landline_number' => $this->landline_number,

            'company_name' => $this->company_name,
            'job_title' => $this->job_title,
            'country' => $this->country,

            'badge_number' => 'temp',
            'pass_type' => $this->pass_type,
            'registration_type' => $this->registration_type,

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
            'name' => $this->salutation . ' ' . $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name,
            'eventName' => $this->event->name,
            'eventDate' => $eventFormattedDate,
            'eventLocation' => $this->event->location,
            'username' => $this->username,
            'password' => $randomPassword,
        ];

        Mail::to($this->email_address)->cc(config('app.ccEmailNotif.test'))->queue(new NewAttendee($details));

        array_push($this->finalListOfAttendees, [
            'id' => $newAttendee->id,
            'name' => $this->salutation . ' ' . $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name,
            'job_title' => $this->job_title,
            'company_name' => $this->company_name,
            'email_address' => $this->email_address,
            'country' => $this->country,
            'registration_type' => $this->registration_type,
            'badge_number' => $badgeNumber,
        ]);

        $this->finalListOfAttendeesConst = $this->finalListOfAttendees;

        $this->resetAddAttendeeFields();
        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Attendee added successfully!',
            'text' => ''
        ]);
    }

    public function fetchMembersData()
    {
        $url = env('API_ENDPOINT') . '/members';
        $response = Http::get($url)->json();

        if ($response['status'] == '200') {
            $this->members = $response;
        }
    }

    public function fetchEventRegistrationTypesData($eventCategory, $eventYear)
    {
        $url = env('API_ENDPOINT') . '/event/' . $eventCategory . '/' . $eventYear;
        $response = Http::get($url)->json();

        if ($response['status'] == '200') {
            $this->registrationTypes = $response;
        }
    }

    public function checkEmailIfExistsInDatabase($emailAddress)
    {
        $attendee = Attendees::where('event_id', $this->event->id)->where('email_address', $emailAddress)->first();

        if ($attendee) {
            return true;
        } else {
            return false;
        }
    }

    public function checkUsernameIfExistsInDatabase($username)
    {
        $attendee = Attendees::where('event_id', $this->event->id)->where('username', $username)->first();

        if ($attendee) {
            return true;
        } else {
            return false;
        }
    }
}
