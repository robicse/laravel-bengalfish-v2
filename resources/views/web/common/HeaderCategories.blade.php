<?php

 function productCategories(){
  $categories = recursivecategories();
  if($categories){
  $parent_id = 0;
  $option = '<option value="0">'. Lang::get("website.Choose Any Category").'</option>';

    foreach($categories as $parents){
      if($parents->slug==app('request')->input('category')){
        $selected = "selected";
      }else {
        $selected = "";
      }

      $option .= '<option value="'.$parents->slug.'" '.$selected.'>'.$parents->categories_name.'</option>';

        if(isset($parents->childs)){
          $i = 1;
          $option .= childcat($parents->childs, $i, $parent_id);
        }

    }

  echo $option;
}
}
 function childcat($childs, $i, $parent_id){
  $contents = '';
  foreach($childs as $key => $child){
    $dash = '';
    for($j=1; $j<=$i; $j++){
        $dash .=  '-';
    }

    if($child->slug==app('request')->input('category')){
      $selected = "selected";
    }else {
      $selected = "";
    }

    $contents.='<option value="'.$child->slug.'" '.$selected.'>'.$dash.$child->categories_name.'</option>';
    if(isset($child->childs)){

      $k = $i+1;
      $contents.= childcat($child->childs,$k,$parent_id);
    }
    elseif($i>0){
      $i=1;
    }

  }
  return $contents;
}


 function recursivecategories(){
  $items = DB::table('categories')
      ->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
      ->select('categories.categories_id', 'categories.categories_slug as slug','categories_description.categories_name', 'categories.parent_id')
      ->where('categories_description.language_id','=', Session::get('language_id'))
      //->orderby('categories_id','ASC')
      ->get();
   if($items->isNotEmpty()){
      $childs = array();
      foreach($items as $item)
          $childs[$item->parent_id][] = $item;

      foreach($items as $item) if (isset($childs[$item->categories_id]))
          $item->childs = $childs[$item->categories_id];

      $tree = $childs[0];
      return  $tree;
    }
 }

function list_of_specific_categories(){
    return $items = DB::table('categories')
        ->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
        ->select('categories.categories_id', 'categories.categories_slug as slug','categories_description.categories_name', 'categories.parent_id')
        ->where('categories_description.language_id','=', Session::get('language_id'))
        ->where('categories_description.categories_name','=', 'Deshi Fish')
        ->orWhere('categories_description.categories_name','=', 'Dry Fish')
        ->orWhere('categories_description.categories_name','=', 'Fresh Fish')
        ->orWhere('categories_description.categories_name','=', 'Sea Fish')
        ->orWhere('categories_description.categories_name','=', 'Meat')
        //->orderby('categories_id','ASC')
        ->get();
}


function listofrecursivecategories(){
    $items = DB::table('categories')
        ->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
        ->select('categories.categories_id', 'categories.categories_slug as slug','categories_description.categories_name', 'categories.parent_id')
        ->where('categories_description.language_id','=', Session::get('language_id'))
        //->orderby('categories_id','ASC')
        ->get();
    if($items->isNotEmpty()){
        $childs = array();
        foreach($items as $item){
            $childs[$item->parent_id][] = $item;
        }


        foreach($items as $item){
            if (isset($childs[$item->categories_id])){
                $item->childs = $childs[$item->categories_id];
            }
        }


        $tree = $childs[0];
        return  $tree;
    }
}

 ?>
