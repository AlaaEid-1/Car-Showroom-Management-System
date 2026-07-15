<x-layout.app title="Recover Password | Alaa Motors" description="Send a secure password recovery link to your registered email address.">
    
    <div class="min-h-[60vh] flex items-center justify-center max-w-7xl mx-auto px-6 py-12">
        <div class="w-full max-w-md bg-white rounded-3xl p-8 border border-slate-200/60 shadow-xl space-y-8">
            
            <div class="text-center">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-luxury-gold mb-3">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                    </svg>
                </span>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Recover Password</h1>
                <p class="text-xs text-slate-500 mt-2 max-w-xs mx-auto leading-relaxed">Enter your registered email address and we'll send you a secure link to reset your password.</p>
            </div>

            <!-- Session Status Alert -->
            @if (session('status'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-xs font-semibold leading-relaxed">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email Address Input -->
                <div>
                    <x-forms.input name="email" 
                                   label="Account Email Address" 
                                   type="email" 
                                   placeholder="john@example.com" 
                                   required 
                                   value="{{ old('email') }}" />
                </div>

                <!-- Submit CTA -->
                <div>
                    <button type="submit" class="w-full flex items-center justify-center h-12 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-300 shadow-md">
                        Send Recovery Link
                    </button>
                </div>
            </form>

            <div class="pt-4 border-t border-slate-100 text-center">
                <a href="{{ route('login') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors uppercase tracking-wider">
                    Return to Login
                </a>
            </div>

        </div>
    </div>
</x-layout.app>
