

namespace App\Http\Controllers\Backend\{{\App\Service\Gen\GenTool::getDir($table)}};
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Backend\BackendModel;
{{-- 关联模型引入 --}}
@foreach($fields as $field)
    @if(isset($field['model']))
use {{ trim($field['model'], '\\') }};
    @endif
@endforeach

class {{\App\Service\Gen\GenTool::getDir($table)}}Model extends BackendModel
{
    protected $table = '{{$table}}';
    use SoftDeletes;
{{-- 关联模型引入 --}}
@foreach($fields as $field)
    @if(isset($field['refMethod']) && $field['refMethod'] != 'belongsToMany')
    public function {{$field['refMethod']}}()
    {
        return $this->{{trim($field['refType'])}}({{trim($field['model'], '\\')}}, '{{$field['name']}}', '{{$field['refKey']}}');
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