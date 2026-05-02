<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of menu items.
     */
    public function index()
    {
        $menus = Menu::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new menu item.
     */
    public function create()
    {
        $categories = ['Veg', 'Non-Veg', 'Beverages', 'Desserts', 'Snacks'];
        return view('admin.menus.create', compact('categories'));
    }

    /**
     * Store a newly created menu item.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'is_available' => 'boolean',
            'cutoff_time' => 'required|date_format:H:i',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $menu = new Menu();
        $menu->name = $request->name;
        $menu->description = $request->description;
        $menu->price = $request->price;
        $menu->category = $request->category;
        $menu->is_available = $request->has('is_available');
        $menu->cutoff_time = $request->cutoff_time;

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menus', 'public');
            $menu->image = $imagePath;
        }

        $menu->save();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu item created successfully!');
    }

    /**
     * Show the form for editing a menu item.
     */
    public function edit(Menu $menu)
    {
        $categories = ['Veg', 'Non-Veg', 'Beverages', 'Desserts', 'Snacks'];
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    /**
     * Update the specified menu item.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'is_available' => 'boolean',
            'cutoff_time' => 'required|date_format:H:i',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $menu->name = $request->name;
        $menu->description = $request->description;
        $menu->price = $request->price;
        $menu->category = $request->category;
        $menu->is_available = $request->has('is_available');
        $menu->cutoff_time = $request->cutoff_time;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }
            $imagePath = $request->file('image')->store('menus', 'public');
            $menu->image = $imagePath;
        }

        $menu->save();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu item updated successfully!');
    }

    /**
     * Remove the specified menu item.
     */
    public function destroy(Menu $menu)
    {
        // Delete image if exists
        if ($menu->image && Storage::disk('public')->exists($menu->image)) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu item deleted successfully!');
    }

    /**
     * Toggle menu availability.
     */
    public function toggleAvailability(Menu $menu)
    {
        $menu->is_available = !$menu->is_available;
        $menu->save();

        $status = $menu->is_available ? 'available' : 'unavailable';
        return redirect()->back()->with('success', "Menu item is now {$status}!");
    }
}
