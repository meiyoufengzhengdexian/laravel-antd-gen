<?php


namespace App\Service\Gen;


use App\Service\Gen\Exception\ConfigNotFoundException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\Yaml\Yaml;

class GenBase
{

    public static function genBaseConfig($tableName, $title = "", $desc = "")
    {
        $baseDir = './app/Http/Controllers/Backend/' . GenTool::getDir($tableName);
        if (!is_dir($baseDir)) {
            mkdir($baseDir);
        }

        $tableYaml = [
            'title' => $title,
            'desc' => $desc,
            'basicUri' => GenTool::getDir($tableName)
        ];

        $yamlString = Yaml::dump($tableYaml);
        file_put_contents($baseDir . '/' . GenTool::getDir($tableName) . '.yaml', $yamlString);
        return true;
    }

    /**
     * @param $tableName
     * @param Command $context
     */
    public static function genTableConfig($tableName, Command $context)
    {
        $tableQuery = DB::select('desc ' . $tableName);
        $fieldsYaml = [
            'fields' => []
        ];


        foreach ($tableQuery as $field) {
            if ($field->Field == 'created_at') {
                $field->Zh = '创建时间';
                continue;
            }
            if ($field->Field == 'updated_at') {
                $field->Zh = '更新时间';
                continue;
            }
            if ($field->Field == 'deleted_at') {
                $field->Zh = '删除时间';
                continue;
            }

            do {
                $zh = $context->ask($field->Field . '的中文');
            } while (!$zh);

            $field->Zh = $zh;

            if (Str::startsWith($field->Type, 'int')) {
                $type = 'int';
            } else if (Str::startsWith($field->Type, 'varchar')) {
                $type = 'text';
            } else if (Str::startsWith($field->Type, 'datetime')) {
                $type = 'datetime';
            } else if (Str::startsWith($field->Type, 'date')) {
                $type = 'date';
            } else if (Str::startsWith($field->Type, 'time')) {
                $type = 'time';
            } else if (Str::startsWith($field->Type, 'tinyint')) {
                $type = 'enum';
            }

            $filedInfo = [
                'name' => $field->Field,
                'as' => $field->Zh,
                'type' => $type
            ];

            if ($filedInfo['type'] == 'enum') {
                $filedInfo['type']['options'][] = [
                    'content' => '开',
                    'value' => 1,
                    'default' => 1
                ];
                $filedInfo['type']['options'][] = [
                    'content' => '关',
                    'value' => 0
                ];
            }

            //获取关联数据类型， 关联关系
            while ($filedInfo['type'] == 'int' && strpos($filedInfo['as'], '_id')) {
                //关联数据
                do {
                    $isRef = $context->ask('是否为关联数据 y/n');
                } while (!in_array($isRef, ['y', 'n']));

                if (!$isRef) {
                    break;
                }

                do {
                    $refType = $context->ask('请输入关联类型: ' . json_encode(GenTool::getRefTypeList()));
                } while (!$refType);

                $filedInfo['refType'] = GenTool::selectRefType($refType);

                do {
                    $refMethod = $context->ask('请输入关联方法');
                } while (!$refMethod);
                $filedInfo['refType'] = $refMethod;


                do {
                    $refModel = $context->ask('请输入关联模型类名 例如 Tag\TagModel');
                } while (!$refModel);
                $filedInfo['model'] = "\\App\\Http\\Backend\\" . $refModel;
                if ($filedInfo['refType'] == 'belongsTo') {
                    $pk = $context->ask('请输入主键 默认为id');
                    $filedInfo['refKey'] = !$pk ? 'id' : $pk;
                }

                if ($filedInfo['refType'] == 'belongsToMany') {
                    do {
                        $table = $context->ask('请输入中间表名（不包括定义的前缀）');
                    } while (!$table);
                    $filedInfo['table'] = $table;

                    do {
                        $foreignPivotKey = $context->ask('请输入本表对于中间表的外键');
                    } while (!$foreignPivotKey);
                    $filedInfo['foreignPivotKey'] = $foreignPivotKey;

                    do {
                        $relatedPivotKey = $context->ask('请输入关联表对于中间表的外键');
                    } while (!$relatedPivotKey);
                    $filedInfo['relatedPivotKey'] = $relatedPivotKey;
                }

                break;
            }

            if (!in_array($field->Field, ['created_at', 'updated_ats', 'deleted_at'])) {
                $filedInfo['rules'][] = [
                    'message' => $filedInfo['as'] . '不能为空',
                    'rule' => 'required'
                ];
            }

            $fieldsYaml['fields'][] = $filedInfo;
        }

        file_put_contents(GenTool::getTableConfigFile($tableName), Yaml::dump($fieldsYaml, 500, 2));
    }

    /**
     * @param $tableName
     * @param Command $context
     * @return bool
     * @throws ConfigNotFoundException
     * @throws \Throwable
     */
    public static function genModelConfig($table, Command $context)
    {
        $baseConfigFile = GenTool::getTableConfigFile($table);

        if (!file_exists($baseConfigFile)) {
            throw new ConfigNotFoundException($baseConfigFile);
        }

        $config = Yaml::parseFile($baseConfigFile);
        $fields = $config['fields'];
        $modelString = "<?php\n";
        $modelString .= view('gen.model', compact('table', 'fields'))->__toString();

        file_put_contents(GenTool::getModelFile($table), $modelString);

        return true;
    }
}