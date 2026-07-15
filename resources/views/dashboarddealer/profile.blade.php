<x-layout.dashboard title="My Profile Settings | Alaa Motors" header="Profile Settings">
    
    <div class="max-w-2xl bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
        
        <div>
            <h2 class="text-xl font-bold text-slate-900">Personal Information</h2>
            <p class="text-xs text-slate-500 mt-1">Configure and manage your dealer account profile details.</p>
        </div>

        <!-- Session Status Notifications -->
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PATCH')

            <!-- Name Input -->
            <x-forms.input name="name" 
                           label="Full Name" 
                           required 
                           value="{{ Auth::user()->name }}" />

            <!-- Username Input -->
            <x-forms.input name="username" 
                           label="Username" 
                           required 
                           value="{{ Auth::user()->username }}" />

            <!-- Email Input -->
            <x-forms.input name="email" 
                           label="Email Address" 
                           type="email" 
                           required 
                           value="{{ Auth::user()->email }}" />

            <!-- Timezone Selector -->
            <x-forms.select name="timezone" label="Preferred Timezone" required>
                <option value="UTC" {{ Auth::user()->timezone === 'UTC' ? 'selected' : '' }}>UTC (Coordinated Universal Time)</option>
                <option value="Asia/Gaza" {{ Auth::user()->timezone === 'Asia/Gaza' ? 'selected' : '' }}>Asia/Gaza</option>
                <option value="Asia/Jerusalem" {{ Auth::user()->timezone === 'Asia/Jerusalem' ? 'selected' : '' }}>Asia/Jerusalem</option>
                <option value="America/New_York" {{ Auth::user()->timezone === 'America/New_York' ? 'selected' : '' }}>Eastern Time (US & Canada)</option>
                <option value="Europe/London" {{ Auth::user()->timezone === 'Europe/London' ? 'selected' : '' }}>London / Greenwich Time</option>
            </x-forms.select>

            <!-- Country Code Input -->
            <x-forms.input name="country_code" 
                           label="Country Code (2 Letters)" 
                           placeholder="e.g. PS" 
                           maxlength="2" 
                           value="{{ Auth::user()->country_code }}" />

            <!-- Submit Button -->
            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-250 shadow-md">
                    Save Profile Changes
                </button>
            </div>
        </form>

    </div>
</x-layout.dashboard>
