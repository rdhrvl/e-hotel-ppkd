<div>
    <select wire:model.live="selectedBranchId" class="block rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3.5 py-2 text-xs font-semibold text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all cursor-pointer">
        @foreach($branches as $branch)
            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
        @endforeach
    </select>
</div>
