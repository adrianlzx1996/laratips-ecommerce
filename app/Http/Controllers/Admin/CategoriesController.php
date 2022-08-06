<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreCategoriesRequest;
    use App\Http\Resources\CategoryResource;
    use App\Models\Category;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Inertia\Inertia;
    use Inertia\Response;

    class CategoriesController extends Controller
    {
        private string $routeResourceName = 'categories';

        public function __construct ()
        {
            $this->middleware('can:view categories list')->only([ 'index' ]);
            $this->middleware('can:create category')->only([ 'create', 'store' ]);
            $this->middleware('can:edit category')->only([ 'edit', 'update' ]);
            $this->middleware('can:delete category')->only([ 'destroy' ]);
        }

        /**
         * Display a listing of the resource.
         *
         * @param \Illuminate\Http\Request $request
         *
         * @return \Inertia\Response
         */
        public function index ( Request $request )
        : Response {
            $categories = Category::query()
                                  ->select([ 'id', 'parent_id', 'name', 'active', 'created_at' ])
                                  ->withCount([ 'children' ])
                                  ->when($request->name, fn ( Builder $query ) => $query->where('name', 'like', "%{$request->name}%"))
                                  ->when(
                                      $request->parent_id,
                                      fn ( Builder $query ) => $query->where('parent_id', $request->parent_id),
                                      fn ( Builder $query ) => $query->root(),
                                  )
                                  ->when(
                                      $request->active !== null,
                                      fn ( Builder $query ) => $query->when(
                                          $request->active,
                                          fn ( Builder $builder ) => $builder->active(),
                                          fn ( Builder $builder ) => $builder->inactive()
                                      )
                                  )
                                  ->latest('id')
                                  ->paginate(100)
            ;

            return Inertia::render('Category/Index', [
                'title'             => 'Categories',
                'items'             => CategoryResource::collection($categories),
                'headers'           => [
                    [
                        'label' => 'Name',
                        'name'  => 'Name',
                    ],
                    [
                        'label' => 'Children',
                        'name'  => 'children_count',
                    ],
                    [
                        'label' => 'Active',
                        'name'  => 'active',
                    ],
                    [
                        'label' => 'Created At',
                        'name'  => 'created_at',
                    ],
                    [
                        'label' => 'Actions',
                        'name'  => 'actions',
                    ],
                ],
                'filters'           => (object)$request->all(),
                'routeResourceName' => $this->routeResourceName,
                'rootCategories'    => CategoryResource::collection(Category::root()->get([ 'id', 'name' ])),
                'can'               => [
                    'view'   => $request->user()->can("view {$this->routeResourceName} list"),
                    'create' => $request->user()->can('create category'),
                ],
            ]);
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param \App\Http\Requests\StoreCategoriesRequest $request
         *
         * @return \Illuminate\Http\RedirectResponse
         */
        public function store ( StoreCategoriesRequest $request )
        : RedirectResponse {
            $category = Category::create($request->safe()->only([ 'name', 'slug', 'active', 'parent_id' ]));

            return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'Category created successfully.');
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Inertia\Response
         */
        public function create ()
        : Response
        {
            return Inertia::render('Category/Create', [
                'edit'              => false,
                'title'             => 'Add Category',
                'routeResourceName' => $this->routeResourceName,
                'rootCategories'    => CategoryResource::collection(Category::root()->get([ 'id', 'name' ])),
            ]);
        }

        /**
         * Display the specified resource.
         *
         * @param int $id
         *
         * @return \Inertia\Response
         */
        public function show ( $id )
        : Response {
            return Inertia::render('Category/Create', [
                'item'              => new CategoryResource(Category::findOrFail($id)),
                'title'             => 'Category Details',
                'routeResourceName' => $this->routeResourceName,
            ]);
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param \App\Models\Category $category
         *
         * @return \Inertia\Response
         */
        public function edit ( Category $category )
        : Response {

            return Inertia::render('Category/Create', [
                'edit'              => true,
                'title'             => 'Edit Category',
                'routeResourceName' => $this->routeResourceName,
                'item'              => new CategoryResource($category),
                'rootCategories'    => CategoryResource::collection(Category::root()->where('id', '!=', $category->id)->get([ 'id', 'name' ])),
            ]);
        }

        /**
         * Update the specified resource in storage.
         *
         * @param \App\Http\Requests\StoreCategoriesRequest $request
         * @param \App\Models\Category                      $category
         *
         * @return \Illuminate\Http\RedirectResponse
         */
        public function update ( StoreCategoriesRequest $request, Category $category )
        : RedirectResponse {
            $category->update($request->safe()->only([ 'name', 'slug', 'active', 'parent_id' ]));

            return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'Category updated successfully.');
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param \App\Models\Category $category
         *
         * @return \Illuminate\Http\RedirectResponse
         */
        public function destroy ( Category $category )
        : RedirectResponse {
            $category->delete();

            return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'Category deleted successfully.');
        }
    }
