@extends('layouts.app')
@section('content')

    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card-material custom-card">

            <div class="topo">
                <h4 class="titulo">{{$title}}</h4>
                <button id="bt-category-visible" class="btn btn-primary btn-custom"
                        type="button">{{empty($category) ? 'Adicionar': 'Atualizar'}}</button>
            </div>

            <form class="formulario" method="POST" role="form"
                  action="{{ !empty($category) ? route('categories.update',['id'=> $category->id]) : route('categories.store') }}">
                @if(!empty($category))
                    <input type="hidden" name="_method" value="PATCH">
                @endif
                @csrf
                <div class="form-row">

                    <div class="form-group col-md-12 m-0">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger">
                                {{ $error }}
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group col-md-4">
                        <label for="nome" class="obrigatorio">Nome</label>
                        <input type="text" class="form-control" name="nome" value="{{$category->nome or old('nome')}}"
                               placeholder="Nome" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="select-tipo-categoria" class="obrigatorio">Tipo</label>
                        <select id="select-tipo-categoria" name="tipo" class="custom-select" required>
                            @foreach($types as $value => $name)
                                <option value="{{$value}}"
                                @if(!empty($category)){{ $category->tipo == $value ? 'selected' :''}} @endif>{{$name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="image-group" class="form-group col-md-4">
                        <label for="selectimagegroup" class="obrigatorio">Grupo de imagens</label>
                        <select id="selectimagegroup" name="grupo_imagem" class="custom-select" required>
                                <option id="option-vazia" value>Selecione..</option>
                                @foreach($group_images_produto as $value => $name)
                                    <option class="produtocategoria" value="{{$value}}"
                                    @if(!empty($category)) {{ $category->grupo_imagem == $value ? 'selected' :''}} @endif>{{$name}}</option>
                                @endforeach

                                @foreach($group_images_aluminio as $value => $name)
                                    <option class="aluminiocategoria" value="{{$value}}"
                                    @if(!empty($category)) {{ $category->grupo_imagem == $value ? 'selected' :''}} @endif>{{$name}}</option>
                                @endforeach

                                @foreach($group_images_componente as $value => $name)
                                    <option class="componentecategoria" value="{{$value}}"
                                    @if(!empty($category)) {{ $category->grupo_imagem == $value ? 'selected' :''}} @endif>{{$name}}</option>
                                @endforeach
                        </select>
                    </div>

                </div>

                <button id="bt-category-invisible" class="d-none" type="submit"></button>

            </form>
        </div>
    </div>
@endsection