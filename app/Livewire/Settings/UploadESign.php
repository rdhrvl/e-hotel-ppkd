<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Models\Upload;
use App\Services\NotificationService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Upload E-Sign')]
class UploadESign extends Component
{
    use WithFileUploads;

    /** @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile|null */
    public $file = null;

    public ?Upload $existingUpload = null;

    /**
     * Validation rules for the e-sign upload.
     *
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    /**
     * Load the existing e-sign upload for the current user.
     */
    public function mount(): void
    {
        $this->existingUpload = Upload::where('user_id', auth()->id())
            ->where('type', 'e_sign')
            ->latest()
            ->first();
    }

    /**
     * Validate and store the uploaded e-sign file.
     */
    public function upload(): void
    {
        $this->validate();

        $path = $this->file->store('uploads/e-signs', 'public');

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $this->existingUpload = Upload::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => 'e_sign',
            ],
            [
                'file_path' => $path,
                'original_name' => $this->file->getClientOriginalName(),
                'mime_type' => $this->file->getMimeType(),
                'file_size' => $this->file->getSize(),
            ]
        );

        NotificationService::eSignUploaded($user);

        $this->reset('file');

        session()->flash('success', 'E-Sign uploaded successfully.');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.settings.upload-e-sign');
    }
}
