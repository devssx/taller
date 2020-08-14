@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Horarios de limpieza diaria</h2>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            @can('listar bitacora')
            <daily-cleaning multi-workshop="{{ $multiWorkshop }}" :workshops="{{ $workshops->toJson() }}" :my-user="{{ $myUser->toJson() }}"></daily-cleaning>
            @endcan
            @cannot('listar bitacora')
            <br>
            <el-alert title="Acceso no autorizado." type="error" closable="false" show-icon>
            </el-alert>
            @endcan
        </el-col>
    </el-row>
</el-main>
@endsection