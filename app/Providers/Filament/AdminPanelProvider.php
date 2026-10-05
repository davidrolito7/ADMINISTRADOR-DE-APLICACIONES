<?php

namespace App\Providers\Filament;

use App\Http\Middleware\SetAdmDatabaseMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('ADMINISTRADOR DE PERMISOS')
            ->favicon(asset('icon.png'))
            ->colors([
                'primary' => Color::Amber,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')

            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn(): string => request()->routeIs('filament.admin.auth.login')
                    ? Blade::render(<<<'HTML'
            <style>
                body {
                    overflow: hidden;
                }

                .fi-simple-layout {
                    position: relative;
                    z-index: 10;
                    background: transparent !important;
                }

                .fi-simple-main {
                    position: relative;
                    z-index: 20;
                }

                .login-collage-bg {
                    position: fixed;
                    inset: 0;
                    width: 100vw;
                    height: 100vh;
                    z-index: 0;
                    overflow: hidden;
                    background:
                        radial-gradient(circle at top left, rgba(245, 158, 11, 0.25), transparent 30%),
                        radial-gradient(circle at bottom right, rgba(251, 191, 36, 0.18), transparent 35%),
                        linear-gradient(135deg, #111827 0%, #1f2937 45%, #0f172a 100%);
                }

                .login-collage-grid {
                    position: absolute;
                    inset: -5%;
                    background-image:
                        linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
                    background-size: 120px 120px;
                    opacity: 0.22;
                }

                .login-collage-item {
                    position: absolute;
                    left: var(--x);
                    top: var(--y);
                    width: var(--size);
                    aspect-ratio: 1 / 1;
                    transform: translate3d(0, 0, 0);
                    animation: floatCard var(--duration, 18s) ease-in-out infinite;
                    animation-delay: var(--delay, 0s);
                }

                .login-collage-item img {
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                    filter: drop-shadow(0 12px 24px rgba(0, 0, 0, 0.28));
                }

                .login-collage-bg::after {
                    content: "";
                    position: absolute;
                    inset: 0;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 1;
                }

                @keyframes floatCard {
                    0%, 100% {
                        transform: translate3d(0, 0, 0);
                    }
                    50% {
                        transform: translate3d(0, -18px, 0);
                    }
                }

                @media (max-width: 768px) {
                    .login-collage-item {
                        width: calc(var(--size) * 0.75);
                    }
                }
            </style>

            <div class="login-collage-bg">
                <div class="login-collage-grid"></div>

                @foreach ($collageItems as $item)
                    <div
                        class="login-collage-item"
                        style="
                            --x: {{ $item['x'] }};
                            --y: {{ $item['y'] }};
                            --size: {{ $item['size'] }};
                            --delay: {{ $item['delay'] }};
                            --duration: {{ $item['duration'] }};
                            z-index: {{ $item['z'] }};
                        "
                    >
                        <img src="{{ $item['src'] }}" alt="Collage decorativo">
                    </div>
                @endforeach
            </div>
        HTML, [
                        'collageItems' => collect([
                            // Agrega aqui mas imagenes de public/, por ejemplo: 'logos/otro-icono.png'
                            'icon.png',
                            'neko.png',
                            'panda.png',
                        ])->values()->pipe(function ($images) {
                            $layout = [
                                ['x' => '2%', 'y' => '4%', 'size' => '160px', 'delay' => '0s', 'duration' => '14s', 'z' => 2],
                                ['x' => '18%', 'y' => '10%', 'size' => '185px', 'delay' => '1.5s', 'duration' => '18s', 'z' => 3],
                                ['x' => '35%', 'y' => '3%', 'size' => '150px', 'delay' => '3s', 'duration' => '16s', 'z' => 2],
                                ['x' => '50%', 'y' => '8%', 'size' => '195px', 'delay' => '0.5s', 'duration' => '20s', 'z' => 3],
                                ['x' => '68%', 'y' => '4%', 'size' => '175px', 'delay' => '2.5s', 'duration' => '19s', 'z' => 2],
                                ['x' => '83%', 'y' => '11%', 'size' => '140px', 'delay' => '4s', 'duration' => '15s', 'z' => 2],
                                ['x' => '8%', 'y' => '34%', 'size' => '205px', 'delay' => '1s', 'duration' => '17s', 'z' => 4],
                                ['x' => '27%', 'y' => '29%', 'size' => '170px', 'delay' => '2s', 'duration' => '18s', 'z' => 2],
                                ['x' => '45%', 'y' => '33%', 'size' => '225px', 'delay' => '3.5s', 'duration' => '16s', 'z' => 4],
                                ['x' => '66%', 'y' => '31%', 'size' => '180px', 'delay' => '1.2s', 'duration' => '19s', 'z' => 3],
                                ['x' => '82%', 'y' => '36%', 'size' => '150px', 'delay' => '2.8s', 'duration' => '14s', 'z' => 2],
                                ['x' => '4%', 'y' => '67%', 'size' => '155px', 'delay' => '0.8s', 'duration' => '18s', 'z' => 2],
                                ['x' => '20%', 'y' => '72%', 'size' => '190px', 'delay' => '2.2s', 'duration' => '15s', 'z' => 3],
                                ['x' => '39%', 'y' => '68%', 'size' => '165px', 'delay' => '3.8s', 'duration' => '20s', 'z' => 2],
                                ['x' => '56%', 'y' => '73%', 'size' => '200px', 'delay' => '1.8s', 'duration' => '17s', 'z' => 3],
                                ['x' => '76%', 'y' => '69%', 'size' => '175px', 'delay' => '2.9s', 'duration' => '16s', 'z' => 2],
                            ];

                            if ($images->isEmpty()) {
                                return [];
                            }

                            return collect($layout)->map(function (array $item, int $index) use ($images) {
                                $item['src'] = asset($images[$index % $images->count()]);

                                return $item;
                            })->all();
                        }),
                    ])
                    : ''
            )

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->navigationGroups([
                'Administrador',
                'Permisos',
                NavigationGroup::make('Configuración')->collapsed(),
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                SetAdmDatabaseMode::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->persistentMiddleware([
                SetAdmDatabaseMode::class,
            ]);
    }
}
