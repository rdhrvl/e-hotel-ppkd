<div class="max-w-5xl mx-auto py-6">
    {{-- Header Banner --}}
    <div class="bg-[var(--bg-card)] text-[var(--text-primary)] p-6 rounded border border-[var(--border-color)] shadow-sm mb-6">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded bg-[var(--text-primary)] flex items-center justify-center font-bold text-[var(--bg-card)]">
                H
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight text-[var(--text-primary)]">PPKD HOTEL</h1>
                <p class="text-[10px] text-[var(--text-muted)] font-bold tracking-wider uppercase mt-0.5">Formulir Pendaftaran / Registration Form</p>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="confirmBooking" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- LEFT COLUMN --}}
            <div class="space-y-6">
                {{-- Section 1 - INFORMASI KAMAR / ROOM DETAILS --}}
                <div class="bg-[var(--bg-card)] rounded border border-[var(--border-color)] p-5 shadow-sm space-y-4">
                    <h2 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wide border-b border-[var(--border-color)] pb-2">
                        I. INFORMASI KAMAR / ROOM DETAILS
                    </h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">ROOM NO.</label>
                            <input type="text" value="{{ $room?->room_number }}" readonly class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-muted)] bg-[var(--bg-secondary)] cursor-not-allowed outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">ROOM TYPE</label>
                            <input type="text" value="{{ $room?->roomType?->name }}" readonly class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-muted)] bg-[var(--bg-secondary)] cursor-not-allowed outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">NO. OF ROOM</label>
                            <input type="number" wire:model="noOfRoom" class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition placeholder-[#8e8d89]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">NO. OF PERSON</label>
                            <input type="number" wire:model="noOfPerson" class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition placeholder-[#8e8d89]">
                            @error('noOfPerson') <span class="text-xs text-[var(--danger)] block mt-1 font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">RECEPTIONIST</label>
                        <input type="text" wire:model="receptionist" readonly class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-muted)] bg-[var(--bg-secondary)] cursor-not-allowed outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">CATATAN TAMBAHAN / ADDITIONAL NOTES (OPTIONAL)</label>
                        <textarea wire:model="additionalNotes" rows="3" class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition resize-none placeholder-[#8e8d89]" placeholder="Masukkan instruksi khusus atau permintaan..."></textarea>
                    </div>

                    <div class="bg-[var(--success-bg)] border border-[var(--border-color)] rounded p-3 text-xs text-[var(--success)] font-bold">
                        CHECK-OUT TIME - 12.00 Noon / Jam 12.00 Siang
                    </div>
                </div>

                {{-- Section 2 - DATA TAMU / GUEST INFORMATION --}}
                <div class="bg-[var(--bg-card)] rounded border border-[var(--border-color)] p-5 shadow-sm space-y-4">
                    <div class="border-b border-[var(--border-color)] pb-2">
                        <h2 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wide">
                            II. DATA TAMU / GUEST INFORMATION
                        </h2>
                        <span class="text-[9px] text-[var(--text-muted)] font-bold uppercase tracking-wide block mt-0.5">
                            Harap tulis dengan huruf cetak - Please print in block letters
                        </span>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">NAMA / NAME <span class="text-[var(--danger)] ml-0.5">*</span></label>
                        <input type="text" wire:model="guestName" required class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition placeholder-[#8e8d89]" placeholder="NAMA LENGKAP / FULL NAME">
                        @error('guestName') <span class="text-xs text-[var(--danger)] block mt-1 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">PEKERJAAN / PROFESSION</label>
                            <input type="text" wire:model="guestProfession" class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition placeholder-[#8e8d89]" placeholder="Pekerjaan">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">PERUSAHAAN / COMPANY</label>
                            <input type="text" wire:model="guestCompany" class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition placeholder-[#8e8d89]" placeholder="Nama Perusahaan">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">KEBANGSAAN / NATIONALITY</label>
                            <input type="text" wire:model="guestNationality" class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition placeholder-[#8e8d89]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">NO. KTP / PASSPORT <span class="text-[var(--danger)] ml-0.5">*</span></label>
                            <input type="text" wire:model="guestKtp" required class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition placeholder-[#8e8d89]" placeholder="Nomor Identitas">
                            @error('guestKtp') <span class="text-xs text-[var(--danger)] block mt-1 font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">TANGGAL LAHIR / BIRTH DATE</label>
                        <input type="date" wire:model="guestBirthDate" class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition placeholder-[#8e8d89]">
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="space-y-6">
                {{-- Section 3 - KONTAK / CONTACT DETAILS --}}
                <div class="bg-[var(--bg-card)] rounded border border-[var(--border-color)] p-5 shadow-sm space-y-4">
                    <h2 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wide border-b border-[var(--border-color)] pb-2">
                        III. KONTAK / CONTACT DETAILS
                    </h2>

                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">ALAMAT / ADDRESS</label>
                        <textarea wire:model="guestAddress" rows="3" class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition resize-none placeholder-[#8e8d89]" placeholder="Alamat Rumah / Kantor"></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">TELEPON / HP <span class="text-[var(--danger)] ml-0.5">*</span></label>
                        <input type="tel" wire:model="guestPhone" required class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition placeholder-[#8e8d89]" placeholder="Nomor Telepon Aktif">
                        @error('guestPhone') <span class="text-xs text-[var(--danger)] block mt-1 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">EMAIL</label>
                            <input type="email" wire:model="guestEmail" class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition placeholder-[#8e8d89]" placeholder="alamat@email.com">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">NO. MEMBER / MEMBER NO.</label>
                            <input type="text" wire:model="guestMemberNo" class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition placeholder-[#8e8d89]" placeholder="Nomor Anggota">
                        </div>
                    </div>
                </div>

                {{-- Section 4 - TANGGAL MENGINAP / STAY DATES --}}
                <div class="bg-[var(--bg-card)] rounded border border-[var(--border-color)] p-5 shadow-sm space-y-4">
                    <h2 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wide border-b border-[var(--border-color)] pb-2">
                        IV. TANGGAL MENGINAP / STAY DATES
                    </h2>

                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">ARRIVAL TIME</label>
                        <input type="time" wire:model="arrivalTime" class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition placeholder-[#8e8d89]">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">ARRIVAL DATE <span class="text-[var(--danger)] ml-0.5">*</span></label>
                            <input type="date" wire:model="arrivalDate" required class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition placeholder-[#8e8d89]">
                            @error('arrivalDate') <span class="text-xs text-[var(--danger)] block mt-1 font-medium">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">DEPARTURE DATE <span class="text-[var(--danger)] ml-0.5">*</span></label>
                            <input type="date" wire:model="departureDate" required class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition placeholder-[#8e8d89]">
                            @error('departureDate') <span class="text-xs text-[var(--danger)] block mt-1 font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 5 - KOTAK DEPOSIT / SAFETY DEPOSIT BOX --}}
                <div class="bg-[var(--bg-card)] rounded border border-[var(--border-color)] p-5 shadow-sm space-y-4">
                    <h2 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wide border-b border-[var(--border-color)] pb-2">
                        V. KOTAK DEPOSIT / SAFETY DEPOSIT BOX
                    </h2>

                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">NOMOR KOTAK / BOX NO.</label>
                        <input type="text" wire:model="boxNo" class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition placeholder-[#8e8d89]" placeholder="Nomor Safe Deposit Box">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">DIKELUARKAN OLEH / ISSUED BY</label>
                            <input type="text" wire:model="boxIssuedBy" readonly class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-muted)] bg-[var(--bg-secondary)] cursor-not-allowed outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wide mb-1">TANGGAL / DATE</label>
                            <input type="date" wire:model="boxDate" class="w-full border border-[var(--border-color)] rounded px-3 py-2 text-sm text-[var(--text-primary)] bg-[var(--bg-card)] focus:border-[#111111] outline-none transition placeholder-[#8e8d89]">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Form Footer --}}
        <div class="bg-[var(--bg-card)] rounded border border-[var(--border-color)] p-5 shadow-sm flex items-center justify-between gap-4 mt-6">
            <a href="{{ route('room-availability') }}" class="text-xs font-bold text-[var(--text-muted)] hover:text-[var(--text-primary)] transition">
                Batal / Cancel
            </a>
            <button type="submit" class="bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] text-[var(--bg-card)] font-semibold py-2.5 px-8 rounded text-xs transition cursor-pointer active:scale-[0.98]">
                Confirm Booking / Submit Pendaftaran
            </button>
        </div>
    </form>
</div>
