<table class="table table-hover">
    <thead>
    <tr>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Id</th>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Nome</th>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Cpf | Cnpj</th>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Telefone</th>
        @can('gerenciamento')<th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Situação</th>@endcan
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Ação</th>
    </tr>
    </thead>
    <tbody>
    @foreach($clients as $client)
        <tr>
            <th scope="row">{{$client->id}}</th>
            <td>{{$client->nome}}</td>
            @php
                $campoNome = '';
                $documento = $client->documento;
                $mask = '';
                if(strlen($client->documento) <= 11){
                    $campoNome = 'Cpf:';
                    $mask = '###.###.###-##';
                }else{
                    $campoNome = 'Cnpj:';
                    $mask = '##.###.###/####-##';
                }

            @endphp
            <td>{{App\Http\Controllers\PdfController::mask($documento,$mask)}}</td>
            @php
                $telefone = $client->contact()->first()->telefone;
                if($telefone !== null){
                // primeiro substr pega apenas o DDD e coloca dentro do (), segundo subtr pega os números do 3º até faltar 4, insere o hifem, e o ultimo pega apenas o 4 ultimos digitos
                $telefone="(".substr($telefone,0,2).") ".substr($telefone,2,-4)." - ".substr($telefone,-4);
                }

            @endphp
            <td class="telefone">{{$telefone}}</td>
            @can('gerenciamento')
            <td>
                <span class="badge {{$client->status === 'EM DIA'? 'badge-success' : 'badge-danger'}}">{{$client->status}}</span>
            </td>
            @endcan
            <td>

                @if(Request::is('restore'))
                    <a class="btn-link" href="{{ route('restore.restore',['tipo'=>'clientes','id'=> $client->id]) }}">
                        <button class="btn btn-light mb-1 card-shadow-1dp" title="Restaurar"><i class="fas fa-undo-alt"></i></button>
                    </a>
                @else
                    <a class="btn-link" href="{{ route('clients.show',['id'=> $client->id]) }}">
                        <button class="btn btn-light mb-1 card-shadow-1dp" title="Ver"><i class="fas fa-eye"></i></button>
                    </a>

                    <a class="btn-link" href="{{ route('clients.edit',['id'=> $client->id]) }}">
                        <button class="btn btn-warning mb-1 card-shadow-1dp pl-2 pr-2" title="Editar"><i
                                    class="fas fa-edit pl-1"></i></button>
                    </a>

                    @if($client->status === 'EM DIA' && !$client->haveBudgetApproved())
                        <a class="btn-link" onclick="deletar(event,this.id,'clients')" id="{{ $client->id }}">
                            <button class="btn btn-danger mb-1 card-shadow-1dp" title="Deletar"><i
                                        class="fas fa-trash-alt"></i></button>
                        </a>
                    @endif
                @endif

            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<p class="m-4 text-center"
   style="font-weight: 600;"> {{ ($clients->count() == 0) ? 'Nenhum cliente encontrado': ''}}</p>
{{ $clients->links('layouts.pagination') }}
