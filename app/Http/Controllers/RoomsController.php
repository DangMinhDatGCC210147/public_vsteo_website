<?php

namespace App\Http\Controllers;

use App\Exports\RoomTestResultsExport;
use App\Imports\StudentsRoomImport;
use App\Models\ExamRooms;
use App\Models\Question;
use App\Models\StudentExamRoom;
use App\Models\StudentResponses;
use App\Models\TestSkill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Support\Str;

class RoomsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = ExamRooms::withCount('students')->get();
        return view('admin.room.tableRoom', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.room.createRoom');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:0|max:25',
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            'end_time' => 'required|date_format:Y-m-d\TH:i|after:start_time',
        ]);

        $room = new ExamRooms();
        $room->room_name = $request->room_name;
        $room->capacity = $request->capacity;
        $room->start_time = $request->start_time;
        $room->end_time = $request->end_time;
        $room->save();

        return redirect()->route('room.index')->with('success', 'Room created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExamRooms $id)
    {
        $room = $id;
        // Pass the user data to the view for editing
        return view('admin.room.createRoom', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'room_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:0|max:25',
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            'end_time' => 'required|date_format:Y-m-d\TH:i|after:start_time',
        ]);

        $examRoom = ExamRooms::findOrFail($id);
        $examRoom->update([
            'room_name' => $request->room_name,
            'capacity' => $request->capacity,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('room.index')->with('success', 'Room updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamRooms $id)
    {
        // Delete the user
        $id->delete();
        // Redirect back with a success message
        return back()->with('success', 'Room deleted successfully');
    }

    public function addStudentForm($id)
    {
        $room = ExamRooms::findOrFail($id);
        $students = User::all();
        $studentsInRoom = $room->students;

        return view('admin.room.addStudentIntoRoom', compact('room', 'students', 'studentsInRoom'));
    }

    public function addStudent(Request $request, $id)
    {
        $request->validate([
            'account_id' => 'required|string',
        ]);

        $student = User::where('account_id', $request->account_id)->first();

        if (!$student) {
            return redirect()->route('room.addStudentForm', ['id' => $id])->with('error', 'Student not found');
        }

        if (!$student->is_active) {
            return redirect()->route('room.addStudentForm', ['id' => $id])->with('error', 'Student is not active');
        }

        $room = ExamRooms::findOrFail($id);

        // Kiểm tra xem phòng thi đã đầy chưa
        if ($room->students()->count() >= $room->capacity) {
            return redirect()->route('room.addStudentForm', ['id' => $id])->with('error', 'Room capacity exceeded');
        }

        // Kiểm tra xem sinh viên đã có trong phòng thi chưa
        if (!$room->students()->where('user_id', $student->id)->exists()) {
            $room->students()->attach($student->id, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return redirect()->route('room.addStudentForm', ['id' => $id])->with('success', 'Student added successfully');
        } else {
            return redirect()->route('room.addStudentForm', ['id' => $id])->with('error', 'Student already exists in the room');
        }
    }

    public function importStudents(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        $room = ExamRooms::findOrFail($id);

        if ($room->students()->count() >= $room->capacity) {
            return redirect()->route('room.addStudentForm', ['id' => $id])->with('error', 'Room capacity exceeded');
        }

        $import = new StudentsRoomImport($room->id);
        Excel::import($import, $request->file('file'));

        if (!empty($import->errors)) {
            return redirect()->route('room.addStudentForm', ['id' => $id])
                ->with('errors', $import->errors)
                ->with('success', 'Students imported successfully with some errors.');
        }

        // Kiểm tra lại sau khi import
        if ($room->students()->count() > $room->capacity) {
            // Xử lý trường hợp quá tải
            return redirect()->route('room.addStudentForm', ['id' => $id])->with('error', 'Room capacity exceeded after import');
        }

        return redirect()->route('room.addStudentForm', ['id' => $id])->with('success', 'Students imported successfully');
    }

    public function removeStudent($room_id, $student_id)
    {
        $room = ExamRooms::findOrFail($room_id);
        $student = User::findOrFail($student_id);

        $room->students()->detach($student_id);

        return response()->json(['success' => true]);
    }

    public function downloadRoomFiles($id)
    {
        // Log::info('Starting downloadRoomFiles', ['id' => $id]);

        $room = ExamRooms::findOrFail($id);
        $students = $room->students;

        Log::info('Retrieved room and students', ['room' => $room, 'students' => $students]);

        $speakingSkillIds = TestSkill::where('skill_name', 'Speaking')->pluck('id');
        $writingSkillIds = TestSkill::where('skill_name', 'Writing')->pluck('id');

        // Log::info('Retrieved skill IDs', ['speakingSkillIds' => $speakingSkillIds, 'writingSkillIds' => $writingSkillIds]);

        if ($speakingSkillIds->isEmpty() || $writingSkillIds->isEmpty()) {
            return redirect()->back()->with('error', 'Skill IDs for Speaking or Writing not found.');
        }

        $responses = StudentResponses::with('test', 'question', 'readingAudio')
            ->whereIn('student_id', $students->pluck('id'))
            ->whereIn('skill_id', $speakingSkillIds->merge($writingSkillIds)->toArray())
            ->whereBetween('created_at', [$room->start_time, $room->end_time])
            ->get();

        // Log::info('Retrieved responses', ['responses' => $responses]);

        if ($responses->isEmpty()) {
            return redirect()->back()->with('error', 'Responses not found.');
        }

        $baseFolderPath = storage_path('app/public/responses');
        if (!file_exists($baseFolderPath)) {
            mkdir($baseFolderPath, 0777, true);
        }

        // Tạo mảng bộ đếm cho các phản hồi viết
        $writingCounters = [];

        // Tạo một mảng để lưu các phần của Speaking
        $speakingParts = [];

        foreach ($responses as $response) {
            $student = User::find($response->student_id);
            if (!$student) continue;

            // Log::info('Processing response', ['response' => $response]);

            $testName = $response->test ? $response->test->test_name : 'default_test_name';
            $responsesFolderPath = $baseFolderPath . '/' . $student->account_id . '_' . $student->slug . '_' . $testName;
            if (!file_exists($responsesFolderPath)) {
                mkdir($responsesFolderPath, 0777, true);
                mkdir($responsesFolderPath . '/speaking', 0777, true);
                mkdir($responsesFolderPath . '/writing', 0777, true);
            }

            if ($speakingSkillIds->contains($response->skill_id)) {
                // Log::info('Processing speaking response', ['response' => $response]);

                // Lưu file mp3 vào thư mục speaking
                $part = $response->question->part_name ?? null;
                if ($part) {
                    // Normalize the part name for the filename
                    $normalizedPartName = str_replace(' ', '_', $part); // Replace spaces with underscores if needed
                    $filePath = str_replace('\\', '/', public_path('storage/' . $response->text_response));
                    if (file_exists($filePath)) {
                        $destinationPath = $responsesFolderPath . '/speaking/' . $normalizedPartName . '.mp3'; // assuming the file is an mp3, adjust accordingly
                        copy($filePath, $destinationPath);
                    }
                }

                // Phân loại các phần của Speaking
                $part = $response->question->part_name ?? null;
                $questionText = $response->question ? $response->question->question_text : 'No question text available';
                $questionId = $response->question ? $response->question->id : null;

                // Log::info('Part and question text', ['part' => $part, 'questionText' => $questionText]);

                if ($part) {
                    // Initialize array for the part if not already done
                    if (!isset($speakingParts[$part])) {
                        $speakingParts[$part] = [];
                    }

                    // Add questions based on unique identifiers
                    $speakingParts[$part][$questionId] = [
                        'questionText' => $questionText,
                        'options' => $response->question->options ?? [],
                        'readingAudio' => $response->readingAudio->reading_audio_file ?? null
                    ];

                    // Log::info('Added question to speaking parts', [
                    //     'part' => $part,
                    //     'questionId' => $questionId,
                    //     'speakingParts' => $speakingParts[$part][$questionId]
                    // ]);
                }
            } elseif ($writingSkillIds->contains($response->skill_id)) {
                Log::info('Processing writing response', ['response' => $response]);

                // Khởi tạo bộ đếm nếu chưa tồn tại
                if (!isset($writingCounters[$response->student_id])) {
                    $writingCounters[$response->student_id] = 0;
                }

                // Tăng bộ đếm lên 1
                $writingCounters[$response->student_id]++;

                $phpWord = new PhpWord();
                $section = $phpWord->addSection();

                $questionText = $response->question ? $response->question->question_text : 'No question text available';
                $section->addText("Question: " . $questionText);

                if ($response->readingAudio) {
                    $readingAudioText = $response->readingAudio->reading_audio_file;

                    // Strip problematic tags or entities
                    $cleanedHtml = preg_replace('/<br[^>]*>/', '<br/>', $readingAudioText);
                    $cleanedHtml = preg_replace('/<p[^>]*>/', '<p>', $cleanedHtml);

                    // Add the cleaned HTML to the section
                    Html::addHtml($section, $cleanedHtml);
                }

                $section->addTextBreak(1); // Thêm một khoảng trống giữa question và response
                $section->addText("Response:");
                $textResponses = explode("\n", $response->text_response);
                foreach ($textResponses as $line) {
                    $section->addText($line);
                }

                // Đặt tên file theo bộ đếm
                $partNumber = $writingCounters[$response->student_id];
                $docxFilePath = $responsesFolderPath . "/writing/writing_response_part_$partNumber.docx";
                $writer = IOFactory::createWriter($phpWord, 'Word2007');
                $writer->save($docxFilePath);

                // Log::info('Saved writing response', ['docxFilePath' => $docxFilePath]);
            }
        }

        // // Xử lý các phần của Speaking và ghi vào tệp DOCX
        // foreach ($speakingParts as $part => $questions) {
        //     // Đảm bảo khởi tạo mới PhpWord mỗi lần lặp
        //     $phpWord = new \PhpOffice\PhpWord\PhpWord();
        //     $section = $phpWord->addSection();

        //     $docxFilePath = $responsesFolderPath . "/speaking/speaking_parts_$part.docx"; // Tạo file riêng cho mỗi part

        //     // Ghi nhật ký các phần của Speaking
        //     Log::info('Speaking part: ' . json_encode($speakingParts[$part]));

        //     // Định dạng lại tên phần để loại bỏ dấu gạch dưới
        //     $formattedPart = str_replace('_', ' ', $part);
        //     $section->addText("$formattedPart: ");

        //     foreach ($questions as $questionData) {
        //         $section->addText("Topic: " . $questionData['questionText']);
        //         foreach ($questionData['options'] as $optionIndex => $option) {
        //             $section->addText("Question " . ($optionIndex + 1) . ": " . $option->option_text);
        //         }

        //         if ($part == 'Part_3' && $questionData['readingAudio']) {
        //             $readingAudioPath = str_replace('\\', '/', public_path('storage/' . $questionData['readingAudio']));
        //             if (file_exists($readingAudioPath)) {
        //                 $section->addImage($readingAudioPath, ['width' => 450, 'height' => 200, 'align' => 'center']);
        //             }
        //         }

        //         $section->addTextBreak(1); // Thêm một khoảng trống giữa các câu hỏi
        //     }

        //     // Lưu lại file DOCX
        //     try {
        //         $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        //         $writer->save($docxFilePath);
        //         Log::info('Saved DOCX file for speaking part', ['docxFilePath' => $docxFilePath]);
        //     } catch (\Exception $e) {
        //         Log::error('Failed to save DOCX file', ['message' => $e->getMessage(), 'filePath' => $docxFilePath]);
        //         return redirect()->back()->with('error', 'Failed to save the word file.');
        //     }
        // }

        $zipFileName = Str::slug($room->room_name) . '.zip';
        $zipFilePath = storage_path('app/public/' . $zipFileName);
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseFolderPath));
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($baseFolderPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();

            // Xóa thư mục responses sau khi đã nén xong
            $this->deleteDirectory($baseFolderPath);

            Log::info('Created ZIP file', ['zipFilePath' => $zipFilePath]);

            // Kiểm tra sự tồn tại của file zip trước khi download
            if (file_exists($zipFilePath)) {
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
            } else {
                return redirect()->back()->with('error', 'Zip file does not exist.');
            }
        } else {
            return redirect()->back()->with('error', 'Could not create zip file.');
        }
    }

    private function fixSpecialCharactersInDocx($docxFilePath)
    {
        $zip = new ZipArchive();
        if ($zip->open($docxFilePath) === TRUE) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                if (strpos($filename, 'word/') !== false && strpos($filename, '.xml') !== false) {
                    $content = $zip->getFromName($filename);
                    // Replace more special characters if necessary
                    $content = str_replace(['&nbsp;', '&amp;', '&lt;', '&gt;'], [' ', '&', '<', '>'], $content);
                    $zip->addFromString($filename, $content);
                }
            }
            $zip->close();
        }
    }

    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    public function exportRoomTestResults($roomId)
    {
        $roomName = ExamRooms::where('id', $roomId)->value('room_name');
        return Excel::download(new RoomTestResultsExport($roomId), $roomName . '.xlsx');
    }
}
