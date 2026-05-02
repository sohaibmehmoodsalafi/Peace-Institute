<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $teacher = auth()->user()->teacher;
        $user    = auth()->user();
        return view('teacher.profile', compact('teacher', 'user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'phone_country'    => 'nullable|string|max:10',
            'phone'            => 'nullable|string|max:20',
            'bio'              => 'nullable|string|max:3000',
            'specialization'   => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0|max:60',
            'education'        => 'nullable|string|max:255',
            'certification'    => 'nullable|string|max:255',
            'language'         => 'nullable|string|max:100',
            'nationality'      => 'nullable|string|max:100',
            'gender'           => 'nullable|in:male,female',
            'avatar'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'documents.*'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user    = auth()->user();
        $teacher = $user->teacher;

        // ── Avatar upload ──────────────────────────────────────
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // ── Update user fields ─────────────────────────────────
        $phone = ($request->phone_country ?? '') . $request->phone;
        $user->fill([
            'name'              => $request->name,
            'phone'             => $phone,
            'phone_country_code'=> $request->phone_country ?? null,
        ])->save();

        // ── Document uploads ───────────────────────────────────
        $existingDocs = json_decode($teacher->documents ?? '[]', true) ?: [];

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $docPath = $file->store('teacher-docs', 'public');
                $existingDocs[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $docPath,
                    'type' => $file->getClientMimeType(),
                    'uploaded_at' => now()->toDateString(),
                ];
            }
        }

        // ── Delete selected documents ──────────────────────────
        if ($request->delete_docs) {
            foreach ($request->delete_docs as $delPath) {
                Storage::disk('public')->delete($delPath);
                $existingDocs = array_filter($existingDocs, fn($d) => $d['path'] !== $delPath);
                $existingDocs = array_values($existingDocs);
            }
        }

        // ── Update teacher fields ──────────────────────────────
        $teacher->update([
            'bio'              => $request->bio,
            'specialization'   => $request->specialization,
            'experience_years' => $request->experience_years ?? 0,
            'education'        => $request->education,
            'certification'    => $request->certification,
            'language'         => $request->language,
            'nationality'      => $request->nationality,
            'gender'           => $request->gender,
            'documents'        => json_encode(array_values($existingDocs)),
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }
}
