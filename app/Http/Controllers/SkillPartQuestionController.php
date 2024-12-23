<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Models\ReadingsAudio;
use App\Models\TestSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SkillPartQuestionController extends Controller
{

    /**LIST FUNCTIONS IN SKILLPARTQUESTIONCONTROLLER
     * 1.create()
     * 2.store(Request $request)
     * showSpeakingPart($skillName, $partName)
     * showReadingPart($skillName, $partName)
     * showWritingPart($skillName, $partName)
     * showListeningPart($skillName, $partName)
     *
     * storeQuestionWriting(Request $request)
     * storeQuestionReading(Request $request)
     * storeQuestionListening(Request $request)
     * storeQuestionSpeaking(Request $request)
     *
     * editQuestionReading($test_slug, $part_name)
     * editQuestionListening($test_slug, $part_name)
     * editQuestionWriting($test_slug, $part_name)
     * editQuestionSpeaking($test_slug, $part_name)
     *
     * updateQuestionReading(Request $request)
     * updateQuestionListening(Request $request)
     * updateQuestionWriting(Request $request)
     * updateQuestionSpeaking(Request $request)
     *
     * destroy(TestSkill $testSkillSlug)
    ================================================*/

    public function create()
    {
        return view('admin.createSkillPart');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'skillName' => 'required|string',
            'partName' => 'required|string',
        ]);

        return redirect()->route('show' . $validatedData['skillName'] . 'Part', ['skillName' => $validatedData['skillName'], 'partName' => $validatedData['partName']]);
    }

    public function showSpeakingPart($skillName, $partName)
    {
        return view('admin.skill_part_question.partForSpeaking', compact('skillName', 'partName'));
    }

    public function showReadingPart($skillName, $partName)
    {
        return view('admin.skill_part_question.partForReading', compact('skillName', 'partName'));
    }

    public function showWritingPart($skillName, $partName)
    {
        return view('admin.skill_part_question.partForWriting', compact('skillName', 'partName'));
    }

    public function showListeningPart($skillName, $partName)
    {
        return view('admin.skill_part_question.partForListening', compact('skillName', 'partName'));
    }

    public function storeQuestionWriting(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'partName' => 'required|string',
            'skillName' => 'required|string',
            'question' => 'required|string',
            'passage' => 'required|string',
        ]);

        $testSkill = new TestSkill();
        $testSkill->skill_name = $validated['skillName'];
        $testSkill->time_limit = '01:00:00';
        $testSkill->part_name = $validated['partName'];
        $testSkill->save();

        $readingAudio = new ReadingsAudio();
        $readingAudio->test_skill_id = $testSkill->id;
        $readingAudio->reading_audio_file = $request->passage;
        $readingAudio->part_name = $validated['partName'];
        $readingAudio->save();

        $question = new Question();
        $question->test_skill_id = $testSkill->id;
        $question->reading_audio_id = $readingAudio->id;
        if ($validated['partName'] == 'Part_1') {
            $question->question_number = 1;
        } else {
            $question->question_number = 2;
        }
        $question->part_name = $validated['partName'];
        $question->question_text = $request->question;
        $question->question_type = 'Text Writing';
        $question->save();

        return redirect()->route('create.skill.part')->with('success', 'Writing Part saved successfully!');
    }

    public function editQuestionWriting($test_slug, $part_name)
    {
        $writing = TestSkill::where('slug', $test_slug)->firstOrFail();

        // Fetch the Questions associated with the reading_audio_id
        $questions = Question::where('test_skill_id', $writing->id)
            ->firstOrFail();
        // Fetch the ReadingAudio record for the specified part_name and test_skill_id
        $readingAudio = ReadingsAudio::where('test_skill_id', $writing->id)
            ->where('part_name', $part_name)
            ->firstOrFail();

        return view('admin.skill_part_question.partForWriting', [
            'slug' => $writing,
            'skillName' => $writing->skill_name,
            'partName' => $part_name,
            'passage' => $readingAudio,
            'questions' => $questions,
            'writing' => $writing
        ]);
    }

    public function updateQuestionWriting(Request $request)
    {
        try {
            $validated = $request->validate([
                'slug' => 'required|integer',
                'partName' => 'required|string',
                'skillName' => 'required|string',
                'question' => 'required|string',
                'passage' => 'required|string',
            ]);

            // Fetch the existing TestSkill
            $testSkill = TestSkill::findOrFail($validated['slug']);

            // Fetch the existing ReadingsAudio for the specific partName
            $readingAudio = ReadingsAudio::where('test_skill_id', $testSkill->id)
                ->where('part_name', $validated['partName'])
                ->firstOrFail();

            // Update the passage
            $readingAudio->reading_audio_file = $validated['passage'];
            $readingAudio->save();

            // Fetch the existing question for the specific partName
            $question = Question::where('test_skill_id', $testSkill->id)
                ->where('reading_audio_id', $readingAudio->id)
                ->firstOrFail();

            // Update the question text
            $question->question_text = $validated['question'];
            $question->save();

            return redirect()->route('questionBank.writing')->with('success', 'Writing Part updated successfully!');
        } catch (\Exception $e) {
            // Log the error
            logger()->error('Error updating writing part:', ['exception' => $e]);

            return redirect()->back()->with('error', 'An error occurred while updating the writing part.');
        }
    }

    public function storeQuestionReading(Request $request)
    {

        $validated = $request->validate([
            'partName' => 'required|string',
            'skillName' => 'required|string',
            'passage' => 'required|string',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'required|array',
            'questions.*.correct_option' => 'required|in:1,2,3,4',
        ]);

        $testSkill = new TestSkill();
        $testSkill->skill_name = $validated['skillName'];
        $testSkill->time_limit = '01:00:00';
        $testSkill->part_name = $validated['partName'];
        $testSkill->save();

        $readingAudio = new ReadingsAudio();
        $readingAudio->test_skill_id = $testSkill->id;
        $readingAudio->reading_audio_file = $validated['passage'];
        $readingAudio->part_name = $validated['partName'];
        $readingAudio->save();

        foreach ($request->questions as $index => $questionData) {
            $question = new Question();
            $question->test_skill_id = $testSkill->id;
            $question->reading_audio_id = $readingAudio->id;
            $question->question_number = $index;
            $question->part_name = $validated['partName'];
            $question->question_text = $questionData['text'];
            $question->question_type = 'Multiple Choice Reading';

            $question->save();

            foreach ($questionData['options'] as $optionIndex => $optionText) {
                $option = new Option();
                $option->question_id = $question->id;
                $option->option_text = $optionText;
                $option->correct_answer = ($questionData['correct_option'] == $optionIndex); // Check if this option is the correct one
                $option->save();
            }
        }

        return redirect()->route('create.skill.part')->with('success', 'Reading Part saved successfully!');
    }

    public function editQuestionReading($test_slug, $part_name)
    {
        $reading = TestSkill::where('slug', $test_slug)->firstOrFail();

        // Fetch the Questions associated with the reading_audio_id
        $questions = Question::where('test_skill_id', $reading->id)
            ->get();
        // Fetch the ReadingAudio record for the specified part_name and test_skill_id
        $readingAudio = ReadingsAudio::where('test_skill_id', $reading->id)
            ->where('part_name', $part_name)
            ->firstOrFail();

        // Fetch the Options for each Question
        foreach ($questions as $question) {
            $question->options = Option::where('question_id', $question->id)->get();
        }

        return view('admin.skill_part_question.partForReading', [
            'slug' => $reading,
            'skillName' => $reading->skill_name,
            'partName' => $part_name,
            'passage' => $readingAudio,
            'questions' => $questions,
            'reading' => $reading
        ]);
    }

    public function updateQuestionReading(Request $request)
    {
        // Log the entire request data
        // logger()->info('Request data:', $request->all());

        try {
            $validated = $request->validate([
                'testSkillId' => 'required',
                'slug' => 'required',
                'partName' => 'required|string',
                'skillName' => 'required|string',
                'passage' => 'required|string',
                'questions' => 'required|array',
                'questions.*.id' => 'required|integer',
                'questions.*.text' => 'required|string',
                'questions.*.options' => 'required|array',
                'questions.*.options.*.id' => 'required|integer',
                'questions.*.options.*.text' => 'required|string',
                'questions.*.correct_option' => 'required|integer',
            ]);

            // Log validated data
            // logger()->info('Validated data:', $validated);

            $slugId = $validated['slug'];
            $readingAudioId = $validated['testSkillId'];
            // Fetch the existing TestSkill
            $testSkill = TestSkill::where('id', $slugId)->first();
            if (!$testSkill) {
                // logger()->error('TestSkill not found', ['id' => $slugId]);
                return redirect()->back()->with('error', 'TestSkill not found.');
            }
            // logger()->info('Fetched TestSkill:', ['id' => $testSkill->id]);

            // Fetch the existing ReadingsAudio
            $readingAudio = ReadingsAudio::where('test_skill_id', $testSkill->id)
                ->where('id', $readingAudioId)
                ->first();
            if (!$readingAudio) {
                // logger()->error('ReadingsAudio not found', ['test_skill_id' => $testSkill->id, 'id' => $slugId]);
                return redirect()->back()->with('error', 'ReadingsAudio not found.');
            }
            $readingAudio->reading_audio_file = $validated['passage'];
            $readingAudio->save();

            // Log after saving ReadingsAudio
            // logger()->info('ReadingsAudio updated:', ['id' => $readingAudio->id]);

            // Loop through the questions to update
            foreach ($validated['questions'] as $questionData) {
                // Log question data before processing
                // logger()->info('Processing question:', $questionData);

                // Fetch the existing question by ID
                $question = Question::findOrFail($questionData['id']);
                $question->question_text = $questionData['text'];
                $question->save();

                // Log after updating question
                // logger()->info('Question updated:', ['id' => $question->id]);

                // Loop through the options to update
                foreach ($questionData['options'] as $optionData) {
                    // Log option data before processing
                    // logger()->info('Processing option:', $optionData);

                    // Fetch the existing option by ID
                    $option = Option::findOrFail($optionData['id']);
                    $option->option_text = $optionData['text'];
                    $option->correct_answer = ($questionData['correct_option'] == $option->id) ? 1 : 0; // Check if this option is the correct one
                    $option->save();

                    // Log after updating option
                    // logger()->info('Option updated:', ['id' => $option->id]);
                }
            }

            return redirect()->route('questionBank.reading')->with('success', 'Reading Part updated successfully!');
        } catch (\Exception $e) {
            // Log the error
            // logger()->error('Error updating reading part:', ['exception' => $e]);

            return redirect()->back()->with('error', 'An error occurred while updating the reading part.');
        }
    }


    public function storeQuestionListening(Request $request)
    {
        $validated = $request->validate([
            'partName' => 'required|string',
            'skillName' => 'required|string',
            'audioFile' => 'required|file|mimes:audio/mpeg,mpga,mp3,wav',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'required|array',
            'questions.*.correct_option' => 'required|in:1,2,3,4',
        ]);

        $testSkill = new TestSkill();
        $testSkill->skill_name = $validated['skillName'];
        $testSkill->time_limit = '00:47:00';
        $testSkill->part_name = $validated['partName'];
        $testSkill->save();

        $audioPath = $request->file('audioFile')->store('audios', 'public');

        $readingAudio = new ReadingsAudio();
        $readingAudio->test_skill_id = $testSkill->id;
        $readingAudio->reading_audio_file = $audioPath;
        $readingAudio->part_name = $validated['partName'];
        $readingAudio->save();

        // Process each question
        foreach ($validated['questions'] as $questionIndex => $questionData) {
            // Save the question to the database
            $question = new Question();
            $question->test_skill_id = $testSkill->id;
            $question->reading_audio_id = $readingAudio->id;
            $question->question_number = $questionIndex;
            $question->part_name = $validated['partName'];
            $question->question_text = $questionData['text'];
            $question->question_type = 'Multiple Choice Listening';
            $question->save();

            // Save the options
            foreach ($questionData['options'] as $optionIndex => $optionText) {
                $option = new Option();
                $option->question_id = $question->id;
                $option->option_text = $optionText;
                // $option->correct = $optionIndex == $questionData['correct_option'];
                $option->correct_answer = ($questionData['correct_option'] == $optionIndex);
                $option->save();
            }
        }

        return redirect()->route('create.skill.part')->with('success', 'Listening Part saved successfully!');
    }

    public function editQuestionListening($test_slug, $part_name)
    {
        $listening = TestSkill::where('slug', $test_slug)->firstOrFail();

        // Fetch the Questions associated with the reading_audio_id
        $questions = Question::where('test_skill_id', $listening->id)
            ->get();
        // Fetch the ReadingAudio record for the specified part_name and test_skill_id
        $readingAudio = ReadingsAudio::where('test_skill_id', $listening->id)
            ->where('part_name', $part_name)
            ->firstOrFail();

        // Fetch the Options for each Question
        foreach ($questions as $question) {
            $question->options = Option::where('question_id', $question->id)->get();
        }

        return view('admin.skill_part_question.partForListening', [
            'slug' => $listening,
            'skillName' => $listening->skill_name,
            'partName' => $part_name,
            'passage' => $readingAudio,
            'questions' => $questions,
            'listening' => $listening
        ]);
    }

    public function updateQuestionListening(Request $request)
    {
        try {
            $validated = $request->validate([
                'slug' => 'required|integer',
                'partName' => 'required|string',
                'skillName' => 'required|string',
                'audioFile' => 'nullable|file|mimes:audio/mpeg,mpga,mp3,wav',
                'questions' => 'required|array',
                'questions.*.id' => 'required|integer',
                'questions.*.text' => 'required|string',
                'questions.*.options' => 'required|array',
                'questions.*.options.*.id' => 'required|integer',
                'questions.*.options.*.text' => 'required|string',
                'questions.*.correct_option' => 'required|integer',
            ]);

            $slugId = $validated['slug'];

            // Fetch the existing TestSkill
            $testSkill = TestSkill::findOrFail($slugId);

            // Fetch the existing ReadingsAudio
            $readingAudio = ReadingsAudio::where('test_skill_id', $testSkill->id)
                ->where('part_name', $validated['partName'])
                ->firstOrFail();

            // Update the audio file if a new file is uploaded
            if ($request->hasFile('audioFile')) {
                if (Storage::disk('public')->exists($readingAudio->reading_audio_file)) {
                    Storage::disk('public')->delete($readingAudio->reading_audio_file);
                }
                $audioPath = $request->file('audioFile')->store('audios', 'public');
                $readingAudio->reading_audio_file = $audioPath;
            }

            $readingAudio->save();

            // Loop through the questions to update
            foreach ($validated['questions'] as $questionData) {

                // Fetch the existing question by ID
                $question = Question::findOrFail($questionData['id']);
                $question->question_text = $questionData['text'];
                $question->save();

                // Loop through the options to update
                foreach ($questionData['options'] as $optionData) {

                    // Fetch the existing option by ID
                    $option = Option::findOrFail($optionData['id']);
                    $option->option_text = $optionData['text'];
                    $option->correct_answer = ($questionData['correct_option'] == $option->id) ? 1 : 0; // Check if this option is the correct one
                    $option->save();
                }
            }

            return redirect()->route('questionBank.listening')->with('success', 'Listening Part updated successfully!');
        } catch (\Exception $e) {
            // Log the error
            logger()->error('Error updating reading part:', ['exception' => $e]);

            return redirect()->back()->with('error', 'An error occurred while updating the listening part.');
        }
    }

    public function storeQuestionSpeaking(Request $request)
    {
        // dd($request->all());
        if ($request->partName == 'Part_1') {
            $validated = $request->validate([
                'partName' => 'required|string',
                'skillName' => 'required|string',
                'part1_question_1' => 'required|string',
                'part1_question_1_option_1' => 'required|string',
                'part1_question_1_option_2' => 'required|string',
                'part1_question_1_option_3' => 'required|string',
                'part1_question_2' => 'required|string',
                'part1_question_2_option_1' => 'required|string',
                'part1_question_2_option_2' => 'required|string',
                'part1_question_2_option_3' => 'required|string',
            ]);

            $testSkill = new TestSkill();
            $testSkill->skill_name = $validated['skillName'];
            $testSkill->time_limit = '00:12:30';
            $testSkill->part_name = $validated['partName'];
            $testSkill->save();

            // Save Part 1 data
            for ($i = 1; $i <= 2; $i++) {
                $questionText = $validated["part1_question_$i"];
                $question = new Question();
                $question->test_skill_id = $testSkill->id;
                $question->question_number = $i;
                $question->part_name = $validated['partName'];
                $question->question_text = $questionText;
                $question->question_type = 'Text Speaking';
                $question->save();

                for ($j = 1; $j <= 3; $j++) {
                    $optionText = $validated["part1_question_{$i}_option_{$j}"];
                    $option = new Option();
                    $option->question_id = $question->id;
                    $option->option_text = $optionText;
                    $option->save();
                }
            }
        } elseif ($request->partName == 'Part_2') {
            $validated = $request->validate([
                'partName' => 'required|string',
                'skillName' => 'required|string',
                'passage' => 'required|string',
            ]);

            $testSkill = new TestSkill();
            $testSkill->skill_name = $validated['skillName'];
            $testSkill->time_limit = '00:12:30';
            $testSkill->part_name = $validated['partName'];
            $testSkill->save();

            // Save Part 2 data
            $question = new Question();
            $question->test_skill_id = $testSkill->id;
            $question->question_text = $validated['passage'];
            $question->question_number = '1';
            $question->part_name = 'Part_2';
            $question->question_type = 'Text Speaking';
            $question->save();
        } else {
            // dd($request->all());
            $validated = $request->validate([
                'partName' => 'required|string',
                'skillName' => 'required|string',
                'part3_question' => 'required|string',
                'part3_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10048',
                'part3_option_1' => 'required|string',
                'part3_option_2' => 'required|string',
                'part3_option_3' => 'required|string',
            ]);

            $testSkill = new TestSkill();
            $testSkill->skill_name = $validated['skillName'];
            $testSkill->time_limit = '00:12:30';
            $testSkill->part_name = $validated['partName'];
            $testSkill->save();
            // Handle file upload
            if ($request->hasFile('part3_image')) {
                $imagePath = $request->file('part3_image')->store('images', 'public');

                $readingAudio = new ReadingsAudio();
                $readingAudio->test_skill_id = $testSkill->id;
                $readingAudio->reading_audio_file = $imagePath;
                $readingAudio->part_name = $validated['partName'];
                $readingAudio->save();
            }

            // Save Part 3 data
            $question = new Question();
            $question->test_skill_id = $testSkill->id;
            $question->question_text = $validated['part3_question'];
            $question->reading_audio_id = $readingAudio->id;
            $question->question_number = '1';
            $question->part_name = 'Part_3';
            $question->question_type = 'Text Speaking';
            $question->save();

            for ($k = 1; $k <= 3; $k++) {
                $optionText = $validated["part3_option_$k"];
                $option = new Option();
                $option->question_id = $question->id;
                $option->option_text = $optionText;
                $option->save();
            }
        }

        return redirect()->route('create.skill.part')->with('success', 'Speaking Part saved successfully!');
    }

    public function editQuestionSpeaking($test_slug, $part_name)
    {

        $speaking = TestSkill::where('slug', $test_slug)->firstOrFail();

        if ($part_name != 'Part_2') {
            $questions = Question::where('test_skill_id', $speaking->id)
                ->get();
            // Fetch the ReadingAudio record for the specified part_name and test_skill_id
            $readingAudio = ReadingsAudio::where('test_skill_id', $speaking->id)
                ->where('part_name', $part_name)
                ->first();
            // Fetch the Options for each Question
            foreach ($questions as $question) {
                $question->options = Option::where('question_id', $question->id)->get();
            }
            // dd($questions);
            return view('admin.skill_part_question.partForSpeaking', [
                'slug' => $speaking,
                'skillName' => $speaking->skill_name,
                'partName' => $part_name,
                'passage' => $readingAudio,
                'questions' => $questions,
                'speaking' => $speaking
            ]);
        } else {
            // Fetch the Questions associated with the reading_audio_id
            $questions = Question::where('test_skill_id', $speaking->id)
                ->first();

            return view('admin.skill_part_question.partForSpeaking', [
                'slug' => $speaking,
                'skillName' => $speaking->skill_name,
                'partName' => $part_name,
                'questions' => $questions,
                'speaking' => $speaking
            ]);
        }
    }

    public function updateQuestionSpeaking(Request $request)
    {
        // Log the entire request data
        // logger()->info('Request data:', $request->all());

        try {
            $validated = $request->validate([
                'slug' => 'required|integer',
                'partName' => 'required|string',
                'skillName' => 'required|string',
                'passage' => 'nullable|string',
                'part3_question' => 'nullable|string',
                'part3_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg',
                'questions' => 'nullable|array',
                'questions.*.id' => 'nullable|integer',
                'questions.*.text' => 'nullable|string',
                'questions.*.options' => 'nullable|array',
                'questions.*.options.*.id' => 'nullable|integer',
                'questions.*.options.*.text' => 'nullable|string',
                'part3_option_1' => 'nullable|string',
                'part3_option_2' => 'nullable|string',
                'part3_option_3' => 'nullable|string',
            ]);

            $slugId = $validated['slug'];
            $partName = $validated['partName'];

            // Fetch the existing TestSkill
            $testSkill = TestSkill::findOrFail($slugId);

            if ($partName == 'Part_2') {
                // Handle Part 2 updates
                $question = Question::where('test_skill_id', $testSkill->id)
                    ->where('part_name', $partName)
                    ->firstOrFail();
                $question->question_text = $validated['passage'];
                $question->save();

                // logger()->info('Question updated for Part 2:', ['id' => $question->id]);
            } elseif ($partName == 'Part_3') {
                // Handle Part 3 updates
                $readingAudio = ReadingsAudio::where('test_skill_id', $testSkill->id)
                    ->where('part_name', $partName)
                    ->firstOrFail();

                // Update the image file if a new file is uploaded
                if ($request->hasFile('part3_image')) {
                    $imagePath = $request->file('part3_image')->store('images', 'public');
                    // Optionally delete the old file
                    if ($readingAudio->reading_audio_file) {
                        Storage::disk('public')->delete($readingAudio->reading_audio_file);
                    }
                    $readingAudio->reading_audio_file = $imagePath;
                }

                $readingAudio->save();

                // logger()->info('ReadingsAudio updated:', ['id' => $readingAudio->id]);

                // Fetch the existing question
                $question = Question::where('test_skill_id', $testSkill->id)
                    ->where('reading_audio_id', $readingAudio->id)
                    ->firstOrFail();
                $question->question_text = $validated['part3_question'];
                $question->save();

                // Fetch and update the options
                $options = $question->options;
                foreach ($options as $index => $option) {
                    $optionField = 'part3_option_' . ($index + 1);
                    if ($request->has($optionField)) {
                        $option->option_text = $validated[$optionField];
                        $option->save();
                    }
                }
            } else {
                // Handle Part 1 updates
                foreach ($validated['questions'] as $questionData) {
                    // Log question data before processing
                    // logger()->info('Processing question:', $questionData);

                    // Fetch the existing question by ID
                    $question = Question::findOrFail($questionData['id']);
                    $question->question_text = $questionData['text'];
                    $question->save();

                    // logger()->info('Question updated:', ['id' => $question->id]);

                    // Loop through the options to update
                    foreach ($questionData['options'] as $optionData) {
                        // Log option data before processing
                        // logger()->info('Processing option:', $optionData);

                        // Fetch the existing option by ID
                        $option = Option::findOrFail($optionData['id']);
                        $option->option_text = $optionData['text'];
                        $option->save();

                        logger()->info('Option updated:', ['id' => $option->id]);
                    }
                }
            }

            return redirect()->route('questionBank.speaking')->with('success', 'Speaking Part updated successfully!');
        } catch (\Exception $e) {
            // Log the error
            logger()->error('Error updating speaking part:', ['exception' => $e]);

            return redirect()->back()->with('error', 'An error occurred while updating the speaking part.');
        }
    }

    public function destroy(TestSkill $testSkillSlug)
    {
        $testSkillSlug->delete();
        return redirect()->route('questionBank.index')->with('success', 'Test Skill deleted successfully');
    }
}
