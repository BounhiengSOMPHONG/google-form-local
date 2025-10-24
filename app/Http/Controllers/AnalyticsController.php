<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Question;
use App\Models\Response;
use App\Models\ResponseAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function showResults(Form $form)
    {
        $responses = $form->responses()->with(['responseAnswers.question'])->get();
        $totalResponses = $responses->count();
        
        $questionStats = [];
        
        // Exclude section-type questions from the results dashboard
        foreach ($form->questions->where('type', '!=', 'section') as $question) {
            $questionStats[$question->id] = $this->calculateQuestionStats($question, $responses);
        }
        
        return view('forms.results', compact('form', 'totalResponses', 'questionStats'));
    }

    private function calculateQuestionStats(Question $question, $responses)
    {
        $stats = [
            'question' => $question,
            'type' => $question->type,
            'total_answers' => 0,
            'answers' => [],
        ];

        // Normalize defined options into value => label so label|value works correctly
        if (in_array($question->type, ['radio', 'checkbox', 'dropdown']) && is_array($question->options)) {
            foreach ($question->options as $opt) {
                if (is_string($opt) && strpos($opt, '|') !== false) {
                    [$label, $value] = explode('|', $opt, 2);
                } else {
                    $label = $opt;
                    $value = $opt;
                }

                $stats['display_options'][$value] = $label;
                if (!isset($stats['answers'][$value])) {
                    $stats['answers'][$value] = 0;
                }
            }
        }

        foreach ($responses as $response) {
            foreach ($response->responseAnswers as $answer) {
                if ($answer->question_id == $question->id) {
                    $stats['total_answers']++;
                    
                    if ($question->type === 'checkbox') {
                        // For checkbox, answer is stored as JSON
                        $selectedOptions = json_decode($answer->answer, true) ?: [];

                        foreach ($selectedOptions as $option) {
                            if (!isset($stats['answers'][$option])) {
                                $stats['answers'][$option] = 0;
                            }
                            $stats['answers'][$option]++;
                        }
                    } else {
                        // For radio, dropdown, short_text, date
                        $val = $answer->answer;
                        if (!isset($stats['answers'][$val])) {
                            // If we have display_options mapping, ensure key exists
                            $stats['answers'][$val] = 0;
                        }
                        $stats['answers'][$val]++;
                    }
                }
            }
        }

        // Calculate percentages for each answer
        foreach ($stats['answers'] as $answer => $count) {
            $percentage = $stats['total_answers'] > 0 ? ($count / $stats['total_answers']) * 100 : 0;
            $stats['answers'][$answer] = [
                'count' => $count,
                'percentage' => round($percentage, 2)
            ];
        }

        return $stats;
    }

    public function exportCsv(Form $form)
    {
        $questions = $form->questions()->orderBy('position')->get();
        $responses = $form->responses()->with(['responseAnswers'])->get();

        // Create CSV content
        $csv = [];
        
        // Create header row
        $header = ['Response ID', 'Submitted At'];
        foreach ($questions as $question) {
            $header[] = $question->question_text;
        }
        $csv[] = $header;

        // Create data rows
        foreach ($responses as $response) {
            $row = [$response->id, $response->created_at->format('Y-m-d H:i:s')];
            
            foreach ($questions as $question) {
                $answerValue = '';
                
                foreach ($response->responseAnswers as $answer) {
                    if ($answer->question_id == $question->id) {
                        if ($question->type === 'checkbox') {
                            $selectedOptions = json_decode($answer->answer, true) ?: [];
                            $answerValue = implode(', ', $selectedOptions);
                        } else {
                            $answerValue = $answer->answer;
                        }
                        break;
                    }
                }
                
                $row[] = $answerValue;
            }
            
            $csv[] = $row;
        }

        // Convert to CSV string
        $output = fopen('php://temp', 'r+');
        foreach ($csv as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        // Return CSV response
        $filename = 'form_' . $form->id . '_responses_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}