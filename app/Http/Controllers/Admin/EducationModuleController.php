<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationModule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:500'],
            'type' => ['required', 'in:video,article'],
            'url' => ['nullable', 'required_if:type,video', 'url', 'max:500'],
            'content' => ['nullable', 'required_if:type,article', 'string'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

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
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:500'],
            'type' => ['required', 'in:video,article'],
            'url' => ['nullable', 'required_if:type,video', 'url', 'max:500'],
            'content' => ['nullable', 'required_if:type,article', 'string'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $educationModule->update($validated);

        return redirect()->route('admin.education.index')
            ->with('status', 'module-updated');
    }

    public function destroy(EducationModule $educationModule): RedirectResponse
    {
        $educationModule->delete();

        return redirect()->route('admin.education.index')
            ->with('status', 'module-deleted');
    }
}
