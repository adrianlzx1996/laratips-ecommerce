<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreRolesRequest;
    use App\Http\Resources\PermissionResource;
    use App\Http\Resources\RoleResource;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Inertia\Inertia;
    use Spatie\Permission\Models\Permission;
    use Spatie\Permission\Models\Role;

    class RolesController extends Controller
    {
        private string $routeResourceName = 'roles';

        public function index ( Request $request )
        {
            $roles = Role::query()
                         ->select([ 'id', 'name', 'created_at' ])
                         ->when($request->name, fn ( Builder $query ) => $query->where('name', 'like', "%{$request->name}%"))
                         ->latest('id')->paginate(10);

            return Inertia::render('Role/Index', [
                'title'             => 'Roles',
                'items'             => RoleResource::collection($roles),
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
            ]);
        }

        public function store ( StoreRolesRequest $request )
        : RedirectResponse {
            $role = Role::create($request->validated());

            return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
        }

        public function create ()
        {
            return Inertia::render('Role/Create', [
                'edit'              => false,
                'title'             => 'Add Role',
                'routeResourceName' => $this->routeResourceName,
            ]);
        }

        public function edit ( Role $role )
        {
            $role->load([ 'permissions:permissions.id,permissions.name' ]);

            return Inertia::render('Role/Create', [
                'edit'              => true,
                'item'              => new RoleResource($role),
                'title'             => 'Edit Role',
                'routeResourceName' => $this->routeResourceName,
                'permissions'       => PermissionResource::collection(Permission::get([ 'id', 'name' ])),
            ]);
        }

        public function update ( StoreRolesRequest $request, Role $role )
        {
            $role->update($request->all());

            return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
        }

        public function destroy ( Role $role )
        {
            $role->delete();

            return back()->with('success', 'Role deleted successfully.');
        }
    }
