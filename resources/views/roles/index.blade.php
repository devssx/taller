@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Roles</h2>
        </el-col>
        @can('listar roles')
        <create-roles :permissions="{{ $permissions->toJson()}}"></create-roles>
        @endcan
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            @can('listar roles')
            <list-roles :permissions="{{ $permissions->toJson()}}"></list-roles>
            @endcan
        </el-col>
    </el-row>
</el-main>
@endsection