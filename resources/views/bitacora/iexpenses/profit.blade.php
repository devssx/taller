@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Ingresos y Utilidad</h2>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <ingresos-utilidad-component></ingresos-utilidad-component>
        </el-col>
    </el-row>
</el-main>
@endsection