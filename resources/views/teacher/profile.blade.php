@extends('layouts.dashboard')
@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('page-subtitle', 'Update your photo, bio, documents and contact info')

@section('sidebar-nav')
    <a href="{{ route('teacher.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-home"></i></span> Dashboard</a>
    <a href="{{ route('teacher.profile') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-user-circle"></i></span> My Profile</a>
    <a href="{{ route('teacher.sessions') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-check"></i></span> Sessions</a>
    <a href="{{ route('teacher.sessions.history') }}" class="sidebar-link"><span class="icon"><i class="fas fa-history"></i></span> History</a>
    <a href="{{ route('teacher.availability') }}" class="sidebar-link"><span class="icon"><i class="fas fa-clock"></i></span> Availability</a>
    <a href="{{ route('teacher.earnings') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
@endsection

@push('styles')
<style>
.profile-avatar-wrap {
    position: relative; width: 120px; height: 120px; margin: 0 auto 8px;
}
.profile-avatar-wrap img {
    width: 120px; height: 120px; border-radius: 50%; object-fit: cover;
    border: 3px solid rgba(201,164,39,.4);
}
.avatar-upload-btn {
    position: absolute; bottom: 0; right: 0;
    width: 34px; height: 34px; border-radius: 50%;
    background: #C9A427; border: 2px solid #0f1117;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: background .2s;
}
.avatar-upload-btn:hover { background: #a88020; }
.avatar-upload-btn i { color: #000; font-size: .8rem; }
.country-select {
    background: #1e2433; border: 1px solid rgba(255,255,255,.1);
    color: #e5e7eb; border-radius: 8px 0 0 8px; padding: 10px 10px;
    font-size: .85rem; outline: none; border-right: none;
    min-width: 90px;
}
.country-select:focus { border-color: rgba(201,164,39,.5); }
.phone-input-group { display: flex; }
.phone-input-group .input-dark {
    border-radius: 0 8px 8px 0 !important; flex: 1;
}
.doc-card {
    background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
    border-radius: 10px; padding: 12px 14px;
    display: flex; align-items: center; gap: 12px;
}
.doc-icon { width: 36px; height: 36px; border-radius: 8px; background: rgba(201,164,39,.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.doc-icon i { color: #C9A427; font-size: .9rem; }
.doc-info { flex: 1; min-width: 0; }
.doc-name { color: #e5e7eb; font-size: .82rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.doc-date { color: #6b7280; font-size: .72rem; }
.section-divider { border: none; border-top: 1px solid rgba(255,255,255,.06); margin: 28px 0; }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="mb-5 p-4 bg-green-900/30 border border-green-500/30 text-green-400 rounded-xl text-sm font-medium">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-5 p-4 bg-red-900/30 border border-red-500/30 text-red-400 rounded-xl text-sm">
    <ul class="list-disc pl-4 space-y-1">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</div>
@endif

<form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Photo + Basic Info --}}
        <div class="lg:col-span-1 space-y-5">

            {{-- Profile Photo Card --}}
            <div class="card p-6 text-center">
                <div class="profile-avatar-wrap">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" id="avatar-preview">
                    <label for="avatar-file" class="avatar-upload-btn" title="Change photo">
                        <i class="fas fa-camera"></i>
                    </label>
                    <input type="file" id="avatar-file" name="avatar" accept="image/jpg,image/jpeg,image/png,image/webp" class="hidden" onchange="previewAvatar(this)">
                </div>
                <div class="text-white font-semibold mt-2">{{ $user->name }}</div>
                <div class="text-gray-500 text-xs mt-1">{{ $user->email }}</div>
                <div class="mt-2">
                    <span class="badge badge-{{ $teacher->status === 'approved' ? 'approved' : ($teacher->status === 'pending' ? 'pending' : 'cancelled') }}">
                        {{ ucfirst($teacher->status) }}
                    </span>
                </div>
                <p class="text-gray-600 text-xs mt-3">JPG, PNG or WebP · Max 2MB</p>
            </div>

            {{-- Stats Card --}}
            <div class="card p-5 space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Rating</span>
                    <span class="text-white font-semibold">{{ number_format($teacher->rating, 1) }} ⭐</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Sessions</span>
                    <span class="text-white font-semibold">{{ $teacher->total_sessions }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Reviews</span>
                    <span class="text-white font-semibold">{{ $teacher->total_reviews }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Hourly Rate</span>
                    <span class="text-gold-DEFAULT font-bold">${{ number_format($teacher->hourly_rate, 2) }}</span>
                </div>
            </div>

        </div>

        {{-- RIGHT: Profile Form --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Personal Info --}}
            <div class="card p-6">
                <h3 class="text-white font-semibold mb-5 flex items-center gap-2">
                    <i class="fas fa-user text-gold-DEFAULT"></i> Personal Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-400 text-xs mb-1 font-medium">Full Name <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            required class="input-dark w-full" placeholder="Your full name">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-xs mb-1 font-medium">Email</label>
                        <input type="email" value="{{ $user->email }}" disabled
                            class="input-dark w-full opacity-50 cursor-not-allowed">
                    </div>
                </div>

                {{-- Phone with country code --}}
                <div class="mt-4">
                    <label class="block text-gray-400 text-xs mb-1 font-medium">
                        <i class="fas fa-phone mr-1 text-gold-DEFAULT"></i> Phone Number
                    </label>
                    @php
                        $savedCountry = $user->phone_country_code ?? '+92';
                        $savedPhone   = $user->phone ?? '';
                        // Strip country code from phone if already prepended
                        if ($savedPhone && str_starts_with($savedPhone, $savedCountry)) {
                            $savedPhone = substr($savedPhone, strlen($savedCountry));
                        }
                    @endphp
                    <div class="phone-input-group">
                        <select name="phone_country" class="country-select">
                            @foreach([
                                ['+92','🇵🇰 +92 Pakistan'],
                                ['+1', '🇺🇸 +1  USA/Canada'],
                                ['+44','🇬🇧 +44 UK'],
                                ['+966','🇸🇦 +966 Saudi Arabia'],
                                ['+971','🇦🇪 +971 UAE'],
                                ['+61','🇦🇺 +61 Australia'],
                                ['+49','🇩🇪 +49 Germany'],
                                ['+33','🇫🇷 +33 France'],
                                ['+39','🇮🇹 +39 Italy'],
                                ['+34','🇪🇸 +34 Spain'],
                                ['+31','🇳🇱 +31 Netherlands'],
                                ['+46','🇸🇪 +46 Sweden'],
                                ['+47','🇳🇴 +47 Norway'],
                                ['+45','🇩🇰 +45 Denmark'],
                                ['+90','🇹🇷 +90 Turkey'],
                                ['+20','🇪🇬 +20 Egypt'],
                                ['+880','🇧🇩 +880 Bangladesh'],
                                ['+91','🇮🇳 +91 India'],
                                ['+60','🇲🇾 +60 Malaysia'],
                                ['+62','🇮🇩 +62 Indonesia'],
                                ['+65','🇸🇬 +65 Singapore'],
                                ['+27','🇿🇦 +27 South Africa'],
                                ['+234','🇳🇬 +234 Nigeria'],
                                ['+212','🇲🇦 +212 Morocco'],
                            ] as [$code, $label])
                            <option value="{{ $code }}" {{ $savedCountry === $code ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <input type="tel" name="phone" value="{{ old('phone', $savedPhone) }}"
                            class="input-dark" placeholder="3xx 1234567" style="border-radius:0 8px 8px 0;flex:1">
                    </div>
                    <p class="text-gray-600 text-xs mt-1">Enter your number without country code</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-gray-400 text-xs mb-1 font-medium">Gender</label>
                        <select name="gender" class="input-dark w-full">
                            <option value="">Select gender</option>
                            <option value="male"   {{ old('gender', $teacher->gender) === 'male'   ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $teacher->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-400 text-xs mb-1 font-medium">Nationality</label>
                        <input type="text" name="nationality" value="{{ old('nationality', $teacher->nationality) }}"
                            class="input-dark w-full" placeholder="e.g. Pakistani">
                    </div>
                </div>
            </div>

            {{-- Professional Info --}}
            <div class="card p-6">
                <h3 class="text-white font-semibold mb-5 flex items-center gap-2">
                    <i class="fas fa-chalkboard-teacher text-gold-DEFAULT"></i> Professional Details
                </h3>

                <div>
                    <label class="block text-gray-400 text-xs mb-1 font-medium">
                        Bio / Description <span class="text-gray-600">(visible on your public profile)</span>
                    </label>
                    <textarea name="bio" rows="5" class="input-dark w-full" placeholder="Tell students about yourself, your teaching style, experience and qualifications...">{{ old('bio', $teacher->bio) }}</textarea>
                    <p class="text-gray-600 text-xs mt-1">Max 3000 characters · <span id="bio-count">{{ strlen($teacher->bio ?? '') }}</span> used</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-gray-400 text-xs mb-1 font-medium">Specialization</label>
                        <input type="text" name="specialization" value="{{ old('specialization', $teacher->specialization) }}"
                            class="input-dark w-full" placeholder="e.g. Hifz, Tajweed, Tafseer">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-xs mb-1 font-medium">Experience (years)</label>
                        <input type="number" name="experience_years" value="{{ old('experience_years', $teacher->experience_years) }}"
                            min="0" max="60" class="input-dark w-full" placeholder="5">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-xs mb-1 font-medium">Education</label>
                        <input type="text" name="education" value="{{ old('education', $teacher->education) }}"
                            class="input-dark w-full" placeholder="e.g. Dars-e-Nizami, BA Islamic Studies">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-xs mb-1 font-medium">Certification / Ijazah</label>
                        <input type="text" name="certification" value="{{ old('certification', $teacher->certification) }}"
                            class="input-dark w-full" placeholder="e.g. Ijazah in Quran recitation">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-xs mb-1 font-medium">Teaching Language(s)</label>
                        <input type="text" name="language" value="{{ old('language', $teacher->language) }}"
                            class="input-dark w-full" placeholder="e.g. Urdu, Arabic, English">
                    </div>
                </div>
            </div>

            {{-- Documents --}}
            <div class="card p-6">
                <h3 class="text-white font-semibold mb-2 flex items-center gap-2">
                    <i class="fas fa-file-certificate text-gold-DEFAULT"></i> Documents & Certificates
                </h3>
                <p class="text-gray-500 text-xs mb-5">Upload your Ijazah, degree, certification, or ID. PDF, JPG or PNG · Max 5MB each.</p>

                {{-- Existing Documents --}}
                @php $docs = json_decode($teacher->documents ?? '[]', true) ?: []; @endphp
                @if(count($docs))
                <div class="space-y-2 mb-4" id="existing-docs">
                    @foreach($docs as $doc)
                    <div class="doc-card">
                        <div class="doc-icon">
                            <i class="fas {{ str_contains($doc['type'] ?? '', 'pdf') ? 'fa-file-pdf' : 'fa-file-image' }}"></i>
                        </div>
                        <div class="doc-info">
                            <div class="doc-name">{{ $doc['name'] }}</div>
                            <div class="doc-date">Uploaded {{ $doc['uploaded_at'] ?? '' }}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ asset('storage/'.$doc['path']) }}" target="_blank"
                                class="text-xs text-blue-400 hover:underline">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                            <label class="flex items-center gap-1 cursor-pointer text-xs text-red-400 hover:text-red-300">
                                <input type="checkbox" name="delete_docs[]" value="{{ $doc['path'] }}" class="w-3 h-3">
                                Delete
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Upload new docs --}}
                <div id="doc-drop-zone" style="border:2px dashed rgba(201,164,39,.3);border-radius:12px;padding:24px;text-align:center;cursor:pointer;transition:all .2s"
                    onclick="document.getElementById('doc-files').click()"
                    ondragover="event.preventDefault();this.style.borderColor='#C9A427'"
                    ondragleave="this.style.borderColor='rgba(201,164,39,.3)'"
                    ondrop="handleDocDrop(event)">
                    <i class="fas fa-cloud-upload-alt text-3xl text-gold-DEFAULT/40 mb-2 block"></i>
                    <div class="text-gray-400 text-sm font-medium">Click or drag files here</div>
                    <div class="text-gray-600 text-xs mt-1">PDF, JPG, PNG — max 5MB each — multiple allowed</div>
                    <input type="file" id="doc-files" name="documents[]" multiple
                        accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="showDocPreviews(this)">
                </div>

                {{-- New doc previews --}}
                <div id="doc-previews" class="mt-3 space-y-2"></div>
            </div>

            {{-- Save button --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('teacher.dashboard') }}" class="btn-outline px-6 py-3 rounded-xl text-sm">Cancel</a>
                <button type="submit" class="btn-gold px-8 py-3 rounded-xl text-sm font-semibold">
                    <i class="fas fa-save mr-2"></i> Save Profile
                </button>
            </div>

        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
// Avatar preview
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatar-preview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}

// Bio character count
const bioTA = document.querySelector('textarea[name="bio"]');
const bioCount = document.getElementById('bio-count');
if (bioTA && bioCount) {
    bioTA.addEventListener('input', () => bioCount.textContent = bioTA.value.length);
}

// Document file previews
function showDocPreviews(input) {
    const container = document.getElementById('doc-previews');
    container.innerHTML = '';
    Array.from(input.files).forEach(f => {
        const div = document.createElement('div');
        div.className = 'doc-card';
        const isImg = f.type.startsWith('image/');
        div.innerHTML = `
            <div class="doc-icon"><i class="fas ${f.type==='application/pdf'?'fa-file-pdf':'fa-file-image'}" style="color:#C9A427"></i></div>
            <div class="doc-info">
                <div class="doc-name" style="color:#e5e7eb;font-size:.82rem;font-weight:600">${f.name}</div>
                <div style="color:#6b7280;font-size:.72rem">${(f.size/1024/1024).toFixed(2)} MB · New</div>
            </div>
            <i class="fas fa-check-circle text-green-400 text-sm"></i>`;
        container.appendChild(div);
    });
}

// Drag & drop docs
function handleDocDrop(e) {
    e.preventDefault();
    document.getElementById('doc-drop-zone').style.borderColor = 'rgba(201,164,39,.3)';
    const input = document.getElementById('doc-files');
    const dt = new DataTransfer();
    Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
    input.files = dt.files;
    showDocPreviews(input);
}
</script>
@endpush
