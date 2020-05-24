@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Horarios de limpieza semanal</h2>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <weekly-cleaning></weekly-cleaning>
        </el-col>
    </el-row>
</el-main>
@endsection