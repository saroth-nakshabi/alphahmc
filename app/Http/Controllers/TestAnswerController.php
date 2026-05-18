<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TestAnswer;
use App\Models\TestQuestion;
use Illuminate\Http\Request;

class TestAnswerController extends Controller
{
    public function index()
    {
        $data = [];
        $data['test_answers'] = TestAnswer::all();
        $data['questions'] = TestQuestion::all();
        return view('dashboard.test_answers.index', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'question_id' => 'required',
            'answer' => 'required|max:255',
        ]);

        $item = TestAnswer::create([
            'test_question_id' => $request->input('question_id'),
            'answer' => $request->input('answer'),
            'is_correct' => $request->input('is_correct') ?? false,
        ]);

        $added_item = TestAnswer::with('test_question')->findOrFail($item->id);
        // Get existing fields
        $attributes = $added_item->attributesToArray();

        // Add additional field
        $attributes['question'] = $added_item->test_question?->question ?? null;

        return response()->json([
            'success' => true,
            'message' => 'Created successfully!',
            'data' => $attributes,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question_id' => 'required',
            'answer' => 'required|max:255',
        ]);

        $item = TestAnswer::findOrFail($id);
        $item->update([
            'question_id' => $request->input('question_id'),
            'answer' => $request->input('answer'),
            'is_correct' => $request->input('is_correct') ?? false,
        ]);


        // Get updated fields
        $attributes = TestAnswer::with('test_question')->find($id)->attributesToArray();

        // Add additional field
        $attributes['question'] = $item->test_question?->question ?? null;

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $attributes,
        ], 201);
    }

    public function destroy($id)
    {
        $item = TestAnswer::findOrFail($id);

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully!',
        ], 201);
    }

    public function getTestAnswer(Request $request)
    {
        $id = $request->input('id');
        $item = TestAnswer::with('test_question')->find($id);

        // Check if the item was found
        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found!',
            ], 404);
        }

        // Get existing fields
        $attributes = $item->attributesToArray();

        // Add additional field
        $attributes['question'] = $item->question?->name ?? null;

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $attributes,
        ], 200);
    }
}