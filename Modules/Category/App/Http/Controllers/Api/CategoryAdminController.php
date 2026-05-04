<?php

namespace Modules\Category\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Category\App\Http\Requests\CategoryRequest;
use Modules\Category\App\Models\Category;
use Modules\Category\App\resources\CategoryResource;
use Modules\Category\DTO\CategoryDto;
use Modules\Category\Service\CategoryService;

class CategoryAdminController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->middleware('auth:admin');
        $this->middleware('role:Super Admin|Restaurant Manager')->only(['index', 'subCategories']);
        $this->middleware('role:Super Admin')->except(['index', 'subCategories']);
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $relations = ['childrenRecursiveAll'];
        $categories = $this->categoryService->findAll($data, $relations);

        return returnMessage(true, 'Categories Fetched Successfully', CategoryResource::collection($categories)->response()->getData(true));
    }

    public function subCategories(Category $category)
    {
        $subCategories = $this->categoryService->findSubCategories($category);

        return returnMessage(true, 'Sub-Categories Fetched Successfully', CategoryResource::collection($subCategories)->response()->getData(true));
    }

    public function store(CategoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new CategoryDto($request))->dataFromRequest();
            $category = $this->categoryService->create($data);
            DB::commit();

            return returnMessage(true, 'Category Created Successfully', $category);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(CategoryRequest $request, Category $category)
    {
        try {
            DB::beginTransaction();
            $data = (new CategoryDto($request))->dataFromRequest();
            $category = $this->categoryService->update($category, $data);
            DB::commit();

            return returnMessage(true, 'Category Updated Successfully', $category);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function destroy(Category $category)
    {
        try {
            DB::beginTransaction();
            $this->categoryService->delete($category);
            DB::commit();

            return returnMessage(true, 'Category Deleted Successfully', null);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function toggleActivate(Request $request, Category $category)
    {
        try {
            DB::beginTransaction();
            $category = $this->categoryService->toggleActivate($category);
            DB::commit();

            return returnMessage(true, 'Category updated successfully', new CategoryResource($category));
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
