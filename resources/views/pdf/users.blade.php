<!DOCTYPE html>
<html>
<style>
</style>

<body>

    <h2>A basic HTML table</h2>

    <table style="width:100%">
        <tr>
            <th>Received By</th>
            <th>Assigned To</th>
            <th>Date Filed</th>
            <th>Case</th>
        </tr>
        @if (count($complaints))
            @foreach ($complaints as $complaint)
                <tr>
                    <td>{{ $complaint->receivedBy }}</td>
                    <td>{{ $complaint->fullname }}</td>
                    <td>{{ $complaint->dateFiled }}</td>
                    <td>{{ $complaint->name }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="3">Empty</td>
            </tr>
        @endif
    </table>

    <p>To undestand the example better, we have added borders to the table.</p>

</body>

</html>
