<div class="card" style="max-width: 500px; margin: 0 auto;">
    <h3 class="mb-2" style="font-size: 1.25rem;">Digital Signature (E-Sign)</h3>
    <p class="mb-6" style="font-size: 0.85rem; color: var(--text-muted);">Upload your electronic signature file. Supported formats: JPG, PNG, PDF (max 2MB).</p>

    <form wire:submit.prevent="upload">
        <!-- Dropzone -->
        <div class="upload-zone mb-6" onclick="document.getElementById('file-upload').click();">
            <input type="file" id="file-upload" wire:model="file" style="display: none;" accept="image/png, image/jpeg, image/jpg, application/pdf">
            <span style="font-size: 2.5rem; display: block; margin-bottom: 8px;">🖋️</span>
            @if($file)
                <span style="color: var(--success); font-weight: 600;">Selected: {{ $file->getClientOriginalName() }}</span>
            @else
                <span>Drag & drop or <span style="color: var(--accent-primary); font-weight: 600;">browse files</span></span>
            @endif
        </div>

        @error('file') <span class="form-error block mb-4" style="text-align: center;">{{ $message }}</span> @enderror

        <!-- Current File Preview -->
        @if($existingUpload)
            <div class="mb-6" style="border-top: 1px solid var(--border-color); padding-top: 16px;">
                <label class="form-label mb-2">Current Active E-Signature</label>
                @if(str_contains($existingUpload->mime_type ?? '', 'pdf'))
                    <div style="background-color: rgba(255,255,255,0.03); padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); display: flex; align-items: center; gap: 12px;">
                        <span style="font-size: 1.5rem;">📄</span>
                        <div>
                            <div style="font-weight: 500; font-size: 0.9rem; color: var(--text-primary);">{{ $existingUpload->original_name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">PDF Document • Updated {{ $existingUpload->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @else
                    <div style="text-align: center; background-color: #0b0b16; border-radius: var(--radius); border: 1px solid var(--border-color); padding: 16px;">
                        <img src="{{ Storage::url($existingUpload->file_path) }}" alt="E-Sign Preview" style="max-height: 120px; max-width: 100%; object-fit: contain;">
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 8px;">Uploaded {{ $existingUpload->updated_at->diffForHumans() }}</div>
                    </div>
                @endif
            </div>
        @endif

        <div class="flex justify-between" style="gap: 12px;">
            <a href="{{ route('settings') }}" class="btn btn-secondary" style="flex: 1;">Back</a>
            <button type="submit" class="btn btn-primary" style="flex: 1;" wire:loading.attr="disabled">
                <span wire:loading.remove>Upload File</span>
                <span wire:loading>Processing...</span>
            </button>
        </div>
    </form>
</div>
