<x-layout.dashboard title="Users Management | Alaa Motors" header="Portal Users Directory">
    
    <div class="space-y-8">
        
        <!-- Filters & Search block -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <form action="{{ route('admin.users') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                
                <!-- Search bar -->
                <div class="sm:col-span-2">
                    <x-forms.input name="search" 
                                   label="Search Directory" 
                                   placeholder="Search by name, email, or username..." 
                                   value="{{ request('search') }}" />
                </div>

                <!-- Role Filter -->
                <div>
                    <x-forms.select name="role" label="Account Role">
                        <option value="">All Roles</option>
                        <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="dealer" {{ request('role') === 'dealer' ? 'selected' : '' }}>Dealer</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                    </x-forms.select>
                </div>

                <!-- Status Filter -->
                <div>
                    <x-forms.select name="status" label="Account Status">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </x-forms.select>
                </div>

                <!-- Action Button Grid -->
                <div class="sm:col-span-4 flex justify-end gap-3 pt-2">
                    <a href="{{ route('admin.users') }}" class="px-5 py-3 rounded-xl text-xs font-bold uppercase tracking-wider border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition-colors">
                        Clear Filters
                    </a>
                    <button type="submit" class="px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-200 shadow-md">
                        Filter Directory
                    </button>
                </div>

            </form>
        </div>

        <!-- Success Notifications -->
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Directory Table -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            @if($users->isEmpty())
                <div class="py-16 text-center text-slate-400">
                    <svg class="h-12 w-12 mx-auto text-slate-350 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0 1 10.089 20.5a11.384 11.384 0 0 1-4.918-1.263v-.109m0-1.076c0-1.113.285-2.16.786-3.07M5.089 18.082a9.38 9.38 0 0 0-2.625.372 9.337 9.337 0 0 0-4.121-.952 4.125 4.125 0 0 0 7.533-2.493M10.5 2.25a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM19.5 5.25a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0ZM10.5 9.75a4.875 4.875 0 0 1 4.875 4.875v.375H5.625v-.375A4.875 4.875 0 0 1 10.5 9.75Z" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-700">No Users Found</p>
                    <p class="text-xs text-slate-500 mt-1">There are no accounts matching your search or filter configuration.</p>
                </div>
            @else
                <x-tables.main :headers="['User Identity', 'Role', 'Status', 'Change Status', 'Action']">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            
                            <!-- Identity -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-luxury-charcoal text-white font-bold text-xs uppercase select-none">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800 select-all">{{ $user->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-semibold uppercase mt-0.5 select-all">@ {{ $user->username }} &bull; {{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Role -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold uppercase tracking-wider text-slate-650">
                                {{ $user->role }}
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-badges.status :status="$user->status" />
                            </td>

                            <!-- Status Switcher -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.users.status', $user->id) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" 
                                            onchange="this.form.submit()" 
                                            class="rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 focus:border-luxury-gold focus:ring-luxury-gold/25 outline-none cursor-pointer">
                                        <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                </form>
                            </td>

                            <!-- Action item -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this user account? All associated records will be lost.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </x-tables.main>

                <!-- Pagination -->
                <div class="pt-6">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layout.dashboard>
