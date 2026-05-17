<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationModule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EducationModuleController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type', 'all');
        $type = is_string($type) ? $type : 'all';

        $query = EducationModule::query()->orderByDesc('created_at');

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        $modules = $query->paginate(15)->withQueryString();

        return view('admin.education.index', [
            'modules' => $modules,
            'type' => $type,
        ]);
    }

    public function create(): View
    {
        return view('admin.education.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:500'],
            'type' => ['required', 'in:video,article'],
            'url' => ['nullable', 'url', 'max:500'],
            'content' => ['nullable', 'string'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'is_active' => ['boolean'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,mov,avi,mkv', 'max:102400'],
            'image_file' => ['nullable', 'file', 'mimes:jpg,jpeg,webp', 'max:5120'],
        ];

        if ($request->input('type') === 'video' && !$request->hasFile('video_file')) {
            $rules['url'][] = 'required_without:video_file';
        }

        $validated = $request->validate($rules);
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('video_file')) {
            $validated['video_path'] = $request->file('video_file')->store('videos', 'public');
        }

        if ($request->hasFile('image_file')) {
            $validated['image_path'] = $request->file('image_file')->store('article-images', 'public');
        }

        EducationModule::create($validated);

        return redirect()->route('admin.education.index')
            ->with('status', 'module-created');
    }

    public function edit(EducationModule $educationModule): View
    {
        return view('admin.education.edit', [
            'module' => $educationModule,
        ]);
    }

    public function update(Request $request, EducationModule $educationModule): RedirectResponse
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:500'],
            'type' => ['required', 'in:video,article'],
            'url' => ['nullable', 'url', 'max:500'],
            'content' => ['nullable', 'string'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'is_active' => ['boolean'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,mov,avi,mkv', 'max:102400'],
            'image_file' => ['nullable', 'file', 'mimes:jpg,jpeg,webp', 'max:5120'],
        ];

        if ($request->input('type') === 'video' && !$request->hasFile('video_file') && !$educationModule->video_path && !$educationModule->url) {
            $rules['url'][] = 'required_without:video_file';
        }

        $validated = $request->validate($rules);
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('video_file')) {
            if ($educationModule->video_path) {
                Storage::disk('public')->delete($educationModule->video_path);
            }
            $validated['video_path'] = $request->file('video_file')->store('videos', 'public');
        }

        if ($request->hasFile('image_file')) {
            if ($educationModule->image_path) {
                Storage::disk('public')->delete($educationModule->image_path);
            }
            $validated['image_path'] = $request->file('image_file')->store('article-images', 'public');
        }

        $educationModule->update($validated);

        return redirect()->route('admin.education.index')
            ->with('status', 'module-updated');
    }

    public function destroy(EducationModule $educationModule): RedirectResponse
    {
        if ($educationModule->video_path) {
            Storage::disk('public')->delete($educationModule->video_path);
        }
        if ($educationModule->image_path) {
            Storage::disk('public')->delete($educationModule->image_path);
        }

        $educationModule->delete();

        return redirect()->route('admin.education.index')
            ->with('status', 'module-deleted');
    }
}
