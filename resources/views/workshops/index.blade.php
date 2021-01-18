@extends('layouts.app') 
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Talleres</h2>
        </el-col>
        <create-workshops></create-workshops>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <list-workshops></list-workshops>
        </el-col>
    </el-row>
</el-main>
@endsection
