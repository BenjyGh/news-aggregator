<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Filters\CategoryFilter;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
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
    public function __invoke(CategoryFilter $filter) : AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::filter($filter)->simplePaginate());
    }
}
