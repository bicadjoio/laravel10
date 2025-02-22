<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Uploads</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>
<body>

<nav class="container-fluid">
    <ul>
        <li><strong>Histórico de Uploads</strong></li>
    </ul>
    <ul>
        <li><a href="{{ route('dashboard') }}" role="button">Home</a></li>
    </ul>
</nav>

<main class="container">
    @section('content')
    <div class="grid">
        <section>
            <hgroup>
                <h2>Histórico de Uploads</h2>
                <h3>Veja o histórico dos arquivos enviados</h3>
            </hgroup>
          
                <!-- Formulário de Filtro -->
                @if ($user->is_admin == 1)
                <!-- Formulário de Filtro para Administradores -->
                <form action="{{ route('upload_history') }}" method="GET">
                    <div class="form-group">
                        <label for="i_cod_cnes_fonte">Filtrar por Código CNES Fonte:</label>
                        <select name="i_cod_cnes_fonte" id="i_cod_cnes_fonte" class="form-control">
                            <option value="">Selecione um Código CNES Fonte</option>
                            @foreach ($cnesCodes as $code)
                                <option value="{{ $code }}" {{ request('i_cod_cnes_fonte') == $code ? 'selected' : '' }}>{{ $code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="in_conferido" {{ request('in_conferido') ? 'checked' : '' }}>
                            Mostrar apenas arquivos conferidos
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </form>
            @endif
          
          
          
          
            <table role="grid">
                <thead>
                    <tr>
                        <th>CNES Code</th>
                        <th>Data de Envio</th>
                        <th>Nome do Arquivo</th>
                        <th>Quantidade de Registros</th>
                        <th>Conferido</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($histories as $history)
                    <tr>
                        <td>{{ $history->i_cod_cnes_fonte}}</td>
                        <td>{{ $history->upload_date }}</td>
                        <td><a href="{{ route('download.file', $history->file_name) }}">{{ $history->file_name }}</a></td>
                        <td>{{ $history->record_count }}</td>
                        <td>
                            <form action="{{ route('upload_history.marcarConferido', $history->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="checkbox" name="in_conferido" onchange="this.form.submit();" {{ $history->in_conferido ? 'checked' : '' }}>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>
</main>

<footer class="container">
    <small><a href="#">Termos de Uso</a> • <a href="#">Política de Privacidade</a></small>
</footer>

</body>
</html>
