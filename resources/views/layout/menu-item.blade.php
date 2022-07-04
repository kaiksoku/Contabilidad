@role('Super Administrador')
@if ($item['submenu']==[])

<li
    class="nav-item {{(((auth()->user()->getAllPermissions()->pluck('name')->contains('ver '.$item['men_url'])||auth()->user()->can('ver '.$item['men_url']))||$item['men_url']=="#"))?'':'disabled'}}">
    <a href="{{url($item['men_url'])}}"
        class="nav-link {{getMenuActivo($item['men_url'])}}
    {{(((auth()->user()->getAllPermissions()->pluck('name')->contains('ver '.$item['men_url'])||auth()->user()->can('ver '.$item['men_url']))||$item['men_url']=="#"))?'':'disabled'}}">
        <i class="nav-icon {{$item["men_icono"]? :"fas fa-circle"}}"></i>
        <p>{{$item["men_nombre"]}}</p>
    </a>
</li>

@else
<li
    class="nav-item has_treeview {{(((auth()->user()->getAllPermissions()->pluck('name')->contains('ver '.$item['men_url'])||auth()->user()->can('ver '.$item['men_url']))||$item['men_url']=="#"))?'':'disabled'}}">
    <a href="#"
        class="nav-link {{(((auth()->user()->getAllPermissions()->pluck('name')->contains('ver '.$item['men_url'])||auth()->user()->can('ver '.$item['men_url']))||$item['men_url']=="#"))?'font-weight-bold':'disabled'}}">
        <i class="nav-icon {{$item["men_icono"]? :"fas fa-circle"}}"></i>
        <p> {{$item['men_nombre']}}
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @foreach ($item["submenu"] as $submenu)
        @include("layout.menu-item",["item"=>$submenu])
        @endforeach
    </ul>
</li>
@endif
@else
<?php
$agents=auth()->user()->getAllPermissions()->pluck('name');
$Padres=array();
array_push($Padres, '-');
$Menus='';
foreach ($agents as $agent=>$value) {
    $Temp=explode(' ', "{$agent}=>{$value}");
    $Temp=explode('/', $Temp[1]);
    try{
        if($Temp[1])array_push($Padres, $Temp[1]);
    }
    catch(Exception $e){}

    array_push($Padres, $Temp[0]);
    }
?>
@if($item["men_id"]!=99)
@if ($item['submenu']==[])
<li {{(($item['men_deshabilitado']!=1)&&(auth()->user()->getAllPermissions()->pluck('name')->contains('ver '.$item['men_url'])||$item['men_url']=="#"))?'':'hidden'}}
    class="nav-item {{(($item['men_deshabilitado']!=1)&&(auth()->user()->getAllPermissions()->pluck('name')->contains('ver '.$item['men_url'])||$item['men_url']=="#"))?'':'disabled'}}">
    <a {{(($item['men_deshabilitado']!=1)&&(auth()->user()->getAllPermissions()->pluck('name')->contains('ver '.$item['men_url'])))?'':'hidden'}} href="{{url($item['men_url'])}}"
        class="nav-link {{getMenuActivo($item['men_url'])}}
    {{(($item['men_deshabilitado']!=1)&&(auth()->user()->getAllPermissions()->pluck('name')->contains('ver '.$item['men_url'])||$item['men_url']=="#"))?'':'disabled'}}">

    <i class="nav-icon {{$item["men_icono"]? :"fas fa-circle"}}"></i>
        <p>{{$item["men_nombre"]}}</p>
    </a>
</li>
@else
@if (array_search($item['men_url'], array_unique($Padres))>0)
<li
    class="nav-item has_treeview ">
    <a  href="#"
        class="nav-link ">
        <i class="nav-icon {{$item["men_icono"]? :"fas fa-circle"}}"></i>
        <p>
            {{$item["men_nombre"]}}
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @foreach ($item["submenu"] as $submenu)
        @include("layout.menu-item",["item"=>$submenu])
        @endforeach
    </ul>
</li>
@endif
@endif
@endif
@endrole
