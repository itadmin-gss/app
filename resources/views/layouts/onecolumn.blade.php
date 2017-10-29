<!DOCTYPE html>
<html lang="en">
    <head>
        @include('common.head')	
    </head>

    <body>
        <!-- start: Header -->
        <div class="navbar">
            @include('common.header')
        </div>
        <!-- start: Header -->

        <div class="container-fluid">
            <div class="row-fluid">

                <noscript>
                <div class="alert alert-block span11">
                    <div class="alert-heading">Warning!</h4>
                    <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
                </div>
                </noscript>

               <div id="addcontenthere" class="registerd">
                @yield('content')
                </div>


            </div><!--/fluid-row-->

            <div class="clearfix"></div>

            <footer>
                @include('common.footer')
            </footer>

        </div><!--/.fluid-container-->

        @include('common.footerbottom')

    </body>
</html>
