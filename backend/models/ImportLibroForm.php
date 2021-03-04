<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use backend\models\Libro;
use backend\models\LibroAutor;
use backend\models\Autor;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use yii\helpers\FileHelper;

class ImportLibroForm extends Model
{
    private $transaction;

    public $file;
    public $errorType;
    public $errorRow;
    public $errorMensaje;
    public $error;
    private $filePath;

    public function rules()
    {
        return [
            [['file'], 'required'],
            [['file'], 'file', 'maxSize' => 8000000, 'tooBig' => 'El archivo es muy grande, máximo 8M'],
        ];
    }

    public function attributeLabels()
    {
        return [];
    }


    public function import()
    {
        if (!$this->saveFile()) {
            $this->errorType = 0;
            return false;
        }
        $fileData = $this->getDataFromFile();

        if (!$this->saveNewBook($fileData)){
            return false;
        }

        $this->deleteFile();
        return true;
    }

    private function saveFile()
    {
        $this->file = UploadedFile::getInstance($this, 'file');
        if (!$this->validate()) {
            return false;
        } else {
            $this->filePath = Yii::getAlias('@backend')."/runtime/imports/{$this->file->baseName}.{$this->file->extension}";
            $this->file->saveAs($this->filePath);
            return true;
        }
    }

    private function getDataFromFile()
    {
        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($this->filePath);
        return $spreadsheet->getActiveSheet()->toArray(null, false, false, true);
    }

    private function saveNewBook($fileData)
    {
        $connection = \Yii::$app->db;
        $this->transaction = $connection->beginTransaction();

        foreach (array_slice($fileData, 1, null, true) as $rowIndex => $bookInfo) {
            if($bookInfo["E"] != null && trim($bookInfo["E"]) != ''){

                if($bookInfo["B"] == null){
                    $bookInfo["B"] = 0;
                }
                if (strlen($bookInfo["E"]) > 125){
                    $bookInfo["E"] = substr($bookInfo["E"], 0, 125);
                }
                if (strlen($bookInfo["F"]) > 500){
                    $bookInfo["F"] = substr($bookInfo["F"], 0, 500);
                }
                if((float)filter_var(str_replace(',', '.', $bookInfo["D"]), FILTER_SANITIZE_NUMBER_FLOAT) >= 3){
                    $bookInfo["D"] = (float)filter_var(str_replace(',', '.', $bookInfo["D"]), FILTER_SANITIZE_NUMBER_FLOAT);
                } else {
                    $this->transaction->rollback();
                    $this->errorType = 1;
                    $this->errorRow = $rowIndex;
                    $this->errorMensaje .= 'El precio mínimo de venta es de $3.00 MXN';
                    return false;
                }

                $libro = new Libro();
                $libro->codigo_barras = $bookInfo["A"].'';
                $libro->cantidad = $bookInfo["B"];
                $libro->titulo = $bookInfo["C"];
                $libro->pvp = $bookInfo["D"];
                $libro->autor = $bookInfo["E"];
                $libro->subtitulo = $bookInfo["F"]."";
                $libro->tema_id = $this->getTema($bookInfo["G"]);
                $libro->sello_id = $this->getSello($bookInfo["H"]);
                $libro->coleccion_id = $this->getColeccion($bookInfo["I"]);
                $libro->isbn = $bookInfo["J"].'';
                $libro->anio = $bookInfo["K"].'';
                $libro->interno = (string)$bookInfo["L"];
                $libro->paginas = $bookInfo["M"];
                $libro->peso = (int)filter_var($bookInfo["N"], FILTER_SANITIZE_NUMBER_INT);
                $libro->profundo = (float)filter_var(str_replace(',', '.', $bookInfo["O"]), FILTER_SANITIZE_NUMBER_FLOAT);
                $libro->alto = (float)filter_var(str_replace(',', '.', $bookInfo["P"]), FILTER_SANITIZE_NUMBER_FLOAT);
                $libro->largo = (float)filter_var(str_replace(',', '.', $bookInfo["Q"]), FILTER_SANITIZE_NUMBER_FLOAT);
                $libro->editorial_id = Yii::$app->user->identity->editorial_id;
                $libro->mostrar = 1;
                if (!$libro->save()) {
                    $this->transaction->rollback();
                    $this->errorType = 1;
                    $this->errorRow = $rowIndex;
                    foreach($libro->getErrors() as $errores){
                        foreach ($errores as $error){
                            $this->errorMensaje .= $error;
                        }
                    }
                    return false;
                }
                $libro_autor = new LibroAutor();
                $libro_autor->libro_id = $libro->id;
                $libro_autor->autor_id = $this->getAutor($bookInfo["E"]);

                if (!$libro_autor->save()){
                    $this->transaction->rollback();
                    $this->errorType = 1;
                    $this->errorRow = $rowIndex;
                    foreach($libro->getErrors() as $errores){
                        foreach($errores as $error){
                            $this->errorMensaje .= $error;
                        }
                    }
                    return false;
                }
            }
        }

        $this->transaction->commit();
        return true;
    }

    private function deleteFile()
    {
        FileHelper::unlink($this->filePath);
    }

    private function getAutor($nombre_autor){
        $autor = Autor::find()->where(['=', 'nombre',$nombre_autor])->one();
        if (!isset($autor)) {
            $autor = new Autor();
            $autor->nombre = $nombre_autor;
            if(!$autor->save()){
                return null;
            }
        }
        return $autor->id;
    }

    private function getSello($name)
    {
        if ($name == ''){
            $name = ucwords(strtolower(Yii::$app->user->identity->usuario));
        }
        $seal = Sello::find()
            ->where([
                'and',
                ['=', 'editorial_id', Yii::$app->user->identity->editorial_id],
                ['like', 'nombre', $name]
            ])
            ->one();
        if (!isset($seal)) {
            $seal = new Sello();
            $seal->nombre = $name;
            $seal->editorial_id = Yii::$app->user->identity->editorial_id;
            if (!$seal->save()) {
                return null;
            }
        }
        return $seal->id;
    }

    private function getTema($name)
    {
        if ($name){
            $name = ucfirst(strtolower($name));
            $theme = Tema::find()->where(['like', 'nombre', $name])->one();
            if (!$theme){
                $theme = Tema::find()->where(['nombre' => '-'])->one();
            }
        } else {
            $theme = Tema::find()->where(['nombre' => '-'])->one();
        }
        return $theme->id;
    }

    private function getColeccion($name)
    {
        if ($name == ''){
            $name = ucwords(strtolower(Yii::$app->user->identity->usuario));
        }
        $collection = Coleccion::find()
            ->where([
                'and',
                ['=', 'editorial_id', Yii::$app->user->identity->editorial_id],
                ['like', 'nombre', $name]
            ])
            ->one();
        if(!isset($collection)) {
            $collection = new Coleccion();
            $collection->nombre = $name;
            $collection->editorial_id = Yii::$app->user->identity->editorial_id;
            if (!$collection->save()) {
                return null;
            }
        }
        return $collection->id;
    }
}
