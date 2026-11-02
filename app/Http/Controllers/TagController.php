<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => ['required', 'string', 'max:50'],
        ]);

        $tags = Tag::query()
            ->where('name', 'like', '%'.$validated['query'].'%')
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($tags);
    }
}
