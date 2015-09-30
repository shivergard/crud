<?php namespace Shivergard\Crud;

use \Session;
use \Shivergard\Crud;

use \Input;
use \Validator;
use \File;
use \Redirect;

class CrudController extends \Shivergard\Crud\BaseCrudController {



    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(){

        //hardCode style @todo review please
        $modelName = $this->model;
        
        if ($modelName::count() == 0)
            return \Redirect::to(action($this->getClassName(true)."@create"));
        $list = $modelName::orderBy('id', 'DESC');
        $list = $this->getFilteredList($list)->paginate(30); 

        $blankItem = new $modelName();

        $view = view($this->bladeDir.'.index' , array(
            'list' => $list,
            'fields' => $this->getAllColumnsNames($blankItem),
            'controller' => $this->getClassName(true),
            'prefix' => $this->getClassName(),
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
    public function create(){
        $modelName = $this->model;
        $list = new $modelName();

        $viewDetails = array(
            'fields' => $this->getAllColumnsNames($list , true),
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
     * Display the specified resource.
     *dd
     * @param  int  $iddd @return Response
     */
    public function show($id){
        $modelName = $this->model;
        $list = $modelName::find($id);
        $view = view($this->bladeDir.'.view' , array(
                'list' => $list,
                'fields' => $this->getAllColumnsNames($list),
                'controller' => $this->getClassName(true),
        ));

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
    public function edit($id){
        $modelName = $this->model;
        $list = $modelName::find($id);
        $view = view($this->bladeDir.'.edit' , array(
                'list' => $list,
                'fields' => $this->getAllColumnsNames($list , true),
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