<!DOCTYPE html>
<html lang="en">

<head>
    <title>Taller Gallegos</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
                        <notify-component></Notify-component>
                    </li>
                    <li>
                        <form id="form-logout" method="POST" action="{{ route('logout') }}">
                            <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                        </form>
                        <a href="#" onclick="javascript:document.getElementById('form-logout').submit();"
                            style="color:white;">Cerrar Sesion</a>
                    </li>
                </ul>
            </el-header>
            <el-row type="flex" style="text-align: center;margin: auto;">
                <el-col :span="24">
                    <el-menu class="el-menu-demo" mode="horizontal"
                        default-active="{{ explode(".", Route::current()->getName())[0] }}">
                        {{-- <el-menu-item style="padding-top: 10px;">
                            <quick-receipt></quick-receipt>
                        </el-menu-item> --}}
                        {{-- <el-menu-item index="dashboard" v-on:click="goto('{{ route('dashboard') }}')" v-if="">
                            Reporte de Ventas
                        </el-menu-item> --}}
                        @can('listar bitacora')
                        <el-submenu index="bitacora">
                            <template slot="title">Bitácora</template>
                            <el-menu-item index="bitacora" v-on:click="goto('{{ route('bitacora.cars') }}')">
                                Registro de carros
                            </el-menu-item>
                            <el-menu-item index="bitacora" v-on:click="goto('{{ route('bitacora.cleaning') }}')">
                                Limpieza
                            </el-menu-item>
                        </el-submenu>
                        @endcan
                        @can('listar ordenes')
                        <el-submenu index="sales">
                            <template slot="title">Ordenes de Servicio</template>
                            <el-menu-item index="sales" v-on:click="goto('{{ route('sales') }}')">
                                Crear Orden de Servicio
                            </el-menu-item>
                            <el-menu-item index="sales" v-on:click="goto('{{ route('sales.list') }}')">
                                Lista Ordenes de servicio
                            </el-menu-item>
                        </el-submenu>
                        @endcan
                        @can('listar gastos')
                        <el-menu-item index="expenses" v-on:click="goto('{{ route('expenses') }}')">
                            Ingresos y Gastos
                        </el-menu-item>
                        @endcan
                        @can('listar nomina')
                        <el-menu-item index="payroll" v-on:click="goto('{{ route('payroll') }}')">
                            Nómina
                        </el-menu-item>
                        @endcan
                        @can('listar reporte')
                        <el-menu-item index="reports" v-on:click="goto('{{ route('reports') }}')">
                            Reportes
                        </el-menu-item>
                        @endcan
                        @can('listar bitacora')
                        <el-menu-item index="guarantee" v-on:click="goto('{{ route('guarantee') }}')">
                            Garantías
                        </el-menu-item>
                        @endcan
                        @can('listar clientes')
                        <el-menu-item index="clients" v-on:click="goto('{{ route('clients.list') }}')">
                            Clientes
                        </el-menu-item>
                        @endcan @can('listar usuarios')
                        <el-menu-item index="users" v-on:click="goto('{{ route('users.list') }}')">
                            Usuarios
                        </el-menu-item>
                        @endcan @can('listar roles')
                        <el-menu-item index="roles" v-on:click="goto('{{ route('roles.list') }}')">
                            Roles
                        </el-menu-item>
                        @endcan
                        @if(Gate::check('listar servicios') || Gate::check('listar carros') || Gate::check('listar
                        articulos'))
                        <el-submenu index="mantenimiento">
                            <template slot="title">Mantenimiento</template>
                            @can('listar servicios')
                            <el-menu-item index="services" v-on:click="goto('{{ route('services.list') }}')">
                                <i class="el-icon-magic-stick"></i>
                                <span>Servicios</span>
                            </el-menu-item>
                            @endcan
                            @can('listar carros')
                            <el-menu-item index="cars" v-on:click="goto('{{ route('cars.list') }}')">
                                <i class="el-icon-truck"></i>
                                <span>Carros</span>
                            </el-menu-item>
                            @endcan @can('listar articulos')
                            <el-menu-item index="items" v-on:click="goto('{{ route('items.list') }}')">
                                <i class="el-icon-box"></i>
                                <span>Artículos</span>
                            </el-menu-item>
                            @endcan @can('editar talleres')                     
                            <el-menu-item index="items" v-on:click="goto('{{ route('workshops.list') }}')">
                                <i class="el-icon-s-shop"></i>
                                <span>Talleres</span>
                            </el-menu-item>
                            @endcan @can('cambiar de taller')
                            <el-menu-item index="carservices" v-on:click="goto('{{ route('carservices.list') }}')">
                                <i class="el-icon-s-flag"></i>
                                <span>Carros y Servicios</span>
                            </el-menu-item>
                            @endcan  
                        </el-submenu>
                        @endif
                    </el-menu>
                </el-col>
            </el-row>
            <el-container class="wrapper">
                {{-- <el-aside class="menu" width="200px"></el-aside> --}}
                @yield('content')
            </el-container>
        </el-container>
    </div>
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>