<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Budget;
use App\Aluminum;
use App\Product;
use App\MProduct;
use App\Component;
use App\Glass;
use App\Category;

class BudgetController extends Controller
{
    protected $states;

    public function __construct()
    {
        $this->middleware('auth');
        $this->states = $states = array(
            ' ' => 'Selecione...',
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapá',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceará',
            'DF' => 'Distrito Federal',
            'ES' => 'Espírito Santo',
            'GO' => 'Goiás',
            'MA' => 'Maranhão',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Pará',
            'PB' => 'Paraíba',
            'PR' => 'Paraná',
            'PE' => 'Pernambuco',
            'PI' => 'Piauí',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondônia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'São Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins'
        );
    }

    public function index()
    {
        $budgets = Budget::all();
        return view('dashboard.list.budget', compact('budgets'))->with('title', 'Orçamentos');
    }

    public function create()
    {
        $states = $this->states;
        $aluminums = Aluminum::where('is_modelo', '1')->get();
        $glasses = Glass::where('is_modelo', '1')->get();
        $components = Component::where('is_modelo', '1')->get();
        $categories = Category::where('tipo', 'produto')->get();
        $mproducts = MProduct::all();
        $titulotabs = ['Orçamento', 'Adicionar', 'Editar', 'Material', 'Total'];
        //dd($mproducts);
        return view('dashboard.create.budget', compact('titulotabs', 'states', 'glasses', 'aluminums', 'components', 'categories', 'mproducts'))->with('title', 'Novo Orçamento');
    }

    public function store(Request $request, $tab)
    {
        switch ($tab) {
            case '1': //tab orçamento
                $budgetcriado = new Budget;
                $budgetcriado = $budgetcriado->create($request->all());
                if ($budgetcriado)
                    return redirect()->back()->with('success', 'Orçamento criado com sucesso')
                        ->with(compact('budgetcriado'));
                break;
            case '2': //tab adicionar
                $product = new Product();
                $product = $product->create($request->except('budgetid'));
                $mproduct = MProduct::with('glasses', 'aluminums', 'components')->find($product->m_produto_id);

                foreach ($mproduct->glasses()->get() as $vidro) {
                    $product->glasses()->attach($vidro->id);
                }

                foreach ($mproduct->aluminums()->get() as $aluminum) {
                    $product->aluminums()->attach($aluminum->id);
                }

                foreach ($mproduct->components()->get() as $components) {
                    $product->components()->attach($components->id);
                }

                if ($product) {
                    $budgetcriado = Budget::find($request->budgetid);
                    $budgetcriado->products()->attach($product->id);
                    if ($budgetcriado) {
                        $products = $budgetcriado->products;
                        return redirect()->back()->with('success', 'Produto adicionado ao orçamento com sucesso')
                            ->with(compact('budgetcriado'))
                            ->with(compact('products'));
                    }
                }

                break;
            case '3': //tab editar
                $budgetcriado = Budget::find($request->budgetid);
                $product = Product::find($request->produtoid);
                $product->update($request->except(['produtoid', 'budgetid']));

                if ($product && $budgetcriado) {
                    $products = $budgetcriado->products;
                    return redirect()->back()->with('success', 'Produto atualizado com sucesso')
                        ->with(compact('budgetcriado'))
                        ->with(compact('products'));
                }
                break;
            case '4': //tab material
                $budgetcriado = Budget::with('products')->find($request->budgetid);
                $products = $budgetcriado->products;

                foreach ($products as $product) {
                    $names = 'id_vidro_' . $product->id;
                    if ($request->get($names) != null) {
                        $product->glasses()->sync($request->get($names));
                    } else {
                        $product->glasses()->detach();
                    }

                    $names = 'id_aluminio_' . $product->id;
                    if ($request->get($names) != null) {
                        $product->aluminums()->sync($request->get($names));
                    } else {
                        $product->aluminums()->detach();
                    }

                    $names = 'id_componente_' . $product->id;
                    if ($request->get($names) != null) {
                        $product->components()->sync($request->get($names));
                    } else {
                        $product->components()->detach();
                    }
                }

                if ($budgetcriado) {
                    return redirect()->back()->with('success', 'Materiais dos produtos atualizados com sucesso')
                        ->with(compact('budgetcriado'))
                        ->with(compact('products'));
                }
                break;
            case '5': //tab total


                break;
            default:
        }
        return redirect()->back()->with('error',"Erro ao adicionar");
    }

    public function show()
    {

    }

    public function edit($id)
    {
        $states = $this->states;
        $aluminums = Aluminum::where('is_modelo', '1')->get();
        $glasses = Glass::where('is_modelo', '1')->get();
        $components = Component::where('is_modelo', '1')->get();
        $categories = Category::where('tipo', 'produto')->get();
        $mproducts = MProduct::all();
        $titulotabs = ['Orçamento', 'Adicionar', 'Editar', 'Material', 'Total'];

        $budgetedit = Budget::with('products')->find($id);
        if ($budgetedit) {
            $products = $budgetedit->products()->with('mproduct', 'glasses', 'aluminums', 'components')->get();
            return view('dashboard.create.budget', compact('titulotabs', 'states', 'glasses', 'aluminums', 'components', 'categories', 'mproducts', 'products', 'budgetedit'))->with('title', 'Atualizar Orçamento');
        }
        return redirect('products')->with('error', 'Erro ao buscar produto');

    }


    public function update(Request $request, $tab, $id)
    {
        switch ($tab) {
            case '1': //tab orçamento
                $budgetcriado = Budget::find($id);
                $budgetcriado = $budgetcriado->update($request->all());
                if ($budgetcriado)
                    return redirect()->back()->with('success', 'Orçamento atualizado com sucesso');
                break;
            case '2': //tab adicionar
                $product = new Product();
                $product = $product->create($request->all());
                if ($product) {
                    $budgetcriado = Budget::find($id);
                    $budgetcriado->products()->attach($product->id);
                    if ($budgetcriado)
                        return redirect()->back()->with('success', 'Produto adicionado ao orçamento com sucesso');
                }
                break;
            case '3': //tab editar
                $product = Product::find($request->produtoid);
                $product->update($request->except(['produtoid']));
                if ($product)
                    return redirect()->back()->with('success', 'Produto atualizado com sucesso');

                break;
            case '4': //tab material
                $budgetcriado = Budget::with('products')->find($id);
                $products = $budgetcriado->products;
                foreach ($products as $product) {
                    $names = 'id_vidro_' . $product->id;
                    if ($request->get($names) != null) {
                        $product->glasses()->sync($request->get($names));
                    } else {
                        $product->glasses()->detach();
                    }

                    $names = 'id_aluminio_' . $product->id;
                    if ($request->get($names) != null) {
                        $product->aluminums()->sync($request->get($names));
                    } else {
                        $product->aluminums()->detach();
                    }

                    $names = 'id_componente_' . $product->id;
                    if ($request->get($names) != null) {
                        $product->components()->sync($request->get($names));
                    } else {
                        $product->components()->detach();
                    }
                }
                if ($products)
                    return redirect()->back()->with('success', 'Materiais dos produtos atualizados com sucesso');
                break;
            case '5': //tab total


                break;
            default:
        }
        return redirect()->back()->with('error',"Erro ao atualizar");
    }

    public function destroy($id)
    {
        $budget = Budget::find($id);
        if ($budget) {
            $budget->delete();
            return redirect()->back()->with('success', 'Orçamento deletado com sucesso');
        } else {
            return redirect()->back()->with('error', 'Erro ao deletar orçamento');
        }
    }


}
