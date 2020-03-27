@extends('layouts.app') 
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Registro de Carros del Dia</h2>
        </el-col>
        <create-cars></create-cars>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <bitacora-cars></list-cars>
        </el-col>
    </el-row>
</el-main>
@endsection
