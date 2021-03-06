
namespace App\Http\Controllers\Backend\{{\App\Service\Gen\GenTool::getDir($table)}}\Request;


use App\Http\Controllers\Backend\BackendException;
use App\Http\Controllers\Backend\BackendRequest;
use App\Http\Controllers\Backend\Lib\AuthResourceTrait;
use App\Http\Controllers\Backend\Lib\ConfigTrait;
use App\Http\Controllers\Backend\Lib\ToolTrait;
use Illuminate\Support\Arr;

class {{\App\Service\Gen\GenTool::getDir($table)}}UpdateRequest extends BackendRequest
{
    use ConfigTrait;
    use ToolTrait;
    use AuthResourceTrait;

    /**
     * @return bool
     * @throws BackendException
     */
    public function authorize()
    {
        if($this->checkAuth('backend.{{$table}}.all', '')){
            return true;
        }
        $action = $this->getRouteAs();
        return $this->checkAuth($action, "您没有更新权限: ". $action);
    }

    public function rules()
    {
        $columnConfig = $this->getConfig('{{\App\Service\Gen\GenTool::getDir($table)}}.Column');
        $column = Arr::get($columnConfig, 'fields');
        $returnRule = $this->makeRuleAndMessage($column);
        return $returnRule;
    }
}
