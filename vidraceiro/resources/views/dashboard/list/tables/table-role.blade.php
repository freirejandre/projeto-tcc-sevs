<table class="table table-hover">
    <thead>
    <tr>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Id</th>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Nome</th>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Descrição</th>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Ação</th>
    </tr>
    </thead>
    <tbody>
    @foreach($roles as $role)
        <tr>
            <th scope="row">{{ $role->id }}</th>
            <td>{{ $role->nome }}</td>
            <td>{{ $role->descricao }}</td>
            <td>
                <a class="btn-link {{$role->nome === 'admin' ? 'disabled' :'' }}" href="{{ route('roles.edit',['id' => $role->id]) }}">
                    <button class="btn btn-warning mb-1 card-shadow-1dp pl-2 pr-2" title="Editar" {{$role->nome === 'admin' ? 'disabled' :'' }}>
                        <i class="fas fa-edit pl-1"></i>
                    </button>
                </a>
                <a class="btn-link {{$role->nome === 'admin' ? 'disabled' :'' }}" href="{{ route('roles.permission.show',['id' => $role->id]) }}">
                    <button class="btn btn-dark mb-1 card-shadow-1dp" title="Permissão" {{$role->nome === 'admin' ? 'disabled' :'' }}>
                        <i class="fas fa-key"></i>
                    </button>
                </a>
                <a class="btn-link {{$role->nome === 'admin' ? 'disabled' :'' }}" onclick="deletar(event,this.id,'roles')" id="{{ $role->id }}">
                    <button class="btn btn-danger mb-1 card-shadow-1dp" title="Deletar" {{$role->nome === 'admin' ? 'disabled' :'' }}>
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<p class="m-4 text-center"
   style="font-weight: 600;"> {{ ($roles->count() == 0) ? 'Nenhuma resultado encontrado': ''}}</p>
{{ $roles->links('layouts.pagination') }}


