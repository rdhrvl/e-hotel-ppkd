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
#[Title('Upload E-Stamp')]
class UploadEStamp extends Component
{
    use WithFileUploads;

    /** @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile|null */
    public $file = null;

    public ?Upload $existingUpload = null;

    /**
     * Validation rules for the e-stamp upload.
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
     * Verify the user is a warehouse user and load existing upload.
     */
    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (! $user->isWarehouse()) {
            abort(403, 'Only warehouse users can upload e-stamps.');
        }

        $this->existingUpload = Upload::where('user_id', $user->id)
            ->where('type', 'e_stamp')
            ->latest()
            ->first();
    }

    /**
     * Validate and store the uploaded e-stamp file.
     */
    public function upload(): void
    {
        $this->validate();

        $path = $this->file->store('uploads/e-stamps', 'public');

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $this->existingUpload = Upload::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => 'e_stamp',
            ],
            [
                'file_path' => $path,
                'original_name' => $this->file->getClientOriginalName(),
                'mime_type' => $this->file->getMimeType(),
                'file_size' => $this->file->getSize(),
            ]
        );

        NotificationService::eStampUploaded($user);

        $this->reset('file');

        session()->flash('success', 'E-Stamp uploaded successfully.');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.settings.upload-e-stamp');
    }
}
