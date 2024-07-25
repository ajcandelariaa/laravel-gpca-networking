<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use App\Models\Notification as Notifications;
use Carbon\Carbon;
use Livewire\Component;

class NotificationList extends Component
{
    public $event;
    public $notificationTypeChoices = array();
    public $finalListOfNotifications = array();

    // ADD MRP
    public $type, $title, $subtitle, $message, $send_datetime;
    public $addNotificationForm;

    public $activeEditIndex, $editNotificationForm;

    // DELETE
    public $activeDeleteIndex;

    protected $listeners = ['addNotificationConfirmed' => 'addNotification', 'editNotificationConfirmed' => 'editNotification', 'deleteNotificationConfirmed' => 'deleteNotification'];
    
    public function mount($eventId, $eventCategory)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();

        $notifications = Notifications::where('event_id', $eventId)->orderBy('send_datetime', 'ASC')->get();

        if ($notifications->isNotEmpty()) {
            foreach ($notifications as $notification) {
                array_push($this->finalListOfNotifications, [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'subtitle' => $notification->subtitle,
                    'message' => $notification->message,
                    'is_sent' => $notification->is_sent,
                    'send_date' => Carbon::parse($notification->send_datetime)->format('M j, Y'),
                    'send_time' => Carbon::parse($notification->send_datetime)->format('g:i A'),
                    'send_datetime' => $notification->send_datetime,
                ]);
            }
        }

        $this->notificationTypeChoices = config('app.notificationTypes');

        $this->addNotificationForm = false;
        $this->editNotificationForm = false;
    }
    public function render()
    {
        return view('livewire.event.notifications.notification-list');
    }
    
    public function showAddNotification()
    {
        $this->addNotificationForm = true;
    }

    public function addNotificationConfirmation()
    {
        $this->validate([
            'type' => 'required',
            'title' => 'required',
            'send_datetime' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addNotificationConfirmed",
        ]);
    }

    public function resetAddNotificationFields()
    {
        $this->addNotificationForm = false;
        $this->type = null;
        $this->title = null;
        $this->subtitle = null;
        $this->message = null;
        $this->send_datetime = null;
    }

    public function addNotification(){
        $newNotification = Notifications::create([
            'event_id' => $this->event->id,
            'type' => $this->type,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'message' => $this->message,
            'send_datetime' => $this->send_datetime,
        ]);
        
        array_push($this->finalListOfNotifications, [
            'id' => $newNotification->id,
            'type' => $this->type,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'message' => $this->message,
            'is_sent' => false,
            'send_date' => Carbon::parse($this->send_datetime)->format('M j, Y'),
            'send_time' => Carbon::parse($this->send_datetime)->format('g:i A'),
            'send_datetime' => $this->send_datetime,
        ]);

        $this->resetAddNotificationFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Notification added successfully!',
            'text' => ''
        ]);
    }


    // EDIT
    public function showEditNotification($index){
        $this->activeEditIndex = $index;
        $this->type = $this->finalListOfNotifications[$this->activeEditIndex]['type'];
        $this->title = $this->finalListOfNotifications[$this->activeEditIndex]['title'];
        $this->subtitle = $this->finalListOfNotifications[$this->activeEditIndex]['subtitle'];
        $this->message = $this->finalListOfNotifications[$this->activeEditIndex]['message'];
        $this->send_datetime = $this->finalListOfNotifications[$this->activeEditIndex]['send_datetime'];
        $this->editNotificationForm = true;

    }

    public function editNotificationConfirmation()
    {
        $this->validate([
            'type' => 'required',
            'title' => 'required',
            'send_datetime' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editNotificationConfirmed",
        ]);
    }

    public function editNotification(){
        Notifications::where('id', $this->finalListOfNotifications[$this->activeEditIndex]['id'])->update([
            'type' => $this->type,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'message' => $this->message,
            'send_datetime' => $this->send_datetime,
        ]);

        $this->finalListOfNotifications[$this->activeEditIndex]['type'] = $this->type;
        $this->finalListOfNotifications[$this->activeEditIndex]['title'] = $this->title;
        $this->finalListOfNotifications[$this->activeEditIndex]['subtitle'] = $this->subtitle;
        $this->finalListOfNotifications[$this->activeEditIndex]['message'] = $this->message;
        $this->finalListOfNotifications[$this->activeEditIndex]['send_date'] = Carbon::parse($this->send_datetime)->format('M j, Y');
        $this->finalListOfNotifications[$this->activeEditIndex]['send_time'] = Carbon::parse($this->send_datetime)->format('g:i A');
        $this->finalListOfNotifications[$this->activeEditIndex]['send_datetime'] = $this->send_datetime;

        $this->resetEditNotificationFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Notification updated successfully!',
            'text' => ''
        ]);
    }
    

    public function resetEditNotificationFields()
    {
        $this->editNotificationForm = false;
        $this->type = null;
        $this->title = null;
        $this->subtitle = null;
        $this->message = null;
        $this->send_datetime = null;
    }


    // DELETE
    public function deleteNotificationConfirmation($index)
    {
        $this->activeDeleteIndex = $index;
        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure you want to delete?',
            'text' => "",
            'buttonConfirmText' => "Yes, delete it!",
            'livewireEmit' => "deleteNotificationConfirmed",
        ]);
    }

    public function deleteNotification()
    {
        $notification = Notifications::where('id', $this->finalListOfNotifications[$this->activeDeleteIndex]['id'])->first();

        if($notification){
            $notification->delete();

            unset($this->finalListOfNotifications[$this->activeDeleteIndex]);
            $this->finalListOfNotifications = array_values($this->finalListOfNotifications);
        }
        
        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Notification deleted successfully!',
            'text' => ''
        ]);
    }
}
