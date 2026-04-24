<?php
namespace App\Http\Controllers\Admin;

use App\Models\FAQ;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FAQController extends Controller
{
    public function index()
    {
        $faqs = FAQ::latest()->paginate(20);
        return view('backend.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('backend.faqs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer'   => 'required|string',
            'status'   => 'nullable|in:active,inactive',
        ]);
        $validated['status'] = $validated['status'] ?? 'active';
        FAQ::create($validated);
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function edit($id)
    {
        $faq = FAQ::findOrFail($id);
        return view('backend.faqs.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $faq = FAQ::findOrFail($id);
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);
        $faq->update($validated);
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy($id)
    {
        $faq = FAQ::findOrFail($id);
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }
}
