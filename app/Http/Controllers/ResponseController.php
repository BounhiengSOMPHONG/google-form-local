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
            $fieldKey = 'question_' . $question->id;
            $rule = $question->required ? 'required' : 'nullable';

            switch ($question->type) {
                case 'short_text':
                    $validatedData[$fieldKey] = [$rule, 'string', 'max:1000'];
                    break;
                case 'radio':
                case 'dropdown':
                    $validatedData[$fieldKey] = [$rule, 'string', 'in:' . implode(',', $question->options)];
                    break;
                case 'checkbox':
                    if ($question->required) {
                        $validatedData[$fieldKey] = ['required', 'array', 'min:1'];
                    } else {
                        $validatedData[$fieldKey] = ['nullable', 'array'];
                    }

                    // custom validator to ensure selected options are valid
                    $validatedData[$fieldKey][] = function ($attribute, $value, $fail) use ($question) {
                        if (!is_array($value)) {
                            $fail('The ' . $attribute . ' must be an array.');
                            return;
                        }

                        $validOptions = $question->options;
                        foreach ($value as $item) {
                            if (!in_array($item, $validOptions)) {
                                $fail('The selected ' . $attribute . ' is invalid.');
                                return;
                            }
                        }
                    };
                    break;
                case 'date':
                    $validatedData[$fieldKey] = [$rule, 'date'];
                    break;
                default:
                    $validatedData[$fieldKey] = [$rule, 'string'];
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

        return redirect()->route('forms.success', $form->id);
    }

    public function showSuccess(Form $form)
    {
        return view('forms.success', compact('form'));
    }
}