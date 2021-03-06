<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Financial extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tipo',
        'descricao',
        'valor',
        'data_vencimento',
        'status',
        'pagamento_id',
        'usuario_id'
    ];

    public function payment(){
        return $this->belongsTo(Payment::class, 'pagamento_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function getWithSearchAndPagination($search, $paginate, $period, &$financialsByPeriod, $restore = false, $status = null, $type = null){

        $paginate = $paginate ?? 10;
        $period = $period ?? 'hoje';

        $data_inicial = $data_final = date_format(date_create(today()), 'Y-m-d');

        switch ($period){
            case 'hoje':
                break;
            case 'semana':

                $data_inicial = date('Y-m-d', strtotime("-6 days", strtotime($data_inicial)));

                break;
            case 'mes':

                $data_inicial = date('Y-m-d', strtotime("-29 days", strtotime($data_inicial)));

                break;
            case 'semestre':

                $data_inicial = date('Y-m-d', strtotime("-179 days", strtotime($data_inicial)));

                break;
            case 'anual':

                $data_inicial = date('Y-m-d', strtotime("-359 days", strtotime($data_inicial)));

                break;
            case 'tudo':

                $data_inicial = $data_final = null;

                break;
        }


        $queryBuilder = self::when($data_inicial, function ($query) use ($data_inicial, $data_final) {

                    return $query-> where(function ($c) use ($data_inicial,$data_final){
                        
                        $c->whereDate('data_vencimento','<=',$data_final);
                        $c->whereDate('data_vencimento','>=',$data_inicial);
                        

                    });
            })
            ->where(function ($c) use ($search) {
                $c->where('descricao', 'like', '%' . $search . '%')
                    ->orWhere('tipo', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });

        if($status !== null)
            $queryBuilder = $queryBuilder->where('status',$status);
        
        if($type !== null)
            $queryBuilder = $queryBuilder->where('tipo',$type);

        if($restore)
            $queryBuilder = $queryBuilder->onlyTrashed();

        $financialsByPeriod = $queryBuilder->get();

        return $queryBuilder->paginate($paginate);
    }


    public function findFinancialById($id){

        return self::find($id);

    }

    public function restoreFinancialById($id){

        $financial = self::onlyTrashed()->find($id);

        return $financial? $financial->restore(): false;
    }

    public function deleteFinancial(){

        return self::delete();

    }

    public static function getAllByStatus($status){

        return self::where('status',$status)->get();

    }

    public static function createFinancial(array $input){

        return self::create($input);

    }

    public function updateFinancial(array $input){

        return self::update($input);

    }

    public static function filterFinancial($request){

        $tipo_financa = $request->tipo_financa;
        $financials = new Financial();
        $valor_inicial = $request->valor_inicial;
        $valor_final = $request->valor_final;
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        $status = $request->status;
        $valorentrou = $dataentrou = false;
        

        if($valor_inicial !== null || $valor_final !== null){
            $valorentrou = true;
        }

        if($data_inicial !== null || $data_final !== null){
            $dataentrou = true;
        }

        if($valorentrou || $dataentrou){

            $financials =  self::where(function ($query) use ($data_inicial,$data_final, $valor_inicial,$valor_final,$valorentrou,$dataentrou,$status){
                if($dataentrou){


                    $query-> where(function ($c) use ($data_inicial,$data_final){

                        if($data_final !== null)
                            $c->whereDate('data_vencimento','<=',$data_final);

                        if($data_inicial !== null)
                            $c->whereDate('data_vencimento','>=',$data_inicial);

                    });


                }

                if($valorentrou){

                    $query->where(function ($q) use ($valor_inicial,$valor_final){
                        if($valor_final !== null)
                            $q->where('valor','<=',$valor_final);

                        if($valor_inicial !== null)
                            $q->where('valor','>=',$valor_inicial);
                    });
                }

                
            });
        }

        $financials = $financials->where('status',$status);
        
        if($tipo_financa === 'TODOS'){
            $financials = $financials->get();
        }else{
            $financials = $financials->where('tipo',$tipo_financa)->get();
        }
        return $financials;

    }
}
