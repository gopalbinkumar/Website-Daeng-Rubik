<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use App\Models\CubeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;

class LearningMaterialController extends Controller
{
    public function bookmarks()
    {
        $userId = Auth::id();

        $materials = LearningMaterial::with('category')
            ->whereHas('bookmarks', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->where('is_published', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.learn.bookmark', compact('materials'));
    }

    /* =====================
     |  TOGGLE BOOKMARK
     ===================== */
    public function toggleBookmark(LearningMaterial $learningMaterial)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $userId = Auth::id();

        $bookmark = Bookmark::where('user_id', $userId)
            ->where('learning_material_id', $learningMaterial->id)
            ->first();

        if ($bookmark) {
            // Jika sudah ada → hapus
            $bookmark->delete();

            return response()->json([
                'status' => 'removed'
            ]);
        } else {
            // Jika belum ada → buat
            Bookmark::create([
                'user_id' => $userId,
                'learning_material_id' => $learningMaterial->id,
            ]);

            return response()->json([
                'status' => 'added'
            ]);
        }
    }


    /* =====================
     |  LIST
     ===================== */
    public function index(Request $request)
    {
        $materials = LearningMaterial::with('category')
            ->when($request->search, function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            })
            ->when($request->level, function ($q) use ($request) {
                $q->where('level', $request->level);
            })
            ->when($request->category, function ($q) use ($request) {
                $q->where('category_id', $request->category);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $categories = CubeCategory::orderBy('name')->get();

        return view('admin.learn.index', compact('materials', 'categories'));
    }


    public function indexUser()
    {
        $userId = Auth::id();

        $bookmarks = LearningMaterial::whereHas('bookmarks', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->where('is_published', 1)
            ->latest()
            ->take(5) // tampilkan max 5 saja
            ->get();

        return view('pages.learn.index', compact('bookmarks'));
    }

    public function videos()
    {
        $userId = Auth::id();

        $videos = LearningMaterial::with([
            'category',
            'bookmarks' => function ($q) use ($userId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                }
            }
        ])
            ->where('type', 'video')
            ->where('is_published', 1)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('level');

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
        $userId = Auth::id();

        $modules = LearningMaterial::with([
            'category',
            'bookmarks' => function ($q) use ($userId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                }
            }
        ])
            ->where('type', 'modul')
            ->where('is_published', 1)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('level');

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
