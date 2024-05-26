<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\UploadHistory;


class CsvController extends Controller
{
    public function showUploadForm()
    {
        return view('upload');
    }

    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        $csvData = file($path);
        $recordCount = count($csvData); // Contando o número de linhas processadas do CSV
       
         // Usando ponto e vírgula como delimitador e garantindo que cada linha é tratada como string
         $csvData = array_map(function($line) {
            return str_getcsv(trim($line), ';');
        }, $csvData);

        $header = array_map('trim', array_shift($csvData)); // Limpa espaços do cabeçalho

        // Campos obrigatórios
        $requiredFields = [
            'i_cod_cnes_fonte', 'i_nome_estab_fonte', 'i_prontuario', 'i_cpf', 'i_doc_paciente', 'i_nome_paciente', 
            'i_nome_mae_paciente', 'i_sexo', 'i_dt_nasc', 'i_raca_cor', 'i_nacionalidade', 'i_grau_instrucao', 
            'i_estado_civil', 'i_cod_profissao', 'i_logradouro', 'i_ra', 'i_estado_residencia', 'i_estado_naturalidade','i_municipio_residencia', 
            'i_desc_topografia', 'i_cod_topografia', 'i_desc_morfologia', 'i_cod_morfologia', 'i_indic_caso_raro', 
            'i_meio_diagnostico', 'i_extensao', 'i_dt_diagnostico', 'i_registrador'
        ];

        // Converte os campos obrigatórios e o cabeçalho para minúsculas para garantir a comparação correta
        $header = array_map('strtolower', $header);
        $requiredFields = array_map('strtolower', $requiredFields);

       // Verificar campos obrigatórios ausentes
       $missingFields = array_diff($requiredFields, $header);
       if (!empty($missingFields)) {
           return back()->withErrors(['missing_fields' => "Campos obrigatórios ausentes: " . implode(', ', $missingFields)]);
       }


        // Verificar e corrigir caracteres especiais
        $cleanedData = [];
        foreach ($csvData as $row) {
            $cleanedRow = array_map(function($value) {
                return str_replace([chr(10), chr(13)], ' ', $value);
            }, $row);
            $cleanedData[] = $cleanedRow;
        }
        

        // Verificar formato de data e campos obrigatórios em branco
        $dateIndexDtNasc = array_search('i_dt_nasc', $header);
        $dateIndexDtDiagnostico = array_search('i_dt_diagnostico', $header);
        $cpfIndex = array_search('i_cpf', $header);
        $pacienteNameIndex = array_search('i_nome_paciente', $header);
        $cnesIndex = array_search('i_cod_cnes_fonte', $header);

        $sexoIndex = array_search('i_sexo', $header);
        $raca_corIndex = array_search('i_raca_cor', $header);
        $nacionalidadeIndex = array_search('i_nacionalidade', $header);
        $grauInstrucaoIndex = array_search('i_grau_instrucao', $header);
        $estadoCivilIndex = array_search('i_estado_civil', $header);
        $estadoNaturalidadeIndex = array_search('i_estado_naturalidade', $header);
        $estadoResidenciaIndex = array_search('i_estado_residencia', $header);
        $municipioResidenciaIndex = array_search('i_municipio_residencia', $header);

        $desc_topografiaIndex = array_search('i_desc_topografia', $header);
        $cod_topografiaIndex = array_search('i_cod_topografia', $header);
        $desc_morfologiaIndex = array_search('i_desc_morfologia', $header);
        $cod_morfologiaIndex = array_search('i_cod_morfologia', $header);
        $indic_caso_raroIndex = array_search('i_indic_caso_raro', $header);
        $meio_diagnosticoIndex = array_search('i_meio_diagnostico', $header);
        $extensaoIndex = array_search('i_extensao', $header);
        

        
        

        
        

        $invalidDatesDtNasc = [];
        $invalidDatesDtDiagnostico = [];
        $invalidCpfs = [];
        $invalidPaciente = [];
        $invalidCnes = [];
        $invalidMunicipio  = [];
        $errors = [];

        $invalidSexos = [];
        $invalidRaca_cor = [];
        $invalidNacionalidade = [];
        $invalidgrauInstrucao = [];
        $invalidEstadoCivil = [];
        $invalidEstadoNaturalidade = [];
        $invalidEstadoResidencia = [];
        $invalidMunicipioResidencia = [];


        $invalidDesc_topografia = [];
        $invalidCod_topografia = [];
        $invalidDesc_morfologia = [];
        $invalidCod_morfologia = [];
        $invalidIndic_caso_raro = [];
        $invalidMeio_diagnostico = [];
        $invalidExtensao = [];


        

        foreach ($cleanedData as $key => $row) {

            // Verificar formato de data
            if (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $row[$dateIndexDtNasc])) {
                $invalidDatesDtNasc[] = $key + 2; // +2 para considerar o cabeçalho e o índice baseado em 0
            }

            if (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $row[$dateIndexDtDiagnostico])) {
                $invalidDatesDtDiagnostico[] = $key + 2; // +2 para considerar o cabeçalho e o índice baseado em 0
            }

            // Verificar codigo CNES - Decimal 7
            if (isset($row[$cnesIndex]) && !preg_match('/^\d{7}$/', $row[$cnesIndex])) {
                $invalidCnes[] = $key + 2; // +2 para considerar o cabeçalho e o índice baseado em 0
            }

            // Verificar formato de CPF
            if (!$this->validateCpf($row[$cpfIndex])) {
                $invalidCpfs[] = $key + 2;
            }

            // Verifica se o campo está em branco ou com valor inválido
            $sexo = $row[$sexoIndex] ?? '9'; // Default para 9 se estiver em branco
            if (!in_array($sexo, ['1', '2', '9'])) {
                $invalidSexos[] = $key + 2; // Adiciona erro se não for 1, 2 ou 9
            }
            $row[$sexoIndex] = $sexo; // Atribui valor corrigido de volta à linha

            // Verifica se o campo está em branco ou com valor inválido
            $racaCor = $row[$raca_corIndex] ?? '6'; // Default para 9 se estiver em branco
            if (!in_array($racaCor, ['1', '2', '3', '4', '5', '6'])) {
                $invalidRaca_cor[] = $key + 2; // Adiciona erro se não for 1,2,3,4,5,6
            }
            $row[$raca_corIndex] = $racaCor; // Atribui valor corrigido de volta à linha

            // Verificar i_nacionalidade - Decimal 2
            if (isset($row[$nacionalidadeIndex]) && !preg_match('/^\d{2}$/', $row[$nacionalidadeIndex])) {
                $invalidNacionalidade[] = $key + 2; // +2 para considerar o cabeçalho e o índice baseado em 0
            }

            // Verifica se o campo i_grau_instrucao está em branco ou com valor inválido
            $temp = $row[$grauInstrucaoIndex] ?? '9'; // Default para 9 se estiver em branco
            if (!in_array($temp, ['0', '1', '2', '3', '4', '5', '9'])) {
                $invalidgrauInstrucao[] = $key + 2; // Adiciona erro se não for 0,1,2,3,4,5,9
            }
            $row[$grauInstrucaoIndex] = $temp; // Atribui valor corrigido de volta à linha

            // Verifica se o campo i_estado_civil está em branco ou com valor inválido
            $temp = $row[$estadoCivilIndex] ?? '9'; // Default para 9 se estiver em branco
            if (!in_array($temp, ['1', '2', '3', '4', '5', '9'])) {
                $invalidEstadoCivil[] = $key + 2; // Adiciona erro se não for 0,1,2,3,4,5,9
            }
            $row[$estadoCivilIndex] = $temp; // Atribui valor corrigido de volta à linha

            // Verificar i_estado_naturalidade - Decimal 2
            $temp = $row[$estadoNaturalidadeIndex] ?? '0'; // Default para 9 se estiver em branco 
            if (isset($row[$estadoNaturalidadeIndex]) && !preg_match('/^\d{2}$/', $row[$estadoNaturalidadeIndex]) && !in_array($temp, ['0'])) {
                $invalidEstadoNaturalidade[] = $key + 2; // +2 para considerar o cabeçalho e o índice baseado em 0
            }
            // Verificar i_estado_residencia - Decimal 2
            if (isset($row[$estadoResidenciaIndex]) && !preg_match('/^\d{2}$/', $row[$estadoResidenciaIndex]) ) {
                $invalidEstadoResidencia[] = $key + 2; // +2 para considerar o cabeçalho e o índice baseado em 0
            }      
            
            // Verificar i_estado_residencia - Decimal 2
            if (isset($row[$municipioResidenciaIndex]) && !preg_match('/^\d{7}$/', $row[$municipioResidenciaIndex]) ) {
                $invalidMunicipioResidencia[] = $key + 2; // +2 para considerar o cabeçalho e o índice baseado em 0
            }      

            // Verificar campos em branco
            if  ( empty($row[$pacienteNameIndex]))  {
                $invalidPaciente[] = $key + 2;
            }

            // Verificar campos em branco
            if  ( empty($row[$desc_topografiaIndex]))  {
                $invalidDesc_topografia[] = $key + 2;
            }

            // Verificar campos em branco
            if  ( empty($row[$cod_topografiaIndex]))  {
                $invalidCod_topografia[] = $key + 2;
            }

            // Verificar campos em branco
            if  ( empty($row[$desc_morfologiaIndex]))  {
                $invalidDesc_morfologia[] = $key + 2;
            }

            // Verificar campos em branco
            if  ( empty($row[$cod_morfologiaIndex]))  {
                $invalidCod_morfologia[] = $key + 2;
            }
            
            // Verifica se o campo i_estado_civil está em branco ou com valor inválido
            if (!in_array($row[$indic_caso_raroIndex], ['1', '2'])) {
                $invalidIndic_caso_raro[] = $key + 2; // Adiciona erro se não for 0,1,2,3,4,5,9
            }

            $temp = $row[$meio_diagnosticoIndex ] ?? '9'; // Default para 9 se estiver em branco
            if (!in_array($temp, ['0', '1', '2', '4', '5', '6', '7', '9'])) {
                $invalidMeio_diagnostico [] = $key + 2; // Adiciona erro se não for 0,1,2,3,4,5,9
            }
            $row[$meio_diagnosticoIndex] = $temp; // Atribui valor corrigido de volta à linha

              
            $temp = $row[$extensaoIndex ] ?? '9'; // Default para 9 se estiver em branco
            if (!in_array($temp, ['1', '2', '3', '4', '9'])) {
                  $invalidExtensao [] = $key + 2; // Adiciona erro se não for 0,1,2,3,4,5,9
            }
            $row[$extensaoIndex] = $temp; // Atribui valor corrigido de volta à linha
            
        }

        if (!empty($invalidDatesDtNasc)) {
            $errors[] = "Formato de data inválido no campo 'i_dt_nasc'. Linha(s): " . implode(', ', $invalidDatesDtNasc);
        }

        if (!empty($invalidDatesDtDiagnostico)) {
            $errors[] ="Formato de data inválido no campo 'i_dt_diagnostico'. Linha(s):  " . implode(', ', $invalidDatesDtDiagnostico);
        }

        if (!empty($invalidCnes)) {
            $errors[] ="Formato inválido no campo 'i_cod_cnes_fonte'. Linha(s):  " . implode(', ', $invalidCnes);
        }

        if (!empty($invalidEstado)) {
            $errors[] ="Formato inválido no campo 'i_estado_residencia'. Linha(s):  " . implode(', ', $invalidEstado);
        }

        if (!empty($invalidMunicipio)) {
            $errors[] ="Formato inválido no campo 'i_municipio_residencia'. Linha(s):  " . implode(', ', $invalidMunicipio);
        }

        if (!empty($invalidCpfs)) {
            $errors[] ="Formato de CPF inválido no campo 'i_cpf'. Linha(s):  " . implode(', ', $invalidCpfs);
        }

        if (!empty($invalidCnes)) {
            $errors[] ="Formato inválido no campo 'i_cod_cnes_fonte'. Linha(s):  " . implode(', ', $invalidCnes);
        }

        if (!empty($blankFields)) {
            $errors[] ="Campos obrigatórios em branco. i_nome_paciente Linha(s):  " . implode(', ', $blankFields);
        }

        if (!empty($invalidSexos)) {
            $errors[] = "Valor inválido no campo 'i_sexo', aceita apenas 1- Masculino 2- Feminino 9- Ignorado.  Linha(s): " . implode(', ', $invalidSexos);
        }

        if (!empty($invalidRaca_cor)) {
            $errors[] = "Valor inválido no campo 'i_raca_cor', aceita apenas 1-Branco 2-Preta 3-Amarela 4-Parda 5-Indígena 6-Sem informação.  Linha(s): " . implode(', ', $invalidRaca_cor);
        }

        if (!empty($invalidNacionalidade)) {
            $errors[] = "Valor inválido no campo 'i_nacionalidade', aceita apenas numerico(2).  Linha(s): " . implode(', ', $invalidNacionalidade);
        }

        if (!empty($invalidgrauInstrucao)) {
            $errors[] = "Valor inválido no campo 'i_grau_instrucao', aceita apenas 0-Sem escolaridade 1-Fundamental I, 2-Fundamental II 3-Médio 4-Superior Incompleto 5-Superior Completo 9-Sem informação.  Linha(s): " . implode(', ', $invalidgrauInstrucao);
        }

        if (!empty($invalidEstadoCivil)) {
            $errors[] = "Valor inválido no campo 'i_estado_civil', aceita 1-Solteiro 2-Casado 3-Viúvo 4-Separdo Judicialmente 5-União Consensual 9-Sem Informação.  Linha(s): " . implode(', ', $invalidEstadoCivil);
        }

        if (!empty($invalidEstadoNaturalidade)) {
            $errors[] = "Valor inválido no campo 'i_estado_naturalidade', aceita numerico(2).  Linha(s): " . implode(', ', $invalidEstadoNaturalidade);
        }

        if (!empty($invalidEstadoResidencia)) {
            $errors[] = "Valor inválido no campo 'i_estado_residencia', aceita numerico(2).  Linha(s): " . implode(', ', $invalidEstadoResidencia);
        }

        if (!empty($invalidMunicipioResidencia)) {
            $errors[] = "Valor inválido no campo 'i_municipio_residencia', aceita numerico(7).  Linha(s): " . implode(', ', $invalidMunicipioResidencia);
        }

        if (!empty($invalidDesc_topografia)) {
            $errors[] = "Valor inválido no campo 'i_desc_topografia', não aceita em branco.  Linha(s): " . implode(', ', $invalidDesc_topografia);
        }
        
        if (!empty($invalidCod_topografia)) {
            $errors[] = "Valor inválido no campo 'i_cod_topografia', não aceita em branco.  Linha(s): " . implode(', ', $invalidCod_topografia);
        }

        if (!empty($invalidDesc_morfologia)) {
            $errors[] = "Valor inválido no campo 'i_desc_morfologia', não aceita em branco.  Linha(s): " . implode(', ', $invalidDesc_morfologia);
        }

        if (!empty($invalidCod_morfologia)) {
            $errors[] = "Valor inválido no campo 'i_cod_morfologia', não aceita em branco.  Linha(s): " . implode(', ', $invalidCod_morfologia);
        } 
        
        if (!empty($invalidIndic_caso_raro)) {
            $errors[] = "Valor inválido no campo 'i_indic_caso_raro', aceita somente 1 = caso raro e 2 caso normal.  Linha(s): " . implode(', ', $invalidIndic_caso_raro);
        }  

        if (!empty($invalidMeio_diagnostico)) {
            $errors[] = "Valor inválido no campo 'i_meio_diagnostico', aceita somente 0-SDO,  1-Clínico, 2-Pesquisa, 4-Marcador tumoral,  5-Citológico, 6-Histologia da metástase, 7-Histológico, 9-Ignorado.  Linha(s): " . implode(', ', $invalidIndic_caso_raro);
        }  

        if (!empty($invalidExtensao)) {
            $errors[] = "Valor inválido no campo 'i_extensao', aceita somente 1-Localizado 2-Metástase 3- In situ 4-Não se aplica 9-Sem Informação.  Linha(s): " . implode(', ', $invalidIndic_caso_raro);
        }  

        if (!empty($errors)) {
            return back()->withErrors(['errors' => $errors]);
        }

        if (!Storage::exists('registro_cancer_csv')) {
            Storage::makeDirectory('registro_cancer_csv');
        }

        // Salvar o arquivo
        $user = Auth::user();
        $filename = $user->i_cod_cnes_fonte . '_' . now()->format('Ymd_His') . '.csv';
        $file->storeAs('registro_cancer_csv', $filename);

        // Criando o histórico de upload
        UploadHistory::create([
        'i_cod_cnes_fonte' => $user->i_cod_cnes_fonte, // Suponha que você tenha esta informação disponível
        'upload_date' => now(),
        'user_id' => $user->id,
        'file_name' => $filename,
        'record_count' => $recordCount
        ]);

        return back()->with('success', 'CSV validado e salvo com sucesso!');
    }

    private function validateCpf($cpf)
    {
        // Remover caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Verificar se o CPF tem 11 dígitos
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verificar se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Calcular e verificar dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}
