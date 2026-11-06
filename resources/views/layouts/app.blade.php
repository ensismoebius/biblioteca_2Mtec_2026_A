<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="flex min-h-screen flex-col bg-library-background text-library-text">
            @hasSection('content')
                <nav x-data="{ open: false }" class="border-b border-library-border bg-library-surface" aria-label="Navegação principal">
                    <div class="mx-auto flex max-w-7xl items-center justify-start px-4 sm:px-6 lg:px-8 md:hidden">
                        <button
                            type="button"
                            @click="open = ! open"
                            :aria-expanded="open"
                            aria-controls="library-navigation"
                            aria-label="Alternar navegação"
                            class="inline-flex items-center justify-center rounded-md p-3 text-library-muted hover:bg-library-surface-hover hover:text-library-text focus:outline-none focus:ring-2 focus:ring-library-secondary">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div id="library-navigation" x-cloak :class="open ? 'block' : 'hidden'" class="md:block">
                        <div class="mx-auto flex max-w-7xl flex-col px-4 pb-2 sm:px-6 md:flex-row md:gap-4 md:overflow-visible md:px-8 md:pb-0 lg:px-8 justify-center">
                            @foreach ([
                                'inicio' => 'Início',
                                'autores' => 'Autores',
                                'classificacao' => 'Classificação',
                                'clientes' => 'Clientes',
                                'emprestimos' => 'Empréstimos',
                                'exemplares' => 'Exemplares',
                                'generos' => 'Gêneros',
                                'livros' => 'Livros',

                            ] as $routeName => $label)
                                <a
                                    href="{{ route($routeName) }}"
                                    @click="open = false"
                                    @class([
                                        'inline-flex shrink-0 items-center border-l-2 px-3 py-3 text-sm font-medium md:border-l-0 md:border-b-2 md:px-1 md:py-4',
                                        'border-library-secondary text-library-text' => request()->routeIs($routeName),
                                        'border-transparent text-library-muted hover:border-library-border hover:text-library-text' => ! request()->routeIs($routeName),
                                    ])
                                >
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </nav>
            @else
                @include('layouts.navigation')
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="w-full flex-1 text-library-text">
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot }}
                @endif
            </main>

            <footer class="mt-auto border-t border-library-border bg-library-surface">
                <div class="mx-auto max-w-7xl px-4 py-4 text-center text-sm text-library-muted sm:px-6 lg:px-8">
                    &copy; 2026 Biblioteca 2ºMDS Grupo A
                </div>
            </footer>
        </div>
    </body>
</html>
