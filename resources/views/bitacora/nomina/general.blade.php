@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Nómina Por Técnico</h2>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <nomina-general></nomina-general>
        </el-col>
    </el-row>
</el-main>
@endsection