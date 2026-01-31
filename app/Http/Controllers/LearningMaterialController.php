<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use App\Models\CubeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LearningMaterialController extends Controller
{
    /* =====================
     |  LIST
     ===================== */
    public function index()
    {
        $materials = LearningMaterial::with('category')
            ->orderBy('position')
            ->latest()
            ->get();

        $categories = CubeCategory::orderBy('name')->get();

        return view('admin.learn.index', compact('materials', 'categories'));
    }

    public function indexUser()
    {
        return view('pages.learn.index');
    }


    public function videos()
    {
        // ambil semua video yang publish
        $videos = LearningMaterial::with('category')
            ->where('type', 'video')
            ->where('is_published', 1)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('level'); // beginner / intermediate / advanced

        // kategori + jumlah materi
        $categories = CubeCategory::withCount([
            'learningMaterials as videos_count' => function ($q) {
                $q->where('type', 'video')
                    ->where('is_published', 1);
            }
        ])->get();

        return view('pages.learn.video', compact('videos', 'categories'));
    }

    public function modules()
    {
        // Ambil materi MODUL (PDF) yang publish
        $modules = LearningMaterial::with('category')
            ->where('type', 'modul')
            ->where('is_published', 1)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('level'); // beginner / intermediate / advanced

        // kategori + jumlah materi
        $categories = CubeCategory::withCount([
            'learningMaterials as videos_count' => function ($q) {
                $q->where('type', 'modul')
                    ->where('is_published', 1);
            }
        ])->get();

        return view('pages.learn.module', compact('modules', 'categories'));
    }



    /* =====================
     |  CREATE
     ===================== */
    public function create()
    {
        $categories = CubeCategory::orderBy('name')->get();

        return view('admin.learn.index', compact('categories'));
    }

    /* =====================
     |  STORE
     ===================== */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,modul',
            'level' => 'required|in:beginner,intermediate,advanced',
            'category_id' => 'nullable|exists:cube_categories,id',
            'video_url' => 'required_if:type,video|nullable|url',
            'module_file' => 'required_if:type,modul|nullable|file',
            'is_published' => 'boolean',
            'position' => 'integer|min:0',
        ]);

        $data = $validated;

        $data['slug'] = Str::slug($request->title);

        // reset field sesuai type
        $data['video_url'] = $request->type === 'video'
            ? $request->video_url
            : null;

        $data['module_path'] = null;

        if ($request->type === 'modul' && $request->hasFile('module_file')) {
            $data['module_path'] = $request
                ->file('module_file')
                ->store('learning-modules', 'public');
        }

        $data['is_published'] = $request->boolean('is_published', true);
        $data['position'] = $request->position ?? 0;

        LearningMaterial::create($data);

        return redirect()
            ->route('admin.learn.index')
            ->with('success', 'Materi berhasil ditambahkan');

    }

    /* =====================
     |  EDIT
     ===================== */
    public function edit(LearningMaterial $learningMaterial)
    {
        $categories = CubeCategory::orderBy('name')->get();

        return view(
            'admin.learning-materials.edit',
            compact('learningMaterial', 'categories')
        );
    }

    /* =====================
     |  UPDATE
     ===================== */
    public function update(Request $request, LearningMaterial $learningMaterial)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,modul',
            'level' => 'required|in:beginner,intermediate,advanced',
            'category_id' => 'nullable|exists:cube_categories,id',
            'video_url' => 'nullable|url',
            'module_file' => 'nullable|file',
        ]);

        $data = $validated;

        // update slug jika judul berubah
        if ($learningMaterial->title !== $request->title) {
            $data['slug'] = Str::slug($request->title);
        }

        // reset field sesuai type
        if ($request->type === 'video') {
            $data['video_url'] = $request->video_url;
            $data['module_path'] = null;
        } else {
            $data['video_url'] = null;

            if ($request->hasFile('module_file')) {
                $data['module_path'] = $request
                    ->file('module_file')
                    ->store('learning-modules', 'public');
            }
        }

        $learningMaterial->update($data);

        return redirect()
            ->route('admin.learn.index')
            ->with('success', 'Materi berhasil diperbarui');
    }

    /* =====================
     |  DELETE
     ===================== */
    public function destroy(LearningMaterial $learningMaterial)
    {
        if ($learningMaterial->module_path) {
            \Storage::disk('public')->delete($learningMaterial->module_path);
        }

        $learningMaterial->delete();

        return back()->with('success', 'Materi berhasil dihapus');
    }
}
