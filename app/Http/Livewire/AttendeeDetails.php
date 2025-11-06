<?php

namespace App\Http\Livewire;

use App\Enums\MediaEntityTypes;
use App\Enums\MediaUsageUpdateTypes;
use App\Enums\PasswordChangedBy;
use App\Mail\AttendeeResetPasswordByAdmin;
use Livewire\Component;
use App\Models\Event as Events;
use App\Models\Attendee as Attendees;
use App\Models\AttendeePasswordReset as AttendeePasswordResets;
use App\Models\Media as Medias;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AttendeeDetails extends Component
{
    // FROM API
    public $registrationTypes, $members;

    public $event, $salutations, $countries, $attendeeData;

    // EDIT ATTENDEE DETAILS
    public $attendee_id, $registration_type, $pass_type, $company_name, $company_country, $company_phone_number, $username, $salutation, $first_name, $middle_name, $last_name, $job_title, $email_address, $mobile_number, $biography, $gender, $birthdate, $country, $city, $address, $nationality, $website, $facebook, $twitter, $linkedin, $instagram;
    public $emailExistingError, $usernameExistingError;
    public $editAttendeeForm;


    // EDIT ATTENDEE PASSWORD
    public $newPassword, $confirmPassword, $passwordError;
    public $resetPasswordForm;

    // EDIT ATTENDEE PFP
    public $image_media_id, $image_placeholder_text;
    public $chooseImageModal, $mediaFileList = array(), $activeSelectedImage;
    public $editAttendeePFPForm;

    protected $listeners = ['editAttendeeConfirmed' => 'editAttendee', 'resetPasswordAttendeeConfirmed' => 'resetPasswordAttendee', 'editAttendeePFPConfirmed' => 'editPFPAttendee'];

    public function mount($eventId, $eventCategory, $attendeeData)
    {
        $this->countries = config('app.countries');
        $this->salutations = config('app.salutations');

        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->mediaFileList = getMediaFileList();
        $this->attendeeData = $attendeeData;
        $this->editAttendeeForm = false;
        $this->resetPasswordForm = false;
        $this->editAttendeePFPForm = false;

        $this->members = fetchMembersData();
        $this->registrationTypes = fetchEventRegistrationTypesData($this->event->category, $this->event->year);
    }

    public function render()
    {
        return view('livewire.event.attendees.attendee-details');
    }

    // EDIT ATTENDEE
    public function showEditAttendee()
    {
        $this->attendee_id = $this->attendeeData['attendeeId'];

        $this->registration_type = $this->attendeeData['registration_type'];

        $this->pass_type = $this->attendeeData['pass_type'];
        $this->company_name = $this->attendeeData['company_name'];
        $this->company_country = $this->attendeeData['company_country'];
        $this->company_phone_number = $this->attendeeData['company_phone_number'];

        $this->username = $this->attendeeData['username'];

        $this->salutation = $this->attendeeData['salutation'];
        $this->first_name = $this->attendeeData['first_name'];
        $this->middle_name = $this->attendeeData['middle_name'];
        $this->last_name = $this->attendeeData['last_name'];
        $this->job_title = $this->attendeeData['job_title'];

        $this->email_address = $this->attendeeData['email_address'];
        $this->mobile_number = $this->attendeeData['mobile_number'];

        $this->biography = $this->attendeeData['biography'];

        $this->gender = $this->attendeeData['gender'];
        $this->birthdate = $this->attendeeData['birthdate'];
        $this->country = $this->attendeeData['country'];
        $this->city = $this->attendeeData['city'];
        $this->address = $this->attendeeData['address'];
        $this->nationality = $this->attendeeData['nationality'];

        $this->website = $this->attendeeData['website'];
        $this->facebook = $this->attendeeData['facebook'];
        $this->linkedin = $this->attendeeData['linkedin'];
        $this->twitter = $this->attendeeData['twitter'];
        $this->instagram = $this->attendeeData['instagram'];
        
        $this->editAttendeeForm = true;
    }

    public function resetEditAttendeeFields()
    {
        $this->editAttendeeForm = false;

        $this->attendee_id = null;

        $this->registration_type = null;

        $this->pass_type = null;
        $this->company_name = null;
        $this->company_country = null;
        $this->company_phone_number = null;

        $this->username = null;

        $this->salutation = null;
        $this->first_name = null;
        $this->middle_name = null;
        $this->last_name = null;
        $this->job_title = null;

        $this->email_address = null;
        $this->mobile_number = null;

        $this->biography = null;

        $this->gender = null;
        $this->birthdate = null;
        $this->country = null;
        $this->city = null;
        $this->address = null;
        $this->nationality = null;

        $this->website = null;
        $this->facebook = null;
        $this->linkedin = null;
        $this->twitter = null;
        $this->instagram = null;
    }

    public function editAttendeeConfirmation()
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

        if (checkAttendeeEmailIfExistsInDatabase($this->attendee_id, $this->event->id, $this->email_address)) {
            $this->emailExistingError = "Email is already registered, please use another email!";
        } else {
            $this->emailExistingError = null;
        }


        if (checkAttendeeUsernameIfExistsInDatabase($this->attendee_id, $this->event->id, $this->username)) {
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
            'registration_type' => $this->registration_type,

            'pass_type' => $this->pass_type,
            'company_name' => $this->company_name,
            'company_country' => $this->company_country == "" ? null : $this->company_country,
            'company_phone_number' => $this->company_phone_number == "" ? null : $this->company_phone_number,

            'username' => $this->username,

            'salutation' => $this->salutation == "" ? null : $this->salutation,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name == "" ? null : $this->middle_name,
            'last_name' => $this->last_name,
            'job_title' => $this->job_title,

            'email_address' => $this->email_address,
            'mobile_number' => $this->mobile_number == "" ? null : $this->mobile_number,

            'biography' => $this->biography == "" ? null : $this->biography,

            'gender' => $this->gender == "" ? null : $this->gender,
            'birthdate' => $this->birthdate == "" ? null : $this->birthdate,
            'country' => $this->country == "" ? null : $this->country,
            'city' => $this->city == "" ? null : $this->city,
            'address' => $this->address == "" ? null : $this->address,
            'nationality' => $this->nationality == "" ? null : $this->nationality,

            'website' => $this->website == "" ? null : $this->website,
            'facebook' => $this->facebook == "" ? null : $this->facebook,
            'linkedin' => $this->linkedin == "" ? null : $this->linkedin,
            'twitter' => $this->twitter == "" ? null : $this->twitter,
            'instagram' => $this->instagram == "" ? null : $this->instagram,
        ]);

        $this->attendeeData['registration_type'] = $this->registration_type;

        $this->attendeeData['pass_type'] = $this->pass_type;
        $this->attendeeData['company_name'] = $this->company_name;
        $this->attendeeData['company_country'] = $this->company_country;
        $this->attendeeData['company_phone_number'] = $this->company_phone_number;

        $this->attendeeData['username'] = $this->username;

        $this->attendeeData['salutation'] = $this->salutation;
        $this->attendeeData['first_name'] = $this->first_name;
        $this->attendeeData['middle_name'] = $this->middle_name;
        $this->attendeeData['last_name'] = $this->last_name;
        $this->attendeeData['job_title'] = $this->job_title;

        $this->attendeeData['email_address'] = $this->email_address;
        $this->attendeeData['mobile_number'] = $this->mobile_number;

        $this->attendeeData['biography'] = $this->biography;

        $this->attendeeData['gender'] = $this->gender;
        $this->attendeeData['birthdate'] = $this->birthdate;
        $this->attendeeData['country'] = $this->country;
        $this->attendeeData['city'] = $this->city;
        $this->attendeeData['address'] = $this->address;
        $this->attendeeData['nationality'] = $this->nationality;

        $this->attendeeData['website'] = $this->website;
        $this->attendeeData['facebook'] = $this->facebook;
        $this->attendeeData['linkedin'] = $this->linkedin;
        $this->attendeeData['twitter'] = $this->twitter;
        $this->attendeeData['instagram'] = $this->instagram;

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
        $this->attendee_id = $this->attendeeData['attendeeId'];
        $this->resetPasswordForm = true;
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
        ]);

        AttendeePasswordResets::create([
            'event_id' => $this->event->id,
            'attendee_id' => $this->attendee_id,
            'password_changed_date_time' => Carbon::now(),
        ]);

        $details = [
            'subject' => 'Password reset for ' . $this->event->full_name,
            'eventCategory' => $this->event->category,
            'eventYear' => $this->event->year,

            'name' => $this->attendeeData['first_name'] . ' ' . $this->attendeeData['last_name'],
            'eventName' => $this->event->full_name,
            'username' => $this->attendeeData['username'],
            'newPassword' => $this->newPassword,
        ];

        Mail::to($this->attendeeData['email_address'])->send(new AttendeeResetPasswordByAdmin($details));

        array_push($this->attendeeData['attendeePasswordResetDetails'], [
            'changed_by' => PasswordChangedBy::ADMIN->value,
            'datetime' => Carbon::parse(Carbon::now())->format('M j, Y g:i A'),
        ]);

        $this->resetResetPasswordAttendeeFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Password reset successfully!',
            'text' => ''
        ]);
    }


    // EDIT ATTENDEE PFP
    public function showUpdatePFPAttendee()
    {
        $this->editAttendeePFPForm = true;
    }

    public function resetPFPAttendeeFields()
    {
        $this->editAttendeePFPForm = false;
        $this->image_media_id = null;
        $this->image_placeholder_text = null;
    }

    public function editPFPAttendeeConfirmation()
    {
        $this->validate([
            'image_placeholder_text' => 'required'
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editAttendeePFPConfirmed",
        ]);
    }

    public function editPFPAttendee()
    {
        Attendees::where('id', $this->attendeeData['attendeeId'])->update([
            'pfp_media_id' => $this->image_media_id,
        ]);

        if ($this->attendeeData['pfp']['media_id'] != null) {
            mediaUsageUpdate(
                MediaUsageUpdateTypes::REMOVED_THEN_ADD->value,
                $this->image_media_id,
                MediaEntityTypes::ATTENDEE_PFP->value,
                $this->attendeeData['attendeeId'],
                $this->attendeeData['pfp']['media_usage_id']
            );
        } else {
            mediaUsageUpdate(
                MediaUsageUpdateTypes::ADD_ONLY->value,
                $this->image_media_id,
                MediaEntityTypes::ATTENDEE_PFP->value,
                $this->attendeeData['attendeeId'],
                $this->attendeeData['pfp']['media_usage_id']
            );
        }

        $this->attendeeData['pfp'] = [
            'media_id' => $this->image_media_id,
            'media_usage_id' => getMediaUsageId($this->image_media_id, MediaEntityTypes::ATTENDEE_PFP->value, $this->attendeeData['attendeeId']),
            'url' => Medias::where('id', $this->image_media_id)->value('file_url'),
        ];

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'PFP updated successfully!',
            'text' => ''
        ]);

        $this->resetPFPAttendeeFields();
    }

    public function activateAccount(){
        Attendees::where('id', $this->attendeeData['attendeeId'])->update([
            'password_set_datetime' => Carbon::now(),
        ]);

        $this->attendeeData['password_set_datetime'] = Carbon::now()->format('M j, Y g:i A');
    }




    // FOR CHOOSING IMAGE MODAL
    public function chooseImage()
    {
        $this->chooseImageModal = true;
    }

    public function showMediaFileDetails($arrayIndex)
    {
        $this->activeSelectedImage = $this->mediaFileList[$arrayIndex];
    }

    public function unshowMediaFileDetails()
    {
        $this->activeSelectedImage = array();
    }

    public function selectChooseImage()
    {
        $this->image_media_id = $this->activeSelectedImage['id'];
        $this->image_placeholder_text = $this->activeSelectedImage['file_name'];
        $this->activeSelectedImage = null;
        $this->chooseImageModal = false;
    }

    public function cancelChooseImage()
    {
        $this->image_media_id = null;
        $this->image_placeholder_text = null;
        $this->activeSelectedImage = null;
        $this->chooseImageModal = false;
    }
}
