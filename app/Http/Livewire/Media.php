<?php

namespace App\Http\Livewire;

use App\Enums\FileUploadDirectory;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Models\Media as Medias;

class Media extends Component
{
    use WithFileUploads;

    public $mediaFile;
    public $addMediaForm;
    public $showMediaModal = false;
    public $mediaFileList = array();
    public $activeMediaFile;

    protected $listeners = ['addMediaConfirmed' => 'addMedia'];

    public function mount()
    {
        $mediaFileListTemp = Medias::orderBy('date_uploaded', 'DESC')->get();
        if($mediaFileListTemp->isNotEmpty()){
            foreach($mediaFileListTemp as $mediaFile){
                array_push($this->mediaFileList, [
                    'id' => $mediaFile->id,
                    'file_url' => $mediaFile->file_url,
                    'file_directory' => $mediaFile->file_directory,
                    'file_name' => $mediaFile->file_name,
                    'file_type' => $mediaFile->file_type,
                    'file_size' => $mediaFile->file_size,
                    'width' => $mediaFile->width,
                    'height' => $mediaFile->height,
                    'date_uploaded' => $mediaFile->date_uploaded,
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.home.media.media');
    }

    public function showAddMedia()
    {
        $this->addMediaForm = true;
    }

    public function cancelAddMedia()
    {
        $this->addMediaForm = false;
        $this->mediaFile = null;
    }

    public function addMediaConfirmation()
    {
        $this->validate([
            'mediaFile' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addMediaConfirmed",
        ]);
    }

    public function addMedia()
    {
        $filename = pathinfo($this->mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $this->mediaFile->getClientOriginalExtension();
        $uniqueFilename = $filename . '_' . time() . '_' . Str::random(10) . '.' . $extension;
        $fileDirectory = FileUploadDirectory::UPLOADS->value;
        $path = $this->mediaFile->storeAs($fileDirectory, $uniqueFilename, 's3');
        $fileUrl = Storage::disk('s3')->url($path);
        $fileSize = $this->mediaFile->getSize();
        $dateUploaded = now();

        $width = null;
        $height = null;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileType = finfo_file($finfo, $this->mediaFile->getRealPath());
        finfo_close($finfo);

        if ($fileType === 'application/octet-stream') {
            $fileType = getMimeTypeByExtension($extension);
        }

        if (str_starts_with($fileType, 'image/')) {
            $imageSize = getimagesize($this->mediaFile->getRealPath());
            if ($imageSize) {
                $width = $imageSize[0];
                $height = $imageSize[1];
            }
        }

        $media = Medias::create([
            'file_url' => $fileUrl,
            'file_directory' => $fileDirectory,
            'file_name' => $uniqueFilename,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'width' => $width, 
            'height' => $height, 
            'date_uploaded' => $dateUploaded,
        ]);

        array_unshift($this->mediaFileList, $media);
        $this->addMediaForm = false;
        $this->mediaFile = null;

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'File added successfully!',
            'text' => ''
        ]);
    }


    public function showMediaFileDetails($arrayIndex){
        $this->showMediaModal = true;
        $this->activeMediaFile = $this->mediaFileList[$arrayIndex];
    }


}
