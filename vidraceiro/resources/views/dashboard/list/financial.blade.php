@extends('layouts.app')
@section('content')

    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card-material custom-card">

            <!-- Inicio tab do financeiro-->
            <input type="hidden" id="tabSession" data-value="{{session('tab')? session('tab') : ''}}" />
                <div class="nav nav-tabs" id="nav-tab">
                    @for($i = 0; $i < count($titulotabs); $i++)
                        @if($i == 0)
                            <!-- <a class="nav-item nav-link active noborder-left" id="nav-{{$titulotabs[$i]}}-tab"
                               data-id="{{lcfirst($titulotabs[$i])}}"
                               data-toggle="tab"
                               href="#nav-{{$titulotabs[$i]}}" role="tab"
                               aria-controls="nav-{{$titulotabs[$i]}}"
                               aria-selected="true">{{$titulotabs[$i]}}</a> -->
                            <a class="tabs-financial nav-item nav-link {{ session('tab')? '' : 'current' }}"
                                data-tab="nav-{{$titulotabs[$i]}}-tab"
                                data-id="{{lcfirst($titulotabs[$i])}}">{{$titulotabs[$i]}}</a>
                        @else
                            <a class="tabs-financial nav-item nav-link"
                                data-tab="nav-{{$titulotabs[$i]}}-tab"
                                data-id="{{lcfirst($titulotabs[$i])}}">{{$titulotabs[$i]}}</a>
                            <!-- <a class="nav-item nav-link" id="nav-{{$titulotabs[$i]}}-tab"
                               data-id="{{lcfirst($titulotabs[$i])}}"
                               data-toggle="tab"
                               href="#nav-{{$titulotabs[$i]}}" role="tab"
                               aria-controls="nav-{{$titulotabs[$i]}}"
                               aria-selected="false">A receber</a> -->
                        @endif
                    @endfor
                </div>

            
            <!-- Fim tab do financeiro-->


            <!--Inicio Conteudo de cada tab -->
            
                <!-- <div class="tab-pane fade show active" id="nav-{{$titulotabs[0]}}" role="tabpanel"
                     aria-labelledby="nav-{{$titulotabs[0]}}-tab"> -->
                <div id="nav-{{$titulotabs[0]}}-tab" class="tab-content current">
                    <form class="formulario" method="POST" role="form"
                          action="{{route('financial.store')}}">
                        @csrf
                        <div class="form-row">

                            <div class="form-group col-12">

                                @if(session('success'))
                                    <div class="alerta p-0">
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    </div>
                                @elseif(session('error'))
                                    <div class="alerta p-0">
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                    </div>
                                @endif
                                @foreach($errors->all() as $error)
                                    <div class="alert alert-danger">
                                        {{ $error }}
                                    </div>
                                @endforeach

                            </div>

                            <div class="form-group col-md-2">
                                <label for="select-tipo" class="obrigatorio">Nova</label>
                                <select id="select-tipo" name="tipo" class="custom-select" required>
                                    <option value="RECEITA" selected>Receita</option>
                                    <option value="DESPESA">Despesa</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="descricao">Descrição</label>
                                <input type="text" maxlength="100" class="form-control" id="descricao" name="descricao">
                            </div>

                            <div class="form-group col-md-2">
                                <label for="valor" class="obrigatorio">Valor</label>
                                <input type="number" step=".01" class="form-control" id="valor" name="valor" required>
                            </div>

                            <div class="form-group col-md-2 align-self-end">
                                <button class="btn btn-primary btn-block btn-custom" type="submit">Adicionar</button>
                            </div>
                            @php
                                $receitas = 0.00;
                                $despesas = 0.00;
                                foreach($allfinancial as $financial){
                                    if($financial->tipo === 'RECEITA'){
                                        $receitas += $financial->valor;
                                    }else{
                                        $despesas += $financial->valor;
                                    }
                                }
                                $saldo = number_format(($receitas - $despesas),2,',','.');
                                $receitas = number_format(($receitas),2,',','.');
                                $despesas = number_format(($despesas),2,',','.');
                            @endphp

                            <div class="form-group col-md-12 mt-2 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item active" style="background-color: #4264FB; border-color: #4264FB">Total geral</li>
                                    <li class="list-group-item" style="color:#28a745;">Total Receitas: R${{$receitas}}</li>
                                    <li class="list-group-item" style="color:#dc3545;">Total Despesas: R${{$despesas}}</li>
                                    <li class="list-group-item" style="color:#191919;">Saldo:
                                        <span style="color:{{$saldo > 0? '#28a745':($saldo < 0?'#dc3545':'')}}">R${{$saldo}}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-row col-12 m-0 formulario px-0 justify-content-between">
                                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-2">
                                    <label for="paginatecaixa">Mostrar</label>
                                    <select id="paginatecaixa" name="paginate" class="custom-select"
                                            onchange="ajaxPesquisaLoad('{{url('financial')}}?caixa=1&search='+$('#searchcaixa').val()+'&paginate='+$('#paginatecaixa').val()+'&period='+$('#period').val(),'caixa')">
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-2">
                                    <label for="period">Período</label>
                                    <select id="period" name="period" class="custom-select"
                                            onchange="ajaxPesquisaLoad('{{url('financial')}}?caixa=1&search='+$('#searchcaixa').val()+'&paginate='+$('#paginatecaixa').val()+'&period='+$('#period').val(),'caixa')">
                                        <option value="hoje" selected>Hoje</option>
                                        <option value="semana">Últimos 7 dias</option>
                                        <option value="mes">Últimos 30 dias</option>
                                        <option value="semestre">Últimos 180 dias</option>
                                        <option value="anual">Últimos 360 dias</option>
                                        <option value="tudo">Todos</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4">
                                    <label for="searchcaixa">Pesquisar</label>
                                    <input type="text" class="form-control"
                                           onkeyup="ajaxPesquisaLoad('{{url('financial')}}?caixa=1&search='+$('#searchcaixa').val()+'&paginate='+$('#paginatecaixa').val()+'&period='+$('#period').val(),'caixa')"
                                           value="{{ old('search') }}" id="searchcaixa" name="search" placeholder="Pesquisar">
                                </div>
                            </div>

                            <div class="table-responsive text-dark p-1" id="caixa">
                                @include('dashboard.list.tables.table-financial')
                            </div>

                        </div>
                    </form>

                </div>

                <!-- <div class="tab-pane fade" id="nav-{{$titulotabs[1]}}" role="tabpanel"
                     aria-labelledby="nav-{{$titulotabs[1]}}-tab"> -->
                <div id="nav-{{$titulotabs[1]}}-tab" class="tab-content">
                    <div class="form-row formulario">

                        @php
                            $receber = 0.00;

                            foreach($allInstallments as $installment){
                                $receber += $installment->valor_parcela + $installment->multa;
                            }
                            $receber = number_format($receber,2,',','.');
                        @endphp

                        <div class="form-group col-md-12 mt-2 mb-2">
                            <ul class="list-group">
                                <li class="list-group-item active" style="background-color: #4264FB; border-color: #4264FB">Total geral</li>
                                <li class="list-group-item text-dark" >A receber: <span style="color:{{$receber > 0?'#28a745':''}};">R${{$receber}}</span></li>
                            </ul>
                        </div>
                        <div class="form-row col-12 m-0 formulario px-0 justify-content-between">
                            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-2">
                                <label for="paginatereceber">Mostrar</label>
                                <select id="paginatereceber" name="paginate" class="custom-select"
                                        onchange="ajaxPesquisaLoad('{{url('financial')}}?receber=1&search='+$('#searchreceber').val()+'&paginate='+$('#paginatereceber').val(),'receber')">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4">
                                <label for="searchreceber">Pesquisar</label>
                                <input type="text" class="form-control"
                                       onkeyup="ajaxPesquisaLoad('{{url('financial')}}?receber=1&search='+$('#searchreceber').val()+'&paginate='+$('#paginatereceber').val(),'receber')"
                                       value="{{ old('search') }}" id="searchreceber" name="search" placeholder="Pesquisar">
                            </div>
                        </div>

                        <div class="table-responsive text-dark p-1" id="receber">
                            @include('dashboard.list.tables.table-installments')
                        </div>

                    </div>


                </div>
            
            <!--Final Conteudo de cada tab -->



        </div>
    </div>
@endsection
