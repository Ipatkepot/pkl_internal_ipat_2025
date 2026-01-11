<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk (Admin)
     */
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['category', 'primaryImage']) // Optimasi query
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->category, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Form Tambah Produk
     */
    public function create(): View
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Simpan Produk Baru
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // 1. Simpan data dasar produk
            $product = Product::create($request->validated());

            // 2. Handle Upload Video
            if ($request->hasFile('video')) {
                $path = $request->file('video')->store('products/videos', 'public');
                $product->update(['video_url' => $path]);
            }

            // 3. Handle Upload Gambar
            if ($request->hasFile('images')) {
                $this->uploadImages($request->file('images'), $product);
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal simpan: ' . $e->getMessage());
        }
    }

    /**
     * Detail Produk
     */
    public function show(Product $product): View
    {
        $product->load(['category', 'images']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Form Edit Produk
     */
    public function edit(Product $product): View
    {
        $categories = Category::active()->orderBy('name')->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update Produk
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // 1. Update data dasar
            $product->update($request->validated());

            // 2. Handle Update/Hapus Video
            if ($request->boolean('delete_video') && $product->video_url) {
                Storage::disk('public')->delete($product->video_url);
                $product->update(['video_url' => null]);
            }

            if ($request->hasFile('video')) {
                // Hapus video lama jika ada file baru didefinisikan
                if ($product->video_url) {
                    Storage::disk('public')->delete($product->video_url);
                }
                $path = $request->file('video')->store('products/videos', 'public');
                $product->update(['video_url' => $path]);
            }

            // 3. Handle Gambar Baru
            if ($request->hasFile('images')) {
                $this->uploadImages($request->file('images'), $product);
            }

            // 4. Handle Hapus Gambar Tertentu
            if ($request->has('delete_images')) {
                $this->deleteImages($request->delete_images);
            }

            // 5. Handle Update Gambar Utama
            if ($request->has('primary_image')) {
                $this->setPrimaryImage($product, $request->primary_image);
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Produk Total
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            // Hapus semua file gambar fisik
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Hapus file video fisik
            if ($product->video_url) {
                Storage::disk('public')->delete($product->video_url);
            }

            $product->delete();
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    // ================= HELPER METHODS =================

    protected function uploadImages(array $files, Product $product): void
    {
        $isFirst = $product->images()->count() === 0;

        foreach ($files as $index => $file) {
            $filename = 'prod-' . $product->id . '-' . uniqid() . '.' . $file->extension();
            $path = $file->storeAs('products', $filename, 'public');

            $product->images()->create([
                'image_path' => $path,
                'is_primary' => $isFirst && $index === 0,
                'sort_order' => $product->images()->count() + $index,
            ]);
        }
    }

    protected function deleteImages(array $imageIds): void
    {
        $images = ProductImage::whereIn('id', $imageIds)->get();
        foreach ($images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
    }

    protected function setPrimaryImage(Product $product, int $imageId): void
    {
        $product->images()->update(['is_primary' => false]);
        $product->images()->where('id', $imageId)->update(['is_primary' => true]);
    }
}