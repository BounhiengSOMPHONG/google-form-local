<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::where('user_id', auth()->id())->latest()->get();

        // Ensure every form has a public_token so URL generation for public routes won't fail
        foreach ($forms as $form) {
            if (empty($form->public_token)) {
                do {
                    $token = bin2hex(random_bytes(16));
                } while (Form::where('public_token', $token)->exists());

                $form->public_token = $token;
                $form->save();
            }
        }
        // If this request is for the forms index route, return the forms listing view.
        if (request()->routeIs('forms.index')) {
            return view('forms.index', compact('forms'));
        }

        $surveys = collect(); // Empty collection since surveys are removed
        return view('dashboard', compact('forms', 'surveys'));
    }

    public function create()
    {
        return view('forms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Generate a unique public token for sharing
        do {
            $token = bin2hex(random_bytes(16));
        } while (Form::where('public_token', $token)->exists());

        $form = Form::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->id(),
            'public_token' => $token,
        ]);

        return redirect()->route('forms.edit', $form->id)->with('success', 'Form created successfully!');
    }

    public function edit(Form $form)
    {
        $questions = $form->questions()->orderBy('position')->get();
        return view('forms.edit', compact('form', 'questions'));
    }

    public function update(Request $request, Form $form)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'accepting_responses' => 'nullable|in:0,1',
        ]);

        $form->update([
            'title' => $request->title,
            'description' => $request->description,
            'accepting_responses' => $request->has('accepting_responses') ? (bool)$request->accepting_responses : true,
        ]);

        return redirect()->route('forms.edit', $form->id)->with('success', 'Form updated successfully!');
    }

    public function destroy(Form $form)
    {
        $form->delete();
        return redirect()->route('forms.index')->with('success', 'Form deleted successfully!');
    }

    public function addQuestion(Request $request, Form $form)
    {
        if ($request->has('options') && is_string($request->options)) {
            $request->merge(['options' => json_decode($request->options, true) ?? []]);
        }

        $validated = $request->validate([
            'question_text' => 'required|string',
            'type' => ['required', Rule::in(['short_text', 'radio', 'checkbox', 'dropdown', 'date'])],
            'required' => 'boolean',
            'options' => 'nullable|array',
            'options.*' => 'string',
        ]);

        $highestPosition = $form->questions()->max('position');
        $position = $highestPosition !== null ? $highestPosition + 1 : 1;

        $dataToCreate = [
            'form_id' => $form->id,
            'question_text' => $validated['question_text'],
            'type' => $validated['type'],
            'required' => $request->required ?? false,
            'options' => ($validated['type'] !== 'short_text' && $validated['type'] !== 'date') ? ($validated['options'] ?? null) : null,
            'position' => $position,
        ];

        Question::create($dataToCreate);

        return redirect()->route('forms.edit', $form->id)->with('success', 'Question added successfully!');
    }

    public function updateQuestion(Request $request, Form $form, Question $question)
    {
        // The type of the question doesn't change during an update.
        // We use the existing question's type to decide on validation rules.
        $type = $question->type;

        if ($type === 'section') {
            $validated = $request->validate([
                'question_text' => 'required|string|max:255', // Section Title
                'description' => 'nullable|string',
            ]);

            $question->update($validated);

        } else {
            // This is a regular question
            if ($request->has('options') && is_string($request->options)) {
                $request->merge(['options' => json_decode($request->options, true) ?? []]);
            }

            $validated = $request->validate([
                'question_text' => 'required|string',
                'type' => ['required', Rule::in(['short_text', 'radio', 'checkbox', 'dropdown', 'date'])],
                'required' => 'boolean',
                'options' => 'nullable|array',
                'options.*' => 'string',
            ]);

            $question->update([
                'question_text' => $validated['question_text'],
                'type' => $validated['type'],
                'required' => $request->required ?? false,
                'options' => in_array($validated['type'], ['radio', 'checkbox', 'dropdown']) ? ($validated['options'] ?? null) : null,
            ]);
        }

        return response()->json($question->fresh());
    }

    public function deleteQuestion(Form $form, Question $question)
    {
        $question->delete();
        return redirect()->route('forms.edit', $form->id)->with('success', 'Question deleted successfully!');
    }

    public function reorderQuestions(Request $request, Form $form)
    {
        $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'integer|exists:questions,id',
        ]);

        foreach ($request->question_ids as $index => $questionId) {
            $question = Question::find($questionId);
            if ($question && $question->form_id === $form->id) {
                $question->update(['position' => $index + 1]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function setAccepting(Request $request, Form $form)
    {
        $request->validate([
            'accepting_responses' => 'required|in:0,1',
        ]);

        $form->accepting_responses = (bool)$request->accepting_responses;
        $form->save();

        return response()->json(['success' => true]);
    }

    public function addSection(Request $request, Form $form)
    {
        $highestPosition = $form->questions()->max('position');
        $position = $highestPosition !== null ? $highestPosition + 1 : 1;

        $section = Question::create([
            'form_id' => $form->id,
            'question_text' => 'Untitled Section',
            'description' => 'Section description',
            'type' => 'section',
            'required' => false,
            'position' => $position,
        ]);

        return response()->json($section);
    }
}