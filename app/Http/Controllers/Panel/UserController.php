<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index(User $user)
    {
        $users = $user->where('id', '<>', 1)->get();

        return view('panel.user.index', compact('users'));
    }

    public function newUser()
    {
        return view('panel.user.create');
    }

    public function store(Request $request, Permission $permission)
    {

        $permissionsTable = $permission->all();

        $superAdmin = $request->only('super-admin');

        if(!isset($superAdmin['super-admin']))
        {
            $superAdmin['super-admin'] = null;
        } else {
            foreach($permissionsTable as $permission) {
                $permissions[] = $permission->name;
            }
        }

        $dataForm = $request->except('_token', 'super-admin');

        $dataForm['password'] = bcrypt($dataForm['password']);

        $insert = User::create($dataForm);

        $user = User::find($insert->id);

        $user->syncPermissions($permissions);

        if ($insert) {
            return redirect(route('users'))->with('success', 'Usuário criado com sucesso!');
        } else {
            return redirect()->back()->with('error', 'Não foi possível criar o usuário!');
        }
    }

    public function edit($id, User $user)
    {

        $users = $user->all();

        $userToEdit = User::find($id);

        return view('panel.user.edit', compact('userToEdit', 'users', 'permissions'));
    }

    public function update($id, Request $request, User $user)
    {
        $dataForm = $request->except('_token');

        $userToEdit = User::find($id);

        if ($dataForm['password'] == null) {
            $dataForm['password'] = $userToEdit['password'];
        } else {
            $dataForm['password'] = bcrypt($dataForm['password']);
        }

        $update = $user->where('id', $dataForm['id'])->update($dataForm);

        if ($update)
            return redirect(route('users'))->with('success', 'Usuário atualizado com sucesso!');

    }

    public function delete($id, User $user)
    {

        $users = $user->all();

        $logedUser = Auth::user();

        if (count($users) > 1) {
            if($logedUser['id'] == $id){
                return redirect()->back()->with('Error', 'Não é possível excluir o usuário logado');
            } else {
                User::destroy($id);
                return redirect(route('users'))->with('success', 'Usuário apagado com sucesso!');
            }
        } else {
            return redirect()->back()->with('error', 'Só existe um usuário cadastrado. Não é possível apagar');
        }

    }

    public function permissions(Permission $permission, $id)
    {
        $user = User::find($id);

        $userPermissions = $user->getDirectPermissions();

        foreach($userPermissions as $permissions)
        {
            $userPermission[] = $permissions->name;
        }

        $permissions = $permission->all();

        foreach ($permissions as $permission) {
            if (starts_with($permission->name, 'user')) {
                $roleUserPermission[] = $permission->name;
            }
            if (starts_with($permission->name, 'athlete')) {
                $roleAthletePermission[] = $permission->name;
            }
            if (starts_with($permission->name, 'fin')) {
                $roleFinPermission[] = $permission->name;
            }
        }

        return view('panel.user.user-permissions', compact('userPermission', 'user', 'permissions', 'roleUserPermission', 'roleAthletePermission', 'roleFinPermission'));

    }

    public function permissionConfirm(Request $request, $id, Permission $permission)
    {

        $permissionsTable = $permission->all();

        foreach($permissionsTable as $permission)
        {
            $permissions[] = $permission->name;
        }

        $permissions = $request->except('_token');

        $user = User::find($id);

        foreach ($permissions as $permission) {
            $user->syncPermissions($permission);
        }

        return redirect()->back();

    }

}
