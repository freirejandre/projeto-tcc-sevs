<?php

namespace App\Http\Controllers;

use App\Installment;
use App\Payment;
use Illuminate\Http\Request;
use App\Sale;
use App\Client;
use App\Budget;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sales = Sale::with('installments','payments','budget')->get();
        $titulotabs = ['Vendas','Pagamentos'];
        return view('dashboard.list.sale', compact('sales','titulotabs'))->with('title', 'Vendas');
    }

    public function create()
    {
        $budgets = Budget::where('status','AGUARDANDO')->get();
        return view('dashboard.create.sale' ,compact('budgets'))->with('title', 'Nova venda');
    }

    public function store(Request $request)
    {
        $arrayextra = null;
        if($request->tipo_pagamento === 'A PRAZO'){

            $arrayextra = [
                'orcamento_id'=>'required|integer|unique:sales,orcamento_id',
                'valor_parcela'=>'required|numeric',
                'qtd_parcelas'=>'required|integer'
            ];

        }else{

            $arrayextra = [
                'orcamento_id'=>'required|integer|unique:sales,orcamento_id'
            ];

        }

        $validado = $this->rules_sale($request->all(),$arrayextra);
        if ($validado->fails()) {
            return redirect()->back()->withErrors($validado);
        }

        $sale = new Sale();
        $sale = $sale->create($request->except('valor_parcela'));

        if($request->has('valor_parcela')){


            for($i = 1; $i <= $request->qtd_parcelas; $i++){
                $installments = new Installment();
                $dias = $i * 30;
                $datavencimento = date('Y-m-d', strtotime("+$dias days",strtotime($request->data_venda)));
                $installments->create([
                    'valor_parcela'=>$request->valor_parcela,
                    'status_parcela'=>'ABERTO',
                    'data_vencimento'=> $datavencimento,
                    'venda_id'=> $sale->id
                ]);
            }


        }else{

            $payment = new Payment();
            $payment->create([
                'valor_pago'=> $sale->budget->total,
                'data_pagamento'=>$request->data_venda,
                'venda_id'=>$sale->id
            ]);

        }

        if ($sale){
            $sale->budget->update(['status'=>'APROVADO']);
            return redirect()->back()->with('success', 'Venda realizada com sucesso');
        }

    }

    public function show()
    {

    }

    public function edit($id)
    {
        $sale = Sale::find($id);
        return view('dashboard.create.sale', compact('sale'))->with('title', 'Atualizar venda');
    }


    public function update(Request $request, $id)
    {

        $arrayextra = [];
        if($request->tipo_pagamento === 'A PRAZO'){

            $arrayextra = [
                'valor_parcela'=>'required|numeric',
                'qtd_parcelas'=>'required|integer'
            ];

        }

        $validado = $this->rules_sale($request->all(),$arrayextra);
        if ($validado->fails()) {
            return redirect()->back()->withErrors($validado);
        }

        $sale = Sale::find($id);

        if($sale->tipo_pagamento === 'A PRAZO'){

            if(empty($sale->installments->where('status_parcela','PAGO')->shift())){

                $sale->installments()->delete();

            }else{
                return redirect()->back()->with('error', 'Não foi possível atualizar a venda pois já existem parcelas que foram pagas!');
            }

            if($request->has('valor_parcela')){

                for($i = 1; $i <= $request->qtd_parcelas; $i++){
                    $installments = new Installment();
                    $dias = $i * 30;
                    $datavencimento = date('Y-m-d', strtotime("+$dias days",strtotime($request->data_venda)));
                    $installments->create([
                        'valor_parcela'=>$request->valor_parcela,
                        'status_parcela'=>'ABERTO',
                        'data_vencimento'=> $datavencimento,
                        'venda_id'=> $sale->id
                    ]);
                }

            }else{

                $payment = new Payment();
                $payment->create([
                    'valor_pago'=> $sale->budget->total,
                    'data_pagamento'=>$request->data_venda,
                    'venda_id'=>$sale->id
                ]);

            }

        }else{

            if($request->has('valor_parcela')){

                $sale->payments()->delete();

                for($i = 1; $i <= $request->qtd_parcelas; $i++){
                    $installments = new Installment();
                    $dias = $i * 30;
                    $datavencimento = date('Y-m-d', strtotime("+$dias days",strtotime($request->data_venda)));
                    $installments->create([
                        'valor_parcela'=>$request->valor_parcela,
                        'status_parcela'=>'ABERTO',
                        'data_vencimento'=> $datavencimento,
                        'venda_id'=> $sale->id
                    ]);
                }

            }

        }

        $sale->update($request->except('valor_parcela'));

        if ($sale){
            return redirect()->back()->with('success', 'Venda atualizada com sucesso');
        }

    }

    public function destroy($id)
    {
        $sale = Sale::find($id);
        if ($sale) {
            $sale->budget->update(['status'=>'AGUARDANDO']);
            $sale->delete();
            return redirect()->back()->with('success', 'Venda deletada com sucesso');
        } else {
            return redirect()->back()->with('error', 'Erro ao deletar venda');
        }
    }

    public function pay($id){

        $sale = Sale::find($id);

        if($sale)
            return view('dashboard.create.pay', compact('sale'))->with('title', 'Efetuar pagamentos');
    }

    public function payupdate(Request $request, $id){

        $installments = null;

        if($request->data_pagamento === null){
            return redirect()->back()->with('error', 'Selecione a data do pagamento!');
        }
        if($request->parcelas !== null){

            $installments = Installment::whereIn('id',$request->parcelas)->get();

            foreach ($installments as $installment){
                $payment = new Payment();
                $payment->create([
                    'valor_pago'=>$installment->valor_parcela,
                    'data_pagamento'=>$request->data_pagamento,
                    'venda_id'=>$id
                ]);
                $installment->update(['status_parcela'=>'PAGO']);
            }

        }else{
            return redirect()->back()->with('error', 'Marque pelo menos uma parcela!');
        }


        if($installments)
            return redirect()->back()->with('success', 'Pagamento efetuado com sucesso');
    }

    public function rules_sale(array $data, $extra)
    {
        $validator = Validator::make($data,
            array_merge(
                [
                    'tipo_pagamento' => 'required|string|max:255',
                    'data_venda' => 'required|date'
                ],
                $extra
            )
        );

        return $validator;
    }
}