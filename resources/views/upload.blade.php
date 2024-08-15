<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Upload CSV</title>
</head>
<body>
    <nav class="container-fluid">
        <ul>
            <li><strong>Upload CSV</strong></li>
        </ul>
        <ul>
            <li><a href="{{ route('dashboard') }}" role="button">Home</a></li>
            <li><a href="{{ route('upload_history') }}" target="_blank" role="button">Ver Histórico de Envios</a></li>
            <li><a href="https://www.ibge.gov.br/explica/codigos-dos-municipios.php#DF" target="_blank" role="button">Codigos IBGE</a></li>
            <li><a href="https://cnes.datasus.gov.br/pages/estabelecimentos/consulta.jsp" target="_blank" role="button">CNES Fonte</a></li>
            <li><a href="{{ asset('documentos/modelo_migracao.xlsx') }}" target="_blank" role="button">Baixar Dicionário de Dados</a></li>
        </ul>
    </nav>

    <main class="container">
        <div class="grid">
            <section>
                <hgroup>
                    <h2>Envio de Arquivo CSV</h2>
                    <h3>Escolha um arquivo CSV para enviar</h3>
                </hgroup>
                
                @if ($errors->any())
                    <div>
                        <strong>Erros:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('upload.csv') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="csv_file" required>
                    <button type="submit">Upload</button>
                </form>

                @if (session('success'))
                    </p>
                    <div>
                        <strong> {{ session('success') }} </strong>
                    </div>
                @endif

            </section>
        </div>
    </main>

    {{-- <section aria-label="Arquivos CSV carregados">
        <div class="container">
            <article>
                <hgroup>
                    <h3>Arquivos CSV carregados</h3>
                <ul>
					@foreach (Storage::files('csv') as $file)
					<li>{{ basename($file) }}</li>
					@endforeach
				</ul>
            </article>
        </div>
    </section> --}}

    <footer class="container">
        <small><a href="#">Termos de Uso</a> • <a href="#">Política de Privacidade</a></small>
    </footer>
</body>
</html>
