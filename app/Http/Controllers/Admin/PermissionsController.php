<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\StorePermissionsRequest;
    use App\Http\Resources\PermissionResource;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\Request;
    use Inertia\Inertia;
    use Spatie\Permission\Models\Permission;

    class PermissionsController extends Controller
    {
        private string $routeResourceName = 'permissions';

        public function __construct ()
        {
            $this->middleware('can:view permissions list')->only([ 'index' ]);
            $this->middleware('can:create permission')->only([ 'create', 'store' ]);
            $this->middleware('can:edit permission')->only([ 'edit', 'update' ]);
            $this->middleware('can:delete permission')->only([ 'destroy' ]);
        }

        /**
         * Display a listing of the resource.
         *
         * @return \Inertia\Response
         */
        public function index ( Request $request )
        : \Inertia\Response {
            $permissions = Permission::query()
                                     ->select([ 'id', 'name', 'created_at' ])
                                     ->when($request->name, fn ( Builder $query ) => $query->where('name', 'like', "%{$request->name}%"))
                                     ->latest('id')->paginate(10);

            return Inertia::render('Permission/Index', [
                'title'             => 'Permissions',
                'items'             => PermissionResource::collection($permissions),
                'headers'           => [
                    [
                        'label' => 'Name',
                        'name'  => 'Name',
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
                'can'               => [
                    'view'   => $request->user()->can('view permissions list'),
                    'create' => $request->user()->can('create permission'),
                ],
            ]);
        }

        /**
         * It creates a new permission and redirects to the index page.
         *
         * @param StorePermissionsRequest $request The request object.
         *
         * @return  \Illuminate\Http\RedirectResponse redirect to the index page with a success message.
         */
        public function store ( StorePermissionsRequest $request )
        : \Illuminate\Http\RedirectResponse {
            $permission = Permission::create($request->validated());

            return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Inertia\Response
         */
        public function create ()
        : \Inertia\Response
        {
            return Inertia::render('Permission/Create', [
                'edit'              => false,
                'title'             => 'Add Permission',
                'routeResourceName' => $this->routeResourceName,
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
        : \Inertia\Response {
            return Inertia::render('Permission/Create', [
                'item'              => new PermissionResource(Permission::findOrFail($id)),
                'title'             => 'Permission Details',
                'routeResourceName' => $this->routeResourceName,
            ]);
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param int $id
         *
         * @return \Inertia\Response
         */
        public function edit ( $id )
        : \Inertia\Response {
            return Inertia::render('Permission/Create', [
                'edit'              => true,
                'item'              => Permission::findOrFail($id),
                'title'             => 'Permission Details',
                'routeResourceName' => $this->routeResourceName,
            ]);
        }

        /**
         * Update the specified resource in storage.
         *
         * @param \App\Http\Requests\StorePermissionsRequest $request
         * @param int                                        $id
         *
         * @return \Illuminate\Http\RedirectResponse
         */
        public function update ( StorePermissionsRequest $request, int $id )
        : \Illuminate\Http\RedirectResponse {
            $permission = Permission::findOrFail($id);
            $permission->update($request->validated());

            return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         *
         * @return \Illuminate\Http\RedirectResponse
         */
        public function destroy ( $id )
        : \Illuminate\Http\RedirectResponse {
            Permission::findOrFail($id)->delete();

            return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
        }
    }
