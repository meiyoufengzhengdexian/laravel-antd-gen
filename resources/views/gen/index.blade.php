<template>
    <div class="app-container">
        <el-row>
            <el-form :inline="true" :model="listSearch" class="demo-form-inline" @keyup.enter.native="fetchData">
                @foreach($indexConfig as $name => $field)
                    <el-form-item label="{{\Illuminate\Support\Arr::get($field, 'as')}}">
                        @if(\Illuminate\Support\Arr::get($field, 'type', 'text') == 'text')
                            @include('gen.type.'.\Illuminate\Support\Arr::get($field, 'type', 'text'), ['name' => 'listSearch.'.$name])
                        @else
                            @include('gen.type.'.\Illuminate\Support\Arr::get($field, 'type').'Search', ['name' => 'listSearch.'.$name])
                        @endif
                    </el-form-item>
                @endforeach
                <el-form-item>
                    <el-button type="primary" @click="fetchData">查询</el-button>
                    <el-button type="success" @click="create">新建</el-button>
                </el-form-item>
            </el-form>
        </el-row>
        <el-table
                v-loading="listLoading"
                :data="list"
                element-loading-text="Loading"
                border
                fit
                highlight-current-row
        >
            @foreach($indexConfig as $name => $field)
                <el-table-column align="center" label="{{\Illuminate\Support\Arr::get($field, 'as')}}"
                                 @if($name == 'id') width="95" @endif>
                    <template slot-scope="scope">
                        <?php $gt = '}}'?>
                        {{   "{{ scope.row.$name$gt" }}
                    </template>
                </el-table-column>
            @endforeach
            <el-table-column label="操作">
                <template slot-scope="scope">
                    <el-button :style="{width: '60px'}" type="primary" plain size="mini"
                               @click="editRow(scope.row)">编辑
                    </el-button>
                    <el-button :style="{width: '60px'}" type="danger" plain size="mini"
                               @click="deleteRow(scope.row)">删除
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
        <div class="page">
            <el-pagination
                    :style="{float:'right',paddingTop:'20px'}"
                    :current-page="listPage.currendPage"
                    :page-sizes="[10, 20, 30, 40]"
                    :page-size="listPage.pageSize"
                    layout="total, sizes, prev, pager, next, jumper"
                    :total="listPage.total"
                    @size-change="pageSizeChange"
                    @current-change="pageChange"
            />
        </div>
        <el-drawer
                ref="drawer"
                title="{{\Illuminate\Support\Arr::get($baseConfig, 'title')}}信息"
                :visible.sync="currentDialog"
                :destroy-on-close="true"
                custom-class="demo-drawer"
                :size="drawerWidth"
        >
            <div class="drawer-content">
                <el-form :model="current" label-position="right" label-width="80px" size="small"
                         @keyup.enter.native="saveCurrent">
                    @foreach($editConfig as $name=>$field)
                        <el-form-item label="{{\Illuminate\Support\Arr::get($field, 'as')}}">
                            @include('gen.type.'.\Illuminate\Support\Arr::get($field, 'type'), ['name' => "current.$name", 'field' => $field])
                        </el-form-item>
                    @endforeach
                    <el-form-item>
                        <el-button type="primary" plain @click="saveCurrent">保存修改</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </el-drawer>
    </div>
</template>

<script>
    import {
        get{{\App\Service\Gen\GenTool::getDir($tableName)}}List,
        update{{\App\Service\Gen\GenTool::getDir($tableName)}},
        store{{\App\Service\Gen\GenTool::getDir($tableName)}},
        delete{{\App\Service\Gen\GenTool::getDir($tableName)}},
        get{{\App\Service\Gen\GenTool::getDir($tableName) ."}"}} from '@/api/{{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}'
    import {create{{\App\Service\Gen\GenTool::getDir($tableName).'}'}} from '@/format/{{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}'
    import Upload from '../../components/Upload/Upload'
    import pageMixins from '@/utils/page'
    import datetimeOption from '@/utils/datetime'
    import { isPhone } from '@/utils'
    export default {
        components: {
            Upload,
        },
        mixins: [pageMixins],
        data() {
            return {
                list: null,
                listLoading: true,
                listPage: {},
                listSearch: {
                    created_at: []
                },
                current: {},
                currentLoading: false,
                currentDialog: false,
                currentType: '',
                datetimeOption
            }
        },
        computed: {
            drawerWidth: () => {
                return isPhone() ? '100%' : '50%'
            }
        },
        created() {
            this.fetchData()
        },
        methods: {
            create() {
                this.current = create{{\App\Service\Gen\GenTool::getDir($tableName)}}({})
                this.currentDialog = true
                this.currentType = 'create'
            },
            editRow(row) {
                get{{\App\Service\Gen\GenTool::getDir($tableName)}}(row).then(response => {
                    this.current = Object.assign({}, create{{\App\Service\Gen\GenTool::getDir($tableName)}}(response.data))
                    this.currentDialog = true
                    this.currentType = 'edit'
                })
            },
            saveCurrent() {
                console.log(this.current)
                if (this.currentType === 'edit') {
                    update{{\App\Service\Gen\GenTool::getDir($tableName)}}(this.current)
                        .then(_ => {
                            this.fetchData()
                            this.currentDialog = false
                            this.$message({
                                type: 'success',
                                message: '修改成功!'
                            })
                        })
                } else if (this.currentType === 'create') {
                    store{{\App\Service\Gen\GenTool::getDir($tableName)}}(this.current)
                        .then(_ => {
                            this.fetchData()
                            this.currentDialog = false
                            this.$message({
                                type: 'success',
                                message: '新建成功!'
                            })
                            this.fetchData()
                        })
                }
            },
            deleteRow(row) {
                this.$confirm('此操作将永久删除该资源, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    delete{{\App\Service\Gen\GenTool::getDir($tableName)}}(row).then(_ => {
                        this.fetchData()
                        this.$message({
                            type: 'success',
                            message: '删除成功!'
                        })
                    })
                })
            },
            fetchData() {
                this.listLoading = true
                console.log(this.listSearch)
                const params = {
                    ...this.listSearch,
                    page: this.listPage.currentPage,
                    pageSize: this.listPage.pageSize
                }
                get{{\App\Service\Gen\GenTool::getDir($tableName)}}List(params).then(response => {
                    console.log('vue', response)
                    this.list = response.data
                    this.listPage.currentPage = response.current_page
                    this.listPage.pageSize = parseInt(response.per_page)
                    this.listPage.total = response.total
                    this.listLoading = false
                })
            },
            test(e) {
                console.log(e)
            }
        }
    }
</script>

<style lang="scss">
    .page {
        clear: both;
    }

    .drawer-content {
        padding: 30px
    }
</style>
