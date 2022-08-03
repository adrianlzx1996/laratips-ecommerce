<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreRolesRequest;
    use App\Http\Resources\RoleResource;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Inertia\Inertia;
    use Spatie\Permission\Models\Role;

    class RolesController extends Controller
    {
        public function index ( Request $request )
        {
            $roles = Role::select([ 'id', 'name', 'created_at' ])->latest('id')->paginate(10);

            return Inertia::render('Role/Index', [
                'roles'   => RoleResource::collection($roles),
                'headers' => [
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
                'edit'  => false,
                'title' => 'Add Role',
            ]);
        }

        public function edit ( Role $role )
        {
            return Inertia::render('Role/Create', [
                'edit'  => true,
                'role'  => new RoleResource($role),
                'title' => 'Edit Role',
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
