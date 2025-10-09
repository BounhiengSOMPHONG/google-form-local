<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Response;
use App\Models\ResponseAnswer;
use App\Models\Question;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function showPublicForm(Request $request, Form $form)
    {
        $questions = $form->questions()->orderBy('position')->get();

        $prefill = [];
        $responseId = $request->query('response_id');
        if ($responseId) {
            $response = Response::with('responseAnswers')->where('id', $responseId)->where('form_id', $form->id)->first();
            if ($response) {
                foreach ($response->responseAnswers as $answer) {
                    $key = 'question_' . $answer->question_id;
                    $decoded = json_decode($answer->answer, true);
                    $prefill[$key] = $decoded === null ? $answer->answer : $decoded;
                }
            }
        }

        return view('forms.public', compact('form', 'questions', 'prefill', 'responseId'));
    }

    public function submitForm(Request $request, Form $form)
    {
        // Prevent accepting responses if the form is closed
        if (! $form->accepting_responses) {
            // Preserve the user's input so they don't lose their answers when rejected
            return redirect()->route('forms.public', $form)->withInput()->with('error', 'This form is not accepting responses.');
        }

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

        // If response_id provided, update existing response; otherwise create new
        $existingResponseId = $request->input('response_id');
        if ($existingResponseId) {
            $response = Response::where('id', $existingResponseId)->where('form_id', $form->id)->first();
        } else {
            $response = null;
        }

        if (! $response) {
            $response = Response::create([
                'form_id' => $form->id
            ]);
        } else {
            // remove previous answers so we can recreate them
            $response->responseAnswers()->delete();
        }

        // Save each answer (create new ResponseAnswer records)
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

        // Redirect to success with the response id so the edit link can point back
        // Use the model instance so route model binding fills {form:public_token}
        return redirect()->route('forms.success', $form)->with('response_id', $response->id);
    }

    public function showSuccess(Form $form)
    {
        return view('forms.success', compact('form'));
    }
}