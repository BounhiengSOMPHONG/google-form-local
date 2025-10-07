<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Form;
use App\Models\Question;
use App\Models\Response;
use App\Models\ResponseAnswer;

class FormSeeder extends Seeder
{
    public function run()
    {
        // Create a sample form
        $user = \App\Models\User::first(); // Get the first user created by default seeder
        $form = Form::create([
            'title' => 'Sample Form',
            'description' => 'This is a sample form to test the form system.',
            'user_id' => $user ? $user->id : null
        ]);

        // Add questions to the form
        $question1 = Question::create([
            'form_id' => $form->id,
            'question_text' => 'What is your name?',
            'type' => 'short_text',
            'required' => true,
            'position' => 1
        ]);

        $question2 = Question::create([
            'form_id' => $form->id,
            'question_text' => 'What is your favorite programming language?',
            'type' => 'radio',
            'required' => true,
            'options' => ['PHP', 'JavaScript', 'Python', 'Java'],
            'position' => 2
        ]);

        $question3 = Question::create([
            'form_id' => $form->id,
            'question_text' => 'Which frameworks do you use? (Select all that apply)',
            'type' => 'checkbox',
            'required' => false,
            'options' => ['Laravel', 'Vue.js', 'React', 'Angular', 'Express'],
            'position' => 3
        ]);

        $question4 = Question::create([
            'form_id' => $form->id,
            'question_text' => 'When did you start programming?',
            'type' => 'date',
            'required' => false,
            'position' => 4
        ]);

        // Create some sample responses
        for ($i = 1; $i <= 5; $i++) {
            $response = Response::create(['form_id' => $form->id]);

            ResponseAnswer::create([
                'response_id' => $response->id,
                'question_id' => $question1->id,
                'answer' => "Name $i"
            ]);

            ResponseAnswer::create([
                'response_id' => $response->id,
                'question_id' => $question2->id,
                'answer' => ['PHP', 'JavaScript', 'Python', 'Java'][array_rand(['PHP', 'JavaScript', 'Python', 'Java'])]
            ]);

            $checkboxOptions = ['Laravel', 'Vue.js', 'React', 'Angular', 'Express'];
            $selectedOptions = array_rand(array_flip($checkboxOptions), rand(1, 3));
            if (!is_array($selectedOptions)) {
                $selectedOptions = [$selectedOptions];
            }
            $selectedValues = array_intersect_key($checkboxOptions, array_flip($selectedOptions));

            ResponseAnswer::create([
                'response_id' => $response->id,
                'question_id' => $question3->id,
                'answer' => json_encode(array_values($selectedValues))
            ]);

            ResponseAnswer::create([
                'response_id' => $response->id,
                'question_id' => $question4->id,
                'answer' => now()->subYears(rand(1, 10))->format('Y-m-d')
            ]);
        }
    }
}