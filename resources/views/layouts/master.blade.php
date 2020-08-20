<!DOCTYPE html>

<html lang="en">
@include('markup.head')

<body class="hold-transition sidebar-mini">
    <div class="wrapper" id="app">
        @include('markup.navbar')

        @include('markup.side-navbar')
        <div class="content-wrapper">
          <div class="content">
            <div class="container-fuild">
              <router-view></router-view>
              <vue-progress-bar></vue-progress-bar>
            </div>
          </div>
        </div>
        <!-- /.content-wrapper -->
        @include('markup.footer')
    </div>
    @auth
    <script type="text/javascript">
      window.user = @json(Auth::user())
    </script>
    @endauth
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
