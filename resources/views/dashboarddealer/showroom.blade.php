<x-layout.dashboard title="Showroom Profile Management | Alaa Motors" header="Showroom Profile Management">
    
    <div class="max-w-2xl bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
        
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                {{ $showroom ? 'Edit Showroom Profile' : 'Initialize Showroom' }}
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Configure your dealership profile, location identifiers, and logo branding.
            </p>
        </div>

        <!-- Session Status Notifications -->
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-rose-800 text-xs font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ $showroom ? route('dashboarddealer.showroom.update') : route('dashboarddealer.showroom.store') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="space-y-5">
            @csrf
            @if($showroom)
                @method('PATCH')
            @endif

            <!-- Showroom Name -->
            <x-forms.input name="name" 
                           label="Showroom Name" 
                           required 
                           value="{{ $showroom->name ?? '' }}" />

            <!-- Description -->
            <x-forms.textarea name="description" 
                              label="Description" 
                              placeholder="Describe your showroom, history, and specialization..." 
                              rows="4" 
                              value="{{ $showroom->description ?? '' }}" />

            <!-- Location -->
            <x-forms.input name="location" 
                           label="Location / Address" 
                           placeholder="e.g. Gaza, Palestine" 
                           value="{{ $showroom->location ?? '' }}" />

            <!-- Phone Contact -->
            <x-forms.input name="phone" 
                           label="Contact Phone Number" 
                           placeholder="e.g. +970 599 000 000" 
                           value="{{ $showroom->phone ?? '' }}" />

            <!-- Logo Upload -->
            <div class="space-y-2">
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-500">Showroom Logo Image</label>
                
                @if($showroom && $showroom->logo)
                    <div class="flex items-center gap-4 mb-3">
                        <div class="h-16 w-16 rounded-full overflow-hidden border border-slate-200 bg-slate-50">
                            <img src="{{ asset('storage/' . $showroom->logo) }}" alt="Current Showroom Logo" class="h-full w-full object-cover">
                        </div>
                        <span class="text-xs text-slate-400">Current uploaded logo. Uploading a new one will replace it.</span>
                    </div>
                @endif

                <div class="flex items-center justify-center w-full">
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 border-dashed rounded-xl cursor-pointer bg-slate-50/50 hover:bg-slate-50 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-2.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                            </svg>
                            <p class="text-xs text-slate-500 font-semibold">Click to upload logo image</p>
                            <p class="text-[10px] text-slate-400 mt-1">PNG, JPG or WEBP (Max 2MB)</p>
                        </div>
                        <input type="file" name="logo" class="hidden" accept="image/*" />
                    </label>
                </div>
                <x-forms.error field="logo" />
            </div>

            <!-- Submit Button -->
            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-250 shadow-md">
                    {{ $showroom ? 'Save Showroom Changes' : 'Initialize Showroom' }}
                </button>
            </div>
        </form>

    </div>
</x-layout.dashboard>
