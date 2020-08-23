@extends('layouts.app') 
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Garantías</h2>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <guarantee-component multi-workshop="{{ $multiWorkshop }}" :workshops="{{ $workshops->toJson() }}" :my-user="{{ $myUser->toJson() }}"></guarantee-component>
        </el-col>
    </el-row>
</el-main>
@endsection
