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
    <div class="grid">
        <section>
            <hgroup>
                <h2>Histórico de Uploads</h2>
                <h3>Veja o histórico dos arquivos enviados</h3>
            </hgroup>
            <table role="grid">
                <thead>
                    <tr>
                        <th>CNES Code</th>
                        <th>Data de Envio</th>
                        <th>Nome do Arquivo</th>
                        <th>Quantidade de Registros</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($histories as $history)
                    <tr>
                        <td>{{ $history->i_cod_cnes_fonte}}</td>
                        <td>{{ $history->upload_date }}</td>
                        <td>{{ $history->file_name }}</td>
                        <td>{{ $history->record_count }}</td>
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
