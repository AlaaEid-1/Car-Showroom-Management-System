<x-layout.app title="Sign In | Alaa Motors" description="Access your dealer showroom console or buyer inquiries dashboard.">
    
    <div class="min-h-[75vh] flex items-stretch max-w-7xl mx-auto px-6 py-12">
        <div class="w-full bg-white rounded-3xl overflow-hidden border border-slate-200/60 shadow-xl flex flex-col md:flex-row items-stretch">
            
            <!-- Left Side: Brand Imagery -->
            <div class="hidden md:flex md:w-1/2 bg-luxury-charcoal relative flex-col justify-between p-12 text-white overflow-hidden">
                <div class="absolute inset-0 z-0">
                    @if(file_exists(public_path('storage/cars/hero.jpg')))
                        <img src="{{ asset('storage/cars/hero.jpg') }}" alt="Luxury automotive" class="w-full h-full object-cover opacity-20 object-center">
                    @else
                        <div class="w-full h-full bg-gradient-to-tr from-slate-950 via-slate-900 to-luxury-charcoal"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-luxury-charcoal via-transparent to-black/35"></div>
                </div>

                <div class="relative z-10">
                    <span class="text-xs font-bold tracking-widest text-luxury-gold uppercase block mb-1">
                        Security Portal
                    </span>
                    <h2 class="text-2xl font-extrabold tracking-tight uppercase">
                        ALAA<span class="text-luxury-gold">MOTORS</span>
                    </h2>
                </div>

                <div class="relative z-10 space-y-4">
                    <blockquote class="text-base italic leading-relaxed text-slate-350">
                        "The design was not just about elegance, but the raw connection between machine and driver."
                    </blockquote>
                    <p class="text-xs font-semibold text-luxury-gold uppercase tracking-wider">
                        Porsche Legacy Curation
                    </p>
                </div>
            </div>

            <!-- Right Side: Forms block -->
            <div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-center bg-white">
                <div class="max-w-md w-full mx-auto space-y-8">
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Sign In to Portal</h1>
                        <p class="text-xs text-slate-500 mt-1.5 font-medium">Access your inquiries, test drives, and vehicle management console.</p>
                    </div>

                    <!-- Session Status or Password Updates -->
                    @if (session('status'))
                        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-xs font-semibold">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Email Input -->
                        <div>
                            <x-forms.input name="email" 
                                           label="Email Address" 
                                           type="email" 
                                           placeholder="you@example.com" 
                                           required 
                                           value="{{ old('email') }}" />
                        </div>

                        <!-- Password Input -->
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <x-forms.label value="Password" for="password" />
                                <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-luxury-gold hover:text-luxury-gold-hover uppercase tracking-wider">
                                    Forgot password?
                                </a>
                            </div>
                            <x-forms.input name="password" 
                                           type="password" 
                                           placeholder="••••••••" 
                                           required />
                        </div>

                        <!-- Remember Me checkbox -->
                        <div class="flex items-center">
                            <input id="remember" 
                                   name="remember" 
                                   type="checkbox" 
                                   class="h-4.5 w-4.5 rounded border-slate-300 text-luxury-gold focus:ring-luxury-gold/25 cursor-pointer">
                            <label for="remember" class="ml-2.5 text-xs font-semibold text-slate-500 cursor-pointer select-none">
                                Keep me signed in
                            </label>
                        </div>

                        <!-- Submit CTA -->
                        <div>
                            <button type="submit" class="w-full flex items-center justify-center h-12 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-300 shadow-md">
                                Sign In
                            </button>
                        </div>
                    </form>

                    <div class="pt-6 border-t border-slate-100 text-center text-xs text-slate-500 font-medium">
                        Need a client account? 
                        <a href="{{ route('register') }}" class="text-luxury-gold font-bold hover:text-luxury-gold-hover">
                            Register Showroom
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layout.app>
