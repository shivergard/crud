<?php namespace Shivergard\Crud;

use App\Requests;

use Illuminate\Http\Request;

use \Carbon;
use \Config;
use \Route;


class SubCrudController extends \Shivergard\ApiDemo\BaseCrudController {



    public function index($method){

        $this->bladeDir = 'crud::sub_crud';

        //hardCode style @todo review please
        $modelName = $this->model;
        
        if ($modelName::count() == 0)
            return \Redirect::to(action($this->getClassName(true)."@create"));
        $list = $modelName::orderBy('id', 'DESC');
        $list = $this->getFilteredList($list)->paginate(4); 

        $blankItem = new $modelName();

        $view = view($this->bladeDir.'.index' , array(
            'list' => $list,
            'fields' => $this->getAllColumnsNames($blankItem),
            'controller' => $this->getClassName(true),
            'prefix' => $this->getClassName(),
            'method' => Route::current()->getParameter('method'),
        ));
        
        if (isset($this->layout) && $this->layout){
            return View($this->layout , array('content' => $view));
        }else{
            return $view;
        }

        
    }

    /**
     * Display the specified resource.
     * @param  int  $iddd @return Response
     */
    public function show($method , $id){

        $this->bladeDir = 'crud::sub_crud';

        $modelName = $this->model;
        $list = $modelName::find($id);
        $view = view($this->bladeDir.'.view' , array(
                'list' => $list,
                'fields' => $this->getAllColumnsNames($list),
                'method' => Route::current()->getParameter('method'),
                'controller' => $this->getClassName(true),
        ));

        if (isset($this->layout) && $this->layout){
            return View($this->layout , array('content' => $view));
        }else{
            return $view;
        }
        
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($method){

        $this->bladeDir = 'crud::sub_crud';

        $modelName = $this->model;
        $list = new $modelName();

        $viewDetails = array(
            'fields' => $this->getAllColumnsNames($list , true),
            'method' => Route::current()->getParameter('method'),
            'controller' => $this->getClassName(true)
        );

        $view = View($this->bladeDir.'.create' , $viewDetails);

        if (isset($this->layout) && $this->layout){
            return View($this->layout , array('content' => $view));
        }else{
            return $view;
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($method , $id){

        $this->bladeDir = 'crud::sub_crud';
        
        $modelName = $this->model;
        $list = $modelName::find($id);
        $view = view($this->bladeDir.'.edit' , array(
                'list' => $list,
                'fields' => $this->getAllColumnsNames($list , true),
                'method' => Route::current()->getParameter('method'),
                'controller' => $this->getClassName(true),
                'model_name' => $modelName

        ));

        if (isset($this->layout) && $this->layout){
            return View($this->layout , array('content' => $view));
        }else{
            return $view;
        }
    }

}