<el-date-picker
        v-model="{{$name}}"
        type="datetimerange"
        :picker-options="datetimeOption"
        range-separator="至"
        start-placeholder="开始日期"
        end-placeholder="结束日期"
        align="right"
        format="yyyy-MM-dd HH:mm"
        value-format="yyyy-MM-dd HH:mm"
/>
