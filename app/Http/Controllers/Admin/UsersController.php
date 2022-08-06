<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreUsersRequest;
    use App\Http\Resources\RoleResource;
    use App\Http\Resources\UserResource;
    use App\Models\User;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\Request;
    use Inertia\Inertia;
    use Spatie\Permission\Models\Role;

    class UsersController extends Controller
    {
        private string $routeResourceName = 'users';

        public function __construct ()
        {
            $this->middleware('can:view users list')->only([ 'index' ]);
            $this->middleware('can:create user')->only([ 'create', 'store' ]);
            $this->middleware('can:edit user')->only([ 'edit', 'update' ]);
            $this->middleware('can:delete user')->only([ 'destroy' ]);
        }

        /**
         * Display a listing of the resource.
         *
         * @return \Inertia\Response
         */
        public function index ( Request $request )
        : \Inertia\Response {
            $users = User::query()
                         ->select([ 'id', 'name', 'email', 'created_at' ])
                         ->with([ 'roles:roles.id,roles.name' ])
                         ->when($request->name, fn ( Builder $query ) => $query->where('name', 'like', "%{$request->name}%"))
                         ->latest('id')->paginate(10);

            return Inertia::render('User/Index', [
                'title'             => 'Users',
                'items'             => UserResource::collection($users),
                'headers'           => [
                    [
                        'label' => 'Name',
                        'name'  => 'Name',
                    ],
                    [
                        'label' => 'Email',
                        'name'  => 'headers',
                    ],
                    [
                        'label' => 'Role',
                        'name'  => 'role',
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
                    'view'   => $request->user()->can("view {$this->routeResourceName} list"),
                    'create' => $request->user()->can('create user'),
                ],
            ]);
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param \App\Http\Requests\StoreUsersRequest $request
         *
         * @return \Illuminate\Http\RedirectResponse
         */
        public function store ( StoreUsersRequest $request )
        : \Illuminate\Http\RedirectResponse {
            $user = User::create($request->safe()->only([ 'name', 'email', 'password' ]));
            $user->assignRole($request->roleId);

            return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully.');
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Inertia\Response
         */
        public function create ()
        : \Inertia\Response
        {
            return Inertia::render('User/Create', [
                'edit'              => false,
                'title'             => 'Add User',
                'routeResourceName' => $this->routeResourceName,
                'roles'             => RoleResource::collection(Role::get([ 'id', 'name' ])),
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
            return Inertia::render('User/Create', [
                'item'              => new UserResource(User::findOrFail($id)),
                'title'             => 'User Details',
                'routeResourceName' => $this->routeResourceName,
            ]);
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param \App\Models\User $user
         *
         * @return \Inertia\Response
         */
        public function edit ( User $user )
        : \Inertia\Response {

            $user->load([ 'roles:roles.id' ]);

            return Inertia::render('User/Create', [
                'edit'              => true,
                'title'             => 'Edit User',
                'routeResourceName' => $this->routeResourceName,
                'item'              => new UserResource($user),
                'roles'             => RoleResource::collection(Role::get([ 'id', 'name' ])),
            ]);
        }

        /**
         * Update the specified resource in storage.
         *
         * @param \App\Http\Requests\StoreUsersRequest $request
         * @param \App\Models\User                     $user
         *
         * @return \Illuminate\Http\RedirectResponse
         */
        public function update ( StoreUsersRequest $request, User $user )
        : \Illuminate\Http\RedirectResponse {
            $user->update($request->safe()->only([ 'name', 'email', 'password' ]));
            $user->assignRole($request->roleId);

            return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User updated successfully.');
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param \App\Models\User $user
         *
         * @return \Illuminate\Http\RedirectResponse
         */
        public function destroy ( User $user )
        : \Illuminate\Http\RedirectResponse {
            $user->delete();

            return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User deleted successfully.');
        }
    }
