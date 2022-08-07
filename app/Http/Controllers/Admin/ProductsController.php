<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreProductsRequest;
    use App\Http\Resources\CategoryResource;
    use App\Http\Resources\ProductResource;
    use App\Models\Category;
    use App\Models\Product;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\Request;
    use Inertia\Inertia;
    use Inertia\Response;

    class ProductsController extends Controller
    {
        private string $routeResourceName = 'products';

        public function __construct ()
        {
            $this->middleware('can:view products list')->only([ 'index' ]);
            $this->middleware('can:create product')->only([ 'create', 'store' ]);
            $this->middleware('can:edit product')->only([ 'edit', 'update' ]);
            $this->middleware('can:delete product')->only([ 'destroy' ]);
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
            $product = Product::query()
                              ->select([ 'id', 'name', 'cost_price', 'price', 'active', 'created_at', 'featured', 'show_on_slider' ])
                              ->when($request->name, fn ( Builder $query ) => $query->where('name', 'like', "%{$request->name}%"))
                              ->when(
                                  $request->category_id,
                                  fn ( Builder $query ) => $query->whereHas(
                                      'categories',
                                      fn ( Builder $builder ) => $builder->where('categories.id', $request->category_id)
                                  )
                              )
                              ->when(
                                  $request->subcategory_id,
                                  fn ( Builder $query ) => $query->whereHas(
                                      'categories',
                                      fn ( Builder $builder ) => $builder->where('categories.id', $request->subcategory_id)
                                  )
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
                              ->paginate(10)
            ;

            return Inertia::render('Product/Index', [
                'title'             => 'Products',
                'items'             => ProductResource::collection($product),
                'headers'           => [
                    [
                        'label' => 'Name',
                        'name'  => 'name',
                    ],
                    [
                        'label' => 'Cost Price',
                        'name'  => 'cost_price',
                    ],
                    [
                        'label' => 'Selling Price',
                        'name'  => 'price',
                    ],
                    [
                        'label' => 'On Slider',
                        'name'  => 'show_on_slider',
                    ],
                    [
                        'label' => 'Featured',
                        'name'  => 'featured',
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
                'categories'        => CategoryResource::collection(Category::root()->with([ 'children:id,name,parent_id' ])->get([ 'id', 'name' ])),
                'can'               => [
                    'view'   => $request->user()->can("view {$this->routeResourceName} list"),
                    'create' => $request->user()->can('create product'),
                ],
            ]);
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param \App\Http\Requests\StoreProductsRequest $request
         *
         * @return \Illuminate\Http\RedirectResponse
         */
        public function store ( StoreProductsRequest $request )
        {
            $product = Product::create($request->safe()->only([ 'name', 'slug', 'active', 'description', 'cost_price', 'price' ]));

            return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', "Product {$product->name} created.");
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Inertia\Response|\Inertia\ResponseFactory
         */
        public function create ()
        : Response|\Inertia\ResponseFactory
        {
            return inertia('Product/Create', [
                'edit'              => false,
                'title'             => 'Add Product',
                'routeResourceName' => $this->routeResourceName,
            ]);
        }

        /**
         * Display the specified resource.
         *
         * @param \App\Models\Product $product
         *
         * @return \Inertia\Response|\Inertia\ResponseFactory
         */
        public function show ( Product $product )
        {
            return inertia('Product/Create', [
                'item'              => new ProductResource($product),
                'title'             => 'Product Detail',
                'routeResourceName' => $this->routeResourceName,
            ]);
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param \App\Models\Product $product
         *
         * @return \Inertia\Response|\Inertia\ResponseFactory
         */
        public function edit ( Product $product )
        {
            return inertia(
                'Product/Create',
                [
                    'edit'              => true,
                    'item'              => new ProductResource($product),
                    'title'             => 'Edit Product',
                    'routeResourceName' => $this->routeResourceName,
                ]
            );
        }

        /**
         * Update the specified resource in storage.
         *
         * @param \App\Http\Requests\StoreProductsRequest $request
         * @param \App\Models\Product                     $product
         *
         * @return \Illuminate\Http\RedirectResponse
         */
        public function update ( StoreProductsRequest $request, Product $product )
        {
            $product->update($request->safe()->only([ 'name', 'slug', 'active', 'description', 'cost_price', 'price' ]));

            return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', "Product {$product->name} updated.");
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param \App\Models\Product $product
         *
         * @return \Illuminate\Http\RedirectResponse
         */
        public function destroy ( Product $product )
        {
            $product->delete();

            return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', "Product {$product->name} deleted.");
        }
    }
