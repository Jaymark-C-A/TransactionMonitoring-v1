<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.head')
</head>
<body class="text-sm">

   <div class="wrapper">
    <nav class="main-header navbar navbar-expand-lg navbar-white navbar-light">
        @include('includes.nav')
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        @include('includes.sidebar.sidebar')  
    </aside>
    <div class="content-wrapper">
        <div class="content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 mt-2">
                        <div class="card">
                            <div class="card-header">
                                <h4>Edit User-type
                                    <a href="{{ url('roles') }}" class="btn btn-danger float-right">Back</a>
                                </h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ url('roles/'.$role->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="name">User-type</label>
                                        <input type="text" name="name" value="{{ $role->name }}" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="{{ url('roles/'.$role->id.'/give-permissions') }}" class="btn btn-success float-right">
                                            Add Permission
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="main-footer">
        @include('includes.footer')
    </footer>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Toggle sidebar menu
        $('.nav-link').click(function() {
            var parent = $(this).parent();
            if ($(parent).hasClass('menu-open')) {
                $(parent).removeClass('menu-open');
            } else {
                $(parent).addClass('menu-open');
            }
        });
    });
</script>
</body>
</html>
