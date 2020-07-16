@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Ingresos / Gastos / Garantías</h2>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <reports-container></reports-container>
        </el-col>
    </el-row>
</el-main>
@endsection