<?php

namespace App\Http\Controllers;

use App\Models\ListeningResponses;
use App\Models\SpeakingResponse;
use App\Models\ReadingResponses;
use App\Models\StudentResponses;
use App\Models\WritingResponse;
use FFMpeg\FFMpeg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentSubmissionController extends Controller
{
    public function saveListening(Request $request)
    {
        // Log::info('Received data for Listening:', $request->all());

        $validated = $request->validate([
            'test_id' => 'required|integer',
            'skill_id' => 'required|integer',
            'responses' => 'required|array',
            'responses.*' => 'nullable|string|max:255',
        ]);

        $studentId = auth()->id();
        foreach ($request->responses as $questionId => $response) {
            if (!empty($response)) {
                StudentResponses::updateOrCreate(
                    [
                        'test_id' => $request->test_id,
                        'skill_id' => $request->skill_id,
                        'student_id' => $studentId,
                        'question_id' => $questionId
                    ],
                    ['text_response' => $response]
                );
            }
        }

        return response()->json(['success' => true, 'message' => 'The listening skill answer has been saved successfully.']);
    }

    public function saveReading(Request $request)
    {
        // Log::info('Received data for Reading:', $request->all());

        $validated = $request->validate([
            'test_id' => 'required|integer',
            'skill_id' => 'required|integer',
            'responses' => 'required|array',
            'responses.*' => 'nullable|string',
        ]);

        $studentId = auth()->id();
        foreach ($request->responses as $questionId => $response) {
            if (!empty($response)) {
                StudentResponses::updateOrCreate(
                    [
                        'test_id' => $request->test_id,
                        'skill_id' => $request->skill_id,
                        'student_id' => $studentId,
                        'question_id' => $questionId
                    ],
                    ['text_response' => $response]
                );
            }
        }

        return response()->json(['success' => true, 'message' => 'The reading skill answer has been saved successfully.']);
    }
    public function saveWriting(Request $request)
    {
        // Log::info('Received data for Writing:', $request->all());

        $validated = $request->validate([
            'test_id' => 'required|integer',
            'skill_id' => 'required|integer',
            'responses' => 'required|array',
            'responses.*' => 'nullable|string',
        ]);

        $studentId = auth()->id();
        foreach ($request->responses as $questionId => $response) {
            if (!empty($response)) {
                StudentResponses::updateOrCreate(
                    [
                        'test_id' => $request->test_id,
                        'skill_id' => $request->skill_id,
                        'student_id' => $studentId,
                        'question_id' => $questionId
                    ],
                    ['text_response' => $response]
                );
            }
        }

        return response()->json(['success' => true, 'message' => 'The writing skill answer has been saved successfully.']);
    }


    public function saveAnswer(Request $request)
    {
        $validatedData = $request->validate([
            'test_id' => 'required|integer',
            'skill_id' => 'required|integer',
            'question_id' => 'required|integer',
            'option_id' => 'required|integer',
        ]);

        $userAnswer = StudentResponses::updateOrCreate(
            [
                'test_id' => $validatedData['test_id'],
                'student_id' => auth()->id(),
                'skill_id' => $validatedData['skill_id'],
                'question_id' => $validatedData['question_id'],
            ],
            [
                'text_response' => $validatedData['option_id'],
            ]
        );

        return response()->json(['message' => 'Answer saved successfully'], 200);
    }

    public function saveRecording(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'test_id' => 'required|integer',
            'recording' => 'required|file|mimes:mp3,webm,ogg,wav,weba',
            'skill_id' => 'required|integer',
            'question_id' => 'required|integer'
        ]);

        // Fetch the authenticated user
        $user = auth()->user();
        $accountId = $user->account_id;

        // Initialize part_number if it does not exist in the session
        if (!session()->has('part_number')) {
            session(['part_number' => 0]);
        }

        // Increment part_number
        $partNumber = session('part_number') + 1;

        // Cycle part_number back to 1 if it exceeds 3
        if ($partNumber > 3) {
            $partNumber = 1;
        }

        // Save the updated part_number back to the session
        session(['part_number' => $partNumber]);

        // Construct a unique file name
        $currentTimeFormatted = date('n_j_Y', time());
        $fileName = $accountId . '_Part_' . $partNumber . '_' . $currentTimeFormatted . '_' . time() . '.mp3';

        // Store the file in a dedicated directory
        $path = $request->file('recording')->storeAs('studentResponse', $fileName, 'public');

        // Save or update the response in the database
        $studentId = $user->id; // Use the authenticated user's ID
        $response = StudentResponses::updateOrCreate(
            [
                'test_id' => $validated['test_id'],
                'skill_id' => $validated['skill_id'],
                'student_id' => $studentId,
                'question_id' => $validated['question_id']
            ],
            ['text_response' => $path]
        );

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Recording saved successfully.',
            'path' => $path
        ]);
    }
}
