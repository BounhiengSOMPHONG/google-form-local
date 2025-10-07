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
        return view('forms.index', compact('forms'));
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

        $form = Form::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->id(),
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
        ]);

        $form->update([
            'title' => $request->title,
            'description' => $request->description,
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
        $request->validate([
            'question_text' => 'required|string',
            'type' => ['required', Rule::in(['short_text', 'radio', 'checkbox', 'dropdown', 'date'])],
            'required' => 'boolean',
            'options' => 'array',
            'options.*' => 'string',
        ]);

        $highestPosition = $form->questions()->max('position');
        $position = $highestPosition !== null ? $highestPosition + 1 : 1;

        $question = new Question();
        $question->form_id = $form->id;
        $question->question_text = $request->question_text;
        $question->type = $request->type;
        $question->required = $request->required ?? false;
        $question->options = $request->type !== 'short_text' && $request->type !== 'date' ? $request->options : null;
        $question->position = $position;
        $question->save();

        return redirect()->route('forms.edit', $form->id)->with('success', 'Question added successfully!');
    }

    public function updateQuestion(Request $request, Form $form, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'type' => ['required', Rule::in(['short_text', 'radio', 'checkbox', 'dropdown', 'date'])],
            'required' => 'boolean',
            'options' => 'array',
            'options.*' => 'string',
        ]);

        $question->update([
            'question_text' => $request->question_text,
            'type' => $request->type,
            'required' => $request->required ?? false,
            'options' => $request->type !== 'short_text' && $request->type !== 'date' ? $request->options : null,
        ]);

        return redirect()->route('forms.edit', $form->id)->with('success', 'Question updated successfully!');
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
}