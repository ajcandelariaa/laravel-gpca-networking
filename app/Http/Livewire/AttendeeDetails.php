<?php

namespace App\Http\Livewire;

use App\Mail\AttendeeResetPasswordByAdmin;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\Event as Events;
use App\Models\Attendee as Attendees;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class AttendeeDetails extends Component
{
    use WithFileUploads;

    public $event, $salutations, $countries, $attendeeData;
    public $registrationTypes, $members;

    public $editAttendeeForm, $resetPasswordForm, $editAttendeeImageForm;

    // Attendee details
    public $attendee_id, $registration_type, $username, $email_address, $pass_type, $company_name, $job_title, $salutation, $first_name, $middle_name, $last_name, $mobile_number, $landline_number, $country, $image, $biography, $password;

    public $newPassword, $confirmPassword, $passwordError;

    public $emailExistingError, $usernameExistingError;

    protected $listeners = ['editAttendeeConfirmed' => 'editAttendee', 'resetPasswordAttendeeConfirmed' => 'resetPasswordAttendee', 'editAttendeeImageConfirmed' => 'editImageAttendee'];

    public function mount($eventId, $eventCategory, $attendeeData)
    {
        $this->countries = config('app.countries');
        $this->salutations = config('app.salutations');

        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->attendeeData = $attendeeData;
        $this->editAttendeeForm = false;
        $this->resetPasswordForm = false;
        $this->editAttendeeForm = false;

        // dd($this->attendeeData);
        $this->fetchMembersData();
        $this->fetchEventRegistrationTypesData($this->event->category, $this->event->year);
    }

    public function render()
    {
        return view('livewire.event.attendees.attendee-details');
    }

    // EDIT ATTENDE 
    public function showEditAttendee()
    {
        $this->editAttendeeForm = true;
        $this->attendee_id = $this->attendeeData['attendeeId'];
        
        $this->salutation = $this->attendeeData['attendeeSalutation'];
        $this->first_name = $this->attendeeData['attendeeFirstName'];
        $this->middle_name = $this->attendeeData['attendeeMiddleName'];
        $this->last_name = $this->attendeeData['attendeeLastName'];

        $this->job_title = $this->attendeeData['attendeeJobTitle'];
        $this->company_name = $this->attendeeData['attendeeCompany'];
        $this->username = $this->attendeeData['attendeeUsername'];
        $this->email_address = $this->attendeeData['attendeeEmail'];

        $this->mobile_number = $this->attendeeData['attendeeMobileNumber'];
        $this->landline_number = $this->attendeeData['attendeeLandlineNumber'];

        $this->country = $this->attendeeData['attendeeCountry'];
        $this->pass_type = $this->attendeeData['attendeePassType'];
        $this->registration_type = $this->attendeeData['attendeeRegistrationType'];

        $this->biography = $this->attendeeData['attendeeBiography'];
    }

    public function cancelEditAttendee()
    {
        $this->resetEditAttendeeFields();
    }

    public function resetEditAttendeeFields()
    {
        $this->editAttendeeForm = false;
        $this->attendee_id = null;

        $this->salutation = null;
        $this->first_name = null;
        $this->middle_name = null;
        $this->last_name = null;

        $this->job_title = null;
        $this->company_name = null;
        $this->username = null;
        $this->email_address = null;

        $this->mobile_number = null;
        $this->landline_number = null;

        $this->country = null;
        $this->pass_type = null;
        $this->registration_type = null;

        $this->biography = null;
    }

    public function editAttendeeConfirmation()
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
                'buttonConfirmText' => "Yes, update it!",
                'livewireEmit' => "editAttendeeConfirmed",
            ]);
        }
    }

    public function editAttendee()
    {
        Attendees::where('id', $this->attendee_id)->update([
            'username' => $this->username,

            'salutation' => $this->salutation,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,

            'email_address' => $this->email_address,
            'mobile_number' => $this->mobile_number,
            'landline_number' => $this->landline_number == "" ? null : $this->landline_number,

            'company_name' => $this->company_name,
            'job_title' => $this->job_title,
            'country' => $this->country,

            'biography' => $this->biography == "" ? null : $this->biography,
            'pass_type' => $this->pass_type,
            'registration_type' => $this->registration_type,
        ]);

        
        $this->attendeeData['attendeeSalutation'] = $this->salutation;
        $this->attendeeData['attendeeFirstName'] = $this->first_name;
        $this->attendeeData['attendeeMiddleName'] = $this->middle_name;
        $this->attendeeData['attendeeLastName'] = $this->last_name;

        $this->attendeeData['attendeeJobTitle'] = $this->job_title;
        $this->attendeeData['attendeeCompany'] = $this->company_name;
        $this->attendeeData['attendeeUsername'] = $this->username;
        $this->attendeeData['attendeeEmail'] = $this->email_address;

        $this->attendeeData['attendeeMobileNumber'] = $this->mobile_number;
        $this->attendeeData['attendeeLandlineNumber'] = $this->landline_number;

        $this->attendeeData['attendeeCountry'] = $this->country;
        $this->attendeeData['attendeePassType'] = $this->pass_type;
        $this->attendeeData['attendeeRegistrationType'] = $this->registration_type;

        $this->attendeeData['attendeeBiography'] = $this->biography;

        $this->resetEditAttendeeFields();
        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Attendee updated successfully!',
            'text' => ''
        ]);
    }



    // RESET PASSWORD
    public function showResetPasswordAttendee()
    {
        $this->resetPasswordForm = true;
        $this->attendee_id = $this->attendeeData['attendeeId'];
    }

    public function cancelResetPasswordAttendee()
    {
        $this->resetResetPasswordAttendeeFields();
    }

    public function resetResetPasswordAttendeeFields()
    {
        $this->resetPasswordForm = false;
        $this->attendee_id = null;
        $this->newPassword = null;
        $this->confirmPassword = null;
        $this->passwordError = null;
    }

    public function resetPasswordAttendeeConfirmation()
    {
        $this->validate([
            'newPassword' => 'required|min:8',
            'confirmPassword' => 'required',
        ]);

        if ($this->newPassword == $this->confirmPassword) {
            $this->passwordError = null;
            $this->dispatchBrowserEvent('swal:confirmation', [
                'type' => 'warning',
                'message' => 'Are you sure?',
                'text' => "",
                'buttonConfirmText' => "Yes, reset it!",
                'livewireEmit' => "resetPasswordAttendeeConfirmed",
            ]);
        } else {
            $this->passwordError = "Password does not match!";
        }
    }

    public function resetPasswordAttendee()
    {
        Attendees::where('id', $this->attendee_id)->update([
            'password' => Hash::make($this->newPassword),
            'password_changed_date_time' => Carbon::now(),
        ]);
        
        Attendees::where('id', $this->attendee_id)->increment('password_changed_count');
        
        $details = [
            'name' => $this->attendeeData['attendeeSalutation'] . ' ' . $this->attendeeData['attendeeFirstName'] . ' ' . $this->attendeeData['attendeeMiddleName'] . ' ' . $this->attendeeData['attendeeLastName'],
            'eventName' => $this->event->name,
            'username' => $this->attendeeData['attendeeUsername'],
            'newPassword' => $this->newPassword,
        ];

        Mail::to($this->attendeeData['attendeeEmail'])->cc(config('app.ccEmailNotif.test'))->queue(new AttendeeResetPasswordByAdmin($details));

        $this->attendeeData['attendeeLastPasswordChangeDateTime'] = Carbon::parse(Carbon::now())->format('M j, Y g:i A');

        $this->resetResetPasswordAttendeeFields();
        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Password reset successfully!',
            'text' => ''
        ]);
    }


    // EDIT ATTENDEE IMAGE
    public function showUpdateImageAttendee(){
        $this->editAttendeeImageForm = true;
        $this->attendee_id = $this->attendeeData['attendeeId'];
    }

    public function cancelEditImageAttendee(){
        $this->resetImageAttendeeFields();
    }

    public function resetImageAttendeeFields()
    {
        $this->editAttendeeImageForm = false;
        $this->attendee_id = null;
        $this->image = null;
    }

    public function editImageAttendeeConfirmation(){
        $this->validate([
            'image' => 'required|mimes:jpeg,jpg,png',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editAttendeeImageConfirmed",
        ]);
    }

    public function editImageAttendee(){
        
        $currentYear = strval(Carbon::parse($this->event->event_start_date)->year);
        $fileName = time() . '-' . $this->image->getClientOriginalName();
        $path = $this->image->storeAs('public/event/' . $currentYear . '/attendees/' . $this->event->category, $fileName);

        Attendees::where('id', $this->attendee_id)->update([
            'image' => $path,
        ]);

        $this->attendeeData['attendeeImage'] = Storage::url($path);
        
        $this->resetImageAttendeeFields();
        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Image updated successfully!',
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
        $attendee = Attendees::where('id', '!=', $this->attendee_id)->where('event_id', $this->event->id)->where('email_address', $emailAddress)->first();

        if ($attendee) {
            return true;
        } else {
            return false;
        }
    }

    public function checkUsernameIfExistsInDatabase($username)
    {
        $attendee = Attendees::where('id', '!=', $this->attendee_id)->where('event_id', $this->event->id)->where('username', $username)->first();

        if ($attendee) {
            return true;
        } else {
            return false;
        }
    }
}
