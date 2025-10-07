<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Response;
use App\Models\ResponseAnswer;
use App\Models\Question;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function showPublicForm(Form $form)
    {
        $questions = $form->questions()->orderBy('position')->get();
        return view('forms.public', compact('form', 'questions'));
    }

    public function submitForm(Request $request, Form $form)
    {
        $questions = $form->questions;
        $validatedData = [];

        foreach ($questions as $question) {
            $rule = $question->required ? 'required' : 'nullable';
            
            switch ($question->type) {
                case 'short_text':
                    $validatedData[$question->id] = ['string', 'max:1000'];
                    break;
                case 'radio':
                case 'dropdown':
                    $validatedData[$question->id] = [$rule, 'string', 'in:' . implode(',', $question->options)];
                    break;
                case 'checkbox':
                    if ($question->required) {
                        $validatedData[$question->id] = ['required', 'array', 'min:1'];
                    } else {
                        $validatedData[$question->id] = ['nullable', 'array'];
                    }
                    $validatedData[$question->id][] = 'array';
                    $validatedData[$question->id][] = function ($attribute, $value, $fail) use ($question) {
                        if (!is_array($value)) {
                            $fail('The ' . $attribute . ' must be an array.');
                        }
                        
                        $validOptions = $question->options;
                        foreach ($value as $item) {
                            if (!in_array($item, $validOptions)) {
                                $fail('The selected ' . $attribute . ' is invalid.');
                            }
                        }
                    };
                    break;
                case 'date':
                    $validatedData[$question->id] = [$rule, 'date'];
                    break;
                default:
                    $validatedData[$question->id] = ['string'];
            }
        }

        $request->validate($validatedData);

        // Create a new response record
        $response = Response::create([
            'form_id' => $form->id
        ]);

        // Save each answer
        foreach ($request->all() as $questionId => $answer) {
            if (str_starts_with($questionId, 'question_')) {
                $questionIdNum = str_replace('question_', '', $questionId);
                
                // Find the question to validate the ID belongs to this form
                $question = $form->questions()->where('id', $questionIdNum)->first();
                if ($question) {
                    ResponseAnswer::create([
                        'response_id' => $response->id,
                        'question_id' => $question->id,
                        'answer' => is_array($answer) ? json_encode($answer) : $answer
                    ]);
                }
            }
        }

        return redirect()->route('forms.public', $form->id)->with('success', 'Form submitted successfully!');
    }
}