<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use App\Models\Attendee as AttendeesModel;
use Livewire\Component;

class Attendees extends Component
{
    public $event, $salutations, $countries;

    public $finalListOfAttendees = array(), $finalListOfAttendeesConst = array();

    public $searchTerm;
    public $addAttendeeForm;

    // Attendee details
    public $username, $password, $salutation, $first_name, $middle_name, $last_name, $email_address, $mobile_number, $landline_number, $company_name, $job_title, $country, $image, $badge_number, $pass_type, $registration_type;

    public $emailExistingError;

    public function mount($eventId, $eventCategory)
    {
        $this->countries = config('app.countries');
        $this->salutations = config('app.salutations');

        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->addAttendeeForm = true;


        $attendees = AttendeesModel::where('event_id', $eventId)->get();
        if ($attendees->isNotEmpty()) {
            foreach ($attendees as $attendee) {
                array_push($this->finalListOfAttendees, [
                    'id' => $attendee->id,
                    'name' => $attendee->salutation . ' ' . $attendee->first_name . ' ' . $attendee->middle_name . ' ' . $attendee->last_name,
                    'job_title' => $attendee->job_title ?? 'N/A',
                    'company_name' => $attendee->company_name ?? 'N/A',
                    'email_address' => $attendee->email_address,
                    'country' => $attendee->country,
                    'registration_type' => $attendee->registration_type,
                ]);
            }
            $this->finalListOfAttendeesConst = $this->finalListOfAttendees;
        }
    }

    public function render()
    {
        return view('livewire.event.attendees.attendees');
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
                        str_contains(strtolower($item['registration_type']), strtolower($this->searchTerm));
                })->all();
        }
    }

    public function showAddAttendee(){
        $this->addAttendeeForm = true; 
    }

    public function cancelAddAttendee(){
        $this->addAttendeeForm = false; 
    }
}
