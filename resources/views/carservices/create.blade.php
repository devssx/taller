@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="24">
            <create-carservices ref="create"></create-carservices>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <view-carservices ref="create"></view-carservices>
        </el-col>
    </el-row>
</el-main>
@endsection