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
            <div class="container-fluid">
                <div class="row">
                    @include('role-permission.nav-links')
                    <div class="col-lg-12">
                        @if (@session('status'))
                            <div class="alert alert-success mt-3">{{ session('status') }}</div>
                        @endif
                        <div class="card mt-3">
                            <div class="card-header">
                                <h4>User Account</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Roles</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)  
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @if (!empty($user->getRoleNames()))
                                                        @foreach ($user->getRoleNames() as $rolename)
                                                            <label class="badge badge-primary mx-1">{{ $rolename }}</label>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('users/'.$user->id.'/edit') }}" class="btn btn-success">Edit</a>
                                                    @if (!$user->hasRole('Super-admin'))
                                                        <button class="btn btn-danger delete-btn" data-user-id="{{ $user->id }}">Delete</button>
                                                    @endif
                                                </td>                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <a href="{{ url('users/create') }}" class="btn btn-primary float-right mt-3">Add Users</a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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

        // Add click event listener to delete buttons
        $('.delete-btn').click(function() {
            var userId = $(this).data('user-id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If user confirms, proceed with the deletion
                    window.location.href = "{{ url('users') }}/" + userId + "/delete";
                }
            });
        });
    });
</script>
</body>
</html>
