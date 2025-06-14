<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workers List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Inter', Arial, sans-serif; }
        .card {
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(53, 122, 255, 0.10);
            border: none;
        }
        .card-header {
            background: linear-gradient(90deg, #357aff 60%, #5eead4 100%);
            color: #fff;
            border-radius: 18px 18px 0 0;
            font-size: 1.3rem;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .btn-primary, .btn-success {
            background: linear-gradient(90deg, #357aff 60%, #5eead4 100%);
            border: none;
            font-weight: 600;
        }
        .btn-primary:hover, .btn-success:hover {
            background: linear-gradient(90deg, #2563eb 60%, #2dd4bf 100%);
        }
        .table thead th {
            background: #e0e7ff;
            color: #357aff;
            font-weight: 700;
        }
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #f3f8ff;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Workers</span>
                    <a href="{{ route('admin.workers.create') }}" class="btn btn-success">Add Worker</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>Supply Center</th>
                                    <th>Current Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workers as $worker)
                                    <tr>
                                        <td>{{ $worker->name }}</td>
                                        <td>{{ $worker->email }}</td>
                                        <td>{{ $worker->position }}</td>
                                        <td>{{ $worker->supply_center }}</td>
                                        <td>{{ $worker->current_role }}</td>
                                        <td>
                                            <a href="{{ route('admin.workers.edit', $worker) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <form action="{{ route('admin.workers.destroy', $worker) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
