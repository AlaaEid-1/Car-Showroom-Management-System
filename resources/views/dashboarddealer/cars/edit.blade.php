<x-layout.dashboard title="Edit Listing: {{ $car->title }} | Alaa Motors" header="Edit Listing Details">

    <div class="max-w-3xl mx-auto bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
        <div class="mb-8 border-b border-slate-100 pb-6 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Vehicle Specification Details</h2>
                <p class="text-xs text-slate-500 mt-1">Update specifications and replace vehicle photography below.</p>
            </div>
            <a href="{{ route('dashboarddealer.cars.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors uppercase tracking-wider">
                Cancel
            </a>
        </div>

        <form action="{{ route('dashboarddealer.cars.update', $car->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-8"
              id="edit-car-form">
            @csrf
            @method('PUT')

            <!-- Form Validation Indicator -->
            @if ($errors->any())
                <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-rose-800 text-xs font-semibold">
                    Please correct the errors in the fields highlighted below.
                </div>
            @endif

            @if(session('success'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-xs font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Main Specs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <x-forms.input name="title" label="Vehicle Listing Title" placeholder="e.g. BMW M3 Competition 2021" required :value="$car->title" />
                </div>

                <!-- Brand -->
                <div>
                    <x-forms.input name="brand" label="Manufacturer / Brand" placeholder="e.g. BMW" required :value="$car->brand" />
                </div>

                <!-- Model -->
                <div>
                    <x-forms.input name="model" label="Model Designation" placeholder="e.g. M3" required :value="$car->model" />
                </div>

                <!-- Year -->
                <div>
                    <x-forms.input name="year" label="Production Year" placeholder="e.g. 2021" type="number" required :value="$car->year" />
                </div>

                <!-- Price -->
                <div>
                    <x-forms.input name="price" label="Asking Price ($)" placeholder="e.g. 55000" type="number" required :value="$car->price" />
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <x-forms.select name="status" label="Listing Status" required>
                        <option value="draft" {{ old('status', $car->status) === 'draft' ? 'selected' : '' }}>Draft (Private Preview)</option>
                        <option value="published" {{ old('status', $car->status) === 'published' ? 'selected' : '' }}>Published (Active on Marketplace)</option>
                        <option value="sold" {{ old('status', $car->status) === 'sold' ? 'selected' : '' }}>Sold (Out of Stock)</option>
                    </x-forms.select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <x-forms.textarea name="description" label="Dealer Narrative & Specifications" placeholder="Describe the engine specs, package tiers, leather contour color, and history of the vehicle..." :value="$car->description" rows="5" />
            </div>

            <!-- AI Listing Assistant Card -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 space-y-4" x-data="{
                loading: false,
                result: null,
                errorMsg: null,

                generateAI() {
                    this.loading = true;
                    this.result = null;
                    this.errorMsg = null;

                    const title = document.getElementById('title').value;
                    const brand = document.getElementById('brand').value;
                    const model = document.getElementById('model').value;
                    const year = document.getElementById('year').value;
                    const price = document.getElementById('price').value;
                    const description = document.getElementById('description').value;

                    fetch('{{ route('dashboarddealer.ai.improve', $car->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            title, brand, model, year, price, description
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('AI generation request failed.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        this.result = data;
                        this.loading = false;
                    })
                    .catch(err => {
                        this.errorMsg = err.message;
                        this.loading = false;
                    });
                },

                applyAI() {
                    if (this.result) {
                        document.getElementById('title').value = this.result.title;
                        document.getElementById('description').value = this.result.description;
                        this.result = null;
                    }
                }
            }">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-luxury-gold animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 21L7.188 15.904 2 15L7.188 14.096 9 9L9.813 14.096 15 15L9.813 15.904ZM18.813 5.904 18 11L16.188 5.904 11 5L16.188 4.096 18 1L18.813 4.096 24 5L18.813 5.904ZM21.313 18.904 20.5 24L18.688 18.904 13.5 18L18.688 17.096 20.5 12L21.313 17.096 26.5 18L21.313 18.904Z" />
                        </svg>
                        <h3 class="text-sm font-bold text-slate-800">AI Listing Assistant</h3>
                    </div>
                    <button type="button" @click="generateAI" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-200 shadow-sm" :disabled="loading">
                        <span x-show="!loading">Improve Listing with AI</span>
                        <span x-show="loading" class="flex items-center gap-1">
                            <svg class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Analyzing...
                        </span>
                    </button>
                </div>

                <p class="text-xs text-slate-500">
                    Click the button above to improve the listing details (title, description, and highlights) based on your current form inputs.
                </p>

                <!-- Error Message -->
                <div x-show="errorMsg" class="p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl font-medium" x-text="errorMsg" style="display: none;"></div>

                <!-- AI Response Preview Panel -->
                <div x-show="result" class="border border-luxury-gold/30 bg-white rounded-2xl p-5 space-y-4 shadow-sm" style="display: none;">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <span class="text-xs font-extrabold text-luxury-gold uppercase tracking-wider">Generated AI Suggestions</span>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="applyAI" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-colors">
                                Apply to Form
                            </button>
                            <button type="button" @click="result = null" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-650 rounded-lg text-xs font-bold transition-colors">
                                Cancel
                            </button>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <!-- Title Preview & Edit -->
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase">Suggested Title</label>
                            <input type="text" x-model="result.title" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-1 focus:ring-luxury-gold focus:border-luxury-gold outline-none">
                        </div>

                        <!-- Description Preview & Edit -->
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-slate-450 uppercase">Suggested Description</label>
                            <textarea x-model="result.description" rows="5" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-650 focus:ring-1 focus:ring-luxury-gold focus:border-luxury-gold outline-none"></textarea>
                        </div>

                        <!-- Highlights Preview -->
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase">Key Highlights</label>
                            <ul class="list-disc list-inside space-y-1">
                                <template x-for="(hl, i) in result.highlights" :key="i">
                                    <li class="text-[11px] text-slate-500 font-semibold" x-text="hl"></li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Photos Gallery -->
            @if ($car->images->isNotEmpty())
                <div class="space-y-3">
                    <x-forms.label value="Current Photos" />
                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-4">
                        @foreach ($car->images as $img)
                            <div class="aspect-[16/10] rounded-xl overflow-hidden bg-slate-50 border border-slate-200 shadow-sm relative group">
                                <img src="{{ asset('storage/' . $img->path) }}" alt="Car thumbnail" class="w-full h-full object-cover">

                                @if($img->is_main)
                                    <span class="absolute top-1.5 left-1.5 bg-luxury-gold text-white text-[8px] font-bold tracking-widest px-2 py-0.5 rounded-full uppercase">
                                        Cover
                                    </span>
                                @endif

                                <!-- Actions Toolbar on Hover -->
                                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                    @if(!$img->is_main)
                                        <!-- Set Main Form Trigger -->
                                        <button type="submit" form="set-main-{{ $img->id }}" title="Set as Cover" class="p-1.5 rounded-lg bg-white/95 text-luxury-gold hover:bg-white transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442a.562.562 0 0 1 .31.966l-4.132 3.83a.562.562 0 0 0-.166.509l1.156 5.402a.562.562 0 0 1-.822.596l-4.897-2.91a.563.563 0 0 0-.523 0l-4.897 2.91a.562.562 0 0 1-.822-.596l1.156-5.402a.562.562 0 0 0-.166-.509l-4.132-3.83a.562.562 0 0 1 .31-.966l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                            </svg>
                                        </button>
                                    @endif

                                    <!-- Delete Image Form Trigger -->
                                    <button type="submit" form="delete-img-{{ $img->id }}" title="Delete Image" class="p-1.5 rounded-lg bg-white/95 text-red-650 hover:bg-white transition-colors" onclick="return confirm('Are you sure you want to delete this image?')">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Note: Hover over an image to set as cover or delete it. Uploading new photos below will append them to the gallery.</p>
                </div>
            @endif

            <!-- Photo Upload Dropzone (Alpine.js UI component) -->
            <div class="space-y-2" x-data="{
                filesList: [],
                handleFiles(event) {
                    const files = Array.from(event.target.files);
                    this.filesList = files.map(file => ({
                        name: file.name,
                        size: (file.size / 1024).toFixed(1) + ' KB'
                    }));
                }
            }">
                <x-forms.label value="Upload Replacement Photos" />

                <div class="border-2 border-dashed border-slate-200 rounded-2xl py-10 px-6 flex flex-col items-center justify-center hover:border-luxury-gold transition-colors relative bg-slate-50/50">
                    <svg class="h-10 w-10 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>

                    <span class="text-xs font-semibold text-slate-700">Drag files here or click to browse</span>
                    <span class="text-[10px] text-slate-400 mt-1 uppercase font-medium">JPEG, JPG, PNG, WEBP (Max 2MB per image)</span>

                    <!-- Real Hidden Input -->
                    <input type="file"
                           name="images[]"
                           id="images"
                           multiple
                           accept="image/*"
                           @change="handleFiles"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                </div>

                <!-- Selected Files List -->
                <div x-show="filesList.length > 0" class="bg-slate-50 border border-slate-200/60 rounded-xl p-4 space-y-2 mt-4" style="display: none;">
                    <span class="text-[10px] font-bold text-slate-400 uppercase block tracking-wider">Replacement Queue:</span>
                    <ul class="divide-y divide-slate-100 text-xs text-slate-650">
                        <template x-for="(file, index) in filesList" :key="index">
                            <li class="py-2 flex items-center justify-between">
                                <span class="font-medium truncate pr-4" x-text="file.name"></span>
                                <span class="text-slate-450 shrink-0 font-semibold uppercase text-[10px]" x-text="file.size"></span>
                            </li>
                        </template>
                    </ul>
                </div>

                <x-forms.error field="images" />
                <x-forms.error field="images.*" />
            </div>

            <!-- Submit buttons -->
            <div class="pt-6 border-t border-slate-100 flex justify-end gap-4">
                <a href="{{ route('dashboarddealer.cars.index') }}" class="inline-flex justify-center items-center rounded-xl px-5 py-3 text-xs font-bold uppercase tracking-wider border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition-colors">
                    Discard
                </a>
                <button type="submit" class="inline-flex justify-center items-center rounded-xl px-6 py-3 text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-300 shadow-md">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Hidden Action Forms outside the main form -->
    @if ($car->images->isNotEmpty())
        @foreach ($car->images as $img)
            @if(!$img->is_main)
                <form id="set-main-{{ $img->id }}" action="{{ route('dashboarddealer.cars.set-main-image', [$car->id, $img->id]) }}" method="POST" class="hidden">
                    @csrf
                </form>
            @endif
            <form id="delete-img-{{ $img->id }}" action="{{ route('dashboarddealer.cars.delete-image', [$car->id, $img->id]) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    @endif
</x-layout.dashboard>
