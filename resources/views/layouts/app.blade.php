<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Taller Gallegos</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div id="app" v-cloak>
        <div class="loader-container">
            <div class="loader align-self-center">Loading...</div>
        </div>
        <el-container class="wrapper">
            <el-header height="80px" :style="{ 'background-color': primaryColor }">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="element-logo" class="header-logo">
                </a>
                <ul class="header-operations">
                    <li>
                        <a href="#" onclick="javascript:document.getElementById('form-logout').submit();">
                            Cerrar Sesion
                        </a>
                        <form id="form-logout" method="POST" action="{{ route('logout') }}">
                            <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                        </form>
                    </li>
                </ul>
            </el-header>
            <el-container class="wrapper">
                <el-aside class="menu">
                    <el-menu>
                        <el-menu-item index="1" v-on:click="goto('{{ route('sales.list') }}')">
                            Ordenes de Servicio
                        </el-menu-item>
                        <el-menu-item index="1" v-on:click="goto('{{ route('services.list') }}')">
                            Servicios
                        </el-menu-item>
                        <el-menu-item index="1" v-on:click="goto('{{ route('cars.list') }}')">
                            Carros
                        </el-menu-item>
                        <el-menu-item index="1" v-on:click="goto('{{ route('items.list') }}')">
                            Articulos
                        </el-menu-item>
                    </el-menu>
                </el-aside>
                @yield('content')
            </el-container>
        </el-container>
    </div>
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
