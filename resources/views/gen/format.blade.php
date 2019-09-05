export function create{{\App\Service\Gen\GenTool::getDir($tableName)}}({{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}) {
  return {
<?php $count = count(\Illuminate\Support\Arr::get($config, 'fields', [])); $i=0;?>
@foreach(\Illuminate\Support\Arr::get($config, 'fields') as $name=>$field)
    {{$name}}: {{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}.{{$name}}@if($i < $count-1),@endif

    <?php ++$i;?>
@endforeach
  }
}
