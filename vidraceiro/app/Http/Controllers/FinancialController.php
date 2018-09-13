<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Financial;

class FinancialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $financials = Financial::all();
        return view('dashboard.list.financial', compact('financials'))->with('title', 'Financeiro');
    }

    public function store(Request $request)
    {
        $validado = $this->rules_financial($request->all());
        if ($validado->fails()) {
            return redirect()->back()->withErrors($validado);
        }

        $financial = new Financial();
        $financial = $financial->create($request->except('_token'));
        if ($financial){
            $mensagem = '';
            if($financial->tipo === 'RECEITA'){
                $mensagem = 'Receita';
            }else{
                $mensagem = 'Despesa';
            }
            return redirect()->back()->with('success', $mensagem.' adicionada com sucesso');
        }


    }

    public function destroy($id)
    {
        $financial = Financial::find($id);
        if ($financial) {
            $mensagem = '';
            if($financial->tipo === 'RECEITA'){
                $mensagem = 'Receita';
            }else{
                $mensagem = 'Despesa';
            }
            $financial->delete();
            return redirect()->back()->with('success', $mensagem.' deletada com sucesso');
        } else {
            return redirect()->back()->with('error', 'Erro ao deletar item');
        }
    }

    public function rules_financial(array $data)
    {
        $validator = Validator::make($data, [
            'tipo' => [
                'required',
                Rule::in(['RECEITA', 'DESPESA'])
            ],
            'descricao' => 'nullable|string|max:255',
            'valor' => 'required|numeric'
        ]);

        return $validator;
    }
}