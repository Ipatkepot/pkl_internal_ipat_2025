<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori.
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->latest()
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'image'       => 'nullable|image|max:1024',
        ]);

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        // Generate Slug
        $validated['slug'] = Str::slug($request->name);

        // Handle Status Aktif (Checkbox Fix)
        $validated['is_active'] = $request->has('is_active');

        Category::create($validated);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Memperbarui data kategori.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'image'       => 'nullable|image|max:1024',
        ]);

        // Handle Image Update
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        // Update Slug
        $validated['slug'] = Str::slug($request->name);

        // Handle Status Aktif (Checkbox Fix)
        // Jika checkbox tidak dicentang, request tidak mengirimkan is_active, maka kita set false
        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Menghapus kategori.
     */
    public function destroy(Category $category)
    {
        // Cegah penghapusan jika kategori masih ada produk
        if ($category->products()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        // Hapus file gambar dari storage
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
