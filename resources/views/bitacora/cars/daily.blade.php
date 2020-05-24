@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Registro de carros del día</h2>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <daily-cars></daily-cars>
        </el-col>
    </el-row>
</el-main>
@endsection