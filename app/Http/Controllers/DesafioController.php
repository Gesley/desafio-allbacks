<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


/**
 * Class DesafioController
 * @package App\Http\Controllers
 */
class DesafioController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {

        if ($request->isMethod('post')) {
            $this->uploadfile($request->all());

        }

        return view('desafio.index');
    }

    /**
     * @param $file
     * @return \Illuminate\Http\RedirectResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function uploadfile( $file)
    {

        if( $file['arquivoxml']->getClientMimeType() != 'text/xml') {

            return back()->with('error','Tipo de arquivo inválido!');
        }

        $this->atualizaPlanilha();

        return back()->with('success','Planilha Atualizada! Arquivos disponiveis na pasta public.');
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function atualizaPlanilha()
    {
        $pasta    = public_path();
        $extencao = pathinfo($_FILES['arquivoxml']['name'], PATHINFO_EXTENSION);
        $nome_arquivo = pathinfo($_FILES['arquivoxml']['name'], PATHINFO_FILENAME);

        $data_atual = date("Y-m-d");

        $arquivo_enviado = $pasta . '\\'. $nome_arquivo . '-' . $data_atual . '.' .$extencao;


        if(is_file($arquivo_enviado)) {
            unlink($arquivo_enviado);
        }

        move_uploaded_file($_FILES['arquivoxml']['tmp_name'], $arquivo_enviado );
        
        $arquivo_editado = $pasta . '\clientes.xlsx';

        $ssheet = $this->atualizaTorcedores($arquivo_editado, $arquivo_enviado);

        $this->generateNewSheet($arquivo_editado, $data_atual, $ssheet);
    }

    /**
     * @param $arquivo_editado path do arquivo a ser editado
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function atualizaTorcedores($arquivo_editado, $arquivo_enviado)
    {
        $ssheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($arquivo_editado);
        $wsheet = $ssheet->getActiveSheet();

        $xml_file = file_get_contents($arquivo_enviado);
        $ob = simplexml_load_string($xml_file);
        $json = json_encode($ob);
        $dump = json_decode($json,true);

        $i=0;

        $first_new_row = $wsheet->getHighestRow()+1;

        /* Atualiza os torcedores */

        while ($i < sizeof($dump['torcedor'])){
            $j=1;/*Counter that iterates over all the columns*/

            $row = $first_new_row+$i;

            if ($dump['torcedor'][$i]['@attributes']['ativo'] == ""){
                $dump['torcedor'][$i]['@attributes']['ativo'] = 'NÃO';
            }
            else{
                $dump['torcedor'][$i]['@attributes']['ativo'] = 'SIM';
            }

            foreach($dump['torcedor'][$i]['@attributes'] as $key => $value){

                $wsheet->setCellValue(chr($j+64) . $row,$value);
                $j++;
            }
            $i++;
        }

        return $ssheet;
    }

    /**
     * @param $arquivo_editado path do arquivo editado
     * @param $data_atual
     * @param $ssheet
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function generateNewSheet($arquivo_editado, $data_atual, $ssheet)
    {
        /*Setting destination folder, extension, and name for the output spreadsheet*/
        $new_sheet_dir = public_path();
        $new_sheet_ext = pathinfo($arquivo_editado, PATHINFO_EXTENSION);
        $new_sheet_name = pathinfo($arquivo_editado, PATHINFO_FILENAME);
        $new_sheet = $new_sheet_dir . '\\' . $new_sheet_name . $data_atual . '.' . $new_sheet_ext;

        /*Saving output spreadsheet*/
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($ssheet);
        $writer->save($new_sheet);
    }

    /**
     * @param $delimeter
     * @param $initialString
     * @return bool|string
     */
    public function after_last ($delimeter, $initialString)
    {
        return substr($initialString, strpos($initialString, "$delimeter") + 6);
    }


}
