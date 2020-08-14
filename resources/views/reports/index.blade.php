@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Ingresos / Gastos / Garantías</h2>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            @can('listar reporte')
            <report-container multi-workshop="{{ $multiWorkshop }}" :workshops="{{ $workshops->toJson() }}" :my-user="{{ $myUser->toJson() }}"></report-container>
            @endcan
            @cannot('listar reporte')
            <br>
            <el-alert title="Acceso no autorizado." type="error" closable="false" show-icon>
            </el-alert>
            @endcan
        </el-col>
    </el-row>
</el-main>
@endsection