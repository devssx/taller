@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Nómina</h2>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            @can('listar nomina')
            <payroll-container multi-workshop="{{ $multiWorkshop }}" :workshops="{{ $workshops->toJson() }}" :my-user="{{ $myUser->toJson() }}"></payroll-container>
            @endcan
            @cannot('listar nomina')
            <br>
            <el-alert title="Acceso no autorizado." type="error" closable="false" show-icon>
            </el-alert>
            @endcan
        </el-col>
    </el-row>
</el-main>
@endsection