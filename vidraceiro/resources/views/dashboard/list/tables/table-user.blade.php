<table class="table table-hover">
    <thead>
    <tr>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Id</th>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Nome</th>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">E-mail</th>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Ação</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <th scope="row">{{ $user->id }}</th>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <a class="btn-link" href="{{ route('users.edit',['id' => $user->id]) }}">
                    <button class="btn btn-warning mb-1 card-shadow-1dp pl-2 pr-2" title="Editar"><i class="fas fa-edit pl-1"></i></button>
                </a>
                <a class="btn-link" href="{{ route('users.role.show',['id' => $user->id]) }}">
                    <button class="btn btn-dark mb-1 card-shadow-1dp" title="Função"><i class="fas fa-id-card"></i></button>
                </a>
                <a class="btn-link" onclick="deletar(this.id,'users')" id="{{ $user->id }}">
                    <button class="btn btn-danger mb-1 card-shadow-1dp" title="Deletar"><i class="fas fa-trash-alt"></i></button>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<p class="m-4 text-center"
   style="font-weight: 600;"> {{ ($users->count() == 0) ? 'Nenhum usuario encontrado': ''}}</p>
{{ $users->links('layouts.pagination') }}

