@extends('layouts.app')

@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Ordenes de Servicio</h2>
        </el-col>
        <el-col :span="5" style="text-align:right;">
            @can('crear contraseña')
            <edit-password :workshops="{{ $workshops->toJson() }}" :my-user="{{ $myUser->toJson() }}"></edit-password>
            @endcan
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            @can('listar ordenes')
            <list-sales multi-workshop="{{ $multiWorkshop }}" :workshops="{{ $workshops->toJson() }}" :my-user="{{ $myUser->toJson() }}"></list-sales>
            @endcan
            @cannot('listar ordenes')
            <br>
            <el-alert title="Acceso no autorizado." type="error" closable="false" show-icon>
            </el-alert>
            @endcan
            {{-- <bitacora-cars></list-cars> --}}
            {{-- <daily-cars></daily-cars>
            <weekly-cars></weekly-cars> --}}
            {{-- <view-carservices ref="create" :full-mode="true"></view-carservices> --}}
        </el-col>
    </el-row>

    @if (session('error'))
    <el-row type="flex">
        <el-alert title="{{ session('error') }}" type="error" show-icon>
        </el-alert>
    </el-row>
    @endif
</el-main>
@endsection