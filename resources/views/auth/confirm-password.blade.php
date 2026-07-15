<x-layout.app title="Confirm Security Password | Alaa Motors" description="Please confirm your account password before executing this operation.">
    
    <div class="min-h-[60vh] flex items-center justify-center max-w-7xl mx-auto px-6 py-12">
        <div class="w-full max-w-md bg-white rounded-3xl p-8 border border-slate-200/60 shadow-xl space-y-8">
            
            <div class="text-center">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-luxury-gold mb-3">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </span>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Confirm Password</h1>
                <p class="text-xs text-slate-500 mt-2 max-w-xs mx-auto leading-relaxed">This is a secure area of the portal. Please verify your identity by entering your account password below.</p>
            </div>

            <!-- Form Validation Indicator -->
            @if ($errors->any())
                <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-rose-800 text-xs font-semibold leading-relaxed">
                    The password you entered is incorrect.
                </div>
            @endif

            <form action="{{ route('password.confirm.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Password Input -->
                <div>
                    <x-forms.input name="password" 
                                   label="Account Password" 
                                   type="password" 
                                   placeholder="••••••••" 
                                   required />
                </div>

                <!-- Submit CTA -->
                <div>
                    <button type="submit" class="w-full flex items-center justify-center h-12 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-300 shadow-md">
                        Confirm Credential
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-layout.app>
