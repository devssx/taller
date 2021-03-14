@extends('layouts.app') 
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Clientes</h2>
        </el-col>
        <create-clients :workshops="{{ $workshops->toJson() }}"></create-clients>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <list-clients></list-clients>
        </el-col>
    </el-row>
</el-main>
@endsection
