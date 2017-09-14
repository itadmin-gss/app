<!DOCTYPE html>
<html lang="en">
    <head>
        @include('common.head')
    </head>

    <body class="pinkBgcolor"  style="background-image: none !important;">
        <!-- start: Header -->
        <div class="navbar">
            @include('common.header')
        </div>
        <!-- start: Header -->

        <div class="container-fluid">
            <div class="row-fluid">

                <!-- start: Main Menu -->
                <div id="sidebar-left" class="span1">
                    @include('common.sidebar_customer')
                </div>
                <!-- end: Main Menu -->

                <noscript>
                <div class="alert alert-block span11">
                    <h4 class="alert-heading">Warning!</h4>
                    <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
                </div>
                </noscript>
                <div id="addcontenthere">
                    @yield('content')
                </div>

            </div>


           
        </div><!--/fluid-row-->


        <div class="modal hide fade modelForm"  id="before">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3>Before Images</h3>
            </div>
            <div class="modal-body">
                <div class="row-fluid">
                    <div class="row-fluid browse-sec">
                        <h2>Images</h2>
                        <ul class="media-list ">
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn" data-dismiss="modal">Close</a>
                <a href="#" class="btn btn-primary">Save</a>
            </div>
        </div>
        <!--/myModa2-->

        <div class="modal hide fade modelForm"  id="after">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h3>After Images</h3>
            </div>
            <div class="modal-body">
                <div class="row-fluid">
                    <div class="row-fluid browse-sec">
                        <h2>Images</h2>
                        <ul class="media-list ">
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn" data-dismiss="modal">Close</a>
                <a href="#" class="btn btn-primary">Save</a>
            </div>
        </div>
        <!--/myModa2-->


        <div class="modal hide fade" id="myModal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>Settings</h3>
            </div>
            <div class="modal-body">
                <p>Here settings can be configured...</p>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn" data-dismiss="modal">Close</a>
                <a href="#" class="btn btn-primary">Save changes</a>
            </div>
        </div>

        <div class="clearfix"></div>

        <footer>
            @include('common.footer')

        </footer>

    </div><!--/.fluid-container-->

    @include('common.footerbottom')
</body>
</html>
