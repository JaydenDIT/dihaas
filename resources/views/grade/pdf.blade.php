<!DOCTYPE html>
<html>
<head>
<style nonce="{{ csp_nonce() }}">
        /* Add your CSS styles here */
    </style>
</head>
<body>
    <h1>Grade List</h1>
    <table>
        <thead>
            <tr>
                <th>Grade Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grades as $grade)
                <tr>
                    <td>{{ $grade->grade_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
