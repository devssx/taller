@extends('layouts.app')

@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Ordenes de Servicio</h2>
        </el-col>
        <el-col :span="5" style="text-align:right;">
            <el-button type="primary" icon="el-icon-circle-plus" v-on:click="goto('{{ route('carservices.create') }}')">
                Crear Orden de Servicio
            </el-button>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <list-sales></list-sales>
        </el-col>
    </el-row>

    @if (session('error'))
    <el-row type="flex">
        <el-alert  title="{{ session('error') }}" type="error" show-icon>
        </el-alert>
    </el-row>
    @endif
</el-main>
@endsection