@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Usuarios</h2>
        </el-col>
        @can('agregar usuarios')
        <create-users :roles="{{ $roles->toJson() }}" :workshops="{{ $workshops->toJson() }}"></create-users>
        @endcan
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            @can('listar usuarios')
            <list-users :roles="{{ $roles->toJson() }}" :workshops="{{ $workshops->toJson() }}"></list-users>
            @endcan
            @cannot('listar usuarios')
            <br>
            <el-alert title="Acceso no autorizado." type="error" closable="false" show-icon>
            </el-alert>
            @endcan
        </el-col>
    </el-row>
</el-main>
@endsection