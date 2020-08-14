@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Roles</h2>
        </el-col>
        @can('agregar roles')
        <create-roles :permissions="{{ $permissions->toJson()}}"></create-roles>
        @endcan
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            {{-- @can('listar roles') --}}
            <list-roles :permissions="{{ $permissions->toJson()}}"></list-roles>
            {{-- @endcan
            @cannot('listar roles')
            <br>
            <el-alert title="Acceso no autorizado." type="error" closable="false" show-icon>
            </el-alert>
            @endcan --}}
        </el-col>
    </el-row>
</el-main>
@endsection