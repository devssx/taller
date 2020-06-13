@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Nómina Completa Resumen</h2>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <nomina-completa></nomina-completa>
        </el-col>
    </el-row>
</el-main>
@endsection