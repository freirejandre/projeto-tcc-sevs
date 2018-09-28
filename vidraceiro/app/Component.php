<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{

    protected $fillable = [
        'nome',
        'qtd',
        'preco',
        'imagem',
        'is_modelo',
        'mcomponent_id',
        'categoria_componente_id',
        'product_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoria_componente_id');
    }


    public function product()
    {
        return $this->belongsTo(
            Product::class,
            'product_id'
        );
    }

    public function mProducts(){
        return $this->belongsToMany(
            MProduct::class,
            'm_product_component',
            'componente_id',
            'm_produto_id'
        );
    }

    public function providers(){
        return $this->belongsToMany(
            Provider::class,
            'provider_component',
            'componente_id',
            'provider_id'
        );
    }

    public function storage()
    {
        return $this->hasOne(
            Storage::class,
            'component_id'
        );
    }

    public function getWithSearchAndPagination($search, $paginate){

        $paginate = $paginate ?? 10;

        return self::where('is_modelo', 1)->where('nome', 'like', '%' . $search . '%')
            ->paginate($paginate);
    }

    public function findComponentById($id){

        return self::find($id);

    }

    public function createComponent(array $input){
        $component = self::create($input);

        if($component->is_modelo === 1){
            Storage::createStorage([
                'qtd' => 0,
                'component_id' => $component->id
            ]);
        }

        return $component;
    }

    public function updateComponent(array $input){

        return self::update($input);

    }

    public function deleteComponent(){

        return self::delete();

    }

    public function syncProviders($ids){
        $this->providers()->sync($ids);
    }

    public static function getAllComponentsOrAllModels($is_modelo = 0){

        return self::where('is_modelo', $is_modelo)->get();

    }

    public static function getComponentsWhereIn(array $ids){

        return self::wherein('id', $ids)->get();

    }

    public static function deleteComponentOnListWhereNotIn($component,array $ids){

        return $component->whereNotIn('id', $ids)->delete();

    }

}
