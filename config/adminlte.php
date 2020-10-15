<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The default title of your admin panel, this goes into the title tag
    | of your page. You can override it per page with the title section.
    | You can optionally also specify a title prefix and/or postfix.
    |
    */

    'title' => 'Dynamo Voleibol',

    'title_prefix' => '',

    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | This logo is displayed at the upper left corner of your admin panel.
    | You can use basic HTML here if you want. The logo has also a mini
    | variant, used for the mini side bar. Make it 3 letters or so
    |
    */

    'logo' => '<b>Dynamo</b>Voleibol',

    'logo_mini' => '<b>DCV</b>',

    /*
    |--------------------------------------------------------------------------
    | Skin Color
    |--------------------------------------------------------------------------
    |
    | Choose a skin color for your admin panel. The available skin colors:
    | blue, black, purple, yellow, red, and green. Each skin also has a
    | ligth variant: blue-light, purple-light, purple-light, etc.
    |
    */

    'skin' => 'yellow',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Choose a layout for your admin panel. The available layout options:
    | null, 'boxed', 'fixed', 'top-nav'. null is the default, top-nav
    | removes the sidebar and places your menu in the top navbar
    |
    */

    'layout' => null,

    /*
    |--------------------------------------------------------------------------
    | Collapse Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we choose and option to be able to start with a collapsed side
    | bar. To adjust your sidebar layout simply set this  either true
    | this is compatible with layouts except top-nav layout option
    |
    */

    'collapse_sidebar' => false,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Register here your dashboard, logout, login and register URLs. The
    | logout URL automatically sends a POST request in Laravel 5.3 or higher.
    | You can set the request to a GET or POST with logout_method.
    | Set register_url to null if you don't want a register link.
    |
    */

    'dashboard_url' => '/',

    'logout_url' => 'logout',

    'logout_method' => null,

    'login_url' => 'login',

    'register_url' => 'register',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Specify your menu items to display in the left sidebar. Each menu item
    | should have a text and and a URL. You can also specify an icon from
    | Font Awesome. A string instead of an array represents a header in sidebar
    | layout. The 'can' is a filter on Laravel's built in Gate functionality.
    |
    */

    'menu' => [
        [
            'text' => 'Página Inicial',
            'url'  => '/panel',
            'icon'  => 'home',
        ],
        'CADASTRO',
        [
            'text' => 'Atletas',
            'icon' => 'users',
            'can' => 'athlete-list',
            'submenu' => [
                [
                    'text' => 'Atletas',
                    'route' => 'athletes',
                    'icon' => 'users',
                    'can' => 'athlete-list',
                ],
                [
                    'text' => 'Categorias',
                    'route' => 'category.index',
                    'icon' => 'tags',
                    'can' => 'athlete-list'
                ],
            ],
        ],
        [
            'text' => 'Financeiro',
            'icon' => 'dollar-sign',
            'submenu' => [
                [
                    'text' => 'Caixas',
                    'icon' => 'archive',
                    'route' => 'cashier.index',
                    'can' => 'fin-list',
                ],
                [
                    'text' => 'Formas de Pagamento',
                    'icon' => 'hand-holding-usd',
                    'route' => 'payment.method.index',
                    'can' => 'fin-list',
                ],
                [
                    'text' => 'Operadora de Cartão',
                    'icon' => 'credit-card',
                    'route' => 'card.index',
                ],
            ],
        ],
        [
            'text' => 'Empresas',
            'icon' => 'user-tie',
            'route' => 'company.index',
        ],
        [
            'text' => 'Clientes e Fornecedores',
            'icon' => 'user-tag',
            'route' => 'customer.index',
        ],
        [
            'text' => 'Envio de E-Mail',
            'icon' => 'envelope',
            'route' => 'custom.mail.index',
        ],
        'FINANCEIRO',
        [
            'text' => 'Caixa Principal',
            'icon' => 'archive',
            'route' => 'caixa',
            'can' => 'fin-list',
        ],
        [
            'text' => 'Conta Corrente',
            'icon' => 'university',
            'route' => 'bank.account.index',
            'can' => 'fin-list',
        ],
        [
            'text' => 'Cartões de crédito e débito',
            'icon' => 'credit-card',
            'route' => 'cartao.movimentos.index'
        ],
        [
            'text' => 'Mensalidades',
            'icon' => 'calendar',
            'route' => 'mensalidades',
            'can' => 'fin-list',
        ],
        [
            'text' => 'Contas A Pagar',
            'icon' => 'cart-arrow-down',
            'route' => 'invoice.pay.index',
            'can' => 'fin-list',
        ],
        [
            'text' => 'Contas A Receber',
            'icon' => 'cart-plus',
            'route' => 'invoice.receive.index',
            'can' => 'fin-list',
        ],
        'RELATÓRIOS',
        [
            'text' => 'Financeiro',
            'icon' => 'dollar-sign',
            'can' => 'fin-list',
            'submenu' => [
                [
                    'text' => 'Contas a Receber',
                    'icon' => 'cart-plus',
                    'route' => 'relatorios.areceber',
                    'can' => 'fin-list',
                ],
                [
                    'text' => 'Contas a Pagar',
                    'icon' => 'cart-arrow-down',
                    'route' => 'relatorios.apagar',
                    'can' => 'fin-list',
                ],
                [
                    'text' => 'Contas Pagas',
                    'icon' => 'cart-arrow-down',
                    'route' => 'relatorios.pagas',
                    'can' => 'fin-list',
                ],
                [
                    'text' => 'Contas Recebidas',
                    'icon' => 'cart-arrow-down',
                    'route' => 'relatorios.recebidas',
                    'can' => 'fin-list',
                ],
                [
                    'text' => 'Contas Pagas x Recebidas',
                    'icon' => 'cart-arrow-down',
                    'route' => 'relatorios.pagas.recebidas',
                    'can' => 'fin-list',
                ],
            ],
        ],
        'USUÁRIOS',
        [
            'text' => 'Usuários',
            'url' => '/painel/usuarios',
            'icon' => 'user',
            'can' => 'user-list'
        ],
        'UTILITÁRIOS',
        [
            'text' => 'Configurações',
            'icon' => 'cog',
            'submenu' =>
            [
                [
                    'text' => 'Administração do Sistema',
                    'icon' => 'gear',
                    'route' => 'settings.index',
                ],
                [
                    'text' => 'Email',
                    'icon' => 'envelope',
                    'route' => 'config.mail',
                ],
            ],
        ],
        [
            'text' => 'Backup',
            'icon' => 'database',
            'submenu' =>
            [
                [
                    'text' => 'Gerar Backup',
                    'icon' => 'database',
                    'route' => 'backup.index',
                ],
                [
                    'text' => 'Restaurar Backup',
                    'icon' => 'database',
                    'route' => 'backup.restore.index',
                ]
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Choose what filters you want to include for rendering the menu.
    | You can add your own filters to this array after you've created them.
    | You can comment out the GateFilter if you don't want to use Laravel's
    | built in Gate functionality
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Choose which JavaScript plugins should be included. At this moment,
    | only DataTables is supported as a plugin. Set the value to true
    | to include the JavaScript file from a CDN via a script tag.
    |
    */

    'plugins' => [
        'datatables' => true,
        'select2'    => true,
        'chartjs'    => true,
    ],
];
