<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\DiscussionGroup;
// removed duplicate Storage import

class CourseController extends Controller
{
    private function ensureOwned(Course $course): void
    {
        if ($course->author_id !== Auth::id()) {
            abort(403);
        }
    }
    public function index()
    {
        $courses = Course::where('author_id', Auth::id())
            ->with(['category', 'tags'])
            ->withCount('enrollments')
            ->latest()
            ->paginate(10);

        return view('pages.mentor.courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $tags = Tag::where('is_active', true)->get();

        return view('pages.mentor.courses.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'mentor_share_percent' => 'required|integer|min:0|max:100',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:draft,published,archived',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'intro_video_url' => 'nullable|url',
            'enable_discussion' => 'nullable',
        ]);

        $slug = $this->makeUniqueSlug($validated['title']);
        $data = [
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'mentor_share_percent' => $validated['mentor_share_percent'],
            'status' => $validated['status'],
            'author_id' => Auth::id(),
            'verification_status' => 'pending',
            'intro_video_url' => $validated['intro_video_url'] ?? null,
        ];
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('course_thumbnails/'.Auth::id(), 'public');
            $data['thumbnail_url'] = Storage::url($path);
        }
        $course = Course::create($data);

        if (isset($validated['tags'])) {
            $course->tags()->attach($validated['tags']);
        }
        if ($request->boolean('enable_discussion')) {
            DiscussionGroup::firstOrCreate([
                'course_id' => $course->id,
            ], [
                'group_name' => $course->title,
            ]);
        }

        return redirect()->route('mentor.courses.index')->with('success', 'Kursus berhasil dibuat!');
    }

    public function show(Course $course)
    {
        $this->ensureOwned($course);

        $course->load(['category', 'tags', 'modules.lessons'])
            ->loadCount('enrollments');
        $course->load('modules.quiz');

        return view('pages.mentor.courses.show', compact('course'));
    }

    public function showBySlug(string $mentor, string $course)
    {
        $user = User::whereRaw('LOWER(REPLACE(name, " ", "-")) = ?', [Str::slug($mentor)])->firstOrFail();
        $model = Course::where('author_id', $user->id)->where('slug', $course)->firstOrFail();
        return $this->show($model);
    }

    public function edit(Course $course)
    {
        $this->ensureOwned($course);

        $categories = Category::where('is_active', true)->get();
        $tags = Tag::where('is_active', true)->get();

        return view('pages.mentor.courses.edit', compact('course', 'categories', 'tags'));
    }

    public function editBySlug(string $mentor, string $course)
    {
        $user = User::whereRaw('LOWER(REPLACE(name, " ", "-")) = ?', [Str::slug($mentor)])->firstOrFail();
        $model = Course::where('author_id', $user->id)->where('slug', $course)->firstOrFail();
        return $this->edit($model);
    }

    public function update(Request $request, Course $course)
    {
        $this->ensureOwned($course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'mentor_share_percent' => 'required|integer|min:0|max:100',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:draft,published,archived',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'intro_video_url' => 'nullable|url',
            'enable_discussion' => 'nullable',
            'slug' => 'nullable|string|max:255',
        ]);

        $newSlug = isset($validated['slug']) && trim($validated['slug']) !== ''
            ? $this->makeUniqueSlug($validated['slug'], $course->id)
            : $course->slug;
        $data = [
            'title' => $validated['title'],
            'slug' => $newSlug,
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'mentor_share_percent' => $validated['mentor_share_percent'],
            'status' => $validated['status'],
            'intro_video_url' => $validated['intro_video_url'] ?? null,
        ];
        if ($data['status'] === 'published' && $course->verification_status !== 'approved') {
            $data['status'] = 'draft';
            session()->flash('warning', 'Kursus belum diverifikasi admin. Status diubah menjadi Draft.');
        }
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('course_thumbnails/'.Auth::id(), 'public');
            $data['thumbnail_url'] = Storage::url($path);
        }
        $course->update($data);

        if ($request->boolean('enable_discussion')) {
            DiscussionGroup::firstOrCreate([
                'course_id' => $course->id,
            ], [
                'group_name' => $course->title,
            ]);
        }

        if (isset($validated['tags'])) {
            $course->tags()->sync($validated['tags']);
        } else {
            $course->tags()->detach();
        }

        return redirect()->route('mentor.courses.index')->with('success', 'Kursus berhasil diperbarui!');
    }

    public function updateBySlug(Request $request, string $mentor, string $course)
    {
        $user = User::whereRaw('LOWER(REPLACE(name, " ", "-")) = ?', [Str::slug($mentor)])->firstOrFail();
        $model = Course::where('author_id', $user->id)->where('slug', $course)->firstOrFail();
        return $this->update($request, $model);
    }

    private function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        while (Course::where('slug', $slug)->when($ignoreId, function($q) use ($ignoreId){ $q->where('id','!=',$ignoreId); })->exists()) {
            $slug = $base.'-'.substr(str_shuffle($chars), 0, 1);
        }
        return $slug;
    }

    public function destroy(Course $course)
    {
        $this->ensureOwned($course);

        $course->delete();

        return redirect()->route('mentor.courses.index')->with('success', 'Kursus berhasil dihapus!');
    }

    public function destroyBySlug(string $mentor, string $course)
    {
        $user = User::whereRaw('LOWER(REPLACE(name, " ", "-")) = ?', [Str::slug($mentor)])->firstOrFail();
        $model = Course::where('author_id', $user->id)->where('slug', $course)->firstOrFail();
        return $this->destroy($model);
    }

    public function storeModule(Request $request, Course $course)
    {
        $this->ensureOwned($course);
        $validated = $request->validate([
            'title' => ['required','string','max:255'],
        ]);
        $nextOrder = (int)($course->modules()->max('order') ?? 0) + 1;
        Module::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'order' => $nextOrder,
        ]);
        return redirect()->route('mentor.courses.show', $course)->with('success','Modul berhasil ditambahkan');
    }

    public function showModule(Course $course, Module $module)
    {
        $this->ensureOwned($course);
        if ($module->course_id !== $course->id) abort(404);
        $module->load(['lessons','quiz.questions.options']);
        return view('pages.mentor.modules.show', compact('course','module'));
    }

    public function storeLesson(Request $request, Course $course, Module $module)
    {
        $this->ensureOwned($course);
        if ($module->course_id !== $course->id) abort(404);
        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'content' => ['nullable','string'],
            'video_url' => ['nullable','url'],
            'duration_minutes' => ['nullable','integer','min:0'],
            'material_files' => ['nullable','array'],
            'material_files.*' => ['file','mimes:pdf,doc,docx','max:10240'],
            
        ]);
        $nextOrder = (int)($module->lessons()->max('order') ?? 0) + 1;
        $files = [];
        if ($request->hasFile('material_files')) {
            foreach ($request->file('material_files') as $file) {
                $original = $file->getClientOriginalName();
                $path = $file->storeAs('lesson_materials/'.$course->id.'/'.$module->id, now()->format('YmdHis').'_'.str_replace(['\\','/'], '-', $original), 'public');
                $files[] = Storage::url($path);
            }
        }
        Lesson::create([
            'module_id' => $module->id,
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'material_file_url' => null,
            'material_files' => $files,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'order' => $nextOrder,
        ]);
        return redirect()->route('mentor.courses.modules.show', [$course, $module])->with('success','Pelajaran berhasil ditambahkan');
    }

    public function showLesson(Course $course, Module $module, Lesson $lesson)
    {
        $this->ensureOwned($course);
        if ($module->course_id !== $course->id || $lesson->module_id !== $module->id) abort(404);
        return view('pages.mentor.lessons.show', compact('course','module','lesson'));
    }

    public function updateLesson(Request $request, Course $course, Module $module, Lesson $lesson)
    {
        $this->ensureOwned($course);
        if ($module->course_id !== $course->id || $lesson->module_id !== $module->id) abort(404);
        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'content' => ['nullable','string'],
            'video_url' => ['nullable','url'],
            'duration_minutes' => ['nullable','integer','min:0'],
            'material_files' => ['nullable','array'],
            'material_files.*' => ['file','mimes:pdf,doc,docx','max:10240'],
            
        ]);
        $data = [
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
        ];
        if ($request->hasFile('material_files')) {
            $newFiles = [];
            foreach ($request->file('material_files') as $file) {
                $original = $file->getClientOriginalName();
                $path = $file->storeAs('lesson_materials/'.$course->id.'/'.$module->id, now()->format('YmdHis').'_'.str_replace(['\\','/'], '-', $original), 'public');
                $newFiles[] = Storage::url($path);
            }
            $existing = is_array($lesson->material_files) ? $lesson->material_files : [];
            $data['material_files'] = array_values(array_merge($existing, $newFiles));
        }
        $lesson->update($data);
        return redirect()->route('mentor.courses.modules.show', [$course, $module])->with('success','Pelajaran berhasil diperbarui');
    }

    public function destroyLesson(Course $course, Module $module, Lesson $lesson)
    {
        $this->ensureOwned($course);
        if ($module->course_id !== $course->id || $lesson->module_id !== $module->id) abort(404);
        $lesson->delete();
        return redirect()->route('mentor.courses.modules.show', [$course, $module])->with('success','Pelajaran berhasil dihapus');
    }
}