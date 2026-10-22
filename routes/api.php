<?php

use App\Http\Controllers\AgentBlogController;
use Illuminate\Support\Facades\Route;

/*
| SEO agent blog API (brief alphahmc-blog-api.md, 6.1). This is the entire surface: read one post's
| state, create a draft, update a draft or stage a revision. There is deliberately no publish,
| delete, list or image route; only a person publishes, in /manage-blog.
*/
Route::middleware(['agent.token', 'throttle:agent-api'])->prefix('agent')->group(function () {
    Route::get('blogs/{slug}', [AgentBlogController::class, 'show']);
    Route::post('blogs', [AgentBlogController::class, 'store']);
    Route::put('blogs/{slug}', [AgentBlogController::class, 'update']);
});
