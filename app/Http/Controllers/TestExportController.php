<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TestExportController extends Controller
{
    public function export($slug)
    {
        try {
            $test = Test::with(['testParts.questions.options', 'testParts.questions.readingsAudio'])
                        ->where('slug', $slug)
                        ->firstOrFail();

            $phpWord = new PhpWord();
            $section = $phpWord->addSection();

            $section->addText($test->test_name, ['size' => 16, 'bold' => true]);

            foreach ($test->testParts as $part) {
                $section->addText('Part: ' . $part->part_name, ['bold' => true]);

                foreach ($part->questions as $question) {
                    $section->addText('Question: ' . $question->question_text);

                    // Add options
                    foreach ($question->options as $option) {
                        $section->addText('Option: ' . $option->option_text . ($option->correct_answer ? ' (Correct)' : ''));
                    }

                    // Add readingsAudio if exists
                    if ($question->readingsAudio) {
                        $section->addText('Audio file: ' . $question->readingsAudio->reading_audio_file);
                    }

                    $section->addTextBreak(1);
                }
                $section->addTextBreak(1);
            }

            $filename = 'test-details.docx';
            $temp_file = tempnam(sys_get_temp_dir(), 'PHPWord');

            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($temp_file);

            Log::info('Word file created at: ' . $temp_file);

            if (file_exists($temp_file)) {
                return new StreamedResponse(function() use ($temp_file) {
                    readfile($temp_file);
                    unlink($temp_file); // xóa file sau khi gửi
                }, 200, [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ]);
            } else {
                throw new \Exception('File was not created');
            }
        } catch (\Exception $e) {
            Log::error('Error creating Word file: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
