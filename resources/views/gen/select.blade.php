<template>
  <div>
    <el-select
      v-model="selectOption"
      remote
      filterable
      :remote-method="search"
      placeholder="请输入关键词"
      :loading="loading"
    >
      <el-option
        v-if="showDefaultOption"
        :key="'default'"
        :label="defaultOption.label"
        :value="defaultOption.value"
      />
      <el-option
        v-for="item in options"
        :key="item.value"
        :label="item.label"
        :value="item.value"
      />
    </el-select>
  </div>
</template>
<script>
import { search{{\App\Service\Gen\GenTool::getDir($tableName)}}  } from '@/api/{{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}'
export default {
  name: '{{\App\Service\Gen\GenTool::getDir($tableName)}}Select',
  props: {
    value: {
      type: Number,
      default: null
    },
    selectAll: {
      type: Boolean,
      default: false
    },
    showDefaultOption: {
      type: Boolean,
      defualt: true
    },
    defaultOption: {
      type: Object,
      default: () => {
        return { value: 0, label: '请选择' }
      }
    }
  },
  data() {
    return {
      options: [],
      selectOption: '',
      list: [],
      loading: false,
      states: ['test1', 'test2']
    }
  },
  watch: {
    selectOption: function(newValue) {
      this.$emit('input', parseInt(newValue))
    },
    value: function(newValue) {
      this.selectOption = parseInt(newValue)
    }
  },
  mounted() {
    console.log(this.showDefaultOption)
    const params = {}
    if (!this.selectAll) {
      params.id = this.value
    } else {
      params.searchKey = ''
    }
    search{{\App\Service\Gen\GenTool::getDir($tableName)}}(params)
      .then(response => {
        this.options = response.data.map({{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}=> {
          return { value: {{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}.id, label: {{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}.name }
        })
        this.selectOption = this.value
      })
  },
  methods: {
    search(query) {
      if (query !== '') {
        this.loading = true
        // 获取数据
        search{{\App\Service\Gen\GenTool::getDir($tableName)}}{ searchKey: query })
          .then(response => {
            this.options = response.data.map({{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}} => {
              return { value: {{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}.id, label: {{lcfirst(\App\Service\Gen\GenTool::getDir($tableName))}}.name }
            })
            this.loading = false
          })
      } else {
        this.options = []
      }
    }
  }
}
</script>
<style lang="scss">

</style>

