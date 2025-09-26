<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surveys = Survey::where('user_id', auth()->id())->latest()->get();
        return view('dashboard', compact('surveys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('surveys.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'allow_multiple_responses' => 'boolean',
            'require_login' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_active'] = true;
        
        $survey = Survey::create($validated);

        return redirect()->route('dashboard')
            ->with('status', 'แบบสอบถามถูกสร้างเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(Survey $survey)
    {
        // TODO: Add authorization
        return view('surveys.show', compact('survey'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Survey $survey)
    {
        // TODO: Add authorization
        return view('surveys.edit', compact('survey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Survey $survey)
    {
        // TODO: Add authorization
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'allow_multiple_responses' => 'boolean',
            'require_login' => 'boolean',
        ]);

        $survey->update($validated);

        return redirect()->route('dashboard')
            ->with('status', 'แบบสอบถามถูกอัปเดตเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Survey $survey)
    {
        // TODO: Add authorization
        $survey->delete();

        return redirect()->route('dashboard')
            ->with('status', 'แบบสอบถามถูกลบเรียบร้อยแล้ว');
    }
}
