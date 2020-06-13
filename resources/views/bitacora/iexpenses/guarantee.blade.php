@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Garantías del Año</h2>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <garantias-component></garantias-component>
        </el-col>
    </el-row>
</el-main>
@endsection