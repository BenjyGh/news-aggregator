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
     * All Categories
     *
     * This endpoint retrieves a list of all categories.
     *
     * @group Article
     * @subgroup General
     *
     * @queryParam include string Comma-separated list of relations to include. Example: articles
     * @queryParam filter[name] string Filter categories by their name. No-example
     * @queryParam sort string Comma-separated list of fields to sort by.
     * Use a minus (`-`) for descending order. Example: -name
     * @queryParam page integer The page number for paginated results. No-example
     */
    public function __invoke(CategoryFilter $filter): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::filter($filter)->simplePaginate());
    }
}
