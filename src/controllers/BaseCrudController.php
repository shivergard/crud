<?php namespace Shivergard\Crud;

use \Session;
use \Shivergard\Crud;

use \Input;
use \Validator;
use \File;
use \Redirect;
use \Route;

class BaseCrudController extends \Shivergard\Crud\PackageController {

    public $bladeDir = 'crud::crud';

    public  $rules = array(
        'name'       => 'required'
    );

    public $constantFilters = array();

    public function clearSession(){
        $prefix = $this->getClassName();
        $modelName = $this->model;
        $list = new $modelName();
        $fields = $this->getAllColumnsNames($list , true);

        foreach ($fields as $key => $value) {
            Session::forget('filter_'.$prefix.'_'.$value);
        }
    }

    public function buildSession($inputs){
        $prefix = $this->getClassName();
        foreach ($inputs as $key => $input){
            if (strlen(trim($input)) > 0 && !in_array($key , array('_token' , 'created_at' , 'updated_at')))
                Session::put('filter_'.$prefix.'_'.$key , $input);
        }
    }

    public function getFilteredList($list , $inPage = false){

        $return = false;
        $modelName = $this->model;
        $blankItem = new $modelName();
        //\DB::enableQueryLog();

        if (!$inPage){
            //$pg = Settings::where('name' , "=" , 'pagination_lenght')->first();
            $inPage = 30;//$pg->value;

        }

        $fields = $this->getAllColumnsNames($blankItem , true);
        $prefix = $this->getClassName();

        foreach ($fields as $key) {
            if (Session::has('filter_'.$prefix.'_'.$key)){
                $list->where($key , "=" , Session::get('filter_'.$prefix.'_'.$key));
            }
        }

        if (count($this->constantFilters) > 0){
            foreach ($this->constantFilters as $key => $value) {
                $list->where($key , "=" , $value);
            }
        }


        if (Session::has('filter_'.$prefix.'_from') && Session::has('filter_'.$prefix.'_till'))
            $list = $list->whereBetween('created_at', array(Session::get('filter_'.$prefix.'_from').' 0:00:00', Session::get('filter_'.$prefix.'_till').' 23:59:59'));


        if (
                session('filter_'.$prefix.'_order_by') 
                && in_array(session('filter_'.$prefix.'_order_by') , $blankItem->getAllColumnsNames(true) )
                && ( session('filter_'.$prefix.'_asc') == "true" || session('filter_'.$prefix.'_desc') == "true")
            ){
            $list = $list->orderBy(session('filter_'.$prefix.'_order_by'), ( session('filter_'.$prefix.'_asc') == "true" ? 'asc' : 'desc'));
        }else{
            $list = $list->orderBy('created_at', 'desc');
        }

        //$list->get();dd(\DB::getQueryLog());
        return $list;
    }


    public function getClassName($full = false) {
        
        if ($this->public)
            return $this->getPublicClassName($full);

        $classFullName = get_called_class();

        $classArray = explode('\\', $classFullName);
        return "\\Shivergard\\Crud\\".end($classArray);
    }


    public function getPublicClassName($full = false) {

        $classFullName = get_called_class();

        if ($full){
            return str_replace("App\\Http\\Controllers\\", "", $classFullName);
        }else{
            $classArray = explode('\\', $classFullName);
            return end($classArray);
        }
    }

    public function getAllColumnsNames($list , $forced = false){
        if (!isset($this->cols) || !$this->cols)
            $return = array_diff(
                $list->getAllColumnsNames($forced) , 
                array_keys($this->constantFilters)
            );
        else
            $return = $this->cols;

        return $return;
    }


    public function filter(){
        if (\Input::has('clear')){
            $this->clearSession();
        }

        $this->buildSession(\Input::all());
        return \Redirect::to(action($this->getClassName(true)."@index"));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(){

        if (\Input::has('crud_filter')){
            if (\Input::has('clear')){
                $this->clearSession();
            }

            $this->buildSession(\Input::all());
            //dd(Session::all());
            return \Redirect::to(action($this->getClassName(true)."@index"));
        }

        // validate
        // read more on validation at http://laravel.com/docs/validation

        $validator = \Validator::make(\Input::all(), $this->rules);

        // process the login
        if ($validator->fails()) {
            return \Redirect::to(action($this->getClassName(true)."@create"))
                ->withErrors($validator)
                ->withInput(\Input::except('password'));
        } else {
            // store
            $modelName = $this->model;
            $list = new $modelName();

            foreach ($list->getAllColumnsNames(true) as $key => $field_name) {
                $list->$field_name = \Input::get($field_name);
            }

            foreach ($this->constantFilters as $fieldName => $fieldValue) {
                $list->$fieldName = $fieldValue;
            }

            $list->save();

            // redirect
            \Session::flash('message', 'Successfully created!');

            if (Route::current()->getParameter('method') && trim(Route::current()->getParameter('method')) != '')
                return \Redirect::to(action($this->getClassName(true)."@index" , array('method' => Route::current()->getParameter('method'))));
            else
                return \Redirect::to(action($this->getClassName(true)."@index"));
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($param1 , $param2 = false){

        if (Route::current()->getParameter('method') && trim(Route::current()->getParameter('method')) != '')
            $id = $param2;
        else
            $id = $param1;
        // validate
        // read more on validation at http://laravel.com/docs/validation

        $validator = \Validator::make(\Input::all(), $this->rules);

        // process the login
        if ($validator->fails()) {
            return \Redirect::to(action($this->getClassName(true)."@edit" , array('id' => $id)))
                ->withErrors($validator)
                ->withInput(\Input::except('password'));
        }else{
            // store
            $modelName = $this->model;
            $list = $modelName::find($id);
            foreach ($this->getAllColumnsNames($list , true) as $key => $field_name) {
                $list->$field_name = \Input::get($field_name);
            }
            $list->save();

            // redirect
            \Session::flash('message', 'Successfully updated!');
            if (Route::current()->getParameter('method') && trim(Route::current()->getParameter('method')) != '')
                return \Redirect::to(action($this->getClassName(true)."@index" , array('method' => Route::current()->getParameter('method'))));
            else
                return \Redirect::to(action($this->getClassName(true)."@index"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($param1 , $param2 = false){
        // delete
        if (Route::current()->getParameter('method') && trim(Route::current()->getParameter('method')) != '')
            $id = $param2;
        else
            $id = $param1;
        
        $modelName = $this->model;
        $itemCount = $modelName::where('id' , intval($id))->count();

        if ($itemCount > 0){
            $item = $modelName::where('id' , intval($id))->first();
            $item->delete();
        }
        
        // redirect
        Session::flash('message', 'Successfully deleted !');
        if (Route::current()->getParameter('method') && trim(Route::current()->getParameter('method')) != '')
            return \Redirect::to(action($this->getClassName(true)."@index" , array('method' => Route::current()->getParameter('method'))));
        else
            return \Redirect::to(action($this->getClassName(true)."@index"));
    }

}