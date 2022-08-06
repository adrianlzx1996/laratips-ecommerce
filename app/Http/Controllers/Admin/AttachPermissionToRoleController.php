<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Spatie\Permission\Models\Permission;

    class AttachPermissionToRoleController extends Controller
    {
        public function __construct ()
        {
            $this->middleware('can:edit role');
        }

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\RedirectResponse
         */
        public function __invoke ( Request $request )
        {
            $permission = Permission::findById($request->permissionId);

            $permission->assignRole($request->roleId);

            return redirect()->back()->with('success', "Permission attached successfully.");
        }
    }
