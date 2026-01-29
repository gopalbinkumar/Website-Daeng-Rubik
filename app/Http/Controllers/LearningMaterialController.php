<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LearningMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LearningMaterialController extends Controller
{
    /**
     * List materi pembelajaran (public).
     * Filter: level, category, search.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'level' => ['nullable', 'in:basic,intermediate,advanced'],
            'category' => ['nullable', 'string', 'max:100'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $query = LearningMaterial::query()->where('is_published', true);

        if (!empty($validated['level'])) {
            $query->where('level', $validated['level']);
        }

        if (!empty($validated['category'])) {
            $query->where('category', $validated['category']);
        }

        if (!empty($validated['search'])) {
            $q = $validated['search'];
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', '%' . $q . '%')
                    ->orWhere('description', 'like', '%' . $q . '%');
            });
        }

        $materials = $query->orderBy('position')
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($materials);
    }

    /**
     * Detail satu materi.
     */
    public function show(LearningMaterial $learningMaterial)
    {
        return response()->json($learningMaterial);
    }

    /**
     * Tambah materi (admin).
     */
    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $material = LearningMaterial::create($validated);

        return response()->json($material, 201);
    }

    /**
     * Update materi (admin).
     */
    public function update(Request $request, LearningMaterial $learningMaterial)
    {
        $validated = $this->validatePayload($request, $learningMaterial->id);

        if (isset($validated['title']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $learningMaterial->update($validated);

        return response()->json($learningMaterial);
    }

    /**
     * Hapus materi (admin).
     */
    public function destroy(LearningMaterial $learningMaterial)
    {
        $learningMaterial->delete();

        return response()->json(['message' => 'Deleted']);
    }

    /**
     * Validasi payload create/update.
     */
    protected function validatePayload(Request $request, ?int $id = null): array
    {
        $uniqueSlug = 'unique:learning_materials,slug';
        if ($id) {
            $uniqueSlug .= ',' . $id;
        }

        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', $uniqueSlug],
            'description' => ['nullable', 'string'],
            'level' => ['required', 'in:basic,intermediate,advanced'],
            'category' => ['nullable', 'string', 'max:100'],
            'video_provider' => ['nullable', 'in:youtube,local,other'],
            'video_url' => ['nullable', 'string', 'max:500'],
            'video_path' => ['nullable', 'string', 'max:500'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'views_count' => ['nullable', 'integer', 'min:0'],
            'rating' => ['nullable', 'integer', 'min:0', 'max:5'],
            'is_published' => ['nullable', 'boolean'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}

