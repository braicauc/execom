<!DOCTYPE HTML>
<html>
<head>
    <title>@yield('pTitle')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('pDesc')">
    @yield('pMeta')
    <link href="@yield('pURL'){{ elixir('css/all.css') }}" rel="stylesheet">
    <script src="@yield('pURL')/js/all_new.js"></script>
</head>
<body style="padding-top: 100px;">

<script>
    APP_PUBLIC_URL = '@yield('pURL')';
</script>

<div class="wrapper">


    <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="z-index: 99999;">
        <div class="container">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapsible">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="Logo" href="{{APP_PUBLIC_URL}}" style="display: block; font-size: 200%;">{{APP_NAME}}</a>
            </div>

            <div class="navbar-collapse collapse" id="navbar-collapsible" style="overflow: visible;">

                <ul class="nav navbar-nav navbar-left">
                    <li><a href="#">Link 1</a></li>
                    <li><a href="#">Link 2</a></li>
                </ul>


                <div class="navbar-form navbar-right btn-group">
                    <a class="btn btn-default" href="{{APP_PUBLIC_URL}}/auth/facebook">Intra cu Facebook</a>
                </div>

                <form class="navbar-form" method="GET" action="{{APP_PUBLIC_URL}}/s">
                    <div style="display:table;" class="input-group">
                        <input type="text" autofocus="autofocus" autocomplete="off"
                               placeholder="cautati..." name="cauta"
                               class="form-control"
                               id="cauta_afaceri_firme" style="width: 100%;">
                        <span style="width: 1%;" class="input-group-addon"><span
                                    class="glyphicon glyphicon-search"></span></span>
                    </div>
                </form>


            </div>

        </div>
    </nav>



    <div class="container navbar-fixed-top" style="margin-top: 50px;">
        <ol class="breadcrumb">
            <li class="home">
                <span typeof="v:Breadcrumb">
                    <a rel="v:url" property="v:title" title="Prima pagina {{ APP_NAME }}" href="{{APP_PUBLIC_URL}}" class="home"><i
                                class="fa fa-home"></i></a>
                </span>
            </li>
            @yield('breadcrumbs')
        </ol>
    </div>

    @yield('container')



</div>  {{--Wrapper--}}


@stack('scripts')

<footer class="footer-basic-centered">

    <p class="footer-company-motto">{{APP_NAME}} - Foruchat pentru toata lumea</p>

    <p class="footer-links">
        <i class="fa fa-home"></i> <a href="{{APP_PUBLIC_URL}}">{{APP_NAME}}</a>
        &nbsp; &nbsp;
    </p>


    <br>
    <?php
    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $finish = $time;
    $total_time = round(($finish - LARAVEL_START), 4);
    echo 'Generata in ' . $total_time . ' secunde.';
    ?>
    <br><br>
    <p class="footer-company-name">{{APP_NAME}} &copy; {{date("Y")}}</p>
    <br><br><br><bR>
</footer>


<script>
    function goToCod(slug) {
        link = makeLink(slug);
        window.location.assign(link);
    }
    function makeLink(slug) {
        link = 'http://' + slug.split(' ').join('-') + '.twx.ro';
        return link;
    }
    var options = {
        url: function(cauta) {
            return APP_PUBLIC_URL + "/api/" + cauta + "?t=" + new Date().getTime();
        },
        getValue: "categorie",
        theme: "bootstrap",
        requestDelay: 300,
        list: {
            onClickEvent: function () {
                goToCod($("#cauta_afaceri_firme").getSelectedItemData().categorie);
            },

            onChooseEvent: function () {
                goToCod($("#cauta_afaceri_firme").getSelectedItemData().categorie);
            }
        }
    };
    $("#cauta_afaceri_firme").easyAutocomplete(options);
</script>

</body>
</html>