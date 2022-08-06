<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/* 
 *  author: Eduardo Gomez
 */

class FileController extends Controller
{

    protected $roomData;
    protected $matrizTmp;

    public function __construct()
    {
        $this->roomData = array();
        $this->matrizTmp = array();
    }

    public function index()
    {

        $matrizArchivo = Storage::disk('local')->get('public/files/matriz.txt');
        $matrizFilas = explode("\r\n", $matrizArchivo);
        $count = 0;

        foreach ($matrizFilas as $singleLine) {
            if (str_split($singleLine)[0] !== '') {
                $this->roomData[] = str_split(str_replace(' ', '', $singleLine));
                $this->matrizTmp[] = array_map(
                    function ($a) {
                        $a = str_replace(array(0, 1), '', $a);
                        return $a;
                    },
                    str_split(str_replace(' ', '', $singleLine))
                );
                $matrizChanged[] = array_map(
                    function ($a) {
                        $a = str_replace(1, 0, $a);
                        return $a;
                    },
                    str_split(str_replace(' ', '', $singleLine))
                );
            }
        }

        $verification = [1,0,0];

         while($verification[0] > 0){
            for ($i = 0; $i < count($this->roomData); $i++)
            {
                for ($j = 0; $j < count($this->roomData[$i]); $j++){
                    $this->matrizTmp[$i][$j] = $this->roomGenerator($i, $j, 0);
                }
            }
            $verification = $this->getIlumination();
            if ($verification[0] == 0){
                break;
            }
            $matrizChanged = $this->roomGenerator($verification[1], $verification[2],1);
            $count = $count + 1;
        }

        return view('file.upload', compact('matrizChanged', 'count'));
    }

    public function getIlumination(){
        $big = 0;
        $suma = 0;
        $arriba = 0;
        $abajo = 0;
        $der = 0;
        $izq = 0;
        $iPosition = 0;
        $jPosition = 0;
        $data = 0;
        $contador = 0;
        
        for ($i = 0; $i < count($this->matrizTmp); $i++){
            
            for ($j = 0; $j < count($this->matrizTmp[$i]); $j++){
                $arriba = 0;
                $abajo = 0;
                $der = 0;
                $izq = 0;
                $data = 0;
                if ($this->matrizTmp[$i][$j] >= $big && $this->matrizTmp[$i][$j] !== 0){
                    $contador = 1;
                    while(($i - $contador) >= 0){

                        if ($this->matrizTmp[$i - $contador][$j] > 0){
                            $arriba = $arriba + 1;
                        }
                        if ($this->matrizTmp[$i - $contador][$j] == 0 && $this->roomData[$i - $contador][$j] == 1){
                            break;
                        }
                        $contador = $contador + 1;
                    }
                    $contador = 1;
                    while (($i + $contador) < count($this->matrizTmp)){
                        if ($this->matrizTmp[$i + $contador][$j] > 0 ){
                            $abajo = $abajo + 1;
                        }
                        if ($this->matrizTmp[$i + $contador][$j] == 0 && $this->roomData[$i + $contador][$j] == 1){
                            break;
                        }
                        
                        $contador = $contador + 1;
                    }
                    $contador = 1;
                    while (($j + $contador) < count($this->matrizTmp[$i])){

                        if ($this->matrizTmp[$i][$j + $contador] > 0){
                            $der = $der + 1;
                        }
                        if ($this->matrizTmp[$i][$j + $contador] == 0 && $this->roomData[$i][$j + $contador] == 1){
                            break;
                        }
                        
                        $contador = $contador + 1;

                    }
                    $contador = 1;
                    while (($j - $contador) >= 0){
                        if ($this->matrizTmp[$i][$j - $contador] > 0){
                            $izq = $izq + 1;
                        }
                        
                        if ($this->matrizTmp[$i][$j - $contador] == 0 && $this->roomData[$i][$j - $contador] == 1){
                            break;
                        }
                        $contador = $contador + 1;
                    }
                    $data = $arriba + $abajo + $der + $izq;
                    $big = $this->matrizTmp[$i][$j];
                    if ($data > $suma || $suma == 0 && $data == 0){
                        $suma = $data;
                        $iPosition = $i;
                        $jPosition = $j;
                    }
                }
            }
        }
        $concatenado = [$big, $iPosition, $jPosition, $suma];
        return $concatenado;
    }

    public function roomGenerator($column, $row, $operation)
    {
        $tmpCol = $column;
        $tmpRow = $row;
        $axis = 0;
        $sum = 0;
        $escape = false;

        while ($axis != 4) {
            try {
                if ($operation == 0) {
                    if ($row == $tmpRow && $column == $tmpCol && $escape == true) {
                        $sum = $sum - 1;
                    }

                    if ($this->roomData[$tmpCol][$tmpRow] == 0 && $this->roomData[$column][$row] == 0) {
                        $sum = $sum + 1;
                        $escape = true;
                    } else {
                        $axis = $axis + 1;
                        $tmpCol = $column;
                        $tmpRow = $row;
                    }
                } else if ($operation == 1) {

                    $this->roomData[$column][$row] = "*";
                    
                    if ($this->roomData[$tmpCol][$tmpRow] == 0 || $this->roomData[$tmpCol][$tmpRow] == "*" || $this->roomData[$tmpCol][$tmpRow] == "-") {
                        $this->roomData[$tmpCol][$tmpRow] = "-";
                    } else {
                        $axis = $axis + 1;
                        $tmpCol = $column;
                        $tmpRow = $row;
                    }
                }
            } catch (Exception $e) {
                $axis = $axis + 1;
                $tmpCol = $column;
                $tmpRow = $row;
            }
            if ($axis == 0) {
                $tmpCol = $tmpCol - 1;
            } else if ($axis == 1) {
                $tmpCol = $tmpCol + 1;
            } else if ($axis == 2) {
                $tmpRow = $tmpRow - 1;
            } else if ($axis == 3) {
                $tmpRow = $tmpRow + 1;
            } else if ($axis == 4) {
                break;
            }
        }
        if ($operation == 0) {
            return $sum;
        } else if ($operation == 1) {
            return $this->roomData;
        }
    }

    public function store(FileRequest $request)
    {

        $request->file('file')->storeAs('public/files', 'matriz.txt');

        return redirect('file')->with('status', 'El archivo ha sido cargado correctamente');
    }

}
