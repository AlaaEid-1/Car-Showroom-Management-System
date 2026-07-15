<x-layout.app title="Register Showroom | Alaa Motors" description="Create your dealer account or buyer profile to start list and inquiring.">
    
    <div class="min-h-[75vh] flex items-stretch max-w-7xl mx-auto px-6 py-12">
        <div class="w-full bg-white rounded-3xl overflow-hidden border border-slate-200/60 shadow-xl flex flex-col md:flex-row items-stretch">
            
            <!-- Left Side: Brand Imagery -->
            <div class="hidden md:flex md:w-1/2 bg-luxury-charcoal relative flex-col justify-between p-12 text-white overflow-hidden">
                <div class="absolute inset-0 z-0">
                    @if(file_exists(public_path('storage/cars/hero.jpg')))
                        <img src="{{ asset('storage/cars/hero.jpg') }}" alt="Luxury automotive" class="w-full h-full object-cover opacity-20 object-center scale-105">
                    @else
                        <div class="w-full h-full bg-gradient-to-tr from-slate-950 via-slate-900 to-luxury-charcoal"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-luxury-charcoal via-transparent to-black/35"></div>
                </div>

                <div class="relative z-10">
                    <span class="text-xs font-bold tracking-widest text-luxury-gold uppercase block mb-1">
                        Access Curation
                    </span>
                    <h2 class="text-2xl font-extrabold tracking-tight uppercase">
                        ALAA<span class="text-luxury-gold">MOTORS</span>
                    </h2>
                </div>

                <div class="relative z-10 space-y-4">
                    <blockquote class="text-base italic leading-relaxed text-slate-350">
                        "Luxury is not the addition of features, but the complete reduction of distraction."
                    </blockquote>
                    <p class="text-xs font-semibold text-luxury-gold uppercase tracking-wider">
                        Tesla Engineering Ethos
                    </p>
                </div>
            </div>

            <!-- Right Side: Forms block -->
            <div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-center bg-white">
                <div class="max-w-md w-full mx-auto space-y-8">
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Create Account</h1>
                        <p class="text-xs text-slate-500 mt-1.5 font-medium">Join our network of luxury car marketplaces and list your showroom fleet.</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Name Input -->
                        <div>
                            <x-forms.input name="name" 
                                           label="Full Display Name" 
                                           placeholder="e.g. John Doe" 
                                           required 
                                           value="{{ old('name') }}" />
                        </div>

                        <!-- Username Input -->
                        <div>
                            <x-forms.input name="username" 
                                           label="System Username" 
                                           placeholder="e.g. johndoe" 
                                           required 
                                           value="{{ old('username') }}" />
                        </div>

                        <!-- Email Input -->
                        <div>
                            <x-forms.input name="email" 
                                           label="Email Address" 
                                           type="email" 
                                           placeholder="john@example.com" 
                                           required 
                                           value="{{ old('email') }}" />
                        </div>

                        <!-- Password Input -->
                        <div>
                            <x-forms.input name="password" 
                                           label="Security Password" 
                                           type="password" 
                                           placeholder="••••••••" 
                                           required />
                        </div>

                        <!-- Password Confirmation Input -->
                        <div>
                            <x-forms.input name="password_confirmation" 
                                           label="Confirm Password" 
                                           type="password" 
                                           placeholder="••••••••" 
                                           required />
                        </div>

                        <!-- Submit CTA -->
                        <div class="pt-2">
                            <button type="submit" class="w-full flex items-center justify-center h-12 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-300 shadow-md">
                                Register Account
                            </button>
                        </div>
                    </form>

                    <div class="pt-6 border-t border-slate-100 text-center text-xs text-slate-500 font-medium">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-luxury-gold font-bold hover:text-luxury-gold-hover">
                            Sign In
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layout.app>
