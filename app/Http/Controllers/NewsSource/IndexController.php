<?php

namespace App\Http\Controllers\NewsSource;

use App\Http\Controllers\Controller;
use App\Http\Filters\NewSourceFilter;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\NewsSourceResource;
use App\Models\Category;
use App\Models\NewsSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class IndexController extends Controller
{
    /**
     * Handle an incoming category index request.
     */
    public function __invoke(NewSourceFilter $filter) : AnonymousResourceCollection
    {
        return NewsSourceResource::collection(NewsSource::filter($filter)->simplePaginate());
    }
}
