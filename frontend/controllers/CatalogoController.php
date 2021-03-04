<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
use frontend\models\Clientes;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactoForm;
use frontend\models\Libro;
use frontend\models\Tema;
use frontend\models\Sello;
use frontend\models\Coleccion;
use frontend\models\Promocion;
use frontend\models\Descuentos;
use frontend\models\Imagenes;
use frontend\models\LibrosSearch;
use frontend\models\Editorial;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use yii\helpers\Url;

/**
 * Site controller
 */
class CatalogoController extends Controller
{

    public function actionIndex(){

        $categorias = Tema::find()->all();
        $searchModel = new LibrosSearch();
        $librosSearch = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->post('incremento')){

            $params = array();

            if(Yii::$app->request->post('tema')){
                $params['tema'] = Yii::$app->request->post('tema');
            }
            if(Yii::$app->request->post('editorial')){
                $params['editorial'] = Yii::$app->request->post('editorial');
            }
            if(Yii::$app->request->post('buscar')){
                $params['buscar'] = Yii::$app->request->post('buscar');
            }
            if(Yii::$app->request->post('promociones')){
                $params['promociones'] = Yii::$app->request->post('promociones');
            }
            if(Yii::$app->request->post('vendidos')){
                $params['vendidos'] = Yii::$app->request->post('vendidos');
            }
            if(Yii::$app->request->post('recomendaciones')){
                $params['recomendaciones'] = Yii::$app->request->post('recomendaciones');
            }

            $params['libros-no'] = Yii::$app->request->post('libros');

            $incremento = Yii::$app->request->post('incremento');

            $searchModel = new LibrosSearch();
            $librosSearch = $searchModel->search($params);

            $libros = $librosSearch->getModels();
            return $this->renderAjax('_mas-libros', [
                'libros' => $libros,
            ]);
        }

        $libros = $librosSearch->getModels();

        if (Yii::$app->request->queryParams['tema']){
            $temaModel = new Tema();
            $tema = $temaModel->obtenerTema(Yii::$app->request->queryParams['tema']);
        }
        if (Yii::$app->request->queryParams['editorial']){
            $editorialModel = new Editorial();
            $editorial = $editorialModel->obtenerEditorial(Yii::$app->request->queryParams['editorial']);
        }
        if (Yii::$app->request->queryParams['buscar']){
            $buscar = Yii::$app->request->queryParams['buscar'];
        }
        if (Yii::$app->request->queryParams['vendidos']){
            $vendidos = Yii::$app->request->queryParams['vendidos'];
        }
        if (Yii::$app->request->queryParams['promociones']){
            $promociones = Yii::$app->request->queryParams['promociones'];
        }
        if (Yii::$app->request->queryParams['recomendaciones']){
            $recomendaciones = Yii::$app->request->queryParams['recomendaciones'];
        }
        return $this->render('index', [
            'categorias' => $categorias,
            'libros' => $libros,
            'editorial' => $editorial,
            'tema' => $tema,
            'buscar' => $buscar,
            'vendidos' => $vendidos,
            'promociones' => $promociones,
            'recomendaciones' => $recomendaciones,
        ]);
    }

    public function actionNovedades(){
        $libros = new Libro();
        $novedades = $libros->obtenerAllNovedades();
        return $this->render('novedades', [
            'novedades' => $novedades,
        ]);
    }

    public function actionVer()
    {
        $libros = new Libro();
        $docu = new Imagenes();
        $libro = $libros->verLibros(Yii::$app->request->get('id'));
        if ($libro == NULL){
            Yii::$app->session->setFlash('error', 'No se encontro el libro que estas buscando');
            return $this->actionIndex();
        }
        $tema = ArrayHelper::map(Tema::find()->all(),'id','nombre');
        $sello = ArrayHelper::map(Sello::find()->all(),'id','nombre');
        $coleccion = ArrayHelper::map(Tema::find()->all(),'id','nombre');
        $documen = $docu -> verDocumento(Yii::$app->request->get('id'));
        $novedad = new Libro();
        $relacionados = $novedad->obtenerLibrosRandom(Yii::$app->request->get('id'), $libro->tema_id);
        return $this->render('ver', [
            'libro' => $libro,
            'documen'=>$documen,
            'relacionados' => $relacionados,
        ]);
    }
}
