@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Horarios de limpieza Diaria</h2>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <daily-cleaning></daily-cleaning>
        </el-col>
    </el-row>
</el-main>
@endsection