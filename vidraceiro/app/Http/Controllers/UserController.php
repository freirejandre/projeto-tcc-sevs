<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();
        return view('dashboard.list.user', compact('users'))->with('title', 'Usuarios');

    }

    public function create()
    {
        return view('dashboard.create.user')->with('title', 'Criar usuario');

    }

    public function store(Request $request)
    {
        $validado = $this->rules_users($request->all());
        if ($validado->fails()) {
            return redirect()->back()->withErrors($validado);
        }
        $user = new User;
        $user = $user->create($request->all());
        if ($user)
            return redirect()->back()->with('success', 'Usuario criado com sucesso');

    }

    public function show($user)
    {
        $users = User::findOrFail($user);
        return view('dashboard.list.user', compact('users'))->with('title', 'Listar usuarios');
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('dashboard.create.user', compact('user'))->with('title', 'Atualizar usuario');
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $validado = $this->rules_update_users($request->all());
        if ($validado->fails()) {
            return redirect()->back()->withErrors($validado);
        }
        $user->update($request->except(['_token']));
        if ($user)
            return redirect()->back()->with('success', 'Usuario atualizado com sucesso');

        return view('dashboard.create.user', compact('user'))->with('title', 'Atualizar usuario');
    }

    public function showdelete($id)
    {
        return view('dashboard.modal.delete');

    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect()->back()->with('success', 'Usuario deletado com sucesso');
        } else {
            return redirect()->back()->with('error', 'Erro ao deletar usuario');
        }

    }

    public function rules_users(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        return $validator;
    }

    public function rules_update_users(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|',
            'password' => 'required|string|min:6',
        ]);

        return $validator;
    }
}
