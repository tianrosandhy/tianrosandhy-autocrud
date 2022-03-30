<table>
    <thead>
        <tr>
            @foreach($fieldTranslate as $field => $label)
            <th>{{ $label }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            @foreach($fieldTranslate as $field => $label)
            <td>{{ $row[$field] ?? '' }}</td>    
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>