<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\TestQuestion;
use Illuminate\Http\Request;

class TestQuestionController extends Controller
{
    public function index()
    {
        $data = [];
        $data['test_questions'] = TestQuestion::all();
        $data['services'] = Service::all();
        return view('dashboard.test_questions.index', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'service_id' => 'required',
            'question' => 'required|max:255',
            'answers' => 'required|array',
            'answers.*.answer' => 'required|max:255',
            'answers.*.is_correct' => 'required',
        ]);

        $item = TestQuestion::create([
            'service_id' => $request->input('service_id'),
            'question' => $request->input('question'),
        ]);

        // Add answers
        foreach ($request->input('answers') as $answer) {
            $item->test_answers()->create([
                'answer' => $answer['answer'],
                'is_correct' => $answer['is_correct'] == 'true' ? 1 : 0,
            ]);
        }

        $added_item = TestQuestion::with('service')->findOrFail($item->id);
        // Get existing fields
        $attributes = $added_item->attributesToArray();

        // Add additional field
        $attributes['service'] = $added_item->service?->name ?? null;

        return response()->json([
            'success' => true,
            'message' => 'Created successfully!',
            'data' => $attributes,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'service_id' => 'required',
            'question' => 'required|max:255',
        ]);

        $item = TestQuestion::findOrFail($id);
        $item->update([
            'service_id' => $request->input('service_id'),
            'question' => $request->input('question'),
        ]);


        // Get updated fields
        $attributes = TestQuestion::with('service')->find($id)->attributesToArray();

        // Add additional field
        $attributes['service'] = $item->service?->name ?? null;

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $attributes,
        ], 201);
    }

    public function destroy($id)
    {
        $item = TestQuestion::findOrFail($id);

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully!',
        ], 201);
    }

    public function getTestQuestion(Request $request)
    {
        $id = $request->input('id');
        $item = TestQuestion::with('service')->find($id);

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
        $attributes['service'] = $item->service?->name ?? null;

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $attributes,
        ], 200);
    }
}
