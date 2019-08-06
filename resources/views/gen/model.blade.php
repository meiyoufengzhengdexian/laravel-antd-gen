

namespace App\Http\Controllers\Backend\{{\App\Service\Gen\GenTool::getDir($table)}};
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Backend\BackendModel;


class {{\App\Service\Gen\GenTool::getDir($table)}}Model extends BackendModel
{
    protected $table = '{{$table}}';
    use SoftDeletes;
{{-- 关联模型引入 --}}
@foreach($fields as $field)
    @if(isset($field['refMethod']) && $field['refMethod'] != 'belongsToMany')
    public function {{$field['refMethod']}}()
    {
        return $this->{{trim($field['refType'])}}(\{{trim($field['model'], '\\')}}::class, '{{$field['name']}}', '{{\Illuminate\Support\Arr::get($field, 'refKey', 'id')}}');
    }
    @endif
@endforeach

@foreach($fields as $field)
    @if(isset($field['refMethod']) && $field['refMethod'] == 'belongsToMany')
    public function {{$field['refMethod']}}()
    {
        return $this->belongsToMany({{$field['model']}}, '{{$field['table']}}', '{{$field['foreignPivotKey']}}', '{{$field['relatedPivotKey']}}');
    }
    @endif
@endforeach

}
