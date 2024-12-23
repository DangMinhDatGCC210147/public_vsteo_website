<?php

namespace App\Http\Controllers;


use App\Models\Student;
use App\Models\Test;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class TestsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tests = Test::all();
        return view('admin.tableTest', compact('tests'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Test $test_slug)
    {
        // dd($test_slug);
        // Load the test with associated test skills and skill parts, including a count of questions for each part
        $test = $test_slug->load([
            'testSkills' => function ($query) {
                $query->withCount('questions') // This will add a `questions_count` attribute to each skill part
                    ->leftJoin('test_parts as tp', 'tp.test_skill_id', '=', 'test_skills.id')
                    ->orderByRaw("FIELD(test_skills.skill_name, 'Listening', 'Speaking', 'Reading', 'Writing')");
            }
        ]);

        return view('admin.testSkills', compact('test'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Test $test_slug)
    {
        DB::transaction(function () use ($test_slug) {
            // Lấy ID của Test
            $testId = $test_slug->id;
            // Xoá các record trong bảng students có test_id bằng với testId
            Student::where('test_id', $testId)->delete();
            // Xoá test
            $test_slug->delete();
        });

        // Chuyển hướng người dùng với thông báo thành công
        return redirect()->route('tableTest.index')->with('success', 'Test and related student records deleted successfully');
    }

    public function destroyAll()
    {
        DB::transaction(function () {
            // Xoá các students liên quan đến mỗi test
            $tests = Test::all();
            foreach ($tests as $test) {
                $test->students()->delete(); // Xoá các bản ghi trong students liên quan đến test
                $test->delete();
            }
        });

        return response()->json(['message' => 'All tests and related student records have been deleted successfully.']);
    }

    public function deleteMultipleTests(Request $request){
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Xóa các bài kiểm tra có ngày tạo trong khoảng start_date và end_date
        $deletedCount = Test::whereBetween('created_at', [$request->start_date, $request->end_date])->delete();

        // Redirect về một trang khác hoặc trả về thông báo thành công
        return back()->with('success', "Deleted $deletedCount tests successfully.");
    }
}
